<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="form_list">
    <h3>
      <?php echo $resx["form_list_all_header"] ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php
      if (isset($current_project)) {
        echo $current_project->project_name();
      } else {
        echo $resx["h3_no_project"];
      }
      ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <span class="h4"><?php echo $resx["h3_project_forms"]; ?></span>
          <?php require $form_modules["group_list_left"]; ?>
        </div>
        <div class="col-lg-1 col-md-1">
          <?php require $form_modules["group_list_promote_buttons_module"]; ?>
          <button id="show-form-form" value="<?php echo $resx["btn_add_user_form"]; ?>" class="btn">
          <?php echo $resx["btn_add_user_form"]; ?></button>
        </div>
        <div  class="col-lg-5 col-md-5">
          <span class="h4"><?php echo $resx["h3_template_forms"]; ?></span>
          <?php require $form_modules["group_list_right"]; ?>
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->