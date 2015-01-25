<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::InfoTab]; ?>" data-form-id="task_info">
      <a href="<?php echo __BASEURL__ . Applications\PMTool\Helpers\TaskHelper::GetTaskInfoTabUrl($current_task); ?>">
        Task Status
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::TechniciansTab]; ?>" data-form-id="task_technicians">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskTechnicians; ?>">
        Map
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::InspFormsTab]; ?>" id="tab2" data-form-id="task_insp_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskInspForms; ?>">
        Forms
      </a>
    </li> 
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ServicesTab]; ?>" id="tab7" data-form-id="task_services">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskServices; ?>">
        Services
      </a>
    </li>
  </ul>
