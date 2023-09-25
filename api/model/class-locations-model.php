<?php

class E25_Locations_Model extends E25_Database_Model
{
    private $table_name;

    /**
     * Constructor
     * @param $request
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->table_name = $this->prefix . "locations";
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
     * Get all locations data
     * @return array|WP_Error
     */
    public function get_locations()
    {
        global $wpdb;
        $this->query = "SELECT L.id, L.location_title, L.location_desc, L.water_led_limit, L.latitude, L.longitude, L.first_name, L.last_name, L.telephone,
       L.email, T.name AS town_name, LT.name AS location_type, L.group_id
       FROM {$this->table_name} AS L " .
            " INNER JOIN {$this->prefix}towns AS T ON L.town_id = T.id " .
            " INNER JOIN {$this->prefix}location_types AS LT ON L.location_type_id = LT.id " .
            "WHERE 1 ";
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
     * Get location records count function
     * @return string|WP_Error|null
     */
    public function get_total_records_count()
    {
        global $wpdb;
        $this->query = "SELECT COUNT(L.id) FROM {$this->table_name} AS L" .
            " INNER JOIN {$this->prefix}towns AS T ON L.town_id = T.id " .
            " INNER JOIN {$this->prefix}location_types AS LT ON L.location_type_id = LT.id ";
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
            $this->query .= " AND (L.location_title LIKE '%%{$this->search}%%' " .
                " OR L.id LIKE '%%{$this->search}%%' " .
                " OR L.telephone LIKE '%%{$this->search}%%' " .
                " OR CONCAT(L.first_name, ' ', L.last_name) LIKE '%%{$this->search}%%' " .
                " OR T.name LIKE '%%{$this->search}%%') ";
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
                if ($key == 'town_id' && !empty($value)) {
                    $this->query .= " AND T.id = '{$value}' ";
                }
                if ($key == 'group_id' && !empty($value)) {
                    $this->query .= " AND L.group_id = '{$value}' ";
                }
                if ($key == 'location_type_id' && !empty($value)) {
                    $this->query .= " AND L.location_type_id = '{$value}' ";
                }
            }
        }
    }

    /**
     * Create location function
     * @return int|WP_Error
     */
    public function create_location()
    {
        global $wpdb;

        try {
            $this->results = $wpdb->insert(
                $this->table_name,
                [
                    'location_type_id' => $this->request->get_param('location_type_id'),
                    'town_id' => $this->request->get_param('town_id'),
                    'group_id' => $this->request->get_param('group_id'),
                    'location_title' => $this->request->get_param('location_title'),
                    'location_desc' => $this->request->get_param('location_desc'),
                    'water_led_limit' => $this->request->get_param('water_led_limit'),
                    'latitude' => $this->request->get_param('latitude'),
                    'longitude' => $this->request->get_param('longitude'),
                    'first_name' => $this->request->get_param('first_name'),
                    'last_name' => $this->request->get_param('last_name'),
                    'telephone' => $this->request->get_param('telephone'),
                    'email' => $this->request->get_param('email'),
                    'created_at' => current_datetime()->format('Y-m-d H:i:s'),
                    'created_by' => get_current_user_id()
                ]
            );

            if ($this->results) {
                return $wpdb->insert_id;
            } else {
                return new WP_Error('db_error', __('Something went wrong', 'e25'), array('status' => 400, 'message' => 'failed'));
            }
        } catch (Exception $error) {

            return $this->send_error($error);
        }
    }

    /**
     * Update location function
     * @return WP_Error
     */
    public function update_location()
    {
        global $wpdb;

        try {
            $this->results = $wpdb->update(
                $this->table_name,
                [
                    'location_type_id' => $this->request->get_param('location_type_id'),
                    'town_id' => $this->request->get_param('town_id'),
                    'group_id' => $this->request->get_param('group_id'),
                    'location_title' => $this->request->get_param('location_title'),
                    'location_desc' => $this->request->get_param('location_desc'),
                    'water_led_limit' => $this->request->get_param('water_led_limit'),
                    'latitude' => $this->request->get_param('latitude'),
                    'longitude' => $this->request->get_param('longitude'),
                    'first_name' => $this->request->get_param('first_name'),
                    'last_name' => $this->request->get_param('last_name'),
                    'telephone' => $this->request->get_param('telephone'),
                    'email' => $this->request->get_param('email'),
                    'updated_at' => current_datetime()->format('Y-m-d H:i:s'),
                    'updated_by' => get_current_user_id()
                ],
                [
                    'id' => $this->request->get_param('id')
                ]
            );

            if ($this->results) {
                return $this->request->get_param('id');
            } else {
                return new WP_Error('db_error', __('Something went wrong', 'e25'), array('status' => 400, 'message' => 'failed'));
            }
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

    /**
     * Delete location function
     * This will delete all the records from the DB which are related to the location id
     * Related Tables: e25_taps, e25_lead_data
     * @return WP_Error
     */
    public function delete_location()
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

    /**
     * Get location by location id
     * @return array|WP_Error
     */
    public function get_location_by_location_id()
    {
        global $wpdb;
        $this->query = "SELECT * FROM {$this->prefix}locations WHERE id={$this->request->get_param('location_id')}";
        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_row($query);
            return $wpdb->num_rows;
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }


    /**
     * Get a location by id
     * @return array|WP_Error
     */
    public function get_location()
    {
        global $wpdb;
        $this->query = "SELECT L.id, L.location_title, L.latitude, L.water_led_limit, L.location_desc, L.longitude, L.first_name, L.last_name, L.telephone,
       L.email, T.name AS town_name, T.id AS town_id, LT.name AS location_type, LT.id AS location_type_id, L.group_id
       FROM {$this->table_name} AS L " .
            " INNER JOIN {$this->prefix}towns AS T ON L.town_id = T.id " .
            " INNER JOIN {$this->prefix}location_types AS LT ON L.location_type_id = LT.id " .
            "WHERE L.id = {$this->request->get_param('id')}";

        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_row($query);
            $this->row_count = $wpdb->num_rows;
            return $this->send_result();
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }
}
