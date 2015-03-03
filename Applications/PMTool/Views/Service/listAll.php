<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="service_list">
          <h3>
      Current Project
      <span class="glyphicon glyphicon-chevron-right"></span>
            <?php 
          if (isset($current_project)) {
      echo $current_project->project_name();
    } else {
      echo $resx["h3_no_project"];
    }
   ?>
    </h3>

    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_project_services"]; ?></h4>
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left]; ?> 
        </div>
        <div class="col-lg-1 col-md-1">
          <div class="buttons">
            <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_promote_buttons]; ?>
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_services_active"]; ?></h4>
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_right]; ?>              
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->