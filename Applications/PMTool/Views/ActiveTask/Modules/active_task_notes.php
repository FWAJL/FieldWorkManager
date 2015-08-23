<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div class="content-container container-fluid">
  <div class="row col-no-right-margin">
    <div  class="col-lg-5 col-md-5">
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_make_notes]; ?>
    </div>
    <div class="col-lg-1 col-md-1">
      <div class="buttons">
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_buttons]; ?>
      </div>
    </div>
    <div  class="col-lg-5 col-md-5">
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_show_notes]; ?>
    </div>
    <input type="hidden" id="modforjs" name="modforjs" value="taskstatus" />
<!--    <input type="hidden" id="modforjs" name="modforjs" value="tasknotes" />-->

  </div>
</div>

