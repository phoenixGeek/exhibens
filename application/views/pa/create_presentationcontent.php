<div class="container">    
    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Create a Presentation</h3>
    </div>

    <div class="jumbotron" style="padding: 1rem 2rem;">
        <h3 class="display-4" style="font-size: 2rem;">Well done!</h3>
        <p class="lead">Your presentation is created, Please add a segment.</p>
    </div>

    <div class="row mb-3">
        <form class="col-md-12">
            <div class="form-group">
                <label for="presentation-name"><b>Title</b></label>
                <input type="text" class="form-control" name="presentation-name" value="<?= $presentation_data->title ?>">
            </div>
            <div class="form-group">
                <label for="presentation-description"><b>Description</b></label>
                <textarea class="form-control" name="presentation-description" placeholder="Your presentation description" rows="10"><?= $presentation_data->description ?></textarea>
            </div>
        </form>        
    </div>

    <div class="jumbotron" style="padding: 1rem 2rem;">
        <p>You can add a segment.</p>
        <p class="lead">
            <a class="btn btn-primary btn-md" href="#" role="button" id="top-btn-add-video" data-toggle="modal" data-target="#add-segment-modal">Add a Segment</a>
        </p>
    </div>

</div>

<!-- Modal -->
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>        
                    <button type="button" class="btn btn-primary pull-right" id="create-segment-btn">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>