<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3><?php echo $resx["users_list"] ?></h3>
  <div class="form_sections">
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left]; ?>
        </div>
      </div>
    </div>
  </div>
</div>
      
