<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="scroll-bar">
  <ol id="inactive-list" class="list-panel">
    <?php
    if (isset($tooltip_message) && !empty($tooltip_message)) {
      $tooltip_configstr = " title=\"" . $tooltip_message['value'] . "\" has-tool-tip=\"1\" placement=\"" . $tooltip_message['placement'] . "\"";;
    } else {
      $tooltip_configstr = "";
    }

    foreach ($data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
      if (!$object->
              $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
              [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active]) {
        echo
        "<li data-"
        . $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
        . "-id=\""
        . $object->
        $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id]
        . "\" class=\"select_item ui-widget-content\"". $tooltip_configstr .">"
        . $object->
        $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
        [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name]
        . "</li>";
      }
    }
    ?>              
  </ol>
</div>