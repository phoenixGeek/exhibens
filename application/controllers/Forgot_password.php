<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Forgot_password extends CI_Controller
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
        $this->data['title'] = $this->lang->line('forgot_password_heading');
        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');

        if ($this->form_validation->run() === FALSE) {
  
            $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');

            // set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->render_view();
        } else {
            
            $identity = $this->ion_auth->where("email", $this->input->post('email'))->users()->row();

            if (empty($identity)) {

                $this->ion_auth->set_error('forgot_password_email_not_found');

                $this->session->set_flashdata('message', $this->ion_auth->errors());
                //echo  $this->ion_auth->errors();
                redirect("/forgot_password", 'refresh');
            }


            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->email);
            echo "error";
            exit(1);
            
            if ($forgotten) {
                // if there were no errors

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("/forgot_password", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect("/forgot_password", 'refresh');
            }
        }
    }

    public function render_view()
    {
        $this->data["flash_message"] = $this->session->flashdata('message');
        
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

        $this->load->view("front-end/Forgot_password", $this->data);
    }
}