<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
    
    <div id="location_info" class="data-form">
    <fieldset class="location_form form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="location_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="project_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["location_name"]; ?></label>
      <input name="location_name" type="text" />
    </li>
<!--    <li>
      <label><?php echo $resx["location_category"]; ?></label>
      <input name="location_category" type="text" />
    </li>-->
    <li>
      <label><?php echo $resx["location_address"]; ?></label>
      <input name="location_address" type="text" />
    </li>
    <li>
      <label><?php echo $resx["location_lat"]; ?></label>
      <input name="location_lat" type="text" />
    </li>
    <li>
      <label><?php echo $resx["location_long"]; ?></label>
      <input name="location_long" type="text" />
    </li>
    <li>
      <label><?php echo $resx["location_desc"]; ?></label>
      <input name="location_desc" type="text" />
    </li>
<!--     <li>
      <label><?php echo $resx["location_document"]; ?></label>
      <input type="button" name="location_document" value="Click to upload photo" class="doc_upload" id="loc_photo_upload"/>
    </li>-->
    <li>
      <label><?php echo $resx["location_active"]; ?></label>
      <input name="location_active" type="checkbox" checked />
    </li>
    <li style="display: none">
      <label><?php // echo $resx["location_visible"]; ?></label>
      <input name="location_visible" type="checkbox"/>
    </li>
  </ol>
</fieldset>
        <input type="button" id="btn_add_location" class="location_add btn btn-default" value="<?php echo $resx["location_button_add"]; ?>" />
 </div>
<div id="photo_form">
        Put the Photo(s) in this space.
</div>
</div>