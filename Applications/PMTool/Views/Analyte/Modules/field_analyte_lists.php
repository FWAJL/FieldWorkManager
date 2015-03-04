<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div class="content-container container-fluid">
  <div class="row">
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx["h4_project_analytes"]; ?></span>
      <span class="glyphicon glyphicon-info-sign" id="active-fieldanalyte-header"></span>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::project_field_analyte_list]; ?>
    </div>
    <div class="col-lg-1 col-md-1">
      <div class="buttons">
        <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::field_analyte_buttons]; ?>
      </div>
    </div>
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx["h4_your_analytes"]; ?></span>
      <span class="glyphicon glyphicon-question-sign" id="inactive-fieldanalyte-header"></span>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::field_analyte_list]; ?>
    </div>
  </div>
</div>
