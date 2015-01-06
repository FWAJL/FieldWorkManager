<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>


<div id="map"></div>

<style>
  #map {
    width: 100%;
    height: 500px;
  }
</style>

<script>
  var map;
  var objects = [
    <?php
    $object = $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects]['facility_obj'];
    echo "['".$object->facility_lat()."', '".$object->facility_long()."']";
    ?>
  ];
  function load() {
    var array = new Array();
    for (var i in objects) {
      array.push({
        "lat": objects[i][0],
        "lng": objects[i][1]
      });
    }

    map.addMarkers(array);
	map.setCenter(objects[i][0],objects[i][1]);
	map.setZoom(6);
  }
  $(document).ready(function () {
    map = new GMaps({
      div: '#map',
      lat: 0,
      lng: 0,
      zoom: 8
    });
    load();
  });
</script>