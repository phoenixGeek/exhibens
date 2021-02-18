<div class="container-fluid">
  <h2>Dashboard / Group</h2>
  <hr class="top-hr-line">

  <div class="row">
    <h3 class="text-dark mb-4 col-xs-12 col-sm-6">User Groups Management</h3>
    <div class="col-xs-12 col-sm-6 text-right"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-group-modal">Create New Group</button></div>
  </div>

  <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="created-group-alert">
    <strong>Well Done!</strong> New user group has been created.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="deleted-group-alert">
    <strong>Well Done!</strong> User Group has been deleted.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="edited-group-alert">
    <strong>Well Done!</strong> User Group has been updated.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>


  <div class="card shadow">
    <div class="card-header py-3">
      <p class="text-primary m-0 font-weight-bold">Videos List</p>
    </div>
    <div class="card-body">

      <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
        <table class="table dataTable my-0" id="groupTable">
          <thead>
            <tr>
              <th>Group Name</th>
              <th>Description</th>
              <th>Number of users</th>
              <th>Detail</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>

            <?php foreach ($groups as $g) : ?>
              <tr data-uid="<?= $g->id ?>">
                <td><?php echo htmlspecialchars($g->name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($g->description, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $g->num_of_users; ?></td>
                <td><a href="<?= base_url() ?>/dashboard/group_detail/<?= $g->id ?>">Detail</a></td>
                <td><button data-toggle="modal" data-target="#edit-group-modal" data-gid="<?= $g->id ?>" data-groupname="<?= $g->name ?>" data-description="<?= $g->description ?>" class="btn btn-light btn-edit-group">Edit</button></td>

                <td>
                  <?php
                  if ($g->name !== $this->config->item('admin_group', 'ion_auth') && $g->name !==  $this->config->item('default_group', 'ion_auth')) {
                  ?>
                    <button data-toggle="modal" data-target="#delete-group-modal" data-gid="<?= $g->id ?>" class="btn btn-light btn-delete-group" data-groupname="<?= $g->name ?>">Delete</button>
                  <?php
                  }
                  ?>
                </td>

              </tr>
            <?php endforeach; ?>
          </tbody>


        </table>
      </div>

    </div>
  </div>

  <!-- The Create User Modal -->
  <div class="modal" id="new-group-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Create New User Group</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert" style="display: none;">
          </div>
          <form method="post">
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <div class="form-group"><input class="form-control" type="text" id="groupname" name="groupname" placeholder="Group Name"></div>
            <div class="form-group"><input class="form-control" type="text" id="groupdescription" name="description" id="description" placeholder="description"></div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-create-group-modal">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="edit-group-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Group Info</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger" role="alert" style="display: none;">
          </div>
          <form method="post">
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf2" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <input type="hidden" name="edit-gid" value="" id="input-edit-gid">
            <div class="form-group"><label>Group Name</label><input class="form-control" type="text" name="edit_groupname" id="edit-groupname" placeholder="Group Name"></div>
            <div class="form-group"><label>Description</label><input class="form-control" type="text" name="edit_description" placeholder="Description" id="edit-description"></div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-edit-group-modal">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

  <!-- The Delete Group Modal -->
  <div class="modal" id="delete-group-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Group</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete the Group "<strong id="delete-group-name">NO NAME</strong>"?</p>
          <form>
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <input type="hidden" name="delete-gid" value="" id="input-delete-gid">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete-group-comfirm">Delete</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>

      </div>
    </div>
  </div>

</div>