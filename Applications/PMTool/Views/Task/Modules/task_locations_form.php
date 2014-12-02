<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_locations" class="data-form">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h4_task_locations"]; ?></h4>
          <?php require $modules["active_list_module"]; ?>              
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" value="<?php echo $resx["btn_add_to_task"]; ?>" class="btn btn-warning from-active-list" />
            <input type="button" value="<?php echo $resx["btn_remove_from_task"]; ?>"  class="btn btn-warning from-inactive-list" />            
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h4_project_locations"]; ?></h4>
          <?php require $modules["inactive_list_module"]; ?>              
        </div>
  </div>

