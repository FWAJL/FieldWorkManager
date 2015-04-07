<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $resx["user_form"] ?>
  </h3>
  <div class="content-container container-fluid">
    <div class="row">
      <div  class="col-lg-5 col-md-5">
        <div class="form_sections add_delete_user">
          <?php require $form_modules["user_form"]; ?>
          <?php require $form_modules["pm_form"]; ?>
        </div>
      </div>
      <div class="col-lg-1 col-md-1">
        <div class="buttons">
          <input type="button" id="btn_add_user" class="user_add btn btn-default" value="<?php echo $resx["btn_add_user"]; ?>" />
          <input type="button" id="btn_edit_user" class="user_edit hide btn btn-default" value="<?php echo $resx["btn_edit_user"]; ?>" />
          <input type="button" id="btn_delete_user" class="user_edit hide btn btn-default" value="<?php echo $resx["btn_delete_user"]; ?>" />
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->