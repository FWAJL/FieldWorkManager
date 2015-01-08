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
    $i = 0;
      foreach ($data['locations'] as $object) {
          echo ($i == 1 ? "," : "") . "['".$object->location_lat()."', '".$object->location_long()."']";
          $i = 1;
      }
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

  }
  $(document).ready(function () {
    map = new GMaps({
      div: '#map',
      lat: 0,
      lng: 0,
      zoom: 13
    });
    load();
	map.fitZoom();
  });
</script>