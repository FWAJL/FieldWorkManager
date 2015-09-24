<div class="action-item">
  <a href="<?php echo $this->app->relative_path . "task/listAll"; ?>">
    <span class="fa fa-list fa-2x"></span>
  </a>
</div>
<div class="action-item">
  <a href="<?php echo $this->app->relative_path . "task/addPrompt"; ?>" >
    <span class="fa fa-plus-circle fa-2x"></span>
  </a>
</div>
<div class="action-item legacy-menu">
  <span class="fa fa-bars fa-2x"></span>
  <div class="hide">
    <?php require __ROOT__ . 'Applications/PMTool/Templates/menus/legacyMenu.php'; ?>
  </div>
</div>
