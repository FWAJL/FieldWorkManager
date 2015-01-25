<?php if ( ! defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section id="top_header">
  <section id="branding">
    <figure class="logo"><img src="<?php echo $this->app->relative_path . $this->app->logoImageUrl; ?>"></figure>
<!--    <p class="brand"><?php //echo $resx_menu_left["brand"]; ?></p>-->
  </section>
</section>
<section  class="login-container">
  <figure class="login-box">
    <h1><?php echo $resx["h1_title"]; ?></h1>
    <section class="login-box-small">
      <p style="display: none;">
        <label><?php echo $resx["email_label"]; ?></label>
        <input autocomplete="on" name="email" type="text" class="field" placeholder="<?php echo $resx["email_ph_text"]; ?>">
      </p>
      <p>
        <label><?php echo $resx["username_label"]; ?></label>
        <input autocomplete="on" name="username" type="text" class="field" placeholder="<?php echo $resx["username_ph_text"]; ?>">
      </p>
      <p>
        <label><?php echo $resx["pwd_label"]; ?></label>
        <input autocomplete="on" name="password" type="password" class="field" placeholder="<?php echo $resx["pwd_ph_text"]; ?>">
      </p>
      <p>
<!--        <input name="remember_me" type="checkbox" value="" />
        <?php echo $resx["remember_me_label"]; ?>
        <a href="#" name="forgot_pwd"  class="password">
          <?php echo $resx["forgot_pwd_label"]; ?>
        </a>-->
      </p>
      <div class="login-btn">
        <p>
          <input id="btn_login" class="btn btn-primary btn-lg" role="button" type="button" value="<?php echo $resx["login_btn_text"]; ?>" />
        </p>
      </div> 
    </section>
  </figure>
</section >