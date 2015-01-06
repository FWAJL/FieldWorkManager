<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="task_tech_list">
    <h3><?php echo $resx["task_tech_list_all_header"]; ?></h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_task_techs_active"]; ?></h4>
          <?php require $active_list_module; ?>              
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn btn-warning from-active-list" />
            <input type="button" value="<?php echo $resx["btn_to_active_list"]; ?>"  class="btn btn-warning from-inactive-list" />  
            <input type="button" value="<?php echo $resx["btn_to_lead_list"]; ?>"  class="btn btn-warning from-active-list" /> 
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_task_techs_inactive"]; ?></h4>
          <?php require $inactive_list_module; ?>              
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->