<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::InfoTab]; ?>" data-form-id="task_info">
      <a href="<?php echo __BASEURL__ . Applications\PMTool\Helpers\TaskHelper::GetTaskInfoTabUrl($current_task); ?>">
        <?php echo $resx["task_tab_info"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::TechniciansTab]; ?>" data-form-id="task_technicians">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskTechnicians; ?>">
        <?php echo $resx["task_tab_technicians"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LocationsTab]; ?>" data-form-id="task_locations">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskLocations; ?>">
        <?php echo $resx["task_tab_locations"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::InspFormsTab]; ?>" id="tab2" data-form-id="task_insp_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskInspForms; ?>">
        <?php echo $resx["task_tab_forms"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::FieldAnalytesTab]; ?>" id="tab3" data-form-id="field_analytes">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::FieldAnalytes; ?>">
        <?php echo $resx["task_tab_field_analytes"]; ?>
      </a>
    </li>
<!--    <li class="tab <?php //echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::FieldSampleMatrixTab]; ?>" id="tab3a" data-form-id="field_matrix">
      <a href="<?php //echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::FieldSampleMatrix; ?>">
        Field Matrix
      </a>
    </li>-->  
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LabAnalytesTab]; ?>" id="tab5" data-form-id="lab_analytes">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LabAnalytes; ?>">
        <?php echo $resx["task_tab_lab_analytes"]; ?>
      </a>
    </li>
<!--    <li class="tab <?php //echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LabSampleMatrixTab]; ?>" id="tab6" data-form-id="lab_matrix">
      <a href="<?php //echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::LabSampleMatrix; ?>">
        Lab Matrix
      </a>
    </li>-->
        <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::CocTab]; ?>" id="tab4" data-form-id="task_coc_info">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskCOC; ?>">
        <?php echo $resx["task_tab_coc"]; ?>
      </a>
    </li>  
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::ServicesTab]; ?>" id="tab7" data-form-id="task_services">
      <a href="<?php echo __BASEURL__ . \Library\Enums\ResourceKeys\UrlKeys::TaskServices; ?>">
        <?php echo $resx["task_tab_services"]; ?>
      </a>
    </li>
  </ul>
