<?php

class E25_Taps_Model extends E25_Database_Model
{
    private $table_name;
    const ACTION_INSERT = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;

    /**
     * Constructor
     * @param $request
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->table_name = $this->prefix . "taps";
    }

    /**
     * Set records count
     * @return void
     */
    public function set_row_count()
    {
        $this->row_count = $this->get_total_records_count();
    }

    /**
     * Get all tap and lead data
     * @return array|WP_Error
     */
    public function get_taps()
    {
        global $wpdb;
        $this->query = "SELECT T.name AS town_name, L.location_title, TAP.id, TAP.tap_detail, TAP.first_draw_lab_id, 
    TAP.flush_lab_id, TAP.date_sampled, TAP.location_id, TAP.fixture_states, LD.first_draw_lead_amount, LD.flush_lead_amount, LD.date_analyzed, 
    PA.action AS planned_action, PA.id AS planned_action_id, IA.action AS immediate_action, IA.id AS immediate_action_id, 
    LD.immediate_action_date, PERA.action AS permanent_action, PERA.id AS permanent_action_id, LD.permanent_action_date
       FROM {$this->table_name} AS TAP " .
            " INNER JOIN {$this->prefix}locations AS L ON L.id = TAP.location_id " .
            " INNER JOIN {$this->prefix}towns AS T ON T.id = L.town_id " .
            " LEFT JOIN {$this->prefix}lead_data AS LD ON LD.tap_id = TAP.id " .
            " LEFT JOIN {$this->prefix}planned_actions AS PA ON PA.id = LD.planned_actions_id " .
            " LEFT JOIN {$this->prefix}immediate_actions AS IA ON IA.id = LD.immediate_actions_id " .
            " LEFT JOIN {$this->prefix}permanent_actions AS PERA ON PERA.id = LD.permanent_actions_id " .
            " WHERE TAP.is_synced = 1 ";
        $this->set_filters();
        $this->set_search();
        $this->set_order_by();
        $this->set_limit();
        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_results($query);
            $this->set_row_count();
            return $this->send_result();
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * Get all tap and lead data
     * @return array|WP_Error
     */
    public function get_all_results_lead_data()
    {
        global $wpdb;
        $this->table_name = $this->prefix . 'lead_data_view';
        $this->query = "SELECT * FROM {$this->table_name} ";
        $this->results_set_filters();
        $count_query    =    $this->query; // create query without limits
        $this->set_order_by();
        $this->set_limit();
        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_results($query);
            $this->results;
            $get_count  =   $wpdb->prepare($count_query);
            $this->row_count = count($wpdb->get_results($get_count));
            return $this->send_result();
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * Get all tap and lead data to download sheet
     * @return array|WP_Error
     */
    public function get_taps_to_download()
    {
        global $wpdb;
        $this->query = "SELECT T.name AS town_name, L.location_title, TAP.id, TAP.tap_detail, TAP.first_draw_lab_id, 
    TAP.flush_lab_id, TAP.date_sampled, TAP.location_id, LD.first_draw_lead_amount, LD.flush_lead_amount, LD.date_analyzed, 
    PA.action AS planned_action, PA.id AS planned_action_id, IA.action AS immediate_action, IA.id AS immediate_action_id, 
    LD.immediate_action_date, PERA.action AS permanent_action, PERA.id AS permanent_action_id, LD.permanent_action_date
       FROM {$this->table_name} AS TAP " .
            " INNER JOIN {$this->prefix}locations AS L ON L.id = TAP.location_id " .
            " INNER JOIN {$this->prefix}towns AS T ON T.id = L.town_id " .
            " LEFT JOIN {$this->prefix}lead_data AS LD ON LD.tap_id = TAP.id " .
            " LEFT JOIN {$this->prefix}planned_actions AS PA ON PA.id = LD.planned_actions_id " .
            " LEFT JOIN {$this->prefix}immediate_actions AS IA ON IA.id = LD.immediate_actions_id " .
            " LEFT JOIN {$this->prefix}permanent_actions AS PERA ON PERA.id = LD.permanent_actions_id " .
            " WHERE TAP.is_synced = 1 ";
        $this->set_filters();
        $this->set_search();
        $this->set_order_by();
        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_results($query);
            $this->set_row_count();
            return $this->send_result();
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * Get location records count function
     * @return string|WP_Error|null
     */
    public function get_total_records_count()
    {
        global $wpdb;
        $this->query = "SELECT COUNT(TAP.id) 
       FROM {$this->table_name} AS TAP " .
            " INNER JOIN {$this->prefix}locations AS L ON L.id = TAP.location_id " .
            " INNER JOIN {$this->prefix}towns AS T ON T.id = L.town_id " .
            " LEFT JOIN {$this->prefix}lead_data AS LD ON LD.tap_id = TAP.id " .
            " LEFT JOIN {$this->prefix}planned_actions AS PA ON PA.id = LD.planned_actions_id " .
            " LEFT JOIN {$this->prefix}immediate_actions AS IA ON IA.id = LD.immediate_actions_id " .
            " LEFT JOIN {$this->prefix}permanent_actions AS PERA ON PERA.id = LD.permanent_actions_id " .
            " WHERE TAP.is_synced = 1 ";
        $this->set_filters();
        $this->set_search();
        try {
            $query = $wpdb->prepare($this->query);
            return $wpdb->get_var($query);
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * DB query search function
     * @return void
     */
    public function set_search()
    {
        if (!empty($this->search)) {
            $this->query .= " AND (TAP.first_draw_lab_id LIKE '%%{$this->search}%%' " .
                " OR TAP.flush_lab_id LIKE '%%{$this->search}%%') ";
        }
    }

    /**
     * DB query filter function
     * @return void
     */
    public function set_filters()
    {
        if (!empty($this->filters) && is_array($this->filters)) {
            foreach ($this->filters as $key => $value) {
                if ($key == 'location_id' && !empty($value)) {
                    $this->query .= " AND TAP.location_id = '{$value}' ";
                }
                if ($key == 'date_sampled' && !empty($value)) {
                    $this->query .= " AND TAP.date_sampled = '{$value}' ";
                }
                if ($key == 'show_incomplete_data' && !empty($value) && $value === 'on') {
                    $this->query .= "  AND (LD.first_draw_lead_amount IS NULL OR LD.date_analyzed IS NULL OR LD.flush_lead_amount IS NULL OR IA.action IS NULL OR LD.immediate_action_date IS NULL OR PERA.action IS NULL OR LD.permanent_action_date IS NULL OR PA.action IS NULL) ";
                }
            }
        }
    }

    /**
     * DB query filter function to all results
     * @return void
     */
    public function results_set_filters()
    {
        if (!empty($this->filters) && is_array($this->filters)) {
            foreach ($this->filters as $key => $value) {
                if ($key == 'location_id' && !empty($value)) {
                    $this->query .= " WHERE location_id = '{$value}' ";
                }
            }
        }
    }

    /**
     * Get tap record by tap id
     * @return array|WP_Error
     */
    public function get_tap_lead_data($tap_id)
    {
        global $wpdb;
        $this->query = "SELECT T.name AS town_name, L.location_title, L.water_led_limit, TAP.id, TAP.fixture_states, TAP.tap_detail, TAP.fixture_states, TAP.first_draw_lab_id, 
    TAP.flush_lab_id, TAP.date_sampled, TAP.location_id, LD.first_draw_lead_amount, LD.flush_lead_amount, LD.date_analyzed, 
    PA.action AS planned_action, PA.id AS planned_action_id, IA.action AS immediate_action, IA.id AS immediate_action_id, 
    LD.immediate_action_date, PERA.action AS permanent_action, PERA.id AS permanent_action_id, LD.permanent_action_date
       FROM {$this->table_name} AS TAP " .
            " INNER JOIN {$this->prefix}locations AS L ON L.id = TAP.location_id " .
            " INNER JOIN {$this->prefix}towns AS T ON T.id = L.town_id " .
            " LEFT JOIN {$this->prefix}lead_data AS LD ON LD.tap_id = TAP.id " .
            " LEFT JOIN {$this->prefix}planned_actions AS PA ON PA.id = LD.planned_actions_id " .
            " LEFT JOIN {$this->prefix}immediate_actions AS IA ON IA.id = LD.immediate_actions_id " .
            " LEFT JOIN {$this->prefix}permanent_actions AS PERA ON PERA.id = LD.permanent_actions_id " .
            " WHERE TAP.is_synced = 1 AND TAP.id = {$tap_id} ";
        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_row($query);
            $this->row_count = $wpdb->num_rows;
            return $this->send_result();
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * Check lead data available for given tap id
     * @param $tap_id
     * @return int|WP_Error
     */
    public function check_tap_lead_data_exist($tap_id)
    {
        global $wpdb;
        $this->query = "SELECT * FROM {$this->prefix}lead_data " .
            " WHERE tap_id = {$tap_id} ";
        try {
            $query = $wpdb->prepare($this->query);
            $wpdb->get_row($query);
            return $wpdb->num_rows;
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * create tap data
     * @return WP_Error
     */
    public function create_tap_data_record()
    {
        global $wpdb;
        $this->results = $wpdb->insert(
            $this->prefix . 'taps',
            [
                'location_id' => $this->request->get_param('location_id'),
                'tap_detail' => $this->request->get_param('tap_detail'),
                'first_draw_lab_id' => $this->request->get_param('first_draw_lab_id'),
                'flush_lab_id' => $this->request->get_param('flush_lab_id'),
                'fixture_states' => $this->request->get_param('fixture_states'),
                'date_sampled' => $this->request->get_param('date_sampled'),
                'created_at' => current_datetime()->format('Y-m-d H:i:s'),
                'created_by' => get_current_user_id(),
                'updated_by' => get_current_user_id(),
                'is_synced' => 1
            ]
        );

        if ($this->results) {
            return $wpdb->insert_id;
        } else {
            return new WP_Error('db_error', __('Something went wrong', 'e25'), array('status' => 400, 'message' => 'failed'));
        }
    }

    /**
     * Update tap and lead data
     * @return WP_Error
     */
    public function bulk_update_lead_data()
    {
        global $wpdb;
        $tap_id = $this->request->get_param('id');
        try {
            //check if lead data is available. if yes update the existing record
            if ($this->check_tap_lead_data_exist($tap_id)) {
                $this->results = $wpdb->update(
                    $this->prefix . 'lead_data',
                    [
                        'first_draw_lead_amount' => $this->request->get_param('first_draw_lead_amount'),
                        'flush_lead_amount' => $this->request->get_param('flush_lead_amount'),
                        'date_analyzed' => $this->request->get_param('date_analyzed'),
                        'immediate_action_date' => $this->request->get_param('immediate_action_date'),
                        'permanent_action_date' => $this->request->get_param('permanent_action_date'),
                        'updated_at' => current_datetime()->format('Y-m-d H:i:s'),
                        'updated_by' => get_current_user_id()
                    ],
                    [
                        'tap_id' => $tap_id
                    ]
                );
            } else {
                $this->results = $wpdb->insert(
                    $this->prefix . 'lead_data',
                    [
                        'tap_id' => $tap_id,
                        'first_draw_lead_amount' => $this->request->get_param('first_draw_lead_amount'),
                        'flush_lead_amount' => $this->request->get_param('flush_lead_amount'),
                        'date_analyzed' => $this->request->get_param('date_analyzed'),
                        'immediate_action_date' => $this->request->get_param('immediate_action_date'),
                        'permanent_action_date' => $this->request->get_param('permanent_action_date'),
                        'created_at' => current_datetime()->format('Y-m-d H:i:s'),
                        'created_by' => get_current_user_id()
                    ]
                );
            }

            if ($this->results) {
                return $this->request->get_param('id');
            } else {
                return new WP_Error(
                    'db_error',
                    __('Something went wrong', 'e25'),
                    array('status' => 400, 'message' => 'failed',)
                );
            }
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * Update tap and lead data
     * @return WP_Error
     */
    public function update_tap_lead_data()
    {
        global $wpdb;
        $tap_id = $this->request->get_param('id');
        try {
            $this->results = $wpdb->update(
                $this->table_name,
                [
                    'tap_detail' => $this->request->get_param('tap_detail'),
                    'first_draw_lab_id' => $this->request->get_param('first_draw_lab_id'),
                    'flush_lab_id' => $this->request->get_param('flush_lab_id'),
                    'fixture_states' => $this->request->get_param('fixture_states'),
                    'date_sampled' => $this->request->get_param('date_sampled'),
                    'updated_at' => current_datetime()->format('Y-m-d H:i:s'),
                    'updated_by' => get_current_user_id()
                ],
                [
                    'id' => $tap_id
                ]
            );

            if ($this->results) {
                //check if lead data is available. if yes update the existing record
                if ($this->check_tap_lead_data_exist($tap_id)) {
                    $this->results = $wpdb->update(
                        $this->prefix . 'lead_data',
                        [
                            'first_draw_lead_amount' => $this->request->get_param('first_draw_lead_amount'),
                            'flush_lead_amount' => $this->request->get_param('flush_lead_amount'),
                            'date_analyzed' => $this->request->get_param('date_analyzed'),
                            'immediate_action_date' => $this->request->get_param('immediate_action_date'),
                            'permanent_action_date' => $this->request->get_param('permanent_action_date'),
                            'planned_actions_id' => $this->request->get_param('planned_actions_id'),
                            'permanent_actions_id' => $this->request->get_param('permanent_actions_id'),
                            'immediate_actions_id' => $this->request->get_param('immediate_actions_id'),
                            'updated_at' => current_datetime()->format('Y-m-d H:i:s'),
                            'updated_by' => get_current_user_id()
                        ],
                        [
                            'tap_id' => $tap_id
                        ]
                    );
                } else { // if lead data not exist insert a record
                    $this->results = $wpdb->insert(
                        $this->prefix . 'lead_data',
                        [
                            'tap_id' => $tap_id,
                            'first_draw_lead_amount' => $this->request->get_param('first_draw_lead_amount'),
                            'flush_lead_amount' => $this->request->get_param('flush_lead_amount'),
                            'date_analyzed' => $this->request->get_param('date_analyzed'),
                            'immediate_action_date' => $this->request->get_param('immediate_action_date'),
                            'permanent_action_date' => $this->request->get_param('permanent_action_date'),
                            'planned_actions_id' => $this->request->get_param('planned_actions_id'),
                            'permanent_actions_id' => $this->request->get_param('permanent_actions_id'),
                            'immediate_actions_id' => $this->request->get_param('immediate_actions_id'),
                            'created_at' => current_datetime()->format('Y-m-d H:i:s'),
                            'created_by' => get_current_user_id()
                        ]
                    );
                }
            }

            if ($this->results) {
                return $this->request->get_param('id');
            } else {
                return new WP_Error('db_error', __('Something went wrong', 'e25'), array('status' => 400, 'message' => 'failed'));
            }
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    // format date to save database
    function format_date_to_save_database($req_date)
    {
        if ($req_date) {
            if ($req_date != '-') {
                return date("Y-m-d", strtotime($req_date));
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    function format_empty_values($value)
    {
        if ($value != "") {
            if ($value == '-') {
                return null;
            } else {
                return filter_var($value, FILTER_VALIDATE_FLOAT);
            }
        } else {
            return null;
        }
    }

    /**
     * CSV bulk update tap and lead data
     * @param $data_array
     * @return array|WP_Error
     */
    function bulk_update_tap_lead_data($data_array)
    {
        $count = count($data_array);
        $updated_row_count = 0;
        $result = [];
        if ($count > 1 && is_array($data_array)) {

            array_shift($data_array);
            foreach ($data_array as $row) {

                $tap_id = $row[0];
                $tap_lead_data_request = new WP_REST_Request("POST", "/e25/v1/taps-led-update/{$tap_id}");

                $tap_lead_data_request->set_body_params([
                    "first_draw_lead_amount" => $this->format_empty_values($row[5]),
                    "flush_lead_amount" => $this->format_empty_values($row[6]),
                    "date_analyzed" => $this->format_date_to_save_database($row[7]),
                    "immediate_action_date" => $this->format_date_to_save_database($row[10]),
                    "permanent_action_date" => $this->format_date_to_save_database($row[12])
                ]);

                $response = rest_do_request($tap_lead_data_request);
                if ($response->is_error()) {
                    $result['records'][] = ['tap_id' => $tap_id, "message" => "Not updated", "errors" => $response->data['message']];
                } else {
                    $result['records'][] = ['tap_id' => $tap_id, "message" => "Success", "errors" => ""];
                    $updated_row_count++;
                }
            }
        } else {
            return new WP_Error('invalid_data', __('Invalid csv file or data', 'e25'), array('status' => 400, 'message' => 'failed'));
        }
        $result['meta_data'] = [
            'total_row_count' => $count - 1,
            'updated_row_count' => $updated_row_count
        ];
        return $result;
    }

    /**
     * sync tap and lead data
     * @param $data_array
     * @return array|WP_Error
     */
    function sync_tap_lead_data($data_array)
    {
        $count = count($data_array['tap_records']);
        if ($count != $data_array['total']) {
            return new WP_Error('invalid_data', __('Invalid data count', 'e25'), array('status' => 400, 'message' => 'failed'));
        }
        $location_id = $data_array['location_id'];
        $status = 'success';
        $updated_row_count = 0;
        $result = [];
        $success_ids = [];
        $tap_records = [];
        if ($count > 0 && is_array($data_array['tap_records'])) {
            foreach ($data_array['tap_records'] as $row) {
                $tap_request = new WP_REST_Request();
                $params = [];
                $tap_id = null;
                if (count($row) > 1) {
                    $params = array(
                        'tap_detail' => $row['tap_detail'],
                        'location_id' => $location_id,
                        'first_draw_lab_id' => $row['first_draw_lab_id'],
                        'flush_lab_id' => $row['flush_lab_id'],
                        'date_sampled' => $row['date_sampled'],
                        'first_draw_lead_amount' => isset($row['first_draw_lead_amount']) ? $row['first_draw_lead_amount'] : null,
                        'flush_lead_amount' => isset($row['flush_lead_amount']) ? $row['flush_lead_amount'] : null,
                        'date_analyzed' => isset($row['date_analyzed']) ? $row['date_analyzed'] : null,
                        'immediate_action_date' => isset($row['immediate_action_date']) ? $row['immediate_action_date'] : null,
                        'permanent_action_date' => isset($row['permanent_action_date']) ? $row['permanent_action_date'] : null,
                        'planned_actions_id' => isset($row['planned_actions_id']) ? $row['planned_actions_id'] : null,
                        'permanent_actions_id' => isset($row['permanent_actions_id']) ? $row['permanent_actions_id'] : null,
                        'immediate_actions_id' => isset($row['immediate_actions_id']) ? $row['immediate_actions_id'] : null
                    );
                }

                $tap_id = isset($row['tap_id']) ? $row['tap_id'] : 0;
                switch ($row['action']) {
                    case self::ACTION_INSERT:
                        $tap_request = new WP_REST_Request("POST", "/e25/v1/taps/create");
                        break;
                    case self::ACTION_UPDATE:
                        $tap_request = new WP_REST_Request("POST", "/e25/v1/taps/{$tap_id}");
                        break;
                    case self::ACTION_DELETE:
                        $params = [];
                        $tap_request = new WP_REST_Request("DELETE", "/e25/v1/taps/{$tap_id}");
                        break;
                }

                $tap_request->set_body_params($params);
                $response = rest_do_request($tap_request);
                if ($response->is_error()) {
                    $row['result'] = 'failed';
                    $status = 'error';
                } else {
                    $row['result'] = 'ok';
                    $updated_row_count++;
                    $success_ids[] = $row['id'];
                }

                $tap_records[] = $row;
            }
        } else {
            return new WP_Error('invalid_data', __('Invalid data', 'e25'), array('status' => 400, 'message' => 'failed'));
        }

        $result['location_id'] = $location_id;
        $result['tap_records'] = $tap_records;
        $result['status'] = $status;
        $result['ids'] = $success_ids;
        $result['total'] = $updated_row_count;
        return $result;
    }

    /**
     * Delete tap function
     * This will delete all the records from the DB which are related to the tap id
     * Related Tables: e25_lead_data
     * @return WP_Error
     */
    public function delete_tap()
    {
        global $wpdb;

        try {
            $this->results = $wpdb->delete($this->table_name, ['id' => $this->request->get_param('id')]);
            if ($this->results) {
                return $this->request->get_param('id');
            } else {
                return new WP_Error('db_error', __('Something went wrong', 'e25'), array('status' => 400, 'message' => 'failed'));
            }
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }
}
