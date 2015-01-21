<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="scroll-bar">
  <ol id="active-list" class="list-panel">
    <?php
    $prop_active =
            $data
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active];
    $prop_id =
            $data
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
    $prop_name =
            $data
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
    foreach ($data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
      if ($object->$prop_active()) {
        echo
        "<li data-"
        . $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module] . "-id=\"" . $object->$prop_id()
        . "\" class=\"select_item ui-widget-content\">"
        . $object->$prop_name()
        . "</li>";
      }
    }
    ?>              
  </ol>
</div>