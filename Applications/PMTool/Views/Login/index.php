<section  class="login-container">
  <figure class="login-box">
    <h1><?php echo $resx["h1_title"]; ?></h1>
    <section class="login-box-small">
      <p>
        <label name="email"><?php echo $resx["email_label"]; ?></label>
        <input name="email" type="text" class="field" placeholder="<?php echo $resx["email_ph_text"]; ?>">
      </p>
      <p>
        <label name="pwd"><?php echo $resx["pwd_label"]; ?></label>
        <input name="pwd" type="text" class="fieldb" placeholder="<?php echo $resx["pwd_ph_text"]; ?>"> </p>
      <p>
        <input name="remember_me" type="checkbox" value="" />
        <?php echo $resx["remember_me_label"]; ?>
        <a href="#" name="forgot_pwd"  class="password">
          <?php echo $resx["forgot_pwd_label"]; ?>
        </a>
      </p>
      <p class="login-btn">
        <input id="btn_login" class="btn btn-primary btn-lg" role="button" type="button" value="<?php echo $resx["login_btn_text"]; ?>" />
      </p>
    </section>
  </figure>
</section >