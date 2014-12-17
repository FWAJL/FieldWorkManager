<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="location_info" class="data-form tab">
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
    <li>
      <label><?php echo $resx["location_document"]; ?></label>
      <input name="location_document" type="text" />
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
    
</div>
