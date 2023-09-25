<?php

/**
 * Register town API endpoints
 */
add_action('rest_api_init', function () {
    $route_namespace = 'e25/v1';
    register_rest_route($route_namespace, '/towns', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'e25_get_town_list',
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
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

/**
 * Get towns request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_town_list(WP_REST_Request $request)
{
    $db_model = new E25_Towns_Model($request);
    $result = $db_model->get_towns();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "list_towns",
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
            )
        ),
    );

    return new WP_REST_Response($output, 200);
}


