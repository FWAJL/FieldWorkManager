<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="project_list">
    <h3>Map <span class="glyphicon glyphicon-chevron-right"></span> <?= @$_GET['location'] != "true" ? $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects]['project_obj']->project_name() : $data[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects][0]->location_name(); ?></h3>

    <div class="content-container container-fluid">
      <div class="row">
        <div class="col-lg-12 col-md-12">
          <?php require $show_one;  ?>
        </div>
      </div>
    </div>

  </div>
</div><!-- END RIGHT ASIDE MAIN -->