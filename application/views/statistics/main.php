<div class="container">
    <div class="row">
        <h3 class="text-dark mb-4 col-xs-12 col-sm-6">Segments Statistics</h3>
    </div>

    <div class="card shadow">
      
        <div class="card-body">

            <div class="table-responsive table mt-2" role="grid" aria-describedby="dataTable_info">
                <table class="table dataTable my-0" id="userTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>ipaddress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($segments as $segment) : ?>
                            <tr data-uid="<?php echo $segment->id; ?>">
                                <td><?php echo htmlspecialchars($segment->name, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($segment->content, ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($segment->ipaddress, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>