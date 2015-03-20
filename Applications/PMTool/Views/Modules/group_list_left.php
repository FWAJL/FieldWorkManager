<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="scroll-bar">
  <ol id="group-list-left" class="list-panel">
    <?php
    foreach ($data_left[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_list_left] as $object) {
      if(isset($data_left[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left][\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_identifier])) {
        $dataIdentifier =
          $data_left
          [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left]
          [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::data_identifier];
        $dataObject = $object->$dataIdentifier();
      } else {
        $dataObject = '';
      }
      $prop_name =
        $data_left
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left]
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
      $prop_id =
        $data_left
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_left]
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
      echo
        "<li data-object=\"".$dataObject."\" data-"
        . $data_left[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
        . "-id=\"" . $object->$prop_id() . "\" class=\"select_item ui-widget-content\">"
        . $object->$prop_name()
        . "</li>";
    }
    ?>
  </ol>
</div>