<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <div class="form_list">
    <h3>
      <?php echo $resx["h3_new_form"]; ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row col-no-right-margin">
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
          <span class="h4"><?php echo $resx["h4_all_master_form"]; ?></span>
          <!--<span class="glyphicon glyphicon-question-sign" id="inactive-taskForm-header"></span>-->
          <?php
          //\Applications\PMTool\Helpers\CommonHelper::pr($form_modules);
          require $form_modules["group_list_right"]; 
          require $form_modules["popup_msg_module"]; 
          ?>
          <input type="hidden" id="modforjs" name="modforjs" value="admin_masterform" />
        </div>
      </div>
    </div>
  </div>
</div><!--END RIGHT ASIDE MAIN -->