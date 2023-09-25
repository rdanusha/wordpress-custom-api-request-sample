<?php

class E25_Immediate_Actions_Model extends E25_Database_Model
{
    private $table_name;

    /**
     * Constructor
     * @param $request
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->table_name = $this->prefix . "immediate_actions";
    }

    /**
     * Get all action items
     * @return array|WP_Error
     */
    public function get_immediate_action_items()
    {
        global $wpdb;
        $this->query = "SELECT * FROM {$this->table_name} ";
        $this->set_order_by();
        $this->set_limit();

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
