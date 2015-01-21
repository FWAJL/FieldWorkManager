<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="scroll-bar">
  <ol id="group-list-right" class="list-panel">
    <?php
    foreach ($data_right[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_right] as $object) {
    $prop_active =
            $data_right
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active];
    $prop_id =
            $data_right
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
    $prop_name =
            $data_right
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
    if ($object->$prop_active()) {
        echo
      "<li data-"
      . $data_right[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
      . "-id=\"" . $object->$prop_id() . "\" class=\"select_item ui-widget-content\">"
      . $object->$prop_name()
        . "</li>";
      }
    }
    ?>              
  </ol>
</div>