<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div id="tab-container" class="tab-container">
    <ul class="etabs">
    <li class="tab <?php echo $active_task_tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskStatusTab]; ?>" data-form-id="task_status">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskShowForm . '?task_id=' . $current_task->task_id(); ?>">
        <?php echo $resx["task_tab_info"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $active_task_tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskMapTab]; ?>" data-form-id="active_task_map">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskMap . '?task_id=' . $current_task->task_id(); ?>">
        <?php echo $resx["task_tab_map"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $active_task_tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFormsTab]; ?>" data-form-id="active_task_forms">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskInspForms . '?task_id=' . $current_task->task_id(); ?>">
        <?php echo $resx["task_tab_forms"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $active_task_tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskCommTab]; ?>" data-form-id="active_task_comm">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskComm . '?task_id=' . $current_task->task_id(); ?>">
        <?php echo $resx["task_tab_comm"]; ?>
      </a>
    </li>   
        <li class="tab <?php echo $active_task_tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFieldDataTab]; ?>" data-form-id="active_task_field_data">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskFieldData . '?task_id=' . $current_task->task_id(); ?>">
        <?php echo $resx["field_data"]; ?>
      </a>
    </li> 
  </ul>
</div>