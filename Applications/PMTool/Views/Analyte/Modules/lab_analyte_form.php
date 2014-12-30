<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="lab_analyte_info" class="data-form-2">
<fieldset class="lab_analyte_form form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="lab_analyte_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="pm_id" type="text" />
    </li>
    <li>
      <label><?php echo $resx["lab_analyte_name_unit"]; ?></label>
      <input name="lab_analyte_name_unit" type="text" />
    </li>
  </ol>
</fieldset>
    
</div>
