<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $current_task->task_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $resx["task_technicians_header"] ?></h3>  
  <div class="form_sections">
    <?php require $form_modules["task_tabs_open"]; ?> 
    <div class="content-container container-fluid">
      <div class="row col-no-right-margin">
        <div  class="col-lg-5 col-md-5">
          <span class="h4"><?php echo $resx["h4_task_technicians"]; ?></span>
          <span class="glyphicon glyphicon-info-sign" id="active-taskTechnician-header"></span>
          <?php require $form_modules["group_list_left"]; ?>              
        </div>
        <div class="col-lg-1 col-md-1">
          <?php require $form_modules["group_list_promote_buttons_module"]; ?> 
          <a
            class="task_add_technician task_add_item btn btn-default"
            href="<?php echo $this->app->relative_path; ?>technician/showForm?origin=task&originid=<?php echo $current_task->task_id(); ?>">
            <?php echo $resx["task_technician_button_add"]; ?>
          </a>
          <!--<input type="button" id="btn_add_task_technician" class="task_add_technician btn btn-default" value="<?php echo $resx["task_technician_button_add"]; ?>" />-->
        </div>
        <div  class="col-lg-5 col-md-5">
          <span class="h4"><?php echo $resx["h4_project_technicians"]; ?></span>
          <span class="glyphicon glyphicon-question-sign" id="inactive-taskTechnician-header"></span>
          <?php require $form_modules["group_list_right"]; ?>              
        </div>
        <?php
        require $form_modules["tabs_close"];
        require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg];
        require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Task::task_analyte_matrix_switch];
        ?>          
      </div>
    </div>
  </div>
</div>

