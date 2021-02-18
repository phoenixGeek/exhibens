<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Class Dashboard
 */
class Profile extends MY_Controller
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

            $this->c_user = $this->ion_auth->user()->row();
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
            $this->data['current_user'] = $this->c_user;
            $this->data['is_admin'] = $this->is_admin;
            $this->data['title'] = $this->lang->line('index_heading');
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        }
    }

    /**
     * Redirect if needed, otherwise display the user list
     */
    public function index()
    {

        $this->data['footer_script'] = [
            "dashboard/js/profile.js"
        ];
        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/profile', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }
    /**
     * Redirect if needed, otherwise display the user list
     */
    public function change_password()
    {
        $this->data['footer_script'] = [
            "dashboard/js/profile.js"
        ];
        $this->load->view('dashboard/header', $this->data);
        $this->load->view('dashboard/change-password', $this->data);
        $this->load->view('dashboard/footer', $this->data);
    }

    public function update_password() {

        $this->check_request_method('post');

        $this->form_validation->set_message('valid_password', 'The {field} field must be at least one lowercase letter.<br>' .
        'The {field} field must be at least one uppercase letter.<br>' .
        'The {field} field must have at least one number.<br>' .
        'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~') .
        '<br>The {field} field must be at least 5 characters in length.<br>' .
        'The {field} field cannot exceed 32 characters in length.<br>');
        $this->form_validation->set_rules('old', "Current password", 'required');
        $this->form_validation->set_rules('password', "Password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|valid_password|differs[old]|matches[re_password]');
        $this->form_validation->set_rules('re_password', "Repeat Password", 'required');
        
        if ($this->form_validation->run() !== TRUE) {
            
            $this->output([
                'error' => validation_errors()
            ], 400);
        
        } else {

            $this->load->model('Ion_auth_model', 'auth_model');
            $user_id = $this->c_user->id;
            if(!$this->auth_model->verify_password($this->input->post('old'), $this->c_user->password, $user_id)) {
                $this->output([
                    'error' => 'Current password is incorrect. Can not do this action'
                ], 401);
            }

            $data = [
                'password' => $this->input->post('password')
            ];
            
            
            $change = $this->ion_auth->update_user($user_id, $data);
            if ($change) {
                //if the password was successfully changed
                $this->output([
                    'msg' => "Your password is updated. You will be logout in 3s",
                    'redirect' => '/admin-login/logout'
                ]);

            } else {

                $this->output([
                    'error' => $this->ion_auth->errors()
                ], 400);
            }
        }
    }

    public function edit() {
        $this->check_request_method('post');
        $user_data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'phone'      => $this->input->post('phone'),
            'company'    => $this->input->post('company'),
            'username'   => $this->input->post('username'),
            'email'      => $this->input->post('email')
        ];
        $user_id = $this->c_user->id;
        $this->form_validation->set_message('edit_unique', 'This {field} is already taken');
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim');
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('username', $this->lang->line('create_user_validation_identity_label'), 'trim|required|alpha_numeric|edit_unique[users.username.'.$user_id.']');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|edit_unique[users.email.'.$user_id.']');

        if ($this->form_validation->run() !== TRUE) {
            $this->output([
                'error' => validation_errors()
            ], 400);
        } else {

            if (!empty($_FILES['avatar']['name'])) {
                $config['upload_path']   = './assets/avatar/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = '2048'; // 2MB
    
                $this->load->library('upload', $config);
    
                // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('avatar')) {
                    $this->output([
                        'error' => $this->upload->display_errors()
                    ], 400);
                
                } else {
    
                    $data = $this->upload->data();
                    $user_data['avatar'] = '/assets/avatar/'.$data['file_name'];         

                }
            }
            
            $updated = $this->ion_auth->update_user($user_id, $user_data);
            
            if ($updated) {
                $this->output([
                    'msg' => 'All your infos are updated',
                ]);
            }
            $this->output([
                'error' => 'Something went wrong'
            ], 400);
        }
    }

    protected function check_unique($val, $col) {
        return $this->db
            ->limit(1)
            ->get_where('users', array(
                    $col => $val,
                    'id !=' => $this->c_user->id
                )
            )
            ->num_rows() == 0;
    }
}