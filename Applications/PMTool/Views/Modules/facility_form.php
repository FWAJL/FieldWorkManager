<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<fieldset class="facility_form">
  <legend><?php echo $resx["facility_legend"]; ?></legend>
  <ol class="add-new-p">
    <li style="display: none;">
      <input name="facility_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_name"]; ?></label>
      <input name="facility_name" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_address"]; ?></label>
      <textarea name="facility_address" type="text"></textarea>
    </li>
    <li>
      <label><?php echo $resx["facility_lat"]; ?></label>
      <input name="facility_lat" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_long"]; ?></label>
      <input name="facility_long" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_contact_name"]; ?></label>
      <input name="facility_contact_name" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_contact_phone"]; ?></label>
      <input name="facility_contact_phone" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_contact_email"]; ?></label>
      <input name="facility_contact_email" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_id_num"]; ?></label>
      <input name="facility_id_num" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_sector"]; ?></label>
      <input name="facility_sector" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_sic"]; ?></label>
      <input name="facility_sic" type="text" />
    </li>
    <li>
      <label><?php echo $resx["boundary"]; ?></label>
      <input name="boundary" type="text" />
    </li>
  </ol>
</fieldset>
<input type="button" id="btn_add_facility" class="facility_add" value="<?php echo $resx["facility_button_add"]; ?>" />
<input type="button" id="btn_edit_facility" class="facility_edit hide" value="<?php echo $resx["facility_button_edit"]; ?>" />
<input type="button" id="btn_delete_facility" class="facility_edit hide" value="<?php echo $resx["facility_button_delete"]; ?>" />
