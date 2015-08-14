<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); 
//\Applications\PMTool\Helpers\CommonHelper::pr($form_modules);
?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <div class="field_analyte_list">
    <h3>
      <?php echo $current_project->project_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>    
      <?php echo $current_task->task_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php echo $resx["h3_taskchecklist"] ?>
    </h3>
    <div class="form_sections">
      <?php require $form_modules["task_tabs_open"]; ?> 
      <div class="content-container container-fluid">
        <div class="row col-no-right-margin">
          <div  class="col-lg-5 col-md-5">
            <span class="h4"><?php echo $resx["h4_taskchecklist"]; ?></span>
            <span class="glyphicon glyphicon-question-sign" id="activequestion-fieldanalyte-uploadList-headerH4"></span>

            <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::check_list_form]; ?>
          </div>
          <div class="col-lg-1 col-md-1" style="margin-top:32px;">
            <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::checklist_buttons]; ?>
            <?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Analyte::common_analyte_buttons]; ?>
          </div>
          <div  class="col-lg-5 col-md-5">
            <span class="h4"><?php echo $resx["h4_existing_checklists"]; ?></span>
            <span class="glyphicon glyphicon-question-sign" id="inactivequestion-fieldanalyte-uploadList-headerH4"></span>
            <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::checklist_list];  ?>
          </div>
        </div>
      </div>
      <?php 
      require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg];
      require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg];
      require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_prompt];
      require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::task_analyte_matrix_switch];
      ?>

    </div>
  </div>
</div>