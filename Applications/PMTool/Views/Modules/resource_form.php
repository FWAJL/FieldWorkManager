<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="resource_info"  class="data-form1">
<fieldset class="resource_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="pm_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="resource_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["resource_name"]; ?></label>
      <input name="resource_name" type="text" />
    </li>
        <li>
      <label><?php echo $resx["resource_type"]; ?></label>
      <input name="resource_type" type="text" />
    </li>
     <li>
   <label><?php echo $resx["resource_url"]; ?></label>
      <input name="resource_url" type="text" />
    </li>
    <li>
   <label><?php echo $resx["resource_address"]; ?></label>
      <input name="resource_address" type="text" />
    </li>
        <li>
      <label><?php echo $resx["resource_contact_name"]; ?></label>
      <input name="resource_contact_name" type="text" />
    </li>
        <li>
      <label><?php echo $resx["resource_contact_phone"]; ?></label>
      <input name="resource_contact_phone" type="text" />
    </li>
    <li>
      <label><?php echo $resx["resource_contact_email"]; ?></label>
      <input name="resource_contact_email" type="text" />
    </li>
    <li style="display: none">
      <!--<label><?php // echo $resx["resource_active"]; ?></label>-->
      <input name="resource_active" type="checkbox" />
    </li>
  </ol>
</fieldset>
    
</div>
 