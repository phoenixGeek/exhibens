<?php
class Segment extends MY_Controller
{
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
    public function index($pid, $segid)
    {
        $this->data['footer_script'] = [
            "pa_dashboard/segment.js",
        ];

        $ip = $this->input->ip_address();
        $segment = $this->pa_model->getSegmentById($pid, $segid)[0];
        $presentation = $this->pa_model->getPresentationById($pid);
        if(!$segment->is_composite) {
            $data = array(
                "ipaddress" => $ip,
                "pid"       => $pid,
                "segid"     => $segid,
                'name'      => $segment->name,
                'content'   => $segment->content,
                'uid'       => $this->ion_auth->user()->row()->id
            );
            $this->pa_model->insertSegmentLog($data);
        }

        if(!$presentation->public) {
            $this->check_login_status();
        }

        $segments_added = $this->pa_model->getSegmentById($pid, $segid);
        
        foreach($segments_added as $s){
            $s->duration_in_format = gmdate("H:i:s", $s->duration);
        } 

        // $body_data = array(
        //     "videos"  => $this->pa_model->getVideoList($this->ion_auth->user()->row()->id),
        //     "presentation_data" => $this->pa_model->getPresentationById($pid),
        //     "videos_added" => $this->pa_model->getVideosById($pid, $segid),
        //     "segments_added" => $segments_added
        // );


        
        $body_data = array(
            "videos_added" => $this->pa_model->getVideosById($pid, $segid),
            "segments_added" => $this->pa_model->getSegmentById($pid, $segid),
            "segments" => $this->pa_model->getSegmentById($pid, $segid),
            "presentation" => $presentation,
            "author" => $this->ion_auth->user($presentation->uid)->row()
        );
        
        // var_dump($body_data);
        // exit(1);
        $this->load->view("public-segment/header");
        $this->load->view("public-segment/main", $body_data);
        $this->load->view("public-segment/footer", $this->data);
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