<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $current_task->task_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $resx["task_technicians_header"] ?></h3>  
  <div class="form_sections">
    <?php require $form_modules["task_tabs_open"]; ?>              
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_task_technicians"]; ?></h4>
      <?php require $form_modules["group_list_left"]; ?>              
    </div>
    <div class="col-lg-1 col-md-1">
        <?php require $form_modules["group_list_promote_buttons_module"]; ?> 
    </div>
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_project_technicians"]; ?></h4>
      <?php require $form_modules["group_list_right"]; ?>              
    </div>
    <?php require $form_modules["tabs_close"]; ?>              
  </div
</div>
