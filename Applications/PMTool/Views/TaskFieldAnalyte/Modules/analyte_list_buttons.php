<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<!-- One set of buttons for all tabs -->
<div class="buttons">
  <button title="<?php echo $resx["btn_add_analyte_to_project"]; ?>" class="btn btn-warning from-field-analyte-list">
    <span class="glyphicon glyphicon-arrow-right"></span>
  </button>
  <button title="<?php echo $resx["btn_remove_analyte_to_project"]; ?>"  class="btn btn-warning from-project-field-analyte-list">
    <span class="glyphicon glyphicon-arrow-left"></span>
  </button>
  <a
    class="task_add_location task_add_item btn btn-default"
    href="<?php echo $this->app->relative_path; ?>analyte/uploadList?origin=task&originid=<?php echo $current_task->task_id(); ?>">
      <?php echo $resx["task_field_data_button_add"]; ?>
  </a>
</div>
