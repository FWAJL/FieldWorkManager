<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="location_info" class="data-form">
<fieldset class="location_form form">
  <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_location_names_form"]; ?></h4>
          <div class="add-new-locations">
            <div class="location-names"><textarea name="location_names" type="text"></textarea></div>
            <div class="location-active">
              <label><?php echo $resx["location_active_many"]; ?></label>
              <input name="location_active" type="checkbox" checked />
            </div>
            <input id="btn-add-location-names" type="button" value="<?php echo $resx["btn_add_location_names"] ?>" class="btn btn-primary" />
          </div>
        </div>
        <div  class="col-lg-2 col-md-2"></div>
<!--        <div  class="col-lg-2 col-md-5">
          <h4><?php // echo $resx["h3_location_add_buttons"]; ?></h4>
          <div class="location-add-buttons">
            <input id="btn-add-location-gmap" type="button" value="<?php // echo $resx["btn_add_location_gmap"] ?>" class="btn btn-primary" />
            <input id="btn-add-location-manual" type="button" value="<?php // echo $resx["btn_add_location_manual"] ?>" class="btn btn-primary" />
          </div>
        </div>-->
      </div>
</fieldset>
    
</div>
