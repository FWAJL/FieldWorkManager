<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
    <span class="h3"><?php echo $resx["h3_title"]; ?><span class="glyphicon glyphicon-chevron-right"></span> <?php
        if (isset($current_project)) {
            echo $current_project->project_name();
        } else {
            echo $resx["h3_no_project"];
        }
        ?><span class="glyphicon glyphicon-chevron-right"></span> <?php
        if (isset($current_task)) {
            echo $current_task->task_name();
        } else {
            echo $resx["h3_no_task"];
        }
        ?>
    </span>
    <span class="glyphicon glyphicon-question-sign" id="question-map-h3"></span>
    <div class="content-container container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10">
                <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::map_module]; ?>
            </div>
            <div class="col-lg-2 col-md-2">
                <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::map_info_module]; ?>
            </div>
        </div>
        <?php require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]; ?>
    </div>
</div><!-- END RIGHT ASIDE MAIN -->