<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <h3><?php echo $resx["users_list"] ?></h3>
  <div class="form_sections">
    <div class="content-container container-fluid">
      <div class="row col-no-right-margin">
        <div  class="col-lg-5 col-md-5">
          <?php require $form_modules[Applications\PMTool\Resources\Enums\ViewVariablesKeys::categorized_list_left]; ?>
        </div>
        <div style="display: none">
          <form action="../login?enc=0" method="POST" id="authorize-form">
            <input type="text" id="authorize-username" name="username">
            <input type="password" id="authorize-pwd" name="pwd">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
      
