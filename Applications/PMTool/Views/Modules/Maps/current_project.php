<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>


<div id="map"></div>

<script>
  var objects = [
    <?php
    $object = $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects]['facility_obj'];
    echo "['".$object->facility_lat()."', '".$object->facility_long()."']";
    ?>
  ];
</script>