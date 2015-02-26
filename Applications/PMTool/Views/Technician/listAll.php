<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="technician_list">
    <h3><?php echo $resx["technician_list_all_header"]; ?></h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_technicians_active"]; ?></h4>
          <?php require $active_list_module; ?>              
        </div>
        <div class="col-lg-1 col-md-1">
       <?php require $promote_buttons_module; ?>           
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_technicians_inactive"]; ?></h4>
          <?php require $inactive_list_module; ?>              
        </div>
        <?php require $popup_msg_module; ?>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->