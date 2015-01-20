<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="task_list">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>
    <?php echo $task_editing_header ?>
  </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
            <div class="form_sections">
      <?php
      foreach ($form_modules as $key => $module_path) {
        require $module_path;
      }
      ?>
  </div>
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
<input type="button" id="btn_add_task" class="task_add btn btn-default" value="<?php echo $resx["task_button_add"]; ?>" />
<input type="button" id="btn_edit_task" class="task_edit hide btn btn-default" value="<?php echo $resx["task_button_edit"]; ?>" />
<input type="button" id="btn_delete_task" class="task_edit hide btn btn-default" value="<?php echo $resx["task_button_delete"]; ?>" />
<input type="button" id="btn_copy_task" class="task_edit hide btn btn-default" value="<?php echo $resx["task_button_copy"]; ?>" />
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
Nothing Here.
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->