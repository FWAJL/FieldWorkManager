<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>


<div id="map"></div>


<script>
  var objects = [
    <?php
    $i = 0;
      foreach ($data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
          echo ($i == 1 ? "," : "") . "['".$object->facility_lat()."', '".$object->facility_long()."']";
          $i = 1;
      }
    ?>
  ];
</script>