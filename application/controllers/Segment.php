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
    }
    public function index($pid, $segid)
    {
        $presentation = $this->pa_model->getPresentationById($pid);
        $data = array(
            "videos" => $this->pa_model->getVideosById($pid, $segid),
            "segments" => $this->pa_model->getSegmentById($pid, $segid),
            "presentation" => $presentation,
            "author" => $this->ion_auth->user($presentation->uid)->row()
        );
        
        $this->load->view("public-segment/header");
        $this->load->view("public-segment/main", $data);
        $this->load->view("public-segment/footer");
    }
}