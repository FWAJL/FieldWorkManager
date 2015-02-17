<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="project_list">
    <!--          <h3>  -->
    <h3>Current Project 
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
          <h4><?php echo $resx["h3_projects_active"]; ?></h4>
          <?php require $active_list_module; ?>              
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
<!--           <input type="button" value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn btn-warning from-active-list" />-->
            <button value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn btn-warning from-active-list"> 
              <span class="glyphicon glyphicon-arrow-right" title="De-activate"></span> </button>
 <!--           <input type="button" value="<?php echo $resx["btn_to_active_list"]; ?>"  class="btn btn-warning from-inactive-list" />-->
            <button value="<?php echo $resx["btn_to_active_list"]; ?>" class="btn btn-warning from-inactive-list"> 
              <span class="glyphicon glyphicon-arrow-left" title="Activate"></span> </button>
            <input type="button" value="<?php echo $resx["btn_set_current_project"]; ?>"  class="btn btn-warning btn_set_current_project" />            
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_projects_inactive"]; ?></h4>
          <?php require $inactive_list_module; ?>
        </div>
        <?php
        require $popup_msg_module;
		require $prompt_msg_module;
        ?>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->