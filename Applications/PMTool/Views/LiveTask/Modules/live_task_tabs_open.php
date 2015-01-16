<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LiveStatus]; ?>" data-form-id="task_status">
      <a href="<?php echo __BASEURL__ . Applications\PMTool\Helpers\TaskHelper::GetTaskInfoTabUrl($current_task_status); ?>">
        Task Status
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LiveTaskMapTab]; ?>" data-form-id="live_task_map">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LiveTaskMap; ?>">
        Map
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LiveInspFormsTab]; ?>" id="tab2" data-form-id="live_task_insp_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LiveTaskInspForms; ?>">
        Forms
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LiveCocTab]; ?>" id="tab4" data-form-id="live_task_coc_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LiveTaskCOC; ?>">
        Chain of Custody
      </a>
    </li>    
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LiveTaskServicesTab]; ?>" id="tab7" data-form-id="live_task_services">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LiveTaskServices; ?>">
        Services
      </a>
    </li>
  </ul>
