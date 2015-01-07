<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="lab_analyte_info" class="hide data-form-2">
  <fieldset class="lab_analyte_form form">
    <div class="row">
      <div class="col-lg-5 col-md-5 col-sm-5">

        <ol class="add-new-item">
          <li style="display: none;">
            <input name="lab_analyte_id" type="text" />
          </li>
          <li style="display: none;">
            <input name="pm_id" type="text" />
          </li>
          <li>
            <label><?php echo $resx["lab_analyte_name"]; ?></label>
            <div>
              <textarea class="high-textarea" name="lab_analyte_name" type="text"></textarea>
            </div>
          </li>
        </ol>
      </div>
    </div>
  </fieldset>
</div>
