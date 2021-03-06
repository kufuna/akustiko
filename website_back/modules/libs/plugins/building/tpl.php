<div class="formRow">
  <div style="margin-bottom: 0px">
    <label><?= $cfg->label ?>:</label><br />
    <input id="<?= $cfg->id ?>" type="file" class="fileInput <?= $cfg->field_name ?>" name="<?= $cfg->field_name ?>" />
  </div>
  <div class="<?= $cfg->preview_name ?> building-container-prev">
      <?php
        $previewStyle = $cfg->value  ? ' style="width: '.$cfg->value->width.'px; height: '.$cfg->value->height.'px;"' : '';
      ?>
    
      <div class="bilding-photo-preview" id="building_photo_parent_<?= $cfg->id ?>"<?= $previewStyle ?>>
        <img src="<?= $cfg->value ? $cfg->value->src : '' ?>?nocache='<?= rand(0, 10000) ?>" id="img_<?= $cfg->id ?>" />
        <svg id="svg_<?= $cfg->id ?>" class="building-svg"<?= $previewStyle ?>>
          <?php
          if($cfg->value) {
            foreach($cfg->value->floors as $floor) {
          ?>
            <polygon fill="rgba(81,124,218,.8)" class="fadeable-svg-polygon" data-floornumber="<?= $floor->{$cfg->floor_number_col} ?>" data-coords="<?= $floor->coords ?>" points="<?= $floor->coords ?>"></polygon>
          <?php
              }
            }
          ?>
        </svg>
        <div class="floor-number-preview" id="fnumber_<?= $cfg->id ?>"></div>
      </div>
    
      <input type="hidden" value="" name="floors_input_<?= $cfg->id ?>" id="floors_input_<?= $cfg->id ?>" />
  </div>
  <div class="clear"></div>
</div>

<div class="formRow">
  <div style="margin-bottom: 15px">
    <label>Coordinates HTML:</label><br />
    <input id="coords_<?= $cfg->id ?>" type="file" class="fileInput coords_<?= $cfg->field_name ?>" />
  </div>
  <div class="clear"></div>
</div>

<script>
  $(function() {
    loadCss(["css/jquery.Jcrop.min.css" ]);
    
    loadScript(["js/plugins/forms/jquery.uniform.js", "js/imageupload.js",
                "js/jquery.Jcrop.js" ], onload);
                
    function onload() {
      $("#<?= $cfg->id ?>, #coords_<?= $cfg->id ?>").uniform();
      
      $("#<?= $cfg->id ?>").change(function() {
        if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
          alert('The File APIs are not fully supported in this browser.');
          return;
        }

        if(this.files.length < 1) return false;
        for(var i = 0; i < this.files.length; i++) {
          if(i == 1) break;
        
          var f = this.files[i];
          if(!f.type.match('image.*')) {
            return alert('Unsupported file');
          }
          
          var reader = new FileReader();
          
          reader.onload = (function(theFile, ord) {
            return function(e) {
              var img = $('#img_<?= $cfg->id ?>');
              img.prop({  draggable: false, src: e.target.result })
              
              setTimeout(function() {
                var H = img.height();
                var W = img.width();
                $('#building_photo_parent_<?= $cfg->id ?>, #svg_<?= $cfg->id ?>').css({ width: W + 'px', height: H + 'px' })
              }, 500)
            };
          })(f, i);

          reader.readAsDataURL(f);
        }
      })
      
    }
    
    $('#coords_<?= $cfg->id ?>').change(function() {
      if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
        alert('The File APIs are not fully supported in this browser.');
        return;
      }

      if(this.files.length < 1) return false;
      for(var i = 0; i < this.files.length; i++) {
        var f = this.files[i];
        
        var reader = new FileReader();
        
        reader.onload = (function(theFile, ord) {
          return function(e) {
            removeOldOnes();
            var html = atob(e.target.result.substring(22));
            var matches = html.match(/coords="(.*?)"/ig);
            for(var x = 0; x < matches.length; x++) {
              var coord = matches[x].match(/coords="(.*?)"/i);
              drawSvg(coord[1], x + 1);
            }
            serializeFloors();
          };
        })(f, i);

        reader.readAsDataURL(f);
      }
    })
    
    function removeOldOnes() {
      $('#floors_input_<?= $cfg->id ?>').val('[]');
      $('#svg_<?= $cfg->id ?>').children().remove();
    }
    
    function initPolygonEvents() {
      var els = $('#svg_<?= $cfg->id ?>').children()
      els.unbind('mouseover')
      els.unbind('mouseout')
      els.unbind('click')
      
      els.mouseover(showFloorNumber)
      els.mouseout(hideFloorNumber)
      els.click(setFloorNumber)
    }
    initPolygonEvents();
    
    function drawSvg(coords, n) {
      var polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
      polygon.setAttributeNS(null, "points", coords);
      polygon.setAttributeNS(null, "fill", "rgba(81,124,218,.8)");
      polygon.setAttribute("class", 'fadeable-svg-polygon');
      polygon.setAttribute("data-floornumber", n);
      polygon.setAttribute("data-coords", coords);
      $('#svg_<?= $cfg->id ?>').append(polygon);
      initPolygonEvents();
    }
    
    function setFloorNumber() {
      var fn = prompt('Enter floor number', $(this).data().floornumber);
      $(this).data().floornumber = fn;
      $('#fnumber_<?= $cfg->id ?>').text(fn);
      serializeFloors();
    }
    
    function showFloorNumber() {
      var offset = $(this).offset();
      var parentOffset = $('#building_photo_parent_<?= $cfg->id ?>').offset();
      var fn = $(this).data().floornumber;
      $('#fnumber_<?= $cfg->id ?>').css({ left: offset.left - parentOffset.left - 100 + 'px', top: offset.top - parentOffset.top + 'px' }).addClass('visible').text(fn);
    }
    
    function hideFloorNumber() {
      $('#fnumber_<?= $cfg->id ?>').removeClass('visible');
    }
    
    function serializeFloors() {
      var floors = $('#svg_<?= $cfg->id ?>').children()
      var result = {};
      
      for(var i = 0; i < floors.length; i++) {
        var data = $(floors[i]).data();
        result[data.floornumber] = data.coords;
      }
      
      $('#floors_input_<?= $cfg->id ?>').val(JSON.stringify(result))
    }
  })
</script>
