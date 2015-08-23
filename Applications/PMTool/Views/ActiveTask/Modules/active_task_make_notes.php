<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
  
  <div>
    <span class="h4"><?php echo $resx["h4_taskstatus_notes_label"]; ?></span>
    <span class="glyphicon glyphicon-info-sign" id="h4-taskstatus-notes-gi"></span>
    <div>
    	<textarea  class="list-panel upload_list" id="task_status_notes" name="task_status_notes" type="text"></textarea>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::upload_file]; ?>
    </div>
  </div>
  
  <div>&nbsp;
  </div>
  