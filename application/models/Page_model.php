<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion Auth Model
 * @property Ion_auth $ion_auth The Ion_auth library
 */
require_once("application/core/CRUD_Model.php");

class Page_model extends CRUD_model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'pages';
    }

    public function get_pages_list($type = 1)
    {
        $q = $this->db->where(['type' => $type])->get($this->table);
        return $q->result();
    }
}