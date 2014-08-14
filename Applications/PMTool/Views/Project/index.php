<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside main col-lg-9 col-md-9">
  
  <section class="welcome <?php echo $display_project_welcome; ?>">
    <h1 class="legend"><?php echo $resx["project_h1"]; ?></h1>
    <p><?php echo $resx["project_welcome_message"]; ?></p>
  </section>
  
  <section class="project_list <?php echo $display_project_list ?>">
    <h3><?php echo $resx["project_list_all_header"]; ?></h3>
    <h4><?php echo $resx["h3_projects_active"]; ?></h4>
    <select multiple id="project-data-active" class="form-control"></select>
    <h4><?php echo $resx["h3_projects_inactive"]; ?></h4>
    <select multiple id="project-data-inactive" class="form-control"></select>
  </section>
  <section class="form_sections <?php echo $display_add_project; ?>">
      <?php
      foreach ($form_modules as $key => $module_path) {
        require $module_path;
      }
      ?>
  </section>

</section>
</div><!-- END ROW DIV -->
</div><!-- END CONTENT CONTAINER -->
