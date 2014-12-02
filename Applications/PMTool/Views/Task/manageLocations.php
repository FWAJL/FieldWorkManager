<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
    <h3>
     <?php echo $current_project->project_name(); ?>
     <span class="glyphicon glyphicon-chevron-right"></span>    
        <?php echo $resx["task_locations_header"] ?></h3>  
  <div class="task_form_sections">
      <?php
      foreach ($form_modules as $key => $module_path) {
        require $module_path;
      }
      ?>
  </div>

</div>
