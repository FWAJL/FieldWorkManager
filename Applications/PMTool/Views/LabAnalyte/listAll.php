<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>
    <?php echo $resx["h4_lab_analytes"]; ?></h3>
  </h3>
  <div class="form_sections">
    <div class="content-container container-fluid">
  <div class="row">
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx["h4_project_lab_analytes"]; ?></span>
      <span class="glyphicon glyphicon-info-sign" id="active-labanalyte-header"></span>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::project_lab_analyte_list]; ?>
    </div>
    <div class="col-lg-1 col-md-1">
      <div class="buttons">
        <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::lab_analyte_buttons]; ?>
      </div>
    </div>
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx["h4_your_lab_analytes"]; ?></span>
      <span class="glyphicon glyphicon-question-sign" id="inactive-labanalyte-header"></span>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::lab_analyte_list]; ?>
    </div>
  </div>
</div>
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::popup_prompt_module]; ?>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]; ?>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]; ?>
    <?php
	//If no alalyte for this project, alert it
	if(empty($data_lab_analyte['objects_list_left'])) {
	  ?>
      <?php
	}
	?>
  </div>

</div>