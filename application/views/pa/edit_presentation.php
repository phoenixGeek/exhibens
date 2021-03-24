<style>
    .userTable {
        border-top: none !important;
    }
    #segment-url-container {
        display: none;
    }
    .custom-row-side-controller-grab {
        cursor: grab;
    }
    .create-composite-btb {
        width: 35%;
        margin-bottom: 1em;
    }
    #create_composite_form p {
        font-weight: bold;
        margin-left: 1em;
    }
    .modal-dialog-centered.modal-dialog-scrollable {
        height: auto;
    }
    .composite-modal {
        max-height: none;
    }
    #preview-composite {
        margin-left: 12%;
        display: none;
    }
    .composite-list .card-body {
        padding: 0px;
    }
</style>
<div class="container">    
    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Presentation Maintenance</h3>
        <div class="col-sm-6">
            <h2 class="mr-2 pull-right"><?= $presentation_data->title?></h2>
        </div>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="deleted-segment-alert">
        <strong>Success!</strong> The Segment has been deleted.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="updated-presentation-alert">
        <strong>Success!</strong> This presentation has been updated!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="updated-segment-order-alert">
        <strong>Success!</strong> Segments' order of this presentation has been updated!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="row mb-3">
        <form class="col-md-12" id="presentation-overview">
            <div class="form-group">
                <label for="presentation-name"><b>Name</b></label>
                <input type="text" class="form-control" name="presentation-name" value="<?= $presentation_data->title ?>">
            </div>
            <div class="form-group">
                <label for="presentation-description"><b>Description</b></label>
                <textarea class="form-control" name="presentation-description" placeholder="Your presentation description" rows="3"><?= $presentation_data->description ?></textarea>
            </div>
            <div class="form-group">
                <label for="presentation-banner"><b>Banner</b></label>
                <textarea class="form-control" name="presentation-banner" placeholder="Your presentation banner" rows="3"><?= $presentation_data->banner ?></textarea>
            </div>
        </form>
        <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
        ?>
        <form>
            <input type="hidden" data-id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
        </form>   
    </div>
    <div>
        <a class="btn btn-primary btn-md create-composite-btb" href="#" role="button" data-toggle="modal" data-target="#create-composite-modal">Create the Composite Presentation</a>
    </div>

    <div class="composite-list">
        <div class="card-body">
            <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
            
                <table class="table dataTable my-0" class="userTable">
                    <?php foreach ($segments_added as $segment) : ?>
                        <?php if($segment->is_public): ?>
                            <p class="text-primary m-0 font-weight-bold">Your Composite List</p>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Delete</th>
                                    <th>Public Page</th>
                                </tr>
                            </thead>
                        <?php break; endif; ?>
                    <?php endforeach; ?>
                    <tbody id="segment-composite-lists">
                        <?php foreach ($segments_added as $segment) : ?>
                            <?php if($segment->is_public): ?>
                            <tr class="segments-list" data-uid="<?php echo $segment->id; ?>">
                                <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><a href="#" data-pid="<?=$segment->presentation_id?>" data-segid="<?=$segment->id?>" class="btn-delete-segment-showmodal" data-name="<?=$segment->name?>">Delete</a></td>
                                <td><a href="<?=base_url()."segment/index/".$presentation_data->id . "/" .$segment->id ?>" target="_blank">View Composite</a></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow">
    
        <div class="card-header py-3">

            <p class="text-primary m-0 font-weight-bold">Your Segments List
                <a class="btn btn-primary btn-md pull-right" href="#" role="button" data-toggle="modal" data-target="#add-segment-modal">Add a Segment</a>
            </p>
        </div>

        <div class="card-body">
        
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?= !isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'selection') ? 'active' : null ?>" id="selection-tab" data-toggle="tab" href="#selection" role="tab" aria-controls="selection" aria-selected="true">Segment Selections</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['tab']) && $_GET['tab'] == 'index'? 'active' : null ?>" id="index-tab" data-toggle="tab" href="#index" role="tab" aria-controls="index" aria-selected="true">Index Sorting</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($_GET['tab']) && $_GET['tab'] == 'composite'? 'active' : null ?>" id="composite-tab" data-toggle="tab" href="#composite" role="tab" aria-controls="composite" aria-selected="false">Composite Sorting</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <?php 
                    // var_dump($_GET['tab']);
                ?>
                <div class="tab-pane fade <?= !isset($_GET['tab']) || (isset($_GET['tab']) && $_GET['tab'] == 'selection') ? 'show active' : null ?>" id="selection" role="tabpanel" aria-labelledby="selection-tab">
                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                        <table class="table dataTable my-0" class="userTable">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Comp</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Public Page</th>
                                </tr>
                            </thead>
                            <tbody id="segments-selection">
                                <?php foreach ($segments_added as $segment) : ?>
                                    <?php if(!$segment->is_composite): ?>
                                    <tr class="segments-list" data-segid="<?php echo $segment->id; ?>">
                                        
                                        <td><input type="checkbox" class="ckb-seg-index" <?= $segment->exist_index ? 'checked' : '' ?> /></td>
                                        <td><input type="checkbox" class="ckb-seg-comp" <?= $segment->segment_url && $segment->segment_url != NULL ? 'disabled' : '' ?> <?= $segment->exist_composite ? 'checked' : '' ?>/></td>
                                        <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($segment->content, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                            <td><a href="<?=base_url()."Pa_dashboard/edit_segment_url/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                                        <?php else: ?>
                                            <td><a href="<?=base_url()."Pa_dashboard/edit_segment/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                                        <?php endif; ?>
                                        
                                        <td><a href="#" data-pid="<?=$segment->presentation_id?>" data-segid="<?=$segment->id?>" class="btn-delete-segment-showmodal" data-name="<?=$segment->name?>">Delete</a></td>
                                            <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                                <td><a href="<?= $segment->segment_url ?>" target="_blank">View Segment</a></td>
                                            <?php else: ?>
                                                <td><a href="<?=base_url()."segment/index/".$presentation_data->id . "/" .$segment->id ?>" target="_blank">View Segment</a></td>
                                            <?php endif; ?>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade <?= isset($_GET['tab']) && $_GET['tab'] == 'index'? 'show active' : null ?>" id="index" role="tabpanel" aria-labelledby="index-tab">
                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                        <table class="table dataTable my-0" class="userTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Public Page</th>
                                </tr>
                            </thead>
                            <tbody id="segments-lists">
                                <?php foreach ($segments_added as $segment) : ?>
                                    <?php if(!$segment->is_composite && $segment->exist_index): ?>
                                    <tr class="segments-list" data-segid="<?php echo $segment->id; ?>">
                                        
                                        <td><i class="fa fa-fw fa-bars text-muted custom-row-side-controller-grab drag"></i></td>
                                        <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($segment->content, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                            <td><a href="<?=base_url()."Pa_dashboard/edit_segment_url/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                                        <?php else: ?>
                                            <td><a href="<?=base_url()."Pa_dashboard/edit_segment/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                                        <?php endif; ?>
                                        
                                        <td><a href="#" data-pid="<?=$segment->presentation_id?>" data-segid="<?=$segment->id?>" class="btn-delete-segment-showmodal" data-name="<?=$segment->name?>">Delete</a></td>
                                            <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                                <td><a href="<?= $segment->segment_url ?>" target="_blank">View Segment</a></td>
                                            <?php else: ?>
                                                <td><a href="<?=base_url()."segment/index/".$presentation_data->id . "/" .$segment->id ?>" target="_blank">View Segment</a></td>
                                            <?php endif; ?>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="tab-pane fade <?= isset($_GET['tab']) && $_GET['tab'] == 'composite'? 'show active' : null ?>" id="composite" role="tabpanel" aria-labelledby="composite-tab">
                    <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                        <table class="table dataTable my-0" class="userTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Public Page</th>
                                </tr>
                            </thead>
                            <tbody id="segments-for-composite-list">
                                <?php foreach ($segments_composite_added as $segment) : ?>
                                    <?php if(!$segment->is_composite && $segment->exist_composite): ?>

                                    <tr class="<?= $segment->segment_url && $segment->segment_url != NULL ? 'segDiabled' : 'segForCompList'?>" data-segid="<?php echo $segment->id; ?>" data-path="<?php echo $segment->path; ?>" data-start="<?php echo $segment->start; ?>" data-duration="<?php echo $segment->duration; ?>">
                                        
                                        <td><i class="fa fa-fw fa-bars text-muted custom-row-side-controller-grab drag"></i></td>
                                        <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($segment->content, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= $segment->segment_url && $segment->segment_url != NULL ? 'URL' : 'Video' ?></td>
                                        <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                                        <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                            <td><a href="<?=base_url()."Pa_dashboard/edit_segment_url/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                                        <?php else: ?>
                                            <td><a href="<?=base_url()."Pa_dashboard/edit_segment/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                                        <?php endif; ?>
                                        
                                        <td><a href="#" data-pid="<?=$segment->presentation_id?>" data-segid="<?=$segment->id?>" class="btn-delete-segment-showmodal" data-name="<?=$segment->name?>">Delete</a></td>
                                            <?php if($segment->segment_url && $segment->segment_url != NULL): ?>
                                                <td><a href="<?= $segment->segment_url ?>" target="_blank">View Segment</a></td>
                                            <?php else: ?>
                                                <td><a href="<?=base_url()."segment/index/".$presentation_data->id . "/" .$segment->id ?>" target="_blank">View Segment</a></td>
                                            <?php endif; ?>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
 
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-3"></div>
        <div class="col-xl-12">
            <a href="#" class="btn btn-primary pull-right" id="updatePre-btn" data-id="<?= $presentation_data->id ?>"><i class="bi bi-save2"></i>Update</a>
            <a href="<?=base_url() ?>" class="btn btn-secondary mr-2 pull-right">Back</a>
        </div>
    </div>

</div>

<div class="modal fade" id="delete-segment-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Delete a Segment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="delete-segment-form">

                <div class="modal-body">
                    <p>Are you sure you want to delete the Segment "<strong id="delete-segment-name">NO NAME</strong>"?</p>
          
                    <?php
                        $token_name = $this->security->get_csrf_token_name();
                        $token_hash = $this->security->get_csrf_hash();
                    ?>
                    <input type="hidden" data-id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
                    <input type="hidden" name="delete-pid" value="" id="input-delete-pid">
                    <input type="hidden" name="delete-segid" value="" id="input-delete-segid">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-delete-segment-comfirm">Delete</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="add-segment-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a segment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="post" id="create_segment_form">
                <div class="modal-body">
                    <?php
                    $token_name = $this->security->get_csrf_token_name();
                    $token_hash = $this->security->get_csrf_hash();
                    ?>
                    <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
                    <div class="form-group" >
                        <label for="segment-name"><b>Name</b></label>
                        <input type="hidden" name="presentation_id" value="<?= $presentation_data->id; ?>">
                        <input type="hidden" name="user_id" value="<?= $presentation_data->uid; ?>">
                        <input type="text" class="form-control" name="segment-name" id="segment-name" placeholder="Your segment name" required>
                    </div>
                    <div class="form-group">
                        <label for="segment-description"><b>Description</b></label>
                        <textarea class="form-control" name="segment-description" id="segment-description" placeholder="Your segment description" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="segment-type">Select a Segment Type</label>
                        <select class="form-control" id="segment-type" name="segment-type" style="width:30%;">
                            <option value="video">Video</option>
                            <option value="url">URL</option>
                        </select>
                    </div>
                    <div class="form-group" id="segment-url-container">
                        <label for="segment-url">Insert your segment URL</label>
                        <input class="form-control" name="segment-url" id="segment-url" placeholder="Place your video URL"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>        
                    <button type="button" class="btn btn-primary pull-right" id="create-segment-btn">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="create-composite-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable composite-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create the Composite Presentation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form method="post" id="create_composite_form">
                <div class="modal-body">
                    <?php
                        $token_name = $this->security->get_csrf_token_name();
                        $token_hash = $this->security->get_csrf_hash();
                    ?>
                    <input type="hidden" id="composite-csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
                    <div class="form-group" >
                        <input type="hidden" name="presentation_id" value="<?= $presentation_data->id; ?>">
                        <input type="hidden" name="user_id" value="<?= $presentation_data->uid; ?>">
                    </div>
                    
                    <div class="form-group">
                        <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                            <table class="table dataTable my-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>Created On</th>
                                    </tr>
                                </thead>

                                <tbody id="segments-preview-list">
                                    <?php foreach ($segments_composite_added as $segment) : ?>
                                    <?php if(!$segment->is_composite && $segment->exist_composite): ?>
                                        <tr class="segments-preview" data-segid="<?php echo $segment->id; ?>" data-path="<?php echo $segment->path; ?>" data-start="<?php echo $segment->start; ?>" data-duration="<?php echo $segment->duration; ?>">
                                            <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($segment->content, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?= $segment->segment_url && $segment->segment_url != NULL ? 'URL' : 'Video' ?></td>
                                            <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <video id="preview-composite" src="" type="video/mp4" width="800" controls autoplay>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info pull-right" id="preview-composite-btn">Preview</button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary pull-right" id="create-composite-btn">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>