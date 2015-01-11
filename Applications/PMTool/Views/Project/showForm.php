<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $resx["project_add_header"] ?>
  </h3>
  <div class="form_sections">
    <!-- open tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::project_tabs_open]; ?>

    <!-- project info block -->
    <div id="project_info" class="data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Project::project_info_lists]; ?>      
    </div>

    <!-- facility info block -->
    <div id="facility_info" class="hide data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Project::facility_info_lists]; ?>
    </div>
    
        <!-- client info block -->
    <div id="client_info" class="hide data-form-2">
      <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariables\Project::client_info_lists]; ?>
    </div>

    <!-- close tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::project_tabs_close]; ?>
  </div>

</div>