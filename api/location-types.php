<?php

/**
 * Register town API endpoints
 */
add_action('rest_api_init', function () {
    $route_namespace = 'e25/v1';
    register_rest_route($route_namespace, '/location-types', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'e25_get_location_types',
        'args' => array(
            'order_by' => array(
                'required' => false,
                'default' => 'name',
                'type' => 'string',
                'description' => 'records order by field',
                'enum' => array(
                    'name',
                    'id',
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
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

/**
 * Get location types request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_location_types(WP_REST_Request $request)
{
    $db_model = new E25_Location_Types_Model($request);
    $result = $db_model->get_location_types();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "list_location_types",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'records' => $result['records'] ?? [],
            'meta_data' => array(
                'total_records' => $result['row_count'] ?? 0,
                'order' => $request['order'],
                'order_by' => $request['order_by'],
            )
        ),
    );

    return new WP_REST_Response($output, 200);
}


