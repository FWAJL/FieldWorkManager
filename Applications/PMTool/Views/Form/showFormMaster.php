<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="form_list">
    <h3>
      <?php echo $resx["h3_new_form"]; ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <div class="form_sections">
            <?php
            require $form_modules["form_form_master"];
            ?>
          </div>
        </div>
        <div class="col-lg-1 col-md-1">
          <div class="buttons">
            <input type="button" id="btn_edit_form" class="form_edit hide btn btn-default" value="<?php echo $resx["form_button_edit"]; ?>" />
            <input type="button" id="btn_delete_form" class="form_edit hide btn btn-default" value="<?php echo $resx["form_button_delete"]; ?>" />
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
        </div>
      </div>
    </div>
  </div>
</div><!--END RIGHT ASIDE MAIN -->