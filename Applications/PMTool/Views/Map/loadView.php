<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3><?php echo $resx["h3_title"]; ?><span class="glyphicon glyphicon-chevron-right"></span> <?php echo $resx["h3_sub_title_all_projects"]; ?></h3>
  <div class="content-container container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12">
        <?php require $form_modules[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::map_module]; ?>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->