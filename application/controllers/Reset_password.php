<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class Reset_Password extends CI_Controller
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

    public function index($code = NULL)
    {
        if (!$code && $this->input->post() == NULL) {
            show_404();
        }
        $this->data['title'] = $this->lang->line('reset_password_heading');
        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user !== NULL) {

            $this->form_validation->set_rules('password', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[password]');
            $this->form_validation->set_rules('password_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() === FALSE) {

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['password'] = [
                    'name' => 'password',
                    'id' => 'password',
                    'type' => 'password',
                    'placeholder' => 'New Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['password_confirm'] = [
                    'name' => 'password_confirm',
                    'id' => 'password_confirm',
                    'type' => 'password',
                    'placeholder' => 'Verify Password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                ];
                $this->data['user_id'] = [
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                ];

                $this->data['code'] = $code;
                $this->render_view();
            } else {

                $identity = $user->email;
                if ($user->id != $this->input->post('user_id')) {

                    $this->ion_auth->clear_forgotten_password_code($identity);
                    show_error($this->lang->line('error_csrf'));
                } else {

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('password'));
                    if ($change) {

                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("/signin", 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("/forgot_password", 'refresh');
        }
    }

    public function render_view()
    {
        $this->data["flash_message"] = $this->session->flashdata('message');
        $this->load->view("front-end/Reset_password", $this->data);
    }

    /**
     * @return array A CSRF key-value pair
     */
    public function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);
        return [$key => $value];
    }

    /**
     * @return bool Whether the posted CSRF token matches
     */
    public function _valid_csrf_nonce()
    {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        }
        return FALSE;
    }
}