<style>
    .search-form .form-group {
        border: 1px solid #9ff0c8;
        padding: 10px;
        border-radius: 3px;
    }

    .search-form .form-group input {
        height: 45px !important;
        border: transparent
    }

    .form-control {
        height: 52px !important;
        background: #fff !important;
        color: #3a4348 !important;
        font-size: 18px;
        border-radius: 0px;
        -webkit-box-shadow: none !important;
        box-shadow: none !important
    }

    .px-4 {
        padding-left: 1.5rem !important
    }

    .search-form .form-group .search-btn {
        background: #22d47b;
        border: 2px solid #22d47b;
        border-radius: 3px !important;
        color: #fff;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        -ms-border-radius: 0;
        border-radius: 0
    }

    .duration-text {
        font-size: 12px;
        color: #b9b7b7;
    }

    .card-img-top {
        min-height: 138px !important;
    }

    .video-card:hover {
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .25) !important;
    }
    
</style>
<div class="container">
    
    <div class="row">
        <h3 class="col-md-12 mb-4">Your Videos Library</h3>
        <div class="col-md-12 mb-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="uploaded-video-alert">
                <strong>Well Done!</strong> Your video has been uploaded.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 ftco-animate fadeInUp ftco-animated">
            <form action="#" class="search-form">
                <div class="form-group d-md-flex"> <input type="text" class="form-control px-4" placeholder="Enter your keyword..."> <input type="submit" class="search-btn btn btn-primary px-5" value="Search Video"> </div>
            </form>
            <p class="text-right"><a href="#" data-toggle="modal" data-target="#upload-modal"><i>Upload a new video</i></a></p>
        </div>
    </div>

    <div class="card collapse mb-4" id="main-player">
        <div class="row no-gutters">
            <div class="col-md-4" id="player">

            </div>
            <div class="col-md-8">
                <div class="card-body" id="video-info">
                    <h5 class="card-title c-video-title"></h5>
                    <p class="card-text c-video-description"></p>
                    <p class="card-text"><small class="text-muted c-video-duration"></small></p>
                </div>
            </div>
        </div>
    </div>


    <?php if (count($videos) == 0) : ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Opps!</strong> You don't have any video.
        </div>
        <div class="row" id="video-grid">
        </div>
    <?php else : ?>
        <div class="row" id="video-grid">
            <?php foreach ($videos as $v) : ?>
                <div class="col-md-3 mb-3 vid" data-vid="<?= $v->id ?>">
                    <div class="card video-card" data-uid="<?= $v->full_path ?>" data-vid="<?= $v->id ?>">
                        <img class="card-img-top" src="<?= base_url() . $v->thumb_path ?>" alt="Card image" style="width:100%">
                        <div class="card-body" data-uid="<?= $v->full_path ?>" data-vid="<?= $v->id ?>">
                            <h4 class="card-title"><?= $v->title ?></h4>
                            <div class="row duration-text mb-2">
                                <div class="col-md-6"><?= substr($v->uploaded_on, 0, 10) ?></div>
                                <div class="col-md-6 text-right"><?= gmdate("H:i:s", $v->duration) ?></div>
                            </div>
                            <p class="card-text"><?= $v->description ?></p>
                            <!-- <a href="#" class="stretched-link"></a> -->
                        </div>
                        <div class="card-footer">
                            <a href="#" class="edit-vid-btn" data-vid="<?= $v->id ?>">Edit</a>
                            <a href="#" class="pull-right delete-vid-btn" data-vid="<?= $v->id ?>">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                </div>
                </form>
            </div>
        </div>
    </div>


    <!-- delete video confirm modal -->

    <div class="modal fade" id="deleteVideoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                <div class="modal-body">
                    Do you want to delete this video?
                    <input type="hidden" name="vid" id="delete-video-id">
                    <!-- <input type="hidden" id="csrf2" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />                     -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-delete-confirm">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>                    
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editVideoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Video Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="edit-video-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" id="edit-video-title" class="form-control" placeholder="Video title">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Description</label>
                        <textarea name="description" class="form-control" rows="5" id="edit-video-description" ></textarea>
                        <input type="hidden" name="edit-vid" id="edit-video-id">
                    </div>
                   
                    <input type="hidden" id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-edit-confirm">SAVE</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>                    
                </div>
                </form>
            </div>
        </div>
    </div>
</div>