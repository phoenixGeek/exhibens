<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Detail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    public function index($slug)
    {
        $this->load->model("page_model");
        $page = $this->page_model->get_by_field('slug', $slug);
        if ($page->status !== 'publish') {
            show_404();
        }
        if (empty($page)) {
            show_404();
        } 
        echo file_get_contents($page->content);
    }
}