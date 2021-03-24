<style>
    #updateSeg-btn {
        position: relative;
        top: -20px;
        right: 40px
    }
    .transition-setting {
        position: relative;
        top: -15px;
    }
    .used-transition {
        position: relative;
        top: 4em;
    }
    .used-transition ul{
        font-size: 1.6em;
    }
</style>

<div class="container">    
    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Add/Maintain a Segment</h3>
        <div class="col-sm-6">
            <h2 class="mr-2 pull-right"><?= $presentation_data->title?></h2>
        </div>
    </div>

    <div class="row mb-3">
        <form class="col-md-12">
            <div class="form-group">
                <label for="presentation-name"><b>Name</b></label>
                <input type="text" class="form-control" name="presentation-name" value="<?= $segments_added[0]->name ?>">
            </div>
            <div class="form-group">
                <label for="presentation-description"><b>Description</b></label>
                <textarea class="form-control" name="presentation-description" placeholder="Your presentation description" rows="5"><?= $segments_added[0]->content ?></textarea>
            </div>
        </form>        
    </div>

    <div class="row collapse" id="top-pl">
    
        <div class="col-md-12"><video id="segment-preview1" style="width:100%;height:auto;" poster="<?=base_url().'assets/pa_dashboard/sample-player.png'?>"></video></div>
        <div class="col-md-3">
            <button id="preview-play1" data-playing="0" class="btn btn-primary">Play</button> <span class="ml-2"id="segchronos1">00</span> / <span id="segDur1"><?= $segments_added[0]->duration?></span>
        </div>
        <div class="col-md-9 text-right">
            <b>Duration</b> : <span id="duration-segment1" data-lbl="duration"> :<input class="time-input" type="hidden" id="start-hour1" min="0" value="00"> <input class="time-input" type="hidden"  min="0" max="60" id="start-minute1" value="00"> <input class="time-input" type="number" id="seg-duration" value="<?= $segments_added[0]->duration?>">.<input class="time-input" type="hidden" id="start-msecond"  min="0" max="999" value="000"></span>
            
            <b class="ml-3">Start</b> : <span id="start-segment1" data-lbl="start"><input class="time-input" type="hidden" id="start-hour1" min="0" value="00"> : <input class="time-input" type="number"  min="0" max="60" id="start-minute1" value="00"> : <input class="time-input" type="number" id="start-second1"  min="0" max="60" value="00">.<input class="time-input" type="number" id="start-msecond"  min="0" max="999" value="000"></span>
            <b class="ml-3">End</b> : <span id="end-segment1" data-lbl="end"><input class="time-input" type="hidden" id="end-hour1" value="00"  min="0"> : <input class="time-input" type="number" id="end-minute1" min="0" max="60" value="00"> : <input class="time-input" type="number" id="end-second"  min="0" max="60" value="00">.<input class="time-input" type="number" id="end-msecond1"   min="0" max="999" value="000"></span>
        </div>
        <div class="col-md-12 mt-4 mb-2">
            <div id="full-duration-display-div1">
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <input id="ex1" type="text" class="span2 col-md-12 mb-2" value="" data-slider-min="0" data-slider-max="1000" data-slider-step="1" />
        <input type="hidden" id="minTime-store1" value="0">
        <input type="hidden" id="maxTime-store1" value="0">
        <input type="hidden" id="vid-duration1" value="0">
        <input type="hidden" id="segment-index" value="0">
        
    </div>

    <div class="row">
        <div class="col-xl-12 mb-3"></div>
        <div class="col-xl-9">
            <p class="mb-4"><h2 class="pull-left"><?= $segments_added[0]->name?></h2><a href="#" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#add-video-modal" style="margin-left:35%;"><i class="bi bi-file-play"></i> Select another Video</a><a href="#" data-toggle="modal" class="pull-right" data-target="#upload-modal"><i>Upload a new video</i></a></p>
            <div id="slt-video-list" class="mt-4 mb-4"></div>
        </div>
        <div class="col-xl-3 used-transition">
            <?php if($segments_added[0]->fade_path) :?>
                <h4>Used Transition</h4>
                <ul>
                    <li><?= $segments_added[0]->transition_in_type?> : <?= $segments_added[0]->transition_in_duration?></li>
                    <li><?= $segments_added[0]->transition_out_type?> : <?= $segments_added[0]->transition_out_duration?></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="transition-setting">
        <h4>Transition Settings</h4>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label>Duration of Transition In</label>
                    <input type="number" class="form-control" name="transition_in_duration" id="transition_in_duration" value="<?= $segments_added[0]->transition_in_duration?>">
                </div>
                <div class="form-group">
                    <label>Type of Transition In</label>
                    
                    <select class="form-control" name="type_for_transition_in" id="type_for_transition_in" value="<?= $segments_added[0]->transition_in_type?>">
                        <option value="fade_in">Fade In</option>
                        <option value="fade_out">Fade Out</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-lg-6">
                <div class="form-group">
                    <label>Duration of Transition Out</label>
                    <input type="number" class="form-control" name="transition_out_duration" id="transition_out_duration" value="<?= $segments_added[0]->transition_out_duration?>">
                </div>
                <div class="form-group">
                    <label>Type of Transition Out</label>
                    <select class="form-control" name="type_for_transition_out" id="type_for_transition_out" value="<?= $segments_added[0]->transition_out_type?>">
                        <option value="fade_out">Fade Out</option>
                        <option value="fade_in">Fade In</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-primary pull-right" id="updateSeg-btn" data-id="<?= $presentation_data->id ?>"><i class="bi bi-save"></i>Update</a>
</div>

<!-- Modal -->
<div class="modal fade" id="add-video-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select a Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (count($videos) == 0) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Opps!</strong> You don't have any video.
                    </div>
                <?php else : ?>
                    <div class="row video-select-modal" id="video-grid">
                        <?php foreach ($videos as $v) : ?>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card video-card" data-uid="<?= $v->full_path ?>" data-vid="<?= $v->id ?>">
                                    <img class="card-img-top" src="<?= base_url() . $v->thumb_path ?>" alt="Card image" style="width:100%">
                                    <div class="card-body">
                                        <h4 class="card-title"><?= $v->title ?></h4>
                                        <div class="row duration-text mb-2">
                                            <div class="col-md-6"><?= substr($v->uploaded_on, 0, 10) ?></div>
                                            <div class="col-md-6 text-right"><?= gmdate("H:i:s", $v->duration) ?></div>
                                        </div>
                                        <p class="card-text"><?= $v->description ?></p>
                                        <a href="#" class="stretched-link"></a>
                                    </div>
                                    <input class="form-check-input video-slt" data-vid="<?= $v->id ?>" type="checkbox" value="1" data-duration="<?=$v->duration?>">
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <form id="secure-frm">
                <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            </form>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="form-check" style="margin-left:-20px;">
                        <input class="form-check-input" type="checkbox" value="1" id="create-segment-from-seletected-video" checked>
                        <label class="form-check-label" for="defaultCheck1">
                            Create a segment from selected video
                        </label>
                    </div>
                </div>
                
                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary pull-right" id="add-video-to-queue">Select</button>
                
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit-video-segment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-9">
                            <h4 class="modal-title" id="exampleModalLabel">Add New Video Segment</h4>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row" id="step-1">
                        <div class="col-md-12"><b>Preview</b></div>
                        <div class="col-md-12"><video id="segment-preview" style="width:100%;height:auto;"></video></div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3"><button id="preview-play" data-playing="0" class="btn btn-primary">Play</button> <span class="ml-2"id="segchronos">00</span> / <span id="segDur">00</span></div>
                                <div class="col-md-9 text-right">
                                    <b>Duration</b> : <span id="duration-segment"></span>
                                    <b class="ml-3">Start</b> : <span id="start-segment" data-lbl="start"><input class="time-input" type="number" id="start-hour" value="00" min="0"> : <input class="time-input" type="number" id="start-minute" min="0" max="60" value="00"> : <input class="time-input" type="number" min="0" id="start-second" value="00" max="60" >.<input class="time-input" type="number" min="0" max="999" id="start-msecond" value="000"></span>
                                    <b class="ml-3">End</b> : <span id="end-segment" data-lbl="end"><input class="time-input" type="number" id="end-hour"  min="0" value="00"> : <input class="time-input" type="number" id="end-minute"  min="0" max="60" value="00"> : <input class="time-input" type="number" id="end-second"  min="0" max="60" value="00">.<input class="time-input" type="number" id="end-msecond"  min="0" max="999" value="000"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4 mb-2 ">
                            <div id="full-duration-display-div">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <input id="ex2" type="text" class="span2 col-md-12" value="" data-slider-min="0" data-slider-max="1000" data-slider-step="1" />

                        <input type="hidden" id="minTime-store" value="0">
                        <input type="hidden" id="maxTime-store" value="0">
                        <input type="hidden" id="vid-duration" value="0">
                    </div>
                    <div class="row" id="step-2">
                        <div class="form-group col-md-12">
                            <label>Description</label>
                            <input type="text" class="form-control" id="seg-desc">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            
                            <button type="button" class="btn btn-primary pull-right ml-2" id="add-segment-to-presentation" data-vid="" data-step="1">Next</button>
                            <button type="button" class="btn btn-secondary pull-right" id="add-segment-to-presentation-back" data-vid="">Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="upload-modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload New Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php echo form_open_multipart("",["id"=>"uploadForm"]); ?>
                <?php
                $token_name = $this->security->get_csrf_token_name();
                $token_hash = $this->security->get_csrf_hash();
                ?>
                <div class="modal-body">
                    <div class="row">
                        <?php if (isset($error)) echo $error; ?>

                        <div class="col-md-12 mb-2">
                            <div class="progress collapse" id="upload-progress-bar">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                Uploading 0% complete
                            </div>
                            </div>
                        </div>
                        <div class="col-md-12 collapse" id="vid-upload-preview">
                            <label>Previews</label>
                            <div class="debug" style="width:100%;"></div>
                            <div class="debug2" style="width:100%;"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Video title">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Description</label>
                                <textarea name="description" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Select file</label>
                                <input type="file" name="userfile" id="videoFileInput" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="duration" id="duration">
                                <input type="hidden" name="thumbnail-base64" id="thumbnail-base64">
                                <input type="hidden" name="width" id="video-width">
                                <input type="hidden" name="height" id="video-height">
                                <input type="hidden" id="csrf2" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                        <input type="submit" value="Upload" class="form-control btn btn-primary" id="sbmt-btn"/>
                </div>
                </form>
            </div>
        </div>
    </div>