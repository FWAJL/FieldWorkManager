<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

  <div>
    <span class="h4"><?php echo $resx["h4_taskstatus_instruction_label"]; ?></span>
    <span class="glyphicon glyphicon-info-sign" id="h4-taskstatus-rightcol-gi"></span>
    <div class="p-desc"><?php echo $current_task->task_instructions(); ?></div>
  </div>
  
  <div>&nbsp;
  </div>
  
  <div>
    <span class="h4"><?php echo $resx["h4_taskstatus_notes_label"]; ?></span>
    <span class="glyphicon glyphicon-info-sign" id="h4-taskstatus-notes-gi"></span>
    <div>
    	<textarea  class="list-panel upload_list" id="task_status_notes" name="task_status_notes" type="text"></textarea>
    </div>
  </div>
  
  <div>&nbsp;
  </div>
  
  <div>
  	<span class="h4"><?php echo $resx["h4_taskstatus_noteshistory"]; ?></span>
    <span class="glyphicon glyphicon-info-sign" id="h4-taskstatus-notesrecord-gi"></span>
    <div class="notes-container">
      <div id="messages">
      	  
      </div>
    </div>
  </div>