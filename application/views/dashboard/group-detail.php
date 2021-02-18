<style>
    #add-user-form {
        margin-bottom: 30px;
    }

    #add-user-to-group {
        margin: 0 15px;
    }

    .tt-menu {
        background: white;
        border: 1px solid #ccc;
        width: 350px;
        padding: 10px 5px;
        left: 15px !important;
    }

    .tt-suggestion {
        padding: 5px 7px;
    }

    .tt-suggestion:hover {
        background: #dedede;
        color: black;
    }
</style>
<script>
    var gid = <?= $group->id ?>;
    var user_not_in_group = JSON.parse('<?= json_encode($user_not_in_group) ?>');
    console.log(user_not_in_group);
</script>
<div class="container-fluid">
    <h2>Group : <?= $group->name ?></h2>
    <p><?= $group->description ?></p>
    <hr class="top-hr-line">

    <div class="row">
        <p class="text-dark mb-4 col-xs-12 col-sm-6">Number of users : <span id="num_of_users"><?= count($users) ?></span></p>
    </div>

    <div class="row" id="add-user-form">
        <form class="form-inline col-md-8" action="">
            <div class="form-group">
                <?php
                $token_name = $this->security->get_csrf_token_name();
                $token_hash = $this->security->get_csrf_hash();
                ?>
                <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
                <label for="user">Add user to group</label>
                <input type="text" class="form-control" id="add-user-to-group" style="width:350px!important; autocomplete=" off">
            </div>

            <button type="button" class="btn btn-primary" id="add-user-to-group-btn">Submit</button>
        </form>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="added-user-alert">
        <strong>Well Done!</strong> User account has been added to the group.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="remove-user-alert">
        <strong>Well Done!</strong> User account has been removed from the group.
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
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                            <tr data-uid="<?= $user->id ?>">
                                <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($user->active, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><button data-toggle="modal" data-target="#remove-user-modal" data-uid="<?= $user->id ?>" class="btn btn-light btn-remove-user" data-username="<?= $user->username ?>">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- The Remove User Modal -->
    <div class="modal" id="remove-user-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Remove User From Group</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove the account "<strong id="delete-account-name">NO NAME</strong>"?</p>
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
                    <button type="button" class="btn btn-primary btn-remove-user-comfirm">Remove</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

</div>