<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $resx["h3_common_lab_analytes"] ?>
  </h3>
  <div class="form_sections">
    <div class="content-container container-fluid">
      <div class="row col-no-right-margin">
        <div  class="col-lg-5 col-md-5">
          <!-- lab analyte block -->
          <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::lab_analyte_form]; ?>
        </div>
        <div class="col-lg-1 col-md-1">
          <div class="buttons">
            <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::analyte_buttons]; ?>
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <?php
          require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::common_lab_analyte_list];
          ?>
        </div>
      </div>
    </div>
  </div>
</div>