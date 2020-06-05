<link rel="stylesheet" media="screen, print" href="v2/css/datagrid/datatables/datatables.bundle.css">
<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Deleted Rows
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="admin-trash-table" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Date</th>
                                <th>Table</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data->trash as $item){  ?>
                            <tr>
                                <td><?= $item->username ?></td>
                                <td><?= $item->date ?></td>
                                <td><?= $item->table ?></td>
                                <td class="action">
                                    <a href="<?= ADMIN_URL."trash/more/".$item->id ?>" class="btn btn-primary btn-sm btn-icon waves-effect waves-themed view-trash-btn">
                                        <i class="fal fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $scriptDataTables = true; ?>
<script>
    $(document).ready(function() {
        $('#admin-trash-table').dataTable({
            lengthChange: true,
            responsive: true,
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end dinamic-table-cols-container'lB>>",
            buttons: [{
                extend: 'colvis',
                text: 'Column Visibility',
                titleAttr: 'Col visibility',
                className: 'btn-outline-default'
            }]
        });
    });
</script>
