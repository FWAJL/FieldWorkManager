<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <?php if ($current_task !== NULL) { ?>
      <span class="glyphicon glyphicon-chevron-right"></span>    
      <?php echo $current_task->task_name();
    } ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
<?php echo $active_task_header ?></h3>  
  <div class="form_sections">
    <!-- open tabs -->
<div id="tab-container" class="tab-container">
<!--  For mockup only.  To be copied to active_task_tabs_open-->
    <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskStatusTab]; ?>" data-form-id="task_status">
      <a href="<?php echo __BASEURL__ . Applications\PMTool\Helpers\TaskHelper::GetTaskInfoTabUrl($current_task_status); ?>">
        <?php echo $resx["task_tab_info"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskMapTab]; ?>" data-form-id="active_task_map">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskMap; ?>">
        <?php echo $resx["task_tab_map"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskFormsTab]; ?>" data-form-id="active_task_forms">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskInspForms; ?>">
        <?php echo $resx["task_tab_forms"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\ActiveTaskTabKeys::ActiveTaskCommTab]; ?>" data-form-id="active_task_comm">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskComm; ?>">
        <?php echo $resx["task_tab_comm"]; ?>
      </a>
    </li>    
  </ul>
</div>
<?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_tabs_open]; ?>

    <!-- task info block -->
<?php //require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::active_task_info_lists]; ?>      

    <!-- close tabs -->
<?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\ActiveTask::tabs_close]; ?>
  </div>
  <?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::popup_msg_module]; ?>
  <?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::popup_prompt_module]; ?>

</div>