<?php
  $map = json_decode($cfg->value);
?>

<div class="form-group">
  <div style="width: 100%; height: 500px;" id="<?= $cfg->id ?>"></div>
  <input type="hidden" name="<?= $cfg->field_name ?>" id="input_<?= $cfg->id ?>" />
</div>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=<?= GOOGLE_MAPS_API_KEY ?>"></script>
<script type="text/javascript">
  function initialize() {
    var data = {
      lon: <?= isset($map->lon) ? $map->lon : 44.8271443 ?>,
      lat: <?= isset($map->lat) ? $map->lat : 41.7177089 ?>,
      zoom: <?= isset($map->zoom) ? $map->zoom : 12 ?>
    };

    $('#input_<?= $cfg->id ?>').val(JSON.stringify(data));

    var myOptions = {
      zoom: data.zoom,
      center: new google.maps.LatLng(data.lat, data.lon),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById('<?= $cfg->id ?>'),
        myOptions);

    var stylez = [
        {
          featureType: "all",
          elementType: "all"//,
          /*stylers: [ { saturation: -100 } ]*/
        }
    ];

    map.setOptions({styles: stylez});

    var marker = new google.maps.Marker({
      position: new google.maps.LatLng(data.lat, data.lon),
      map: map
    });

    function placeMarker(location) {
      data.lat = location.lat();
      data.lon = location.lng();
      data.zoom = map.getZoom();
      $('#input_<?= $cfg->id ?>').val(JSON.stringify(data));

      if ( marker ) {
        marker.setPosition(location);
      } else {
        marker = new google.maps.Marker({
          position: location,
          map: map
        });
      }
    }

    google.maps.event.addListener(map, 'click', function(event) {
      placeMarker(event.latLng);
    });

    google.maps.event.addListener(map, 'zoom_changed', function(event) {
      data.zoom = map.getZoom();
      $('#input_<?= $cfg->id ?>').val(JSON.stringify(data));
    });
  }

  google.maps.event.addDomListener(window, 'load', initialize);
</script>
