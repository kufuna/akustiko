<!--Statistic Js-->
<script type="text/javascript" src="js/plugins/charts/jquery.flot.js"></script>
<script type="text/javascript" src="js/charts/chart.js"></script>
<!--users js-->
<script type="text/javascript" src="js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript">
$(function() {
  oTable = $('.dTable').dataTable({
		"bJQueryUI": false,
		"bAutoWidth": false,
		"sPaginationType": "full_numbers",
		"sDom": '<"H"fl>t<"F"ip>'
	});
})
</script>

<?php if($data->error) { ?>
  <div class="nNote nFailure">
    <p><?= $data->error ?></p>
  </div>
<?php } ?>


<div class="widget chartWrapper">
  <div class="whead"><h6>Charts</h6>
      <div class="titleOpt">
          <a href="#" data-toggle="dropdown"><span class="icos-cog3"></span><span class="clear"></span></a>
          <ul class="dropdown-menu pull-right">
            <li><a href="#"><span class="icos-add"></span>Add</a></li>
            <li><a href="#"><span class="icos-trash"></span>Remove</a></li>
            <li><a href="#" class=""><span class="icos-pencil"></span>Edit</a></li>
            <li><a href="#" class=""><span class="icos-heart"></span>Do whatever you like</a></li>
          </ul>
      </div>
      <div class="clear"></div>
  </div>
  <div class="body">
    <div class="chart"></div>
  </div>
</div>

<form action="<?= ADMIN_URL ?>stats" method="POST">
  <fieldset>
    <div class="fluid">
      <div class="widget grid6" style="width: 100%">
        <div class="formRow">
          <input type='submit' style="margin: 0 auto; width: 120px; height: 40px; text-transform:capitalize" value="Redraw Statistics" name="clear_stats" class="sideB bLightBlue">
        </div>
      </div>
    </div>
  </fieldset>
</form>
     
<?php
function buildJsString($data, &$maxY) {
  $javascriptArrayString = '';
  
  foreach($data as $stat) {
    $date = $stat->date;
    $count = $stat->count;
    
    $javascriptArrayString .= '[new Date("'.$date.'"), '.$count.'],';
    $maxY = $maxY < $count ? $count : $maxY;
  }
  
  return $javascriptArrayString;
}

$maxY = 0;

$jsStatString = buildJsString($data->stats, $maxY);
?>
  

<script>
$(function() {
  
  var stats = [];
  
  stats.push({ data: [<?= $jsStatString ?>], label: "Users"});
  
  var cfg = {
    stats: stats,
    yMin: 0,
    yMax: <?= $maxY + 1 ?>,
    label: ' visited your website in '
  };
  
  drawGeneralChart(cfg);
})
</script>

