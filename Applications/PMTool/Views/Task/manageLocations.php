<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $current_task->task_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $resx["task_locations_header"] ?></h3>  
  <div class="task_form_sections">
    <?php require $form_modules["task_tabs_open"]; ?>              
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_task_locations"]; ?></h4>
      <?php require $form_modules["group_list_left"]; ?>              
    </div>
    <div class="col-lg-2 col-md-2">
      <div class="buttons">
        <p><input type="button" value="<?php echo $resx["btn_add_to_task"]; ?>" class="btn btn-warning from-active-list" /></p>
        <p><input type="button" value="<?php echo $resx["btn_remove_from_task"]; ?>"  class="btn btn-warning from-inactive-list" /></p>
      </div>
    </div>
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_project_locations"]; ?></h4>
      <?php require $form_modules["group_list_right"]; ?>              
    </div>
    <?php require $form_modules["task_tabs_close"]; ?>              
  </div
</div>
