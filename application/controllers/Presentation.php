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
    }
    public function index($pid)
    {
        $presentation = $this->pa_model->getPresentationById($pid);
        $data = array(
            "segments" => $this->pa_model->getSegments($pid),
            "presentation" => $presentation,
            "author" => $this->ion_auth->user($presentation->uid)->row()
        );
        
        $this->load->view("presentation/header");
        $this->load->view("presentation/main", $data);
        $this->load->view("presentation/footer");
    }
}