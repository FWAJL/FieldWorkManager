<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
   <h3>
      <?php echo $resx["h4_analytes"] ?>
    </h3>
  <div class="form_sections">
    <!-- open tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::analyte_tabs_open]; ?>

    <!-- field analyte block -->
    <div id="up_field_analyte_info" class="data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::up_field_analyte_lists]; ?>      
    </div>

    <!-- lab analyte block -->
    <div id="up_lab_analyte_info" class="hide data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::up_lab_analyte_lists]; ?>
    </div>

    <!-- close tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::analyte_tabs_close]; ?>
  </div>

</div>