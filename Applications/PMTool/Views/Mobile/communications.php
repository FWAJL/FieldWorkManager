<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<?php
//check if discussion started
$comm_with_name = '';
if (isset($current_discussion['comm_type'])) {
  if ($current_discussion['comm_type'] == 'technician_id') {
    $comm_with_name = $current_discussion['comm_with']->technician_name();
  } else if($current_discussion['comm_type'] == 'service_id') {
    $comm_with_name = $current_discussion['comm_with']->service_name();
  } else if($current_discussion['comm_type'] == 'pm_id') {
    $comm_with_name = $current_discussion['comm_with']->pm_name();
  }
}
?>
<div class="right-aside col-no-right-pad main col-lg-12 col-md-12 col-sm-12">
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
    <!-- task communication block -->
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_comm]; ?>

  </div>
  <input type="hidden" id="modforjs" name="modforjs" value="taskcomm" />
</div>