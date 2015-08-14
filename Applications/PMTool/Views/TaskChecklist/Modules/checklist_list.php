<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<ol id="task-check-list-list" class="list-panel">
  <?php
  //\Applications\PMTool\Helpers\CommonHelper::pr($data_all_checklists);
  foreach ($data_all_checklists[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
    $prop_id = $data_all_checklists
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
    $prop_name = $data_all_checklists
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
    echo
    "<li data-"
    . $data_all_checklists[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
    . "-id=\"" . $object->$prop_id() . "\" class=\"ui-widget-content\">"
    . "<input disabled type=\"checkbox\" style=\"width: 30px !important;\">"
    . $object->$prop_name()
    . "</li>";
  }
  ?>          
</ol>