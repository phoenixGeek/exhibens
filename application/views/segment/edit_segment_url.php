<style>
    #updateSeg-btn {
        position: relative;
        right: 40px
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
        <form class="col-md-12" id="update-segment-url-form">
            <?php
                $token_name = $this->security->get_csrf_token_name();
                $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" data-id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <div class="form-group">
                <label for="segment-name"><b>Name</b></label>
                <input type="text" class="form-control" name="segment-name" value="<?= $segments_added[0]->name ?>">
                <input type="hidden" name="update-pid" value="<?= $presentation_data->id ?>" id="update-pid">
                <input type="hidden" name="update-segUrlId" value="<?= $segments_added[0]->id ?>" id="update-segUrlId">
            </div>
            <div class="form-group">
                <label for="segment-description"><b>Description</b></label>
                <textarea class="form-control" name="segment-description" placeholder="Your segment description" rows="5"><?= $segments_added[0]->content ?></textarea>
            </div>
            <div class="form-group">
                <label for="segment-url"><b>Segment URL</b></label>
                <input type="text" class="form-control" name="segment-url" value="<?= $segments_added[0]->segment_url ?>">
            </div>
        </form>        
    </div>

    <a href="#" class="btn btn-primary pull-right" id="updateSegURL-btn" data-id="<?= $presentation_data->id ?>"><i class="bi bi-save"></i>Update</a>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {

        $('#updateSegURL-btn').click(function() {
            console.log("click update")
            var thisFormData = new FormData($("#update-segment-url-form")[0]);
            $.ajax({
                type: "POST",
                url: base_url + "pa_dashboard/update_segment_url/" + $("#update-pid").val() + "/" + $("#update-segUrlId").val(),
                data: thisFormData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(segid) {
                    
                    location.reload();
                }
            });
        })
    })
</script>