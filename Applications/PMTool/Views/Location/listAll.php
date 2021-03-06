<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <div class="location_list">
    <h3>
      <?php echo $current_project->project_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php echo $resx["location_list_all_header"] ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row col-no-right-margin">
        <div  class="col-lg-5 col-md-5">
          <span class="h4"><?php echo $resx["h3_locations_active"]; ?></span>
          <span class="glyphicon glyphicon-info-sign" id="active-location-header"></span>
          <?php require $active_list_module; ?>              
        </div>
        <div class="col-lg-1 col-md-1">
            <?php require $promote_buttons_module; ?>    
        </div>
        <div  class="col-lg-5 col-md-5">
          <span class="h4"><?php echo $resx["h3_locations_inactive"]; ?></span>
          <span class="glyphicon glyphicon-question-sign" id="inactive-location-header"></span>
          <?php
		  require $tooltip_message_module;
		  require $inactive_list_module; 
		  ?>
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->