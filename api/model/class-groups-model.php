<?php

class E25_Groups_Model extends E25_Database_Model
{
    private $table_name;

    /**
     * Constructor
     * @param $request
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->table_name = "wp_groups_group";
    }

    /**
     * Get all user groups
     * @return array|WP_Error
     */
    public function get_groups()
    {
        global $wpdb;
        $this->query = "SELECT * FROM {$this->table_name} ";
        $this->set_order_by();

        try {
            $query = $wpdb->prepare($this->query);
            $this->results = $wpdb->get_results($query);
            $this->row_count = $wpdb->num_rows;
            return $this->send_result();
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }

}
