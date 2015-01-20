<section id="top_header">
  <div id="branding">
    <figure class="logo"><img src="<?php echo $this->app->relative_path . $this->app->logoImageUrl; ?>"></figure>
<!--    <p class="brand"><?php // echo $resx_menu_left["brand"]; ?></p>-->
  </div>
  <div id="pm_info">
    <span id="pm_name" class="top-right-option">
      <?php echo $resx_menu_left["p_user_name_label"]; ?><?php echo $pm['pm_name']; ?>
    </span>
    <span id="view_pm_info" class="top-right-option" data-pm-id="<?php echo $pm['pm_id']; ?>">
      <a class="glyphicon glyphicon-user" title="<?php echo $resx_menu_left["pm_view_info"]; ?>"></a>
    </span>
    <span id="pm_logout" class="top-right-option">
      <a class="glyphicon glyphicon-log-out" title="<?php echo $resx_menu_left["logout_link_text"]; ?>" href="<?php echo $logout_url; ?>" ></a>
    </span>
  </div>
</section>
<div class="clearfix"></div>