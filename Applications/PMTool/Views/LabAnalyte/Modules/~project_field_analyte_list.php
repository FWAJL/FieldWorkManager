<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<ol id="project-field-analyte-list" class="list-panel">
  <?php
  foreach ($data_field_analyte[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left] as $object) {
    $prop_name =
            $data_field_analyte
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
    $prop_id =
            $data_field_analyte
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
    echo
    "<li data-"
    . $data_field_analyte[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
    . "-id=\"" . $object->$prop_id()
    . "\" class=\"ui-widget-content\">"
    . $object->$prop_name()
    . "</li>";
  }
  ?>              
</ol>
