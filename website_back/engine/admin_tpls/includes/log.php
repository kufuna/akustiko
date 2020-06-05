<?php 
  $log = $data->log;
  $username = $log->username;
  $date = $log->date;

?>

<?php if ($log->type == 'content_edit'): ?>
    <?php 
        $metadata = json_decode($log->metadata);

        $newDataArray = array();
        $oldDataArray = array(); 

        foreach ($metadata->new as $key => $value) {
          $newDataArray[$key] = $value;
        }

        foreach ($metadata->old as $key => $value) {
          $oldDataArray[$key] = $value;
        }
    ?>
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        Old <span class="fw-300"><i><?= $log->type ?></i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>User</td>
                                    <td><?= $username ?></td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td><?= $date ?></td>
                                </tr>
                                <?php foreach ($oldDataArray as $key => $value) {
                               if ($value == $newDataArray[$key]) { ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $value ?></td>
                                </tr>
                                <?php }else{ ?>
                                <tr style="background: #FC9898; word-break: break-all;">
                                    <td><?= $key ?></td>
                                    <td><?= $value ?></td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>
                        New <span class="fw-300"><i><?= $log->type ?></i></span>
                    </h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>User</td>
                                    <td><?= $username ?></td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td><?= $date ?></td>
                                </tr>
                                <?php foreach ($newDataArray as $key => $value) {
                               if ($value == $oldDataArray[$key]) { ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $value ?></td>
                                </tr>
                                <?php }else{ ?>
                                <tr style="background: #BBFFAA; word-break: break-all;">
                                    <td><?= $key ?></td>
                                    <td><?= $value ?></td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php $metadata = json_decode($log->metadata); ?>
    <div id="panel-1" class="panel">
        <div class="panel-hdr">
            <h2>
                Changes <span class="fw-300"><i><?= $log->type ?></i></span>
            </h2>
            <div class="panel-toolbar">
                <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
            </div>
        </div>
        <div class="panel-container show">
            <div class="panel-content">
                <table class="table table-striped">
                    <tbody>
                        <?php foreach ($metadata as $data): ?>
                            <?php foreach ($data as $key => $value): ?>
                            <tr>
                                <td><?= $key ?></td>
                                <td><?= $value ?></td>
                            </tr>
                            <?php endforeach ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif ?>
