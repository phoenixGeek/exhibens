<style>
    .SumoSelect>.optWrapper {
        z-index: 1001;
    }
</style>
<?php
$single_name = $type === 'pages' ? 'Page' : 'Post';
$plural_name = $type === 'pages' ? 'Pages' : 'Posts';
?>
<div class="container-fluid">
    <h2>Dashboard / <?= $plural_name; ?></h2>
    <hr class="top-hr-line">
    <?php
    $token_name = $this->security->get_csrf_token_name();
    $token_hash = $this->security->get_csrf_hash();
    ?>
    <input type="hidden" id="csrf" class="csrftoken" name="<?php echo $token_name; ?>" value="<?php echo $token_hash; ?>" />

    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6"><?= $plural_name; ?> Management</h3>
        <div class="col-xs-12 col-sm-6 text-right"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#new-page-modal">Create New <?= $single_name; ?></button></div>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="created-page-alert">
        <strong>Well Done!</strong> New <?= $single_name; ?> has been created.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="edited-page-alert">
        <strong>Well Done!</strong> <?= $single_name; ?> has been updated.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;" id="deleted-page-alert">
        <strong>Well Done!</strong> User <?= $single_name; ?> has been deleted.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <input type="hidden" name="type" id="page-type" value="<?= $type; ?>">

    <div class="card shadow">
        <div class="card-header py-3">
            <p class="text-primary m-0 font-weight-bold"><?= $plural_name; ?> List</p>
        </div>
        <div class="card-body">

            <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                <table class="table dataTable my-0" id="pageTable">
                    <thead>
                        <tr>
                            <th><?= $plural_name; ?> Title</th>
                            <th>Description</th>
                            <th>Categories</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page) : ?>
                            <tr data-id="<?php echo $page->id; ?>">
                                <td><?php echo htmlspecialchars($page->page_title, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($page->page_description, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php $list_page_categories = []; ?>
                                    <?php foreach ($page->categories as $cat) : ?>
                                        <?php $list_page_categories[] = $cat->id; ?>
                                        <span class="badge badge-pill badge-info"><?= $cat->name; ?> (<?= $cat->id; ?>)</span>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <span class="badge badge-pill status-<?= $page->status ?>"><?= $page->status ?></span>
                                </td>
                                <td>
                                    <button data-toggle="modal" data-target="#edit-page-modal" data-id="<?= $page->id ?>" data-title="<?= $page->page_title ?>" data-status="<?= $page->status ?>" data-slug="<?= $page->slug ?>" data-description="<?= $page->page_description ?>" data-categories="<?= implode(",", $list_page_categories) ?>" class="btn btn-light btn-edit-page">
                                        Quick Edit
                                    </button>
                                    <a href="<?= base_url() ?>dashboard/pages/edit/<?= $page->id ?>" class="btn btn-light">
                                        Edit
                                    </a>
                                    <button data-toggle='modal' data-target='#delete-page-modal' data-id='<?= $page->id ?>' class='btn btn-danger btn-delete-page' data-title='<?= $page->page_title ?>'>Delete</button>
                                    <a href="<?= base_url() ?>preview/<?= $page->id ?>" class="btn btn-light">
                                        Preview
                                    </a>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- The Create page Modal -->
<div class="modal" id="new-page-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Create New <?= $single_name; ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="alert alert-danger" role="alert" style="display: none;">
                </div>
                <form method="post">

                    <div class="form-group">
                        <label for="edit-page_title">Title *</label>
                        <input class="form-control" type="text" name="page_title" placeholder="Content title" id="page_title">
                    </div>
                    <div class="form-group">
                        <label for="edit-page_description">Description</label>
                        <textarea class="form-control" type="text" name="page_description" id="page_description" placeholder="Write something to describe your content" rows="7"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="search-category-edit">Categories</label>
                        <select name="" id="search-category-create" class="search-category" multiple="true">
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat->id; ?>"><?= $cat->name; ?> (<?= $cat->id ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">Chose a status</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="private">Private</option>
                            <option value="trash">Trash</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug *</label>
                        <input type="text" name="slug" id="slug" class="slug form-control" required placeholder="title-content">
                        <small class="form-text text-muted">
                            This text wil be display in url<br>
                            Ex: domain.com/contact-us
                        </small>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-create-page-modal">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<!-- The Create page Modal -->
<div class="modal" id="edit-page-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Edit <?= $single_name; ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="alert alert-danger" role="alert" style="display: none;">
                </div>
                <form method="post">
                    <input type="hidden" name="edit-page_id" value="" id="edit-page_id">

                    <div class="form-group">
                        <label for="edit-page_title">Title *</label>
                        <input class="form-control" type="text" name="edit-page_title" placeholder="Content title" id="edit-page_title">
                    </div>
                    <div class="form-group">
                        <label for="edit-page_description">Description</label>
                        <textarea class="form-control" type="text" name="edit-page_description" id="edit-page_description" placeholder="Write something to describe your content" rows="7"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="search-category-edit">Categories</label>
                        <select name="" id="search-category-edit" class="search-category" multiple="true">
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat->id; ?>"><?= $cat->name; ?> (<?= $cat->id ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status *</label>
                        <select name="edit-status" id="edit-status" class="form-control" required>
                            <option value="">Chose a status</option>
                            <option value="publish">Publish</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="private">Private</option>
                            <option value="trash">Trash</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-slug">Slug *</label>
                        <input type="text" name="edit-slug" id="edit-slug" class="slug form-control" required placeholder="title-content">
                        <small class="form-text text-muted">
                            This text wil be display in url<br>
                            Ex: domain.com/contact-us
                        </small>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-edit-page-modal">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<!-- The Delete page Modal -->
<div class="modal" id="delete-page-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete <?= $single_name; ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the <?= $single_name; ?> "<strong id="delete-page-title">NO NAME</strong>"?</p>
                <form>
                    <input type="hidden" name="delete-id" value="" id="input-delete-id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-delete-page-comfirm">Delete</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>