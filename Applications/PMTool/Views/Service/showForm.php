<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="service_list">

    <h3>
    <?php echo $resx["service_list_all_header"] ?>
  </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
            <div class="form_sections">
      <?php
      foreach ($form_modules as $key => $module_path) {
        require $module_path;
      }
      ?>
  </div>
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
<input type="button" id="btn_add_service" class="service_add btn btn-default" value="<?php echo $resx["service_button_add"]; ?>" />
<input type="button" id="btn_edit_service" class="service_edit hide btn btn-default" value="<?php echo $resx["service_button_edit"]; ?>" />
<input type="button" id="btn_delete_service" class="service_edit hide btn btn-default" value="<?php echo $resx["service_button_delete"]; ?>" />
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
Nothing Here.
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->