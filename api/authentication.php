<?php
/**
 * Modify the response of valid credential.
 *
 * @param array $response The default valid credential response.
 * @param WP_User $user The authenticated user.
 * .
 * @return array The valid credential response.
 */

function e25_modify_valid_credential_response($response, $user): array
{
    $token = $response['token'];

    $response = [];

    $response['code'] = 'jwt_auth_valid_credential';
    $response['message'] = 'User authenticated';
    $is_first_login = 0;
    $login_check = get_user_meta($user->ID, 'login_check', true);

    if ($login_check == 0 || empty($login_check)) {
        $is_first_login = 1;
    }
    $response['data'] = array(
        'status' => 200,
        'token' => $token,
        'user_id' => $user->ID,
        'user_role' => isset($user->roles) ? $user->roles[0] : "",
        'email' => $user->user_email,
        'user_name' => $user->user_login,
        'first_name' => $user->user_firstname,
        'last_name' => $user->user_lastname,
        'designation' => get_field('designation', 'user_' . $user->ID),
        'phone_number' => get_field('phone_number', 'user_' . $user->ID),
        'user_group' => e25_user_group($user->ID),
        'is_first_login' => $is_first_login
    );

    return $response;
}

add_filter('jwt_auth_token_before_dispatch', 'e25_modify_valid_credential_response', 10, 2);

/**
 * Register custom auth API endpoints
 */
add_action('rest_api_init', function () {
    $route_namespace = 'e25/v1';
    register_rest_route($route_namespace, 'user/reset-password', array(
        'methods' => 'POST',
        'callback' => 'e25_set_new_user_password',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

/**
 * Change user password API callback function
 * @param $data
 * @return array|WP_Error
 */
function e25_set_new_user_password(WP_REST_Request $request)
{
    $result = validate_reset_password_data($request);

    if (is_wp_error($result)) {
        return $result;
    }

    $new_password = $result['new_password'];
    $user_id = $result['user_id'];

    wp_set_password($new_password, $user_id);
    update_user_meta($user_id, 'login_check', 1);

    return array(
        'code' => 'password_reset_success',
        'message' => __('Password reset successfully.', 'e25'),
        'data' => array(
            'status' => 200,
            'message' => 'success'
        ),
    );
}

/**
 * Validate reset password request data
 * @param $data
 * @return array|WP_Error
 */
function validate_reset_password_data($data)
{
    $email = $data['email'];
    $current_password = $data['current_password'];
    $new_password = $data['new_password'];
    $confirm_password = $data['confirm_password'];

    if (empty($email) || !is_email($email)) {
        return new WP_Error('no_email', __('Invalid email address.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    if (empty($current_password)) {
        return new WP_Error('no_current_password', __('Invalid current password.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    if (empty($new_password)) {
        return new WP_Error('invalid_password', __('Invalid new password.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    if (empty($confirm_password)) {
        return new WP_Error('invalid_password', __('Invalid confirm password.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $exists = email_exists($email);

    if (!$exists) {
        return new WP_Error('bad_email', __('No user found with this email address.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $user = get_user_by('email', $data['email']);

    if (!wp_check_password($current_password, $user->data->user_pass, $user->ID)) {
        return new WP_Error('wrong_current_password', __('Invalid current password.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    if ($new_password !== $confirm_password) {
        return new WP_Error('unmatched_password', __('Password does not match', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $password_length = strlen($new_password);

    if ($password_length < 8) {
        return new WP_Error('password_length_error', __('Please make sure your password is a minimum of 8 characters long', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $password_complexity = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d,.;:]).+$/', $new_password);
    if (!$password_complexity) {
        return new WP_Error('password_complexity_error', __('Your password must contain at least 1 uppercase, 1 lowercase letter and at least 1 number.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    return ['user_id' => $user->ID, 'new_password' => $new_password];
}
