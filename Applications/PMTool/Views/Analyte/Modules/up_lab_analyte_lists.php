<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div class="content-container container-fluid">
  <div class="row">
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_analyte_form"]; ?></h4>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::lab_analyte_form]; ?>
    </div>
    <div class="col-lg-2 col-md-2">
        <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::analyte_buttons]; ?>
        <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::common_analyte_buttons]; ?>
    </div>
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_common_lab_analytes"]; ?></h4>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::common_lab_analyte_list]; ?>
    </div>
  </div>
</div>
