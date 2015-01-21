<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="service_info"  class="data-form">
<fieldset class="service_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="pm_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="service_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["service_name"]; ?></label>
      <input name="service_name" type="text" />
    </li>
        <li>
      <label><?php echo $resx["service_type"]; ?></label>
      <input name="service_type" type="text" />
    </li>
     <li>
   <label><?php echo $resx["service_url"]; ?></label>
      <input name="service_url" type="text" />
    </li>
    <li>
   <label><?php echo $resx["service_address"]; ?></label>
      <input name="service_address" type="text" />
    </li>
        <li>
      <label><?php echo $resx["service_contact_name"]; ?></label>
      <input name="service_contact_name" type="text" />
    </li>
        <li>
      <label><?php echo $resx["service_contact_phone"]; ?></label>
      <input name="service_contact_phone" type="text" />
    </li>
    <li>
      <label><?php echo $resx["service_contact_email"]; ?></label>
      <input name="service_contact_email" type="text" />
    </li>
        <li style="display: none;">
<!--      <label><?php // echo $resx["service_active"]; ?></label>-->
      <input name="service_active" type="checkbox" checked />
    </li>
  </ol>
</fieldset>
    
</div>
 