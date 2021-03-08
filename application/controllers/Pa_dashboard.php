<?php
defined('BASEPATH') or exit('No direct script access allowed');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', 'debug.log');

/**
 * Class Dashboard
 */
class Pa_dashboard extends MY_Controller
{
    public $data = [];
    public $c_user = null;
    public $is_admin = null;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("pa_model");
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
        $this->check_login_status();

        // Create a FFmpeg Instance
        $this->ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => 'c:\ffmpeg\bin\ffmpeg.exe',
            'ffprobe.binaries' => 'c:\ffmpeg\bin\ffprobe.exe' 
        ]);
    }

    public function check_login_status()
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
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
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        }
    }

    public function index()
    {
        $this->data['footer_script'] = [
            "pa_dashboard/create_presentation.js",
            "pa_dashboard/clipboard.min.js"
        ];

        $this->data["presentations_list"] = $this->pa_model->get_presentations_list($this->ion_auth->user()->row()->id);
        $this->load->view('pa/header', $this->data);
        $this->load->view('pa/presentations');
        $this->load->view('pa/footer', $this->data);
    }
    
    public function video_upload()
    {
        $this->data['footer_script'] = [
            "pa_dashboard/upload.js"
        ];

        $this->load->view('pa/header', $this->data);
        $this->load->view('pa/upload', array('error' => ' '));
        $this->load->view('pa/footer',  $this->data);
    }

    public function do_upload()
    {
        $config['upload_path']     = './uploads/';
        $config['allowed_types']   = 'mp4|mov|webm|flv|avi';
        $config['max_size']        = 1024 * 1024 * 35;
        $config['encrypt_name']    = TRUE;
        $thumbs_dir = '/uploads/thumbs/';
        $thumb_path = $thumbs_dir . "video-default-thumbnail.jpg";
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('upload', $error);
        } else {

            $upload_data = $this->upload->data();
            $thumb_name = $upload_data["file_name"];
            if ($this->input->post("thumbnail-base64")) {

                if (preg_match('/data:([^;]*);base64,(.*)/', $this->input->post("thumbnail-base64"), $matches)) {
                    $thumbdata = $matches[2];
                    $thumbdata = str_replace(' ', '+', $thumbdata);
                    $thumbdata = base64_decode($thumbdata);
                    $thumb_path = $thumbs_dir . $thumb_name . ".jpg";
                    file_put_contents("." . $thumb_path, $thumbdata);
                }
            }

            $data = array(
                "title"         =>  $this->input->post("title"),
                "description"   =>  $this->input->post("description"),
                "uid"           =>  $this->ion_auth->user()->row()->id,
                "full_path"     =>  "uploads/" . $upload_data["file_name"],
                "ext"           =>  $upload_data["file_ext"],
                "thumb_path"    =>  $thumb_path
            );

            if ($this->input->post("duration")) {
                $data["duration"] = $this->input->post("duration");
            }

            if ($this->input->post("width")) {
                $data["width"] = $this->input->post("width");
            }

            if ($this->input->post("height")) {
                $data["height"] = $this->input->post("height");
            }

            $new_video = $this->pa_model->insertVideo($data);
            ob_start();
            ?>
            <div class="col-md-3 mb-3 vid" data-vid="<?= $new_video->id ?>">
                <div class="card video-card" data-uid="<?= $new_video->full_path ?>" data-vid="<?= $new_video->id ?>">
                    <img class="card-img-top" src="<?= base_url() . $new_video->thumb_path ?>" alt="Card image" style="width:100%">
                    <div class="card-body" data-uid="<?= $new_video->full_path ?>" data-vid="<?= $new_video->id ?>">
                        <h4 class="card-title"><?= $new_video->title ?></h4>
                        <div class="row duration-text mb-2">
                            <div class="col-md-6"><?= substr($new_video->uploaded_on, 0, 10) ?></div>
                            <div class="col-md-6 text-right"><?= gmdate("H:i:s", $new_video->duration) ?></div>
                        </div>
                        <p class="card-text"><?= $new_video->description ?></p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="edit-vid-btn" data-vid="<?= $new_video->id ?>">Edit</a>
                        <a href="#" class="pull-right delete-vid-btn" data-vid="<?= $new_video->id ?>">Delete</a>
                    </div>
                </div>
            </div>
            <?php
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
            die();
        }
    }

    public function videos_list()
    {
        $body_data = array(
            "videos"  => $this->pa_model->getVideoList($this->ion_auth->user()->row()->id)
        );
        $this->data['footer_script'] = [
            "pa_dashboard/upload.js"
        ];

        $this->load->view('pa/header', $this->data);
        $this->load->view('pa/videos_list', $body_data);
        $this->load->view('pa/footer', $this->data);
    }

    public function ajax_getVideobyId($vid)
    {
        echo json_encode($this->pa_model->getVideoById($vid));
    }

    public function ajax_editVideo($vid)
    {
        $data = array(
            "title" => $this->input->post('title'),
            "description" => $this->input->post('description')
        );
        $this->pa_model->edit_video($vid, $data);

        echo json_encode([
            "success"  => $this->get_videoHTML($vid),
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
        die();
    }

    public function ajax_deleteVideo($vid)
    {
        //delete file first
        $this->pa_model->delete_video($vid);
        echo "done";
        die();
    }

    public function get_videoHTML($vid)
    {
        $new_video = $this->pa_model->getVideoById($vid);
        ob_start();
        ?>
        <div class="card video-card" data-uid="<?= $new_video->full_path ?>" data-vid="<?= $new_video->id ?>">
            <img class="card-img-top" src="<?= base_url() . $new_video->thumb_path ?>" alt="Card image" style="width:100%">
            <div class="card-body" data-uid="<?= $new_video->full_path ?>" data-vid="<?= $new_video->id ?>">
                <h4 class="card-title"><?= $new_video->title ?></h4>
                <div class="row duration-text mb-2">
                    <div class="col-md-6"><?= substr($new_video->uploaded_on, 0, 10) ?></div>
                    <div class="col-md-6 text-right"><?= gmdate("H:i:s", $new_video->duration) ?></div>
                </div>
                <p class="card-text"><?= $new_video->description ?></p>
            </div>
            <div class="card-footer">
                <a href="#" class="edit-vid-btn" data-vid="<?= $new_video->id ?>">Edit</a>
                <a href="#" class="pull-right delete-vid-btn" data-vid="<?= $new_video->id ?>">Delete</a>
            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }
   
    public function ajax_getVideoHTMLbyId($vid)
    {
        $videoData = $this->pa_model->getVideoById($vid);
        $segmentId = uniqid();
        ob_start();
        ?>
        <div class="row mb-3" id="single-slt-video" data-vid="<?= $vid ?>" data-segment-id="<?= $segmentId ?>">
            <div class="col-md-4"><img class="img-thumbnail" src="<?= base_url().$videoData->thumb_path ?>"></div>
            <div class="col-md-8">
                <h4><?= $videoData->title ?></h4>
                <p><?= $videoData->description ?></p>
                <div><span>Duration : <?= gmdate("H:i:s", $videoData->duration) ?></span></div>
            </div>
        </div>
        <?php
        $video_div = ob_get_contents();
        ob_end_clean();

        $return_data = array(
            'videoHtml'      => $video_div,
            'csrfName'  => $this->security->get_csrf_token_name(),
            'csrfHash'  => $this->security->get_csrf_hash(),
            'video'   => array(
                'vid'                   => $vid,
                'title'                 => $videoData->title,
                'desc'                  => $videoData->description,
                'duration'              => $videoData->duration,
                'duration_in_format'    => gmdate("H:i:s", $videoData->duration),
                'segment_id'            => $segmentId,
                'path'                  => $videoData->full_path
            )
        );
        echo json_encode($return_data);
        die();
    }

    public function ajax_get_segment_uniqueid()
    {
        echo uniqid();
        die();
    }

    public function ajax_save_presentation($id)
    {
        $title = $this->input->post("title");
        $description = $this->input->post("description");
        $videos = $this->input->post("videos");
        $segments = $this->input->post("segments");

        foreach($videos as $v) {
            $this->pa_model->insertPresentationVideo(array(
                "pid" => $id,
                "vid" => $v["vid"]
            ));
        }

        foreach($segments as $s) {
            $data = array(
                "content" => $s["description"],
                "start" => $s["start"],
                "end" => $s["end"],
                "videoid" => $s["video_id"],
                "path" => $s["path"],
                "duration" => $s["duration"],
                "uid" =>  $this->ion_auth->user()->row()->id,
                "presentation_id" => $id            
            );
            $insertid =  $this->pa_model->insertSegment($data);
        }

        $inputData = array(
            "title"         => $this->input->post("title"),
            "description"   => $this->input->post("description"),
            "uid"           => $this->ion_auth->user()->row()->id
        ); 
        $this->pa_model->updatePresentationTextContent($inputData,$id);

        echo $id;
        die();
    }

    public function create_presentation()
    {
        $publicURL = $this->string_generate(10);
        $inputData = array(
            "title"         => trim($this->input->post("title")),
            "description"   => trim($this->input->post("description")),
            "banner"   => trim($this->input->post("banner")),
            "uid"           => $this->ion_auth->user()->row()->id,
            "display_url"   => 'pre' .$publicURL
        );

        $presentation_id = $this->pa_model->insertPresentation($inputData);
        echo $presentation_id;
        die();
    }

    public function delete_presentation($presentation_id)
    {
        $this->pa_model->delete_presentation($presentation_id);
        echo json_encode([
            "success"  => $presentation_id,
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
        die();
    }

    public function single_presentation($presentation_id)
    {
        echo "this is the public page of presentation. ID = " . $presentation_id;
    }

    public function edit_presentation($presentation_id)
    {
        $this->data['footer_script'] = [
            "pa_dashboard/sortable.js",
            "pa_dashboard/custom_edit_presentation.js"
        ];

        $segments_added = $this->pa_model->getSegments($presentation_id);
        $segments_composite_added = $this->pa_model->getCompositeSegments($presentation_id);

        foreach($segments_added as $s){
            $s->duration_in_format = gmdate("H:i:s", $s->duration);
        } 

        $body_data = array(
            "videos"  => $this->pa_model->getVideoList($this->ion_auth->user()->row()->id),
            "presentation_data" => $this->pa_model->getPresentationById($presentation_id),
            "videos_added" => $this->pa_model->getPreVideos($presentation_id),
            "segments_added" => $segments_added,
            "segments_composite_added" => $segments_composite_added
        );

        $this->load->view('pa/header', $this->data);
        $this->load->view('pa/edit_presentation', $body_data);
        $this->load->view('pa/footer', $this->data);
    }

    public function create_presentationcontent($presentation_id)
    {
        $this->data['footer_script'] = [
            "pa_dashboard/edit_presentation.js"
        ];

        $body_data = array(
            "presentation_data" => $this->pa_model->getPresentationById($presentation_id)
        );

        $this->load->view('pa/header', $this->data);
        $this->load->view('pa/create_presentationcontent', $body_data);
        $this->load->view('pa/footer', $this->data);
    }

    public function update_presentation($pid)
    {
        $data = array(
            "title"         => $this->input->post('name'),
            "description"   => $this->input->post('description'),
            "banner"        => $this->input->post('banner')
        );
        $this->pa_model->updatePresentation($data, $pid);
        echo json_encode([
            "success"  => $pid,
        ]);
        die();
    }

    public function update_order_segment($pid)
    {
        if(isset($_POST['segments']) && is_array($_POST['segments'])) {

            foreach($_POST['segments'] as $segment) {
                $segment['segment_id'] = (int) $segment['segment_id'];
                $segment['order'] = (int) $segment['order'];
                $data = array(
                    "order" => $segment['order'] + 1,
                );
                $this->pa_model->updateSegmentOrder($data, $segment['segment_id']);
            }
        }
        echo json_encode([
            "success"  => $pid,
        ]);
        die();
    }

    public function update_composite_order_segment($pid)
    {
        if(isset($_POST['segmentsForComp']) && is_array($_POST['segmentsForComp'])) {

            foreach($_POST['segmentsForComp'] as $segmentForComp) {
                $segmentForComp['segment_id'] = (int) $segmentForComp['segment_id'];
                $segmentForComp['order'] = (int) $segmentForComp['order'];
                $data = array(
                    "order_composite" => $segmentForComp['order'] + 1,
                );
                $this->pa_model->updateSegmentOrder($data, $segmentForComp['segment_id']);
            }
        }
        echo json_encode([
            "success"   => $pid,
            "tab_type"  => $_POST['tab_type']
        ]);
        die();
    }

    public function update_seg_index_exist($pid)
    {
   
        if(isset($_POST['existIndex'])) {

            $exist_index = filter_var($_POST['existIndex'], FILTER_VALIDATE_BOOLEAN);
            $data = array(
                "exist_index" => $exist_index,
            );
     ;
            $this->pa_model->updateSegmentOrder($data, $_POST['segment_id']);
        }
        echo json_encode([
            "success"   => $pid,
            "tab_type"  => $_POST['tab_type']
        ]);
        die();
    }

    public function update_seg_composite_exist($pid)
    {
        if(isset($_POST['existComposite'])) {

            $exist_composite = filter_var($_POST['existComposite'], FILTER_VALIDATE_BOOLEAN);
            $data = array(
                "exist_composite" => $exist_composite,
            );
            $this->pa_model->updateSegmentOrder($data, $_POST['segment_id']);
        }
        echo json_encode([
            "success"   => $pid,
            "tab_type"  => $_POST['tab_type']
        ]);
        die();
    }

    public function update_publicstatus($pid)
    {
        $data = array(
            "public" => !$this->input->post('ckb_' .$pid),
        );
        $this->pa_model->updatePresentation($data, $pid);

        echo $pid;
        die();
    }

    public function ajax_addsegment()
    {
        $segment_type = $this->input->post('segment-type');
        $segment_name = $this->input->post('segment-name');
        $segment_description = $this->input->post('segment-description');
        $uid = $this->input->post('user_id');
        $pid = $this->input->post('presentation_id');
        $max_segment_order = $this->pa_model->getMaxSegmentOrder($pid);
        $max_segment_order = (int) $max_segment_order;

        $max_composite_segment_order = $this->pa_model->getMaxCompositeSegmentOrder($pid);
        $max_composite_segment_order = (int) $max_composite_segment_order;

        if($segment_type === 'video') {
    
            $data = array(
                "name"              => $segment_name,
                "content"           => $segment_description,
                "presentation_id"   => $pid,
                "order"             => $max_segment_order + 1,
                "order_composite"   => $max_segment_order + 1,
                "uid"               =>  $this->ion_auth->user()->row()->id,
                "exist_index"       => 1
            );
        } else {

            $segment_url = $this->input->post('segment-url');
            $data = array(
                "name"              => $segment_name,
                "content"           => $segment_description,
                "presentation_id"   => $pid,
                "segment_url"       => $segment_url,
                "order"             => $max_segment_order + 1,
                "order_composite"   => $max_segment_order + 1,
                "uid"               =>  $this->ion_auth->user()->row()->id,
                "exist_index"       => 1
            );
        }

        $insertid =  $this->pa_model->insertSegment($data);
            
        echo json_encode([
            "type"          => 'success',
            "segment_id"    => $insertid,
            "segment_type"  => $segment_type
        ]);
        die();
    }

    public function create_segment($pid, $segid)
    {
        $this->data['footer_script'] = [
            "pa_dashboard/edit_presentation.js",
            "pa_dashboard/upload.js"
        ];

        $segments_added = $this->pa_model->getSegmentById($pid, $segid);
        
        foreach($segments_added as $s){
            $s->duration_in_format = gmdate("H:i:s", $s->duration);
        } 

        $body_data = array(
            "videos"  => $this->pa_model->getVideoList($this->ion_auth->user()->row()->id),
            "presentation_data" => $this->pa_model->getPresentationById($pid),
            "videos_added" => $this->pa_model->getVideosById($pid, $segid),
            "segments_added" => $segments_added
        );

        $this->load->view('segment/seg_header', $this->data);
        $this->load->view('segment/create_segment', $body_data);
        $this->load->view('segment/seg_footer', $this->data);
    }

    public function edit_segment($pid, $segid)
    {
        $this->data['footer_script'] = [
            "pa_dashboard/edit_presentation.js",
            "pa_dashboard/upload.js"
        ];

        $segments_added = $this->pa_model->getSegmentById($pid, $segid);
        
        foreach($segments_added as $s){
            $s->duration_in_format = gmdate("H:i:s", $s->duration);
        } 

        $body_data = array(
            "videos"  => $this->pa_model->getVideoList($this->ion_auth->user()->row()->id),
            "presentation_data" => $this->pa_model->getPresentationById($pid),
            "videos_added" => $this->pa_model->getVideosById($pid, $segid),
            "segments_added" => $segments_added
        );

        $this->load->view('segment/seg_header', $this->data);
        $this->load->view('segment/edit_segment', $body_data);
        $this->load->view('segment/seg_footer', $this->data);        
    }

    public function edit_segment_url($pid, $segid)
    {
        $segments_added = $this->pa_model->getSegmentById($pid, $segid);
        $body_data = array(
            "presentation_data" => $this->pa_model->getPresentationById($pid),
            "segments_added" => $segments_added
        );

        $this->load->view('segment/seg_header', $this->data);
        $this->load->view('segment/edit_segment_url', $body_data);
        $this->load->view('segment/seg_footer', $this->data);    
    }

    public function delete_segment($pid, $segid)
    {
        $segment = $this->pa_model->getSegmentById($pid, $segid)[0];
        if($segment->is_public) {
            $_data = array(
                "composite_exist" => 0,
            );
            $this->pa_model->updatePresentation($_data, $pid);
        }
        $this->pa_model->deleteSegment($pid, $segid);
        echo json_encode([
            "success"  => $segid,
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash()
        ]);
        die();
    }

    public function ajax_save_segment($pid)
    {
        $title = $this->input->post("title");
        $description = $this->input->post("description");
        $videos = $this->input->post("videos");
        $segments = $this->input->post("segments");
        $segid = $segments[0]["id"];

        if($this->pa_model->getVideosById($pid, $segid)) {

            $this->pa_model->updatePresentationVideo(array(
                "pid" => $pid,
                "vid" => $videos[0]["vid"],
            ), $segid);
        } else  {

            $this->pa_model->insertPresentationVideo(array(
                "pid" => $pid,
                "vid" => $videos[0]["vid"],
                "segid" => $segid
            ));
        }

        $data = array(
            "name" => $title,
            "content" => $description,
            "start" => $segments[1]["start"],
            "end" => $segments[1]["end"],
            "videoid" => $segments[1]["video_id"],
            "path" => $segments[1]["path"],
            "duration" => $segments[1]["duration"],
            "presentation_id" => $pid            
        );
        $this->pa_model->updateSegment($data, $segid);

        echo $segid;
        die();
    }

    public function ajax_update_segment($pid)
    {
        $title = $this->input->post("title");
        $description = $this->input->post("description");
        $videos = $this->input->post("videos");
        $segments = $this->input->post("segments");
        $segid = $segments[0]["id"];

        if($this->pa_model->getVideosById($pid, $segid)) {

            $this->pa_model->updatePresentationVideo(array(
                "pid" => $pid,
                "vid" => $videos[0]["vid"],
            ), $segid);
        } else  {

            $this->pa_model->insertPresentationVideo(array(
                "pid" => $pid,
                "vid" => $videos[0]["vid"],
                "segid" => $segid
            ));
        }

        $data = array(
            "name" => $title,
            "content" => $description,
            "start" => $segments[0]["start"],
            "end" => $segments[0]["end"],
            "videoid" => $segments[0]["videoid"],
            "path" => $segments[0]["path"],
            "duration" => $segments[0]["duration"],
            "presentation_id" => $pid            
        );
        $this->pa_model->updateSegment($data, $segid);

        echo $segid;
        // die();
    }

    public function update_segment_url($pid, $segid)
    {
        $segment_name = $this->input->post("segment-name");
        $segment_description = $this->input->post("segment-description");
        $segment_url = $this->input->post("segment-url");
        $data = array(
            "name" => $segment_name,
            "content" => $segment_description,          
            "presentation_id" => $pid,           
            "segment_url" => $segment_url
        );
        $this->pa_model->updateSegment($data, $segid);
        echo $segid;
    }

    public function string_generate($length)
    {
        $characters = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
        $content = '';
    
        for($i = 1; $i <= $length; $i++) {
            $content .= $characters[array_rand($characters, 1)];
        }
    
        return $content;
    }

    public function preview_composite($pid)
    {
        if(isset($_POST['segmentsForComp']) && is_array($_POST['segmentsForComp'])) {

            $format=new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'); 
            $format->setAdditionalParameters( [ '-crf', '29' ] )->setKiloBitrate(1000);
            $format->setAudioCodec("libmp3lame");
            $clippedFileNameArr = [];
            $cnt = 0;
            $totalDuration = 0;
            foreach($_POST['segmentsForComp'] as $segment) {
                
                $segment['segment_id'] = (int) $segment['segment_id'];
                $segment_path = $segment['segment_path'];
                $startTime = (float) $segment['segment_start'];
                $duration = (float) $segment['segment_duration'];
                $totalDuration += $duration;
                if($cnt == 0) {
                    $video1 = $this->ffmpeg->open($segment_path);
                    $video1
                        ->filters()
                        ->synchronize();
                    $video1->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($startTime), \FFMpeg\Coordinate\TimeCode::fromSeconds($duration));

                    $currentDateTime = date("Y-m-d h:i:sa");
                    $clip_command = $currentDateTime .': '. 'ffmpeg -ss ' .$startTime. ' -i ' .$segment_path. ' -c copy -t ' .$duration. ' output.wmv';
                    file_put_contents('./ffmpeg.log', $clip_command, FILE_APPEND | LOCK_EX);

                    $randStr = $this->string_generate(10);
                    $clippedFileName = 'uploads/segments/' . 'clip_' . $randStr .'.mp4';
                    array_push($clippedFileNameArr, $clippedFileName);
                    $video1->save($format, $clippedFileName);
                } else {
                    $video = $this->ffmpeg->open($segment_path);
                    $video
                        ->filters()
                        ->synchronize();
                    $video->filters()->clip(\FFMpeg\Coordinate\TimeCode::fromSeconds($startTime), \FFMpeg\Coordinate\TimeCode::fromSeconds($duration));
                    $randStr = $this->string_generate(10);
                    $clippedFileName = 'uploads/segments/' . 'clip_' . $randStr .'.mp4';
                    array_push($clippedFileNameArr, $clippedFileName);
                    $video->save($format, $clippedFileName);
                }

                $cnt++;
            }
            
            $d2 = new Datetime("now");
            $currentTime = $d2->format('U');
            $randStr = $this->string_generate(10);
            $previewFileName = $currentTime . '_' . $randStr .'.mp4';
            $_previewFileName = 'again_' .$previewFileName;
            $video1
                ->concat($clippedFileNameArr)
                ->saveFromSameCodecs('uploads/' .$previewFileName, TRUE);

            $mergeVideo = $this->ffmpeg->open('uploads/' .$previewFileName);
            $_format = new FFMpeg\Format\Video\X264();
            $_format->setAudioCodec("libmp3lame");
            
            $mergeVideo
                ->save($_format, 'uploads/' .$_previewFileName);

            $filepath = base_url() . 'uploads/' .$_previewFileName;

            $data = array(
                "start"             => 0,
                "end"               => $totalDuration,
                "duration"          => $totalDuration,
                "path"              => $filepath,
                "presentation_id"   => $pid,
                "is_composite"      => 1,
                "is_public"         => 0
            );
            $insertid = $this->pa_model->insertSegment($data);
        }

        echo json_encode([
            "success"   => $insertid,
            "path"      => $filepath
        ]);
    }

    public function public_composite($pid)
    {
        if(isset($_POST['publicCompId'])) {

            $publicCompId = $_POST['publicCompId'];
            $data = array(
                "is_public" => 1            
            );
            $this->pa_model->updateSegment($data, $publicCompId);

            $_data = array(
                "composite_exist" => 1,
            );
            $this->pa_model->updatePresentation($_data, $pid);
    
            echo json_encode([
                "success"   => $publicCompId,
            ]);
        }
    }

    public function statistics()
    {
        $uid = $this->ion_auth->user()->row()->id;
        $segments = $this->pa_model->getSegmentsLog($uid);

        $body_data = array(
            "segments" => $segments
        );

        $this->load->view('statistics/header', $this->data);
        $this->load->view('statistics/main', $body_data);
        $this->load->view('statistics/footer', $this->data);
    }

}