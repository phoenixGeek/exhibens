<div class="container-fluid">
  <h2>Dashboard / User</h2>
  <hr class="top-hr-line">

  <div class="row">
    <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Users Management</h3>
    <div class="col-xs-12 col-sm-6 text-right"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-user-modal">Create New User</button></div>
  </div>

  <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="created-user-alert">
    <strong>Well Done!</strong> New user account has been created.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="edited-user-alert">
    <strong>Well Done!</strong> User account has been updated.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="deleted-user-alert">
    <strong>Well Done!</strong> User account has been deleted.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="card shadow">
    <div class="card-header py-3">
      <p class="text-primary m-0 font-weight-bold">Users List</p>
    </div>
    <div class="card-body">

      <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
        <table class="table dataTable my-0" id="userTable">
          <thead>
            <tr>
              <th>Username</th>
              <th>Email</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Active</th>
              <th>Profile</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>

            <?php foreach ($users as $user) : ?>
              <tr data-uid="<?php echo $user->id; ?>">
                <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->active, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><button data-toggle="modal" data-target="#edit-user-modal" data-uid="<?= $user->id ?>" data-username="<?= $user->username ?>" data-email="<?= $user->email ?>" data-first-name="<?= $user->first_name ?>" data-last-name="<?= $user->last_name ?>" data-active="<?= $user->active ?>" class="btn btn-light btn-edit-user">Edit</button></td>
                <td><button data-toggle="modal" data-target="#delete-user-modal" data-uid="<?= $user->id ?>" class="btn btn-light btn-delete-user" data-username="<?= $user->username ?>">Delete</button></td>

              </tr>
            <?php endforeach; ?>
          </tbody>


        </table>
      </div>

    </div>
  </div>

  <!-- The Create User Modal -->
  <div class="modal" id="new-user-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Create New User Account</h4>
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
            <div class="form-group"><input class="form-control" type="email" name="email" id="email" placeholder="Email"></div>
            <div class="form-group"><input class="form-control" type="text" name="username" placeholder="Username" id="username"></div>
            <div class="form-group"><input class="form-control" type="text" name="first_name" id="first_name" placeholder="First Name"></div>
            <div class="form-group"><input class="form-control" type="text" name="last_name" id="last_name" placeholder="Last Name"></div>
            <div class="form-group"><input class="form-control" type="password" name="password" value="" id="password" placeholder="Password"></div>
            <div class="form-group"><input class="form-control" type="password" name="password_confirm" placeholder="Password (repeat)" id="password_confirm"></div>
            <div class="form-group">
              <label for="group-slt">User Group</label>

              <select class="form-control" id="group-slt" name="group-slt">
                <option value="-1" selected="">Select a group</option>
                <?php
                foreach ($groups as $key => $g) :
                ?>
                  <option value="<?= $g->id ?>"><?= $g->name ?></option>
                <?php
                endforeach;
                ?>
              </select>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-create-user-modal">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>


  <!-- The Edit User Modal -->
  <div class="modal" id="edit-user-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Edit User Info</h4>
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

            <input type="hidden" name="edit-uid" value="" id="input-edit-uid">

            <div class="form-group"><label>Email</label><input class="form-control" type="email" name="edit_email" id="edit-email" placeholder="Email"></div>
            <div class="form-group"><label>Username</label><input class="form-control" type="text" name="edit_username" placeholder="Username" id="edit-username"></div>
            <div class="form-group"><label>First Name</label><input class="form-control" type="text" name="edit_first_name" id="edit-first-name" placeholder="First Name"></div>
            <div class="form-group"><label>Last Name</label><input class="form-control" type="text" name="edit_last_name" id="edit-last-name" placeholder="Last Name"></div>
            <div class="custom-control custom-checkbox form-group">
              <input type="checkbox" class="custom-control-input" name="edit-active-account" id="active-check">
              <label class="custom-control-label" for="active-check">Active account</label>
            </div>
            <label>Reset Password : </label>
            <div class="form-group"><input class="form-control" type="text" name="edit_password" id="edit-password" placeholder="New Password"></div>
            <div class="form-group"><input class="form-control" type="text" name="edit_password_confirm" id="edit-password-confirm" placeholder="Repeat New Password"></div>
            <div class="form-group">
              <label for="group-slt2">User Group</label>

              <select class="form-control" id="group-slt2" name="group-slt2" multiple="multiple">
                <?php
                foreach ($groups as $key => $g) :
                ?>

                  <option value="<?= $g->id ?>">
                    <?= $g->name ?>
                  </option>
                <?php
                endforeach;
                ?>
              </select>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-edit-user-modal">Submit</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

  <!-- The Delete User Modal -->
  <div class="modal" id="delete-user-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title">Delete User Account</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <p>Are you sure you want to delete the account "<strong id="delete-account-name">NO NAME</strong>"?</p>
          <form>
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <input type="hidden" name="delete-uid" value="" id="input-delete-uid">
          </form>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-delete-user-comfirm">Delete</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>

      </div>
    </div>
  </div>
</div>