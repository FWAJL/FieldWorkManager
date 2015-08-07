<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
//\Applications\PMTool\Helpers\CommonHelper::pr($form_modules);
?>
<div class="mobile_map right-aside col-no-right-pad main col-lg-12 col-md-12 col-sm-12 col-no-pad">

  <div class="content-container container-fluid col-no-pad">


    <div class="row col-no-right-margin">
      <div id="mobile-location-list" class="col-lg-2 col-md-2">
        <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::location_list]; ?>
      </div>
      <div class="col-lg-8 col-md-8"></div>
    </div>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]; ?>
    <?php //require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_maplegends_module]; ?>
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Map::popup_task_location_info]; ?>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_msg]; ?>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_selector_module]; ?>
    <?php
    //\Applications\PMTool\Helpers\CommonHelper::pr($form_modules);
    ?>

    <input type="hidden" disabled id="tasks-heading" value="<?php echo $resx['tasks_heading']; ?>"/>
    <input type="hidden" disabled id="other-locations-heading" value="<?php echo $resx['other_locations_heading']; ?>"/>
  </div>




</div>