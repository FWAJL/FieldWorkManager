<section id="top_header">
  <?php require __ROOT__ . 'Applications/PMTool/Templates/menus/branding.php'; ?>
  <?php
  if ($user->getAttribute(\Library\Enums\SessionKeys::UserRole) == 2) {
    require __ROOT__ . 'Applications/PMTool/Templates/menus/actionMenu.php';
  }
  ?>
<?php require __ROOT__ . 'Applications/PMTool/Templates/menus/userMenu.php'; ?>
</section>  
<div class="clearfix"></div>