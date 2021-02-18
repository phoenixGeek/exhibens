<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CRUD_model extends CI_Model
{
    protected $table;
    public function __construct()
    {
        parent::__construct();
    }
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }
    
    public function update($where, $data_to_update)
    {
        $this->db->where($where);
        return $this->db->update($this->table, $data_to_update);
    }
    
    public function delete($where)
    {
        return $this->db->delete($this->table, $where);
    }

    public function get_paging($page = 1, $per_page = 10, $where = '1', $order_by = 'id', $order = 'desc')
    {
        $offset = ( $page - 1) * $per_page;
        return $this->db
                ->where($where)
                ->limit($per_page, $offset)
                ->order_by($order_by, $order)
                ->get($this->table)
                ->result();
    }

    public function get_table()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_field($field, $value)
    {
        $data = $this->db
                ->where($field, $value)
                ->get($this->table)
                ->result();
        if(count($data)) return $data[0];
        return 0;
    }
}