<?php
  $tpls = array();

  foreach($cfg->value as $tpl) {
    $tpls[] = (object) array( 'id' => $tpl->id, 'title' => $tpl->title );
  }
?>
<script>
  var tpls = {};

  <?php
    foreach($cfg->value as $tpl) {
      echo 'tpls["'.$cfg->id.'_tpl_'.$tpl->id.'"] = '.str_replace("'", "\\'", json_encode($tpl)).';';
    }
  ?>
</script>

<div class="formRow" id="select_box_formrow_<?= $cfg->id ?>">
  <div class="grid3"><label style="float:none">Choose template:</label></div>
  <div class="grid9 searchDrop">
    <select id="select_box_<?= $cfg->id ?>" name="template_id_<?= $cfg->field_name ?>" data-placeholder="<?= $cfg->placeholder ?>" class="select" style="width:<?= $cfg->width ?>px;">
      <option value="0">Choose template</option>
      <?php foreach($tpls as $option) { ?>
        <option value="<?= $option->id ?>"><?= $option->title ?></option>
      <?php } ?>
    </select>
  </div>             
  <div class="clear"></div>
</div>

<iframe id="iframe_<?= $cfg->id ?>" style="display: block; width: 100%; height: 500px; border:0"></iframe>

<input type="hidden" name="template_generated_html_<?= $cfg->id ?>" id="template_generated_html_input_<?= $cfg->id ?>" />

<script>
$(function() {
  var chosenTemplate;
  
  loadScript(["js/plugins/forms/jquery.chosen.min.js" ], onload);
  function onload() {
    $("#select_box_<?= $cfg->id ?>").chosen();
    
    $("#select_box_<?= $cfg->id ?>").change(function() {
      chosenTemplate = tpls["<?= $cfg->id ?>_tpl_" + this.value];
      
      $('#iframe_<?= $cfg->id ?>').contents().find("body").html(chosenTemplate.html)
      
      createFields(JSON.parse(chosenTemplate.fields))
    });
  }
  
  function createFields(fields) {
    $('.custom-dynamic-field').remove();
  
    for(i in fields) {
      var field = fields[i];
      
      for(key in field) {
        var input = $('<input type="text" class="dynamic_field_<?= $cfg->id ?>" data-name="' + field[key] + '" name="dynamic_field_<?= $cfg->id ?>_' + field[key] + '" placeholder="" value="">').change(setCustomValues)
        
        var div = $('<div class="formRow custom-dynamic-field"><label>' + key + ':</label></div>').append(input);
        div.insertAfter($('#select_box_formrow_<?= $cfg->id ?>'));
      }
    }
  }
  
  function setCustomValues() {
    var inputs = $('.dynamic_field_<?= $cfg->id ?>');
    var html = chosenTemplate.html;
    
    for(var i = 0; i < inputs.length; i++) {
      var input = inputs[i];
      
      var pattern = new RegExp($(input).data().name, 'g')
      html = html.replace(pattern, input.value);
    }
    
    $('#iframe_<?= $cfg->id ?>').contents().find("body").html(html)
    $('#template_generated_html_input_<?= $cfg->id ?>').val(html)
  }
})
</script>
