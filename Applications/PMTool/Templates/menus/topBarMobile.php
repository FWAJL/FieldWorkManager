<section id="top_header">
  <div id="branding">
    <div id="collapse-menu-button" class="top-right-option glyphicon fa fa-bars fa-2x"></div>
    <figure class="logo"><img src="<?php echo $this->app->relative_path . $this->app()->imageUtil->getImageUrl("FWM_logo_only.png"); ?>" alt="Mobile logo"/></figure>
  </div>
  <div class="breacrumb-top">
    <?php echo (isset($current_project) and isset($current_task)) ? $current_project->project_name() : ''; ?>
    <?php if (isset($current_task) && $current_task !== NULL) { ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php
      echo $current_task->task_name();
    }
    ?>
  </div>
</section>
<div class="clearfix"></div>