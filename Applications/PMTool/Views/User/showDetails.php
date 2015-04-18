<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3><?php echo $resx["button_edit"]; ?></h3>
  <div class="form_sections user_details">
    <div class="col-lg-5 col-md-5 col-sm-5">
      <?php require $user_form; ?>
      <?php if(isset($user_details)) require $user_details; ?>
    </div>
    <div class="col-lg-1 col-md-1 col-sm-1">
      <?php require $user_details_buttons; ?>
    </div>
  </div>

</div>
