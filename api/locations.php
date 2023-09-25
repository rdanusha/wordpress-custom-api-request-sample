<?php

/**
 * Register town API endpoints
 */
add_action('rest_api_init', function () {
    $route_namespace = 'e25/v1';
    $route = 'locations';
    //locations list request
    register_rest_route($route_namespace, "/{$route}", array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_get_location_list',
        'args' => array(
            'order_by' => array(
                'required' => false,
                'default' => 'location_title',
                'type' => 'string',
                'description' => 'records order by field',
                'enum' => array(
                    'id',
                    'location_title',
                )
            ),
            'order' => array(
                'required' => false,
                'default' => 'asc',
                'type' => 'string',
                'description' => 'records order',
                'enum' => array(
                    'desc',
                    'asc',
                )
            ),
            'per_page' => array(
                'required' => true,
                'default' => 20,
                'type' => 'integer',
                'description' => 'records limit per page',
                'minimum' => 1,
                'maximum' => 1000
            ),
            'page' => array(
                'required' => false,
                'default' => 1,
                'type' => 'integer',
                'description' => 'current page',
                'minimum' => 1,
            ),
            'search' => array(
                'required' => false,
                'type' => 'string',
                'description' => 'search',
                'sanitize_callback' => function ($value, $request, $param) {
                    return sanitize_text_field($value);
                }
            )
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
    
    //locations list request
    register_rest_route($route_namespace, "/{$route}/static", array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_get_location_list_static',
        'args' => array(
            'order_by' => array(
                'required' => false,
                'default' => 'location_title',
                'type' => 'string',
                'description' => 'records order by field',
                'enum' => array(
                    'id',
                    'location_title',
                )
            ),
            'order' => array(
                'required' => false,
                'default' => 'asc',
                'type' => 'string',
                'description' => 'records order',
                'enum' => array(
                    'desc',
                    'asc',
                )
            ),
            'per_page' => array(
                'required' => true,
                'default' => 20,
                'type' => 'integer',
                'description' => 'records limit per page',
                'minimum' => 1,
                'maximum' => 1000
            ),
            'page' => array(
                'required' => false,
                'default' => 1,
                'type' => 'integer',
                'description' => 'current page',
                'minimum' => 1,
            ),
            'search' => array(
                'required' => false,
                'type' => 'string',
                'description' => 'search',
                'sanitize_callback' => function ($value, $request, $param) {
                    return sanitize_text_field($value);
                }
            )
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    //get a location request
    register_rest_route($route_namespace, "/{$route}/(?P<id>\d+)", array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'e25_get_location',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
            ),
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    //create location request
    register_rest_route($route_namespace, "/{$route}/create", array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_create_location',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    //update location request
    register_rest_route($route_namespace, "/{$route}/(?P<id>\d+)", array(
        'methods' => WP_REST_Server::EDITABLE,
        'callback' => 'e25_update_location',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
            ),
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    //Delete location request
    register_rest_route($route_namespace, "/{$route}/(?P<id>\d+)", array(
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'e25_delete_location',
        'args' => array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
            ),
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

/**
 * Get location request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_location_list_static(WP_REST_Request $request)
{
    $result = [
        [
            "id"=> "14",
            "location_title"=> "Arlington Memorial High School",
            "location_desc"=> null,
            "water_led_limit"=> null,
            "latitude"=> "14.184562343",
            "longitude"=> "-33.75985543",
            "first_name"=> "Joe",
            "last_name"=> "Coffe",
            "telephone"=> "(987) 654-3256",
            "email"=> "e25mediatest@eight25media.com",
            "town_name"=> "Arlington",
            "location_type"=> "School",
            "group_id"=> "21"
        ],
        [
            "id"=> "12",
            "location_title"=> "Arlington School",
            "location_desc"=> null,
            "water_led_limit"=> null,
            "latitude"=> "14.18456",
            "longitude"=> "-33.75985",
            "first_name"=> "John",
            "last_name"=> "Wick",
            "telephone"=> "(786) 718-6718",
            "email"=> "test@mail.com",
            "town_name"=> "Arlington",
            "location_type"=> "School",
            "group_id"=> "21"
        ]
    ];

    $output = array(
        "code" => "list_locations",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'records' => $result ?? [],
            'meta_data' => array(
                'total_records' => 2,
                'order' => 'desc',
                'order_by' => 'id',
                'page' => 1,
                'per_page' => 10,
                'search' => 'Arlington',
                'filters' => ["town_id"=> 1,"group_id"=> 21,"location_type_id"=> 1]
            )
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Get location request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_location_list(WP_REST_Request $request)
{
    $db_model = new E25_Locations_Model($request);
    $result = $db_model->get_locations();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "list_locations",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'records' => $result['records'] ?? [],
            'meta_data' => array(
                'total_records' => $result['row_count'] ?? 0,
                'order' => $request['order'],
                'order_by' => $request['order_by'],
                'page' => $request['page'],
                'per_page' => $request['per_page'],
                'search' => $request['search'],
                'filters' => $request['filters'] ?? [],
            )
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Validate create request data
 * @param WP_REST_Request $request
 * @return bool|WP_Error
 */
function e25_validate_location_request(WP_REST_Request $request)
{
    if (empty($request->get_param('group_id'))) {
        return new WP_Error('invalid_group_id', __('Invalid group id.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('town_id'))) {
        return new WP_Error('invalid_town_id', __('Invalid town id.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('location_type_id'))) {
        return new WP_Error('invalid_location_type_id', __('location type id.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('location_title'))) {
        return new WP_Error('invalid_location_title', __('location title can not be empty', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('first_name'))) {
        return new WP_Error('invalid_first_name', __('first name can not be empty', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('last_name'))) {
        return new WP_Error('invalid_last_name', __('last name can not be empty', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (!empty($request->get_param('email')) && !is_email($request->get_param('email'))) {
        return new WP_Error('invalid_email', __('Invalid email address.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    return true;
}

/**
 * Create location request callback function
 * @param WP_REST_Request $request
 * @return bool|int|WP_Error|WP_REST_Response
 */
function e25_create_location(WP_REST_Request $request)
{
    $validation_error = e25_validate_location_request($request);

    if (is_wp_error($validation_error)) {
        return $validation_error;
    }

    $db_model = new E25_Locations_Model($request);
    $result = $db_model->create_location();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "location_created",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'insert_id' => $result,
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Update location request callback function
 * @param WP_REST_Request $request
 * @return bool|int|WP_Error|WP_REST_Response
 */
function e25_update_location(WP_REST_Request $request)
{
    $validation_error = e25_validate_location_request($request);

    if (is_wp_error($validation_error)) {
        return $validation_error;
    }

    $db_model = new E25_Locations_Model($request);
    $result = $db_model->update_location();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "location_updated",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'updated_id' => $result,
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Delete location callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_delete_location(WP_REST_Request $request)
{

    $location_model = new E25_Locations_Model($request);

    if (empty($request->get_param('id'))) {
        return new WP_Error('invalid_location_id', __('Invalid location id.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $result = $location_model->delete_location();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "location_deleted",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'deleted_id' => $result,
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Get location details request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_location(WP_REST_Request $request)
{
    $db_model = new E25_Locations_Model($request);
    $result = $db_model->get_location();

    if (is_wp_error($result)) {
        return $result;
    }

    if (!$result['row_count']) {
        return new WP_Error('no_record_found', __('No records found.', 'e25'), array('status' => 404, 'message' => 'failed'));
    }

    $output = array(
        "code" => "list_location",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'records' => $result['records'] ?? [],
            'meta_data' => array(
                'id' => $request->get_param('id'),
                'total_records' => $result['row_count'] ?? 0,
            )
        ),
    );

    return new WP_REST_Response($output, 200);
}
