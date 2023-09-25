<?php

class E25_Towns_Model extends E25_Database_Model
{
    private $table_name;

    /**
     * Constructor
     * @param $request
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->table_name = $this->prefix . "towns";
    }

    /**
     * Set records row count
     * @return void
     */
    public function set_row_count(){
        $this->row_count = $this->get_total_records_count();
    }

    /**
     * Get all towns
     * @return array|WP_Error
     */
    public function get_towns()
    {
        global $wpdb;
        $this->query = "SELECT * FROM {$this->table_name} ";
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
     * Get towns records count
     * @return string|WP_Error|null
     */
    public function get_total_records_count()
    {
        global $wpdb;
        $this->query = "SELECT COUNT('id') FROM {$this->table_name} ";
        try {
            $query = $wpdb->prepare($this->query);
            return $wpdb->get_var($query);
        } catch (Exception $error) {
            return $this->send_error($error);
        }
    }
}
