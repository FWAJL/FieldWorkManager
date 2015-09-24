<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section 
  id="left-menu" 
  <?php if ($this->app->user->getAttribute(\Library\Enums\SessionKeys::UserRole) == 3) echo 'style="display:none"'; ?> 
  class="left-aside sidebar">
  <section class="left-asidebg">
    <nav>
      <?php echo $leftMenu; ?>
    </nav>
  </section>
</section>
