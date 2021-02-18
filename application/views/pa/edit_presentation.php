<style>
    #userTable {
        border-top: none !important;
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

    <div class="row mb-3">
        <form class="col-md-12" id="presentation-overview">
            <div class="form-group">
                <label for="presentation-name"><b>Name</b></label>
                <input type="text" class="form-control" name="presentation-name" value="<?= $presentation_data->title ?>">
            </div>
            <div class="form-group">
                <label for="presentation-description"><b>Description</b></label>
                <textarea class="form-control" name="presentation-description" placeholder="Your presentation description" rows="5"><?= $presentation_data->description ?></textarea>
            </div>
        </form>
        <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
        ?>
        <form>
            <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
        </form>   
    </div>

    <div class="card shadow">
      <div class="card-header py-3">
        <p class="text-primary m-0 font-weight-bold">Your Segments List
            <a class="btn btn-primary btn-md pull-right" href="#" role="button" id="top-btn-add-video" data-toggle="modal" data-target="#add-segment-modal">Add a Segment</a>
        </p>
      </div>
      <div class="card-body">

        <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
          <table class="table dataTable my-0" id="userTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Public Page</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($segments_added as $segment) : ?>
                <tr data-uid="<?php echo $segment->id; ?>">
                  <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($segment->content, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($segment->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><a href="<?=base_url()."/Pa_dashboard/create_segment/". $segment->presentation_id."/". $segment->id?>">Edit</a></td>
                  <td><a href="#" data-pid="<?=$segment->presentation_id?>" data-segid="<?=$segment->id?>" class="btn-delete-segment-showmodal" data-name="<?=$segment->name?>">Delete</a></td>
                  <td><a href="<?=base_url()."segment/index/".$presentation_data->id . "/" .$segment->id ?>" target="_blank">View Segment</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-3"></div>
        <div class="col-xl-12">
            <a href="#" class="btn btn-primary pull-right" id="updatePre-btn" data-id="<?= $presentation_data->id ?>"><i class="bi bi-save2"></i>Update</a>
            <a href="<?=base_url()."Pa_dashboard/presentations/" ?>" class="btn btn-secondary mr-2 pull-right">Back</a>
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
                    <input type="hidden" id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">Close</button>        
                    <button type="button" class="btn btn-primary pull-right" id="create-segment-btn">Create</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    
    $(".btn-delete-segment-showmodal").click(function(event){
        $("#delete-segment-name").html($(this).attr("data-name"));
        $("#input-delete-pid").val($(this).attr("data-pid"));
        $('#input-delete-segid').val($(this).attr("data-segid"));
        $("#delete-segment-modal").modal();
    });

    $(".btn-delete-segment-comfirm").click(function() {

        var thisFormData = new FormData($("#delete-segment-form")[0]);
        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/delete_segment/" + $("#input-delete-pid").val() + "/" + $("#input-delete-segid").val(),
            data: thisFormData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(data){
                
                if(data.success){
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;

                    $(".csrftoken").attr("name", csrfName);
                    $(".csrftoken").val(csrfHash);

                    $("#delete-segment-modal").modal("hide");

                    $("a[data-segid='" + data.success + "']").closest("tr").fadeOut(1000, function() {
                        $("a[data-segid='" + data.success + "']").closest("tr").remove();
                    });

                    $("#deleted-segment-alert").show();

                    setTimeout(function() {
                        $('#deleted-segment-alert').fadeOut(1000);
                    }, 3000);
                }
            }
        });
    });

    $("#updatePre-btn").click(function() {
        var data = {
            name: $("input[name='presentation-name']").val(),
            description: $("textarea[name='presentation-description']").val(),
        };
        data[$("#csrf").attr("name")] = $("#csrf").val();

        var pid = $(this).attr("data-id");

        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/update_presentation/" + pid,
            processData: true,
            dataType: "json",
            data: data,
            success: function(res) {
                console.log("res", res);
                if(res.success) {
                    $("#updated-presentation-alert").show();
                    setTimeout(function() {
                        $('#updated-presentation-alert').fadeOut(1000);
                    }, 3000);
                }
            }
        });
    });

    $('#create-segment-btn').click(function () {

        var thisFormData = new FormData($("#create_segment_form")[0]);
        $.ajax({
            type: "POST",
            url: base_url + "pa_dashboard/ajax_addsegment",
            data: thisFormData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (res) {
                console.log("success", res);
                window.location.href = base_url + "pa_dashboard/create_segment/" + $('input[name="presentation_id"]').val() + "/" + res;
            }
        });
    })



</script>