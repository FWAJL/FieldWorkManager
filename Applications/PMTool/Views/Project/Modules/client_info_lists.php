<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div class="content-container container-fluid">
  <div class="row">
    <div  class="col-lg-5 col-md-5">
      <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::client_form]; ?>
    </div>
    <div class="col-lg-2 col-md-2">
      <div class="buttons">
        <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Project::project_buttons]; ?>
      </div>
    </div>
    <div  class="col-lg-5 col-md-5">

    </div>
  </div>
</div>
