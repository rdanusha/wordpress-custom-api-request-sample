<?php

class E25_Database_Model
{
    protected $prefix;
    protected $page;
    protected $per_page;
    protected $order;
    protected $order_by;
    protected $search;
    protected $filters;
    protected $offset;
    protected $results;
    protected $row_count;
    protected $request;
    protected $query;

    /**
     * DB model constructor
     * @param $request
     */
    public function __construct($request)
    {
        $this->prefix = "e25_";
        $this->request = $request;
        $this->page = $request->get_param('page');
        $this->per_page = $request->get_param('per_page');
        $this->order = $request->get_param('order');
        $this->order_by = $request->get_param('order_by');
        $this->search = $request->get_param('search');
        $this->filters = $request->get_param('filters');
        $this->offset = ($this->page - 1) * $this->per_page;
    }

    /**
     * DB model send results function
     * @return array
     */
    protected function send_result()
    {
        return array(
            'records' => $this->results,
            'row_count' => $this->row_count
        );
    }

    /**
     * DB model send error messages function
     * @param $error
     * @return WP_Error
     */
    protected function send_error($error)
    {
        return new WP_Error(
            'db_error',
            $error->getMessage(),
            array(
                'status' => 200,
                'message' => 'fail',
            )
        );
    }

    /**
     * DB query order by function
     * @return void
     */
    protected function set_order_by()
    {
        if (!empty($this->order_by)) {
            $this->query .= " ORDER BY `{$this->order_by}` {$this->order} ";
        }
    }

    /**
     * DB query set limit function
     * @return void
     */
    protected function set_limit()
    {
        if (!empty($this->per_page)) {
            $this->query .= " LIMIT {$this->offset},{$this->per_page} ";
        }
    }
}
