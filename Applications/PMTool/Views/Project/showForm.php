<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $resx["project_add_header"] ?>
  </h3>
  <div class="form_sections">

    <!-- open tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::project_tabs_open]; ?>

    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::project_form]; ?>
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::facility_form]; ?>
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::client_form]; ?>
        </div>
        <div class="col-lg-1 col-md-1">
          <div class="buttons">
            <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::project_buttons]; ?>
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          
        </div>
      </div>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::popup_msg_module]; ?>
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::popup_prompt_module]; ?>
    </div>

    <!-- close tabs -->
    <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::tabs_close]; ?>
  </div>
	
</div>