<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Signup extends CI_Controller
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
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[users.email]');       
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() === TRUE) {
            
            $email = $this->input->post("email");
            $username = $email;
            $password = $this->input->post("password");
            $uid = $this->ion_auth->register($username, $password, $email);

            if($uid) {
                $this->session->set_flashdata('account_created', 'Your account was created sucessfully!');
                redirect('/signin', 'refresh');
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
        $this->data["flash_message"] = $this->session->flashdata('ion_auth_error');
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

        $this->data['password_confirm'] = [
            'name' => 'password_confirm',
            'id' => 'repassword',
            'type' => 'password',
            'placeholder' => 'Repeat password',
            'class' => 'input100'
        ];

        $this->load->view("front-end/Signup",$this->data);
    }
}