<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <?php if ($current_task !== NULL) { ?>
      <span class="glyphicon glyphicon-chevron-right"></span>    
      <?php echo $current_task->task_name();
    } ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
<?php echo $resx["active_task_header"] ?></h3>  
  <div class="form_sections">
    <!-- open tabs -->
	<?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_tabs_open]; ?>

    <!-- task info block -->
    	<?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_notes]; ?>      


    <!-- close tabs -->
	<?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::tabs_close]; ?>
  </div>
  <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::tooltip_msg_module]; ?>
  
</div>