<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<?php
//check if discussion started
$comm_with_name = '';
if (isset($current_discussion['comm_type'])) {
  if ($current_discussion['comm_type'] == 'technician_id') {
    $comm_with_name = $current_discussion['comm_with']->technician_name();
  } else {
    $comm_with_name = $current_discussion['comm_with']->service_name();
  }
}
?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <?php if ($current_task !== NULL) { ?>
      <span class="glyphicon glyphicon-chevron-right"></span>    
      <?php echo $current_task->task_name();
    }
    ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $resx['active_task_comm_header'] ?>
    <?php if ($comm_with_name !== '') { ?>
      <span class="glyphicon glyphicon-chevron-right"></span>    
      <?php echo $comm_with_name;
    }
    ?>
  </h3>  
  <div class="form_sections">
    <!-- open tabs -->
<?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_tabs_open]; ?>

    <!-- task communication block -->
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_comm]; ?>      

    <!-- close tabs -->
  <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::tabs_close]; ?>
  </div>
  <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::tooltip_msg_module]; ?>
  <input type="hidden" id="modforjs" name="modforjs" value="taskcomm" />
</div>