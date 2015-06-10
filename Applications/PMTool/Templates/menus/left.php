<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="content-container container-fluid col-no-pad">
  <div class="row col-no-right-margin mobile_main">
  <section id="left-menu" <?php if($this->app->user->getAttribute(\Library\Enums\SessionKeys::UserRole)==3) echo 'style="display:none"' ;?> class="left-aside sidebar col-lg-2 col-md-2 col-sm-2 col-no-pad col-no-width">
    <section class="left-asidebg">
      <nav>
        <!-- CONTENT -->
        <?php
          echo $leftMenu;
        ?>
      </nav>
    </section>
  </section>