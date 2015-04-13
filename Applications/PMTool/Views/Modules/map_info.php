<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div id="map-info">
  <div class="row">
    <div id="map-info-pan" class="col-md-3 col-lg-3 <?php echo $default_active_control == 'pan' ? 'control-active' : '' ?>">
      <div id="map-info-pan-icon"></div>
    </div>
    <div id="map-info-add" class="col-md-3 col-lg-3 <?php echo $default_active_control == 'add' ? 'control-active' : '' ?>">
      <div id="map-info-add-icon"></div>
    </div>
    <div id="map-info-shape" class="col-md-3 col-lg-3 <?php echo $default_active_control == 'shape' ? 'control-active' : '' ?>">
      <div id="map-info-shape-icon"></div>
    </div><div id="map-info-ruler" class="col-md-3 col-lg-3 <?php echo $default_active_control == 'ruler' ? 'control-active' : '' ?>">
      <div id="map-info-ruler-icon"></div>
    </div>
  </div>
  <div class="row">
    <div id="map-ruler"></div>
  </div>
</div>
