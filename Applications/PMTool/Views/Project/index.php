<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside">
  <h2></h2>
  <section class="form_sections <?php echo $display_add_project; ?>">
    <?php
    foreach ($form_modules as $key => $module_path) {
      require $module_path;
    }
    ?>
  </section>
  <section class="project_list_section <?php echo $display_project_list; ?>">
    <h1 class="legend"><?php echo $resx["project_list_h1"]; ?></h1>
  </section>
</section>	
</div><!-- END CONTENT CONTAINER -->