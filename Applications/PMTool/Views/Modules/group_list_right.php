<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<?php if ($ProjectHasActiveLocation) { ?>
  <ol id="group-list-right" class="list-panel">
    <?php
    $noActiveOject = FALSE;
    foreach ($data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects_right] as $object) {
      if ($object->$data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right][\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_active]) {
        echo
        "<li data-" . $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module] . "-id=\"" . $object->$data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right][\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id] . "\" class=\"select_item ui-widget-content\">"
        . $object->$data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties_right][\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name]
        . "</li>";
      }
    }
    ?>              
  </ol>   
<?php } else { ?>
  <p><?php $resx["no_active_project_location"]; ?></p>
<?php } ?>
