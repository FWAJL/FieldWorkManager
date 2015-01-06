<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="tab-container">

<div id="technician_info"  class="data-form">
<fieldset class="technician_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="pm_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="technician_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["technician_name"]; ?></label>
      <input name="technician_name" type="text" />
    </li>
    <li>
      <label><?php echo $resx["technician_phone"]; ?></label>
      <input name="technician_phone" type="text" />
    </li>
        <li>
      <label><?php echo $resx["technician_email"]; ?></label>
      <input name="technician_email" type="text" />
    </li>
    <li>
      <label><?php echo $resx["technician_document"]; ?></label>
      <input type="button" name="technician_document" value="Click to upload photo" class="doc_upload" id="tech_photo_upload"/>
    </li>
    <li>
      <label><?php echo $resx["technician_active"]; ?></label>
      <input name="technician_active" type="checkbox" checked />
    </li>
  </ol>
</fieldset>
    <input type="button" id="btn_add_technician" class="technician_add btn btn-default" value="<?php echo $resx["technician_button_add"]; ?>" />
<input type="button" id="btn_edit_technician" class="technician_edit hide btn btn-default" value="<?php echo $resx["technician_button_edit"]; ?>" />
<input type="button" id="btn_delete_technician" class="technician_edit hide btn btn-default" value="<?php echo $resx["technician_button_delete"]; ?>" />
</div>
    <div id="photo_form">
        Put the Photo in this space.
</div>
</div>