<?php
class MY_Controller extends CI_Controller
{
    var $table_name;
    var $table_structure;
    var $inputForm;
    var $inputFormConfig;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model("MY_Model");        
        $this->load->library(['form_validation']);        
    }
    
    public function init_dbobj($db_config)
    {
        $this->table_name = $db_config["table_name"];
        $this->table_structure = (!isset($db_config["structure"])) ? $this->MY_Model->get_table_structure($this->table_name) : $db_config["structure"];
        $this->inputFormConfig = (!isset($db_config["input_form"])) ? null : $db_config["input_form"];
    }

    function get_input_form()
    {
        $method = (!isset($this->inputFormConfig["method"])) ? "POST" : $this->inputFormConfig["method"];
        $action = (!isset($this->inputFormConfig["action"])) ? "create_record" : $this->inputFormConfig["action"];
        $except = (!isset($this->inputFormConfig["except"])) ? array() : $this->inputFormConfig["except"];
        $form = "<form method ='{$method}' action='{$action}'>";
        $is_csrf_protection = $this->config->item('csrf_protection');
        $csfrInput = ($is_csrf_protection) ? "<input type='hidden' name='{$this->security->get_csrf_token_name()}' value='{$this->security->get_csrf_hash()}'>" : "";
        $inputField = "";

        foreach ($this->table_structure as $i => $row) {
            if ($row["Extra"] !== "auto_increment" && $row["Key"] !== "PRI" && ($except == null ||  !in_array($row['Field'],$except) ) ) {

                $require = ($row["Null"] == 'NO') ? "required" : "";
                $inputField .= "<input type='text' name='{$row["Field"]}' {$require}>";
            }
        }
        $submitBtn = "<input type='submit' name='hn87_submit' value='Submit'>";

        $form .= $csfrInput . $inputField . $submitBtn . "</form>"; 
        return $form;       
    }
    /**
     * Undocumented function
     *
     * @param string $fields Fields list 
     * @param string $method
     * @param string $action
     * @return void
     */

    public function create_record()
    {
        if(isset($_POST["hn87_submit"])){
            $except = (!isset($this->inputFormConfig["except"])) ? array() : $this->inputFormConfig["except"];
            //can update phan nay de them cac rule khac : unique,digit ....
            foreach ($this->table_structure as $i => $row) {
                if ($row["Extra"] !== "auto_increment" && $row["Key"] !== "PRI" && ($except == null ||  !in_array($row['Field'],$except) ) ) {
                    if ($row["Null"] == 'NO') {
                        $this->form_validation->set_rules($row['Field'], $row['Field'], 'required');
                    }
                }
            }

            if ($this->form_validation->run()){
                $inputData = array();
                foreach ($this->table_structure as $field) {
                    if ($field["Extra"] !== "auto_increment" && $field["Key"] !== "PRI"){
                        if(!empty($this->input->post($field['Field']))) $inputData["{$field['Field']}"] = $this->input->post($field['Field']);
                    }                    
                }
                $this->MY_Model->insert($this->table_name,$inputData);
                
                $redirect_uri = (!isset($this->inputFormConfig["redirect_uri"])) ? "" : $this->inputFormConfig["redirect_uri"];
                $newid = $this->db->insert_id();
                if(!$newid){
                    $newid = -1;
                }
                redirect($redirect_uri."/".$newid);
            } else {
                echo "Form Validation False";
            }
            
        }
    }

    protected function check_request_method($method)
    {
        // Lower case method
        $method = strtolower($method);

        if ($this->input->method(FALSE) !== $method) {
            
            $this->output([
                'error' => 'Can not access this source'
            ], 401, false);
        
        }
    }

    protected function output ($data, $status = 200, $render_crsf = true)
    {
        if ($render_crsf) {
            $data = array_merge(
                $data,
                $this->render_new_crsf()
            );
        }
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data))
            ->_display();
        exit;
    }

    protected function render_new_crsf()
    {
        return [
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ];
    }
}