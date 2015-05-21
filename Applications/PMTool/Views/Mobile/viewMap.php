<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed');
//\Applications\PMTool\Helpers\CommonHelper::pr($form_modules);
?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <span class="h3">
    <?php echo $current_project->project_name(); ?>
    <?php if ($current_task !== NULL) { ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php echo $current_task->task_name();
    } ?>
    <span class="glyphicon glyphicon-chevron-right"></span>
    <?php echo $resx['map_header'] ?></span>
  <span class="glyphicon glyphicon-question-sign" id="question-map-h3"></span>

  <div class="content-container container-fluid">


    <div class="row col-no-right-margin">
      <div class="col-lg-10 col-md-10">
        <p>
          <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::map_module]; ?>
        </p>
      </div>
      <div class="col-lg-2 col-md-2">
        <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::map_info_module]; ?>
      </div>

    </div>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]; ?>
    <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::popup_maplegends_module]; ?>
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Map::popup_task_location_info]; ?>

    <input type="hidden" disabled id="tasks-heading" value="<?php echo $resx['tasks_heading']; ?>"/>
    <input type="hidden" disabled id="other-locations-heading" value="<?php echo $resx['other_locations_heading']; ?>"/>
  </div>




</div>