<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

  <div>
    <span class="h4"><?php echo $resx["h4_taskstatus_instruction_label"]; ?></span>
    <span class="glyphicon glyphicon-info-sign" id="h4-taskstatus-rightcol-gi"></span>
    <div class="p-desc"><?php echo $current_task->task_instructions(); ?></div>
  </div>
  
