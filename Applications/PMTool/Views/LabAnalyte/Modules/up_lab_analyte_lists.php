<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div class="content-container container-fluid">
  <div class="row">
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx["h4_analyte_form"]; ?></span>
      <span class="glyphicon glyphicon-question-sign" id="activequestion-labanalyte-uploadList-headerH4"></span>

      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::lab_analyte_form]; ?>
    </div>
    <div class="col-lg-1 col-md-1">
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::analyte_buttons]; ?>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::common_analyte_buttons]; ?>
    </div>
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx["h4_common_lab_analytes"]; ?></span>
      <span class="glyphicon glyphicon-question-sign" id="inactivequestion-labanalyte-uploadList-headerH4"></span>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::common_lab_analyte_list]; ?>
    </div>
  </div>
</div>
