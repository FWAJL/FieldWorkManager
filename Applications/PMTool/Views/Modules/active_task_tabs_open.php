<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ActiveInfoTab]; ?>" data-form-id="task_status">
      <a href="<?php echo __BASEURL__ . Applications\PMTool\Helpers\TaskHelper::GetTaskInfoTabUrl($current_task_status); ?>">
        <?php echo $resx["task_tab_info"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ActiveTaskMapTab]; ?>" data-form-id="live_task_map">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskMap; ?>">
        <?php echo $resx["task_tab_map"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ActiveInspFormsTab]; ?>" id="tab2" data-form-id="live_task_insp_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskInspForms; ?>">
        <?php echo $resx["task_tab_forms"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ActiveCocTab]; ?>" id="tab4" data-form-id="live_task_coc_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskCOC; ?>">
        <?php echo $resx["task_tab_coc"]; ?>
      </a>
    </li>    
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ActiveTaskServicesTab]; ?>" id="tab7" data-form-id="live_task_services">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::ActiveTaskServices; ?>">
        <?php echo $resx["task_tab_services"]; ?>
      </a>
    </li>
  </ul>
