<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside main col-lg-9 col-md-9 col-sm-9">
  
  <section class="form_sections">
      <?php
      foreach ($form_modules as $key => $module_path) {
        require $module_path;
      }
      ?>
  </section>

</section>
</div><!-- END ROW DIV -->
</div><!-- END CONTENT CONTAINER -->
