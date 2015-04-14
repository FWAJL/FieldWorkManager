<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<ol id="field-analyte-list" class="list-panel">
  <?php
  foreach ($data_field_analyte[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right] as $object) {
    $prop_id =
            $data_field_analyte
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
    $prop_name =
            $data_field_analyte
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
    echo
    "<li data-"
    . $data_field_analyte[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
    . "-id=\"" . $object->$prop_id() . "\" class=\"select_item ui-widget-content\">"
    . $object->$prop_name()
    . "</li>";
  }
  ?>              
</ol>   