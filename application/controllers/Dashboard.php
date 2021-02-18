<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Dashboard
 */
class Dashboard extends MY_Controller
{
    public $data = [];
    public $c_user = null;
    public $is_admin = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->check_login_status();        
    }

    public function check_login_status()
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('signin', 'refresh');
        } else {

            $this->c_user   = $this->ion_auth->user()->row();
            $this->c_user->thumb = '';

            if (!empty($this->c_user->avatar)) {

                $full_file_path = $this->c_user->avatar;
                $ext = pathinfo($full_file_path, PATHINFO_EXTENSION);
                $this->c_user->thumb = str_replace(".$ext", "_thumb.$ext", $full_file_path);
                $this->c_user->thumb = $full_file_path;
                $this->c_user->avatar = $full_file_path;
            } else {

                $this->c_user->avatar = $this->c_user->thumb = base_url('assets/dashboard/img/avatars/user-default.jpg');
            }
            
            $this->is_admin = $this->ion_auth->is_admin();
            $this->data['is_admin']     = $this->is_admin;
            $this->data['current_user'] = $this->c_user;
            $this->data['title']        = $this->lang->line('index_heading');
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        }
    }

    public function check_is_admin () {
        if (!$this->is_admin) {
            show_error('You must be an administrator to view this page.');
        }
    }

    /**
     * Redirect if needed, otherwise display the user list
     */
    public function index()
    {        
        $this->check_is_admin();
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->data['users'] = $this->ion_auth->users()->result();

        $this->data['footer_script'] = [
            "dashboard/js/users.js",
            "dashboard/sumo-select/jquery.sumoselect.js"
        ];
        $this->data['stylesheet'] = [
            "dashboard/sumo-select/jquery.sumoselect.css"
        ];

        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/users', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function users()
    {
        $this->check_is_admin();
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->data['users'] = $this->ion_auth->users()->result();
        $this->data['footer_script'] = [
            "dashboard/js/users.js",
            "dashboard/sumo-select/jquery.sumoselect.js"
        ];
        $this->data['stylesheet'] = [
            "dashboard/sumo-select/jquery.sumoselect.css"
        ];

        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/users', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function groups()
    {
        $this->check_is_admin();
        $this->data['groups'] = $this->ion_auth->groups()->result();

        foreach ($this->data["groups"] as $key => $g) {
            $g->num_of_users = count($this->ion_auth->users($g->id)->result());
        }

        $this->data['footer_script'] = [
            "dashboard/js/groups.js",
            "dashboard/sumo-select/jquery.sumoselect.js"
        ];
        $this->data['stylesheet'] = [
            "dashboard/sumo-select/jquery.sumoselect.css"
        ];

        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/groups', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function preview($id)
    {
        $this->load->model("page_model");
        $page = $this->page_model->get_by_field('id', $id);
        if (empty($page)) {
            show_404();
        }
        echo file_get_contents($page->content);
    }

    public function group_detail($gid)
    {
        $this->check_is_admin();
        $all_users = $this->ion_auth->users()->result();
        $user_not_in_group = array();
        foreach ($all_users as $key => $u) {
            if (!$this->ion_auth->in_group($gid, $u->id)) {
                array_push(
                    $user_not_in_group,
                    array(
                        'id' => $u->id,
                        'email' => $u->email,
                        'fullname' => $u->first_name . " " . $u->last_name
                    )
                    
                );
            }
        }
        
        $data2 = array(
            "group"  => $this->ion_auth->group($gid)->row(),
            "users" => $this->ion_auth->users($gid)->result(),
            "user_not_in_group" => $user_not_in_group,
            "current_user" => $this->c_user,
            'is_admin' => $this->is_admin
        );

        $data2['footer_script'] = [
            "dashboard/js/group-detail.js",
            "dashboard/js/typeahead.jquery.min.js"
        ];

        $this->load->view('dashboard/header', $data2);
        $this->load->view('dashboard/group-detail', $data2);
        $this->load->view('dashboard/footer', $data2);
    }

    public function pages()
    {
        $this->check_is_admin();
        $this->load->model("page_model");
        $this->load->model("categories_model", 'category');
        $this->data['categories'] = $this->category->get_table();
        $pages = $this->page_model->get_pages_list();
        
        foreach ($pages as &$page) {
            $page->categories = $this->category->get_category_by_page($page->id);
        }

        $this->data['pages'] = $pages;
        $this->data['type'] = 'pages';
        
        $this->data['footer_script'] = [
            "dashboard/js/pages.js",
            "dashboard/sumo-select/jquery.sumoselect.js"
        ];

        $this->data['stylesheet'] = [
            "dashboard/sumo-select/jquery.sumoselect.css"
        ];

        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/pages', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function posts()
    {
        $this->check_is_admin();
        $this->load->model("page_model");
        $this->load->model("categories_model", 'category');
        $this->data['categories'] = $this->category->get_table();
        $pages = $this->page_model->get_pages_list(2);
        
        foreach ($pages as &$page) {
            $page->categories = $this->category->get_category_by_page($page->id);
        }

        $this->data['pages'] = $pages;
        $this->data['type'] = 'post';

        $this->data['footer_script'] = [
            "dashboard/js/pages.js",
            "dashboard/sumo-select/jquery.sumoselect.js"
        ];

        $this->data['stylesheet'] = [
            "dashboard/sumo-select/jquery.sumoselect.css"
        ];

        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/pages', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function categories()
    {
        $this->check_is_admin();
        $this->data['footer_script'] = [
            "plugins/jstree/jstree.min.js",
            "dashboard/js/categories.js?v=" . time()
        ];
        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/category', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function edit_page($page_id) 
    {
        $this->check_is_admin();
        $this->load->model("page_model");
        $page = $this->page_model->get_by_field('id', $page_id);
        if (empty($page)) {
            show_404();
        } else {
            $data = array_merge(
                (array) $page, 
                [
                    'title' => $page->page_title
                ]
            );
            $this->load->view('dashboard/pagebuilder/editor', $data);
        }
    }
}