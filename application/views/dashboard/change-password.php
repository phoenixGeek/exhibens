<div class="container">
    <h2>Change password</h2>
    <hr class="top-hr-line">
    <div class="alert alert-warning" style="display:none;" role="alert">
    </div>
    <div class="alert alert-success" style="display:none;" role="alert">
    </div>
    <form action="/profile/update-password" method="post" id="edit-profile-form" onsubmit="return;">
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
                    <label for="old_password">Current Password</label>
                    <input type="password" class="form-control" id="old_password" name="old">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="re-password">Repeat password</label>
                    <input type="password" class="form-control" id="re-password" name="re_password">
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