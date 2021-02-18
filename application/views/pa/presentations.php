<div class="container">
    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Presentations Management</h3>
        <div class="col-xs-12 col-sm-6 text-right"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-presentation-modal">Create New Presentation</button></div>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="deleted-presentation-alert">
      <strong>Well Done!</strong> The Presentation has been deleted.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="card shadow">
      <div class="card-header py-3">
        <p class="text-primary m-0 font-weight-bold">Your Presentations List</p>
      </div>
      <div class="card-body">

        <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
          <table class="table dataTable my-0" id="userTable">
            <thead>
              <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Public Page</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($presentations_list as $p) : ?>
                <tr data-uid="<?php echo $p->id; ?>">
                  <td><?php echo htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($p->description, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars($p->created_on, ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><a href="<?=base_url()."Pa_dashboard/edit_presentation/".$p->id?>">Edit</a></td>
                  <td><a href="#" data-pid="<?=$p->id?>" class="btn-delete-presentation-showmodal" data-name="<?=$p->title?>">Delete</a></td>
                  <td><a href="<?=base_url()."presentation/index/".$p->id?>" target="_blank">User View</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

<!-- Modal -->
<div class="modal fade" id="new-presentation-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create New Presentation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="create_presentation_form">
      <div class="modal-body">
        
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <div class="form-group"><input class="form-control" type="text" name="title" id="presentation-name" placeholder="Name"></div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" id="presentation-description" rows="10"></textarea>                
            </div>           
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary form-control" id="create-presentation-btn">Create</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>        
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete presentation modal -->
<div class="modal fade" id="delete-presentation-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Delete presentation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form id="delete-presentation-form">
        <!-- Modal body -->
        <div class="modal-body">
          <p>Are you sure you want to delete the presentation "<strong id="delete-presentation-name">NO NAME</strong>"?</p>
          
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <input type="hidden" name="delete-pid" value="" id="input-delete-pid">
          
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete-presentation-comfirm">Delete</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
        </form>

      </div>
    </div>
  </div>