<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="pm_edit_info"  class="pm_edit data-form user-type-form">
<fieldset class="pm_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="pm_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["pm_name"]; ?></label>
      <input name="pm_name" type="text" />
    </li>
    <li>
      <label><?php echo $resx["pm_comp_name"]; ?></label>
      <input name="pm_comp_name" type="text" />
    </li>
    <li>
      <label><?php echo $resx["pm_address"]; ?></label>
      <input name="pm_address" type="text" />
    </li>
    <li>
      <label><?php echo $resx["pm_phone"]; ?></label>
      <input name="pm_phone" type="text" />
    </li>
    <li>
      <label><?php echo $resx["pm_email"]; ?></label>
      <input name="pm_email" type="text" />
    </li>
  </ol>
</fieldset>
    
</div>
