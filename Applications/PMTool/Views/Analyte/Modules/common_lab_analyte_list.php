<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<ol id="common-lab-analyte-list" class="list-panel">
  <?php
  foreach ($data_common_lab_analyte[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
    $prop_id =
            $data_common_lab_analyte
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
    $prop_name =
            $data_common_lab_analyte
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
    echo
    "<li data-"
    . $data_common_lab_analyte[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
    . "-id=\"" . $object->$prop_id() . "\" class=\"ui-widget-content\">"
    . $object->$prop_name()
    . "</li>";
  }
  ?>              
</ol>   