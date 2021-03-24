<?php
class Presentation extends MY_Controller
{
    public $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("pa_model");
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }
    public function index($pid)
    {
        $pos = strpos($pid, 'pre');
        if($pos === false) {
            $presentation = $this->pa_model->getPresentationById($pid);
            if(!$presentation->public) {
                $this->check_login_status();
            }
            $data = array(
                "segments" => $this->pa_model->getSegments($pid),
                "presentation" => $presentation,
                "author" => $this->ion_auth->user($presentation->uid)->row(),
                "segsForComp"   => $this->pa_model->getSegmentsForComp($pid)
            );
        } else {
            $presentation = $this->pa_model->getPresentationByPublicURL($pid);
            if(!$presentation->public) {
                $this->check_login_status();
            }
            $data = array(
                "segments" => $this->pa_model->getSegments($presentation->id),
                "presentation" => $presentation,
                "author" => $this->ion_auth->user($presentation->uid)->row(),
                "segsForComp"   => $this->pa_model->getSegmentsForComp($pid)
            );
        }

        $this->load->view("presentation/header");
        $this->load->view("presentation/main", $data);
        $this->load->view("presentation/footer");
    }

    public function check_login_status()
    {
        if (!$this->ion_auth->logged_in()) {
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
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        }
    }

}