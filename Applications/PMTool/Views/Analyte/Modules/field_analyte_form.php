<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="field_analyte_info" class="data-form-2">
  <fieldset class="field_analyte_form form">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-5">
        <ol class="add-new-item">
          <li style="display: none;">
            <input name="field_analyte_id" type="text" />
          </li>
          <li style="display: none;">
            <input name="pm_id" type="text" />
          </li>
          <li>
            <label><?php echo $resx["field_analyte_name_unit"]; ?></label>
            <div>
              <textarea class="high-textarea" name="field_analyte_name_unit"></textarea>
            </div>
          </li>
        </ol>            
      </div>
    </div>
  </fieldset>

</div>
