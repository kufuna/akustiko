<?php 
  if($data->error) {
    echo '<div class="nNote nFailure"><p>'.$data->error.'</p></div><br />';
  }

  if($data->item) {
    $date = $data->item->date;
    $username = $data->item->username;
    $metadata = json_decode($data->item->metadata);
  ?>
<?php } ?>


<div id="panel-1" class="panel trash-inner-panel">
    <div class="panel-hdr">
        <h2>
            Deleted Item
        </h2>
        <div class="panel-toolbar">
            <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
        </div>
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
                    <?php foreach ($metadata as $key => $value): ?>
                    <tr>
                        <td><?= $key ?></td>
                        <td><?= $value ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <form action="<?= ADMIN_URL.'trash/' ?>" method="POST">
                <input type="hidden" name="delete" value="<?= $data->item->id ?>">
                <button type="submit" class="btn btn-danger mr-2 waves-effect waves-themed">Delete Forever</button>
            </form>
            <form action="<?= ADMIN_URL.'trash' ?>" method="POST">
                <input type="hidden" name="restore" value="<?= $data->item->id ?>">
                <button type="submit" class="btn btn-primary mr-2 waves-effect waves-themed">Restore</button>
            </form>
        </div>
    </div>
</div>
