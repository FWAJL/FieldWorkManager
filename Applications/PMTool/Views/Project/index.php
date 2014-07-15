<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside">
  <h2></h2>
  <section class="form_sections">
    <?php 
      foreach ($form_modules as $key => $module_path) {
        require $module_path;
      }
    ?>
  </section>
  <section class="project_list_section">
    <?php
        foreach ($project_list_modules as $key => $module_path) {
          require $module_path;
        }
    ?>
  </section>
</section>	
</div><!-- END CONTENT CONTAINER -->