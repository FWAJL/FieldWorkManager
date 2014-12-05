<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::InfoTab]; ?>" data-form-id="task_info">
      <a href="../task">Task Info</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::TechniciansTab]; ?>" data-form-id="task_technicians">
      <a href="technicians">Technicians</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LocationsTab]; ?>" data-form-id="task_locations">
      <a href="locations">Locations</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::InspFormsTab]; ?>" id="tab2" data-form-id="task_insp_info">
      <a>Inspection Forms</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::FieldAnalytesTab]; ?>" id="tab3" data-form-id="field_analytes">
      <a>Field Analytes</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::FieldSampleMatrixTab]; ?>" id="tab3a" data-form-id="field_matrix">
      <a>Field Sample Matrix</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::CocTab]; ?>" id="tab4" data-form-id="coc_info">
      <a>Chain of Custody Information</a>
    </li>    
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LabAnalytesTab]; ?>" id="tab5" data-form-id="lab_analytes">
      <a>Lab Analytes</a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\TaskTabKeys::LabSampleMatrixTab]; ?>" id="tab6" data-form-id="lab_matrix">
      <a>Lab Sample Matrix</a>
    </li>
  </ul>
