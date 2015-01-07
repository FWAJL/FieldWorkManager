<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="location_info" class="data-form">
  <fieldset class="location_form form">
    <div class="row">

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
  </fieldset>

</div>
