<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-9 col-md-9 col-sm-9">
  <div class="location_list">
    <h3><?php echo $resx["location_list_all_header"]; ?></h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_location_names_form"]; ?></h4>
          <div class="add-new-locations">
            <textarea></textarea>
            <input type="button" value="<?php echo $resx["btn_add_location_names"] ?>" class="btn btn-primary btn-add-location-names" />
          </div>
        </div>
        <div  class="col-lg-2 col-md-2"></div>
        <div  class="col-lg-2 col-md-5">
          <h4><?php echo $resx["h3_location_add_buttons"]; ?></h4>
          <div class="location-add-buttons">
            <input type="button" value="<?php echo $resx["btn_add_location_gmap"] ?>" class="btn btn-primary btn-add-location-gmap" />
            <input type="button" value="<?php echo $resx["btn_add_location_manual"] ?>" class="btn btn-primary btn-add-location-manual" />
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