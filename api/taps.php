<?php

/**
 * Register town API endpoints
 */
add_action('rest_api_init', function () {
    $route_namespace = 'e25/v1';
    //get all taps records request
    register_rest_route($route_namespace, '/taps', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_get_taps_list',
        'args' => array(
            'order_by' => array(
                'required' => false,
                'default' => 'id',
                'type' => 'string',
                'description' => 'records order by field',
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
                'minimum' => -1,
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

    //get tap lead data request
    register_rest_route($route_namespace, '/taps/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'e25_get_tap_lead_data',
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

    //update tap lead data request
    register_rest_route($route_namespace, '/taps/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_update_tap_lead_data',
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

    //update tap lead data request (tmp endpoint for ipad)
    register_rest_route($route_namespace, '/taps/mobile/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_update_ipad_tap_lead_data',
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

    //update tap lead data bulk update request
    register_rest_route($route_namespace, '/taps-led-update/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_bulk_update_tap_lead_data',
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

    //delete tap request
    register_rest_route($route_namespace, '/taps/(?P<id>\d+)', array(
        'methods' => WP_REST_Server::DELETABLE,
        'callback' => 'e25_delete_tap',
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

    //bulk update tap lead data request
    register_rest_route($route_namespace, '/taps/bulk-update', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_bulk_update_tap_and_lead_data',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    //get data for results table
    register_rest_route($route_namespace, '/taps/all-results', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_get_all_tap_and_lead_data_results',
        'args' => array(
            'order_by' => array(
                'required' => false,
                'default' => 'id',
                'type' => 'string',
                'description' => 'records order by field',
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
                'minimum' => -1,
                'maximum' => 1000
            ),
            'page' => array(
                'required' => false,
                'default' => 1,
                'type' => 'integer',
                'description' => 'current page',
                'minimum' => 1,
            )
        ),
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    //add Tap data
    register_rest_route($route_namespace, '/taps/create', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_create_taps_data',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));

    register_rest_route($route_namespace, '/taps/download-sheet', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_download_taps_list',
        'args' => array(
            'order_by' => array(
                'required' => false,
                'default' => 'id',
                'type' => 'string',
                'description' => 'records order by field',
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
                'minimum' => -1,
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

    //sync Tap data
    register_rest_route($route_namespace, '/taps/sync', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'e25_sync_taps_data',
        'permission_callback' => function () {
            return is_user_logged_in();
        },
    ));
});

/**
 * Sync taps list request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_sync_taps_data(WP_REST_Request $request)
{

    $data_array = json_decode($request->get_body(), true);
    $db_model = new E25_Taps_Model($request);

    $result = $db_model->sync_tap_lead_data($data_array);

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "tap_lead_data_updated_bulk",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'result' => $result,
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Create taps list request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_create_taps_data(WP_REST_Request $request)
{
    $validation_error    =   e25_validate_tap_data_records($request);

    if (is_wp_error($validation_error)) {
        return $validation_error;
    }

    // check given location id is valid
    $db_model = new E25_Locations_Model($request);
    $result = $db_model->get_location_by_location_id();
    if ($result == 0) {
        return new WP_Error('invalid_location_id', __('Unable to find given location ID.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $tap_model   =   new E25_Taps_Model($request);
    $tap_insert  =   $tap_model->create_tap_data_record();

    if (is_wp_error($tap_insert)) {
        return $tap_insert;
    }

    $output = array(
        "code" => "create_tap_data_record",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'updated_id' => $tap_insert,
        ),
    );

    return new WP_REST_Response($output, 200);
}

/**
 * Get taps list request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_taps_list(WP_REST_Request $request)
{
    $db_model = new E25_Taps_Model($request);
    $result = $db_model->get_taps();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "list_taps",
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
 * Get taps list request callback function for download sheet
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_download_taps_list(WP_REST_Request $request)
{
    $db_model = new E25_Taps_Model($request);
    $result = $db_model->get_taps_to_download();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "list_taps",
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
 * Get tap lead data request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_get_tap_lead_data(WP_REST_Request $request)
{
    $db_model = new E25_Taps_Model($request);
    $result = $db_model->get_tap_lead_data($request->get_param('id'));

    if (is_wp_error($result)) {
        return $result;
    }

    if (!$result['row_count']) {
        return new WP_Error('no_record_found', __('No records found.', 'e25'), array('status' => 404, 'message' => 'failed'));
    }

    $output = array(
        "code" => "list_tap_lead_data",
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

/**
 * Tab and lead data update request callback function
 * @param WP_REST_Request $request
 * @return bool|WP_Error|WP_REST_Response
 */
function e25_update_tap_lead_data(WP_REST_Request $request)
{
    $validation_error = e25_validate_lead_data_record($request);

    if (is_wp_error($validation_error)) {
        return $validation_error;
    }

    $db_model = new E25_Taps_Model($request);
    $result = $db_model->update_tap_lead_data();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "tap_lead_data_updated",
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
 * Tab and lead data update request callback function (ipad tmp)
 * @param WP_REST_Request $request
 * @return bool|WP_Error|WP_REST_Response
 */
function e25_update_ipad_tap_lead_data(WP_REST_Request $request)
{
    $validation_error = e25_validate_lead_data_record($request, true);

    if (is_wp_error($validation_error)) {
        return $validation_error;
    }

    $db_model = new E25_Taps_Model($request);
    $result = $db_model->update_tap_lead_data();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "tap_lead_data_updated",
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
 * Tab and lead data update request callback function
 * @param WP_REST_Request $request
 * @return bool|WP_Error|WP_REST_Response
 */
function e25_bulk_update_tap_lead_data(WP_REST_Request $request)
{
    $validation_error = e25_validate_bulk_lead_data_record($request);

    if (is_wp_error($validation_error)) {
        return $validation_error;
    }

    $db_model = new E25_Taps_Model($request);
    $result = $db_model->bulk_update_lead_data();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "tap_lead_data_bulk_updated",
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
 * Validate tap data request
 * @param WP_REST_Request $request
 * @return bool|WP_Error
 */
function e25_validate_bulk_lead_data_record(WP_REST_Request $request)
{
    $first_draw =  e25_validate_lead_amounts($request->get_param('first_draw_lead_amount'), 'First draw lead amount');
    if ($first_draw) {
        return $first_draw;
    }

    $flush_lead     =    e25_validate_lead_amounts($request->get_param('flush_lead_amount'), 'Flush lead amount');
    if ($flush_lead) {
        return $flush_lead;
    }

    return true;
}


/**
 * Validate tap data request
 * @param WP_REST_Request $request
 * @return bool|WP_Error
 */
function e25_validate_tap_data_records(WP_REST_Request $request)
{
    if (empty($request->get_param('location_id'))) {
        return new WP_Error('invalid_location_id', __('Location ID can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('tap_detail'))) {
        return new WP_Error('invalid_tap_detail', __('Tap Details can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('first_draw_lab_id'))) {
        return new WP_Error('invalid_first_draw_lab_id', __('First Draw Lab ID can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('flush_lab_id'))) {
        return new WP_Error('invalid_flush_lab_id', __('Flush Lab ID can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('date_sampled'))) {
        return new WP_Error('invalid_date_sampled', __('Date Sampled can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
}


/**
 * Validate tap and lead data request
 * @param WP_REST_Request $request
 * @return bool|WP_Error
 */
function e25_validate_lead_data_record(WP_REST_Request $request, $is_ipad = false)
{
    if (empty($request->get_param('tap_detail'))) {
        return new WP_Error('invalid_tap_detail', __('tab detail can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('date_sampled'))) {
        return new WP_Error('invalid_date_sampled', __('date sampled can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('flush_lab_id'))) {
        return new WP_Error('invalid_flush_lab_id', __('flush lab id can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    if (empty($request->get_param('first_draw_lab_id'))) {
        return new WP_Error('invalid_first_draw_lab_id', __('first draw lab id can not be empty.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    if(!$is_ipad) {
        $first_draw =  e25_validate_lead_amounts($request->get_param('first_draw_lead_amount'), 'First draw lead amount');
        if ($first_draw) {
            return $first_draw;
        }

        $flush_lead     =    e25_validate_lead_amounts($request->get_param('flush_lead_amount'), 'Flush lead amount');
        if ($flush_lead) {
            return $flush_lead;
        }
    }

    return true;
}

// validate lead amounts before save
function e25_validate_lead_amounts($lead, $field)
{
    $field_name  =   str_replace('-', "_", sanitize_title($field));
    // check lead amount contain characters
    if ($lead === false) {
        return new WP_Error('invalid_' . $field_name, __($field . ' is Invalid', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    // check lead value has more than 2 decimal places
    if (preg_match('/\.\d{3,}/', $lead)) {
        return new WP_Error('invalid_' . $field_name, __($field . ' allows maximum two decimals', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    // check lead value is less than 0
    if ($lead < 0) {
        return new WP_Error('invalid_' . $field_name, __($field . ' should be more than 0', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    // check lead value exceed 1000
    if ($lead > 999.99) {
        return new WP_Error('invalid_' . $field_name, __($field . ' should be less than 1000', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
    return false;
}

/** Tab and lead data csv bulk update request callback function
 * @param WP_REST_Request $request
 * @return array|WP_Error|WP_REST_Response
 */
function e25_bulk_update_tap_and_lead_data(WP_REST_Request $request)
{
    try {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $reader->setReadDataOnly(true);
        $reader->setPreserveNullString(true);

        $file = $request->get_file_params();
        $file_data = $file['csv_file'];
        $file_temp_path = $file_data['tmp_name'];

        $spreadsheet = $reader->load($file_temp_path);
        $data_array = $spreadsheet->getSheet(0)->toArray();
        $spreadsheet->disconnectWorksheets();

        $db_model = new E25_Taps_Model($request);

        $result = $db_model->bulk_update_tap_lead_data($data_array);

        if (is_wp_error($result)) {
            return $result;
        }

        $output = array(
            "code" => "tap_lead_data_updated_bulk",
            "message" => "success",
            'data' => array(
                'status' => 200,
                'message' => 'success',
                'result' => $result,
            ),
        );

        return new WP_REST_Response($output, 200);
    } catch (Exception $error) {
        return new WP_Error('csv_file_error', __('invalid csv file', 'e25'), array('status' => 400, 'message' => 'failed'));
    }
}

/**
 * get all data by request table data
 * @param WP_REST_Request $request
 * @return WP_Error|WP_REST_Response
 */
function e25_get_all_tap_and_lead_data_results(WP_REST_Request $request)
{
    $db_model = new E25_Taps_Model($request);
    $result = $db_model->get_all_results_lead_data();

    if (is_wp_error($result)) {
        return $result;
    }

    if (!$result['row_count']) {
        return new WP_Error('no_record_found', __('No records found.', 'e25'), array('status' => 404, 'message' => 'failed'));
    }

    $output = array(
        "code" => "list_tap_lead_data",
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

/**
 * Delete tap request callback function
 * @param WP_REST_Request $request
 * @return WP_Error|WP_REST_Response
 */
function e25_delete_tap(WP_REST_Request $request)
{
    $db_model = new E25_Taps_Model($request);

    if (empty($request->get_param('id'))) {
        return new WP_Error('invalid_tap_id', __('Invalid tap id.', 'e25'), array('status' => 400, 'message' => 'failed'));
    }

    $result = $db_model->delete_tap();

    if (is_wp_error($result)) {
        return $result;
    }

    $output = array(
        "code" => "tap_deleted",
        "message" => "success",
        'data' => array(
            'status' => 200,
            'message' => 'success',
            'deleted_id' => $result,
        ),
    );

    return new WP_REST_Response($output, 200);
}
