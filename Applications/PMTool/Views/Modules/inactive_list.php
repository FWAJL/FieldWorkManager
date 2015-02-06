<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="scroll-bar">
  <ol id="inactive-list" class="list-panel">
    <?php
    $tooltip_configstr = "";
    if (isset($tooltip_message) && !empty($tooltip_message)) {
	  foreach($tooltip_message as $the_msg_node)
	  {
	    if(isset($the_msg_node['tooltip']))
		{
		  $tooltip_configstr .= " title=\"" . $the_msg_node['tooltip']['value'] . "\" has-tool-tip=\"1\" placement=\"" . $the_msg_node['tooltip']['placement'] . "\"";;  
		}
	  }
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