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
          Active List as placeholder
          <br />
              <?php require $active_list_module; ?> 
<!--          Insert Project Services module here-->
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" value="<?php echo $resx["btn_to_inactive_project_list"]; ?>" class="btn btn-warning from-active-project-list" />
            <input type="button" value="<?php echo $resx["btn_to_active_project_list"]; ?>"  class="btn btn-warning from-inactive-project-list" />            
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_services_active"]; ?></h4>
          <?php require $active_list_module; ?>              
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->