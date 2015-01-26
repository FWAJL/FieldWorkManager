<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::CompleteInfoTab]; ?>" data-form-id="task_status">
      <a href="<?php echo __BASEURL__ . Applications\PMTool\Helpers\TaskHelper::GetTaskInfoTabUrl($current_task_status); ?>">
        <?php echo $resx["task_tab_info"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::CompleteTaskMapTab]; ?>" data-form-id="live_task_map">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::CompleteTaskMap; ?>">
        <?php echo $resx["task_tab_map"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::CompleteInspFormsTab]; ?>" id="tab2" data-form-id="live_task_insp_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::CompleteTaskInspForms; ?>">
        <?php echo $resx["task_tab_forms"]; ?>
      </a>
    </li> 
<!--    Note: The COC, if =used, is not just a form on the forms list.-->
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::CompleteTaskServicesTab]; ?>" id="tab7" data-form-id="live_task_services">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::CompleteTaskServices; ?>">
        <?php echo $resx["task_tab_services"]; ?>
      </a>
    </li>
  </ul>
