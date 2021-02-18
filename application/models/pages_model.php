<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion Auth Model
 * @property Ion_auth $ion_auth The Ion_auth library
 */
class Pages_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_data($data)
    {
        $this->db->insert('pages', $data);
    }
    
    public function test()
    {
        echo "hello";
    }
}