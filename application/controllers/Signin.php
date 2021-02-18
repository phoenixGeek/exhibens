<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Signin extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function index()
    {
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');       
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required');
        if ($this->form_validation->run() === TRUE) {
            
            $email = $this->input->post("email");
            $password = $this->input->post("password");
            $remember = (bool) $this->input->post('remember');
            $login = $this->ion_auth->login($email, $password, $remember);

            if($login) {
                $current_user_data = $this->ion_auth->user()->row();
                if($this->ion_auth->in_group("Presentation Author",$current_user_data->id)) $redirect = '/Pa_dashboard';
                if($this->ion_auth->in_group("Admin",$current_user_data->id)) $redirect = '/dashboard';
                redirect($redirect, 'refresh');
            } else {
                $this->session->set_flashdata('ion_auth_error', $this->ion_auth->errors());    
                $this->render_view();            
            }
        } else {
            $this->render_view();
        }
    }

    public function render_view()
    {
        $this->data["flash_message"] = $this->session->flashdata('account_created');
        $this->data["error"] = $this->session->flashdata('ion_auth_error');
        $this->data['email'] = [
            'name' => 'email',
            'id' => 'email',
            'type' => 'email',
            'value' => $this->form_validation->set_value('email'),
            'placeholder' => "Enter Email",
            'class' => 'input100'
        ];

        $this->data['password'] = [
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',
            'placeholder' => 'Enter password',
            'class' => 'input100'
        ];

        $this->load->view("front-end/Signin",$this->data);
    } 
}