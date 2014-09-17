<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<ol id="inactive-list" class="list-panel">
<?php
foreach ($data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
    $dao_id_prop = $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module] . "_id";
    $dao_name_prop = $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module] . "_name";
    $dao_active_prop = $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module] . "_active";
    if (!$object->$dao_active_prop) {
      echo
    "<li data-project-id=\"" . $object->$dao_id_prop . "\" class=\"select_item ui-widget-content\">"
    . $object->$dao_name_prop
    . "</li>";
  }
}
?>              
</ol>