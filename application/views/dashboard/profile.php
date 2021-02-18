<div class="container">
    <h2>My profile</h2>
    <hr class="top-hr-line">
    <div class="alert alert-warning" style="display:none;" role="alert">
    </div>
    <div class="alert alert-success" style="display:none;" role="alert">
    </div>
    <form action="/profile/edit" method="post" id="edit-profile-form" onsubmit="return;">
        <?php
            $csrf = array(
                'name' => $this->security->get_csrf_token_name(),
                'hash' => $this->security->get_csrf_hash()
            );
        ?>
        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" class="csrf-token" />
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="user-avatar">
                        <input type="file" id="user-avatar" name="avatar" accept="image/*" class="form-control d-none">
                        <img 
                            src="<?= empty($current_user->avatar) ? '/assets/dashboard/img/avatars/user-default.jpg' : $current_user->avatar; ?>" 
                            alt="<?= $current_user->first_name ?>"
                            class="img-thumbnail img-fluid"
                        >
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" class="form-control" id="first-name" name="first_name" value="<?= $current_user->first_name ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" class="form-control" id="last-name" name="last_name" value="<?= $current_user->last_name ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= $current_user->phone ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" class="form-control" id="company" name="company" value="<?= $current_user->company ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?= $current_user->username ?>">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?= $current_user->email ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-btn-outline-primary">
                        <i class="fa fa-save"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>