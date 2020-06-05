<link rel="stylesheet" media="screen, print" href="v2/css/datagrid/datatables/datatables.bundle.css">


<div class="row dashboard-row">
    <div class="col-xl-12">
      <div class="dashboard-container">
          <?php foreach($data->dashboard as $icon) { ?>
              <div class="dashboard-item">
                <a href="<?= URL::parseLink($icon->link) ?>">
                  <div>
                    <img src="<?= ROOT_URL ?>uploads/adminicons/<?= $icon->icon ?>">
                      <p><?= $icon->name ?></p>
                  </div>
                </a>
              </div>
            <?php } ?>
      </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Login Statistic
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <table id="admin-logins-table" class="table table-bordered table-hover table-striped w-100">
                        <thead>
                            <tr>
                                <th>Rendering engine</th>
                                <th>Browser</th>
                                <th>Platform(s)</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data->authlogs as $login){ ?>
                            <tr>
                                <td><?= $login->user ?></td>
                                <td><?= $login->browser ?></td>
                                <td><?= $login->platform ?></td>
                                <td><?= $login->date ?></td>
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
        $('#admin-logins-table').dataTable({
            lengthChange: true,
            responsive: true,
            dom: "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end dinamic-table-cols-container'lB>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                    extend: 'colvis',
                    text: 'Column Visibility',
                    titleAttr: 'Col visibility',
                    className: 'btn-outline-default'
                },
                {
                    extend: 'csvHtml5',
                    text: 'CSV',
                    titleAttr: 'Generate CSV',
                    className: 'btn-outline-default'
                },
                {
                    extend: 'copyHtml5',
                    text: 'Copy',
                    titleAttr: 'Copy to clipboard',
                    className: 'btn-outline-default'
                },
                {
                    extend: 'print',
                    text: 'Print',
                    titleAttr: 'Print Table',
                    className: 'btn-outline-default'
                }

            ]
        });
    });
</script>
