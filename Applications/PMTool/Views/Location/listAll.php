<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="location_list">
    <h3>
      <?php echo $current_project->project_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php echo $resx["location_list_all_header"]?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_location_names_form"]; ?></h4>
          <div class="add-new-locations">
            <div class="location-names"><textarea name="location_names" type="text"></textarea></div>
            <input id="btn-add-location-names" type="button" value="<?php echo $resx["btn_add_location_names"] ?>" class="btn btn-primary" />
          </div>
        </div>
        <div  class="col-lg-2 col-md-2"></div>
        <div  class="col-lg-2 col-md-5">
          <h4><?php echo $resx["h3_location_add_buttons"]; ?></h4>
          <div class="location-add-buttons">
            <input id="btn-add-location-gmap" type="button" value="<?php echo $resx["btn_add_location_gmap"] ?>" class="btn btn-primary" />
            <input id="btn-add-location-manual" type="button" value="<?php echo $resx["btn_add_location_manual"] ?>" class="btn btn-primary" />
          </div>
        </div>
      </div>
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_locations_active"]; ?></h4>
          <?php require $active_list_module; ?>              
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn btn-warning from-active-list" />
            <input type="button" value="<?php echo $resx["btn_to_active_list"]; ?>"  class="btn btn-warning from-inactive-list" />            
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_locations_inactive"]; ?></h4>
          <?php require $inactive_list_module; ?>              
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->