<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<fieldset class="facility_form">
  <legend><?php echo $resx["facility_legend"]; ?></legend>
  <ol class="add-new-p">
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
      <label><?php echo $resx["facility_id_number"]; ?></label>
      <input name="facility_id_number" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_sector"]; ?></label>
      <input name="facility_sector" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_sic_code"]; ?></label>
      <input name="facility_sic_code" type="text" />
    </li>
    <li>
      <label><?php echo $resx["facility_boundary"]; ?></label>
      <input name="facility_boundary" type="text" />
    </li>
  </ol>
</fieldset>