<link rel="stylesheet" href="/assets/plugins/jstree/style.min.css">
<div class="container-fluid">
    <h2>Dashboard / Categories</h2>
    <hr class="top-hr-line">

    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Categories Management</h3>
    </div>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="created-page-alert">
        <strong>Well Done!</strong> New page has been created.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 font-weight-bold">Categories List</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-sm-8 col-xs-8">
                    <button type="button" class="btn btn-success" onclick="category_create();"><i class="fa fa-plus-square-o"></i> Create</button>
                    <button type="button" class="btn btn-warning" onclick="category_rename();"><i class="fa fa-pencil"></i> Rename</button>
                    <button type="button" class="btn btn-danger" onclick="category_delete();"><i class="fa fa-trash-o"></i> Delete</button>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-4" style="text-align:right;">
                    <input type="text" class="form-control" id="category_q" placeholder="Search" />
                </div>
            </div>
            <?php
            $token_name = $this->security->get_csrf_token_name();
            $token_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" id="csrf3" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />
            <div class="list-category mt-2">
            </div>

        </div>
    </div>
</div>