<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="tab-container" class="tab-container">
  <ul class="etabs">
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\AnalyteTabKeys::FieldTab]; ?>" data-form-id="field_analyte_info">
      <a>
        <?php echo $resx["field_analyte_info_tab"]; ?>
      </a>
    </li>
    <li class="tab <?php echo $tab[Applications\PMTool\Resources\Enums\AnalyteTabKeys::LabTab]; ?>" data-form-id="lab_analyte_info">
      <a>
        <?php echo $resx["lab_analyte_info_tab"]; ?>
      </a>
    </li>
  </ul>