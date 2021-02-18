<?php
class MY_Model extends CI_Model
{
    var $table_name = "";
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function set_table_name($data_table_name)
    {
        $this->table_name = $data_table_name;
    }

    public function get_table_structure($table_name)
    {
        $query = "DESC {$table_name}";
        return $this->db->query($query)->result_array();
    }

    public function insert($table_name,$data)
    {
        $this->db->insert($table_name, $data);
    }
}