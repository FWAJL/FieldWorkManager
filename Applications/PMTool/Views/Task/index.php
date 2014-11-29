<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside main col-lg-10 col-md-10 col-sm-10">
  
  <section class="welcome">
    <h1 class="legend"><?php echo $resx["task_h1"]; ?></h1>
    <p><?php echo $resx["task_welcome_message"]; ?></p>
    <a href="<?php echo \Library\Enums\ResourceKeys\UrlKeys::ProjectsListAll; ?>" class="btn btn-primary">
      <?php echo $resx["btn_location_select_project"]; ?>
    </a>
  </section>
</section>
