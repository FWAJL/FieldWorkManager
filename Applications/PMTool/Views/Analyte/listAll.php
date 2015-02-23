<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<?php
//echo '<pre>';
//print_r($form_modules);
?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>
    <?php echo $resx["h4_analytes"]; ?></h3>
  </h3>
  <div class="form_sections">
    <!-- open tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::analyte_tabs_open]; ?>

    <!-- field analyte block -->
    <div id="field_analyte_info" class="data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::field_analyte_lists]; ?>      
    </div>

    <!-- lab analyte block -->
    <div id="lab_analyte_info" class="hide data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Analyte::lab_analyte_lists]; ?>
    </div>

    <!-- close tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::tabs_close]; ?>
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::popup_prompt_module]; ?>
  </div>

</div>