<?php

/**
 * Register town API endpoints
 */
add_action('rest_api_init', function () {
    $route_namespace = 'e25/v1';
    //TODO Remove this route later. use meta-data endpoint
    register_rest_route($route_namespace, '/settings', array(
        'methods' => 'GET',
        'callback' => 'e25_get_settings',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
    register_rest_route($route_namespace, '/meta-data', array(
        'methods' => 'GET',
        'callback' => 'e25_get_settings',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

/**
 * Get settings request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_settings(WP_REST_Request $request)
{
    $request->set_param('order_by','name');
    $town_model = new E25_Towns_Model($request);
    $towns = $town_model->get_towns();

    $location_type_model = new E25_Location_Types_Model($request);
    $location_types = $location_type_model->get_location_types();

    $group_model = new E25_Groups_Model($request);
    $groups = $group_model->get_groups();

    $request->set_param('order_by','action');
    $planned_action_model = new E25_Planned_Actions_Model($request);
    $planned_actions = $planned_action_model->get_planned_action_items();

    $permanent_action_model = new E25_Permanent_Actions_Model($request);
    $permanent_actions = $permanent_action_model->get_permanent_action_items();

    $immediate_action_model = new E25_Immediate_Actions_Model($request);
    $immediate_actions = $immediate_action_model->get_immediate_action_items();

    if (is_wp_error($towns)) {
        return $towns;
    }
    if (is_wp_error($location_types)) {
        return $location_types;
    }
    if (is_wp_error($groups)) {
        return $groups;
    }
    if (is_wp_error($planned_actions)) {
        return $planned_actions;
    }
    if (is_wp_error($permanent_actions)) {
        return $permanent_actions;
    }
    if (is_wp_error($immediate_actions)) {
        return $immediate_actions;
    }

    $output = array(
        "code" => "list_settings",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'records' => [
                'towns'=> $towns['records'] ?? [],
                'location_types'=> $location_types['records'] ?? [],
                'groups'=> $groups['records'] ?? [],
                'planned_action_items'=> $planned_actions['records'] ?? [],
                'permanent_action_items'=> $permanent_actions['records'] ?? [],
                'immediate_action_items'=> $immediate_actions['records'] ?? [],
            ]
        ),
    );

    return new WP_REST_Response($output, 200);
}




