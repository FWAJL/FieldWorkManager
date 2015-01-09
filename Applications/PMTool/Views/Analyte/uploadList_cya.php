<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="analyte_list">
    <h3>
      <?php echo $resx["h4_analytes"] ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["field_analytes_header"]; ?></h4>
          <fieldset class="analyte_form form" id="upload_analytes_form">
            <div class="analyte-names">
              <textarea  class="list-panel upload_list" name="field_analyte_names" type="text"></textarea>
            </div>
          </fieldset>
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" id="btn_add_analyte" class="btn btn-default" value="<?php echo $resx["analyte_button_add"]; ?>" />
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["lab_analytes_header"]; ?></h4>
          <fieldset class="analyte_form form" id="upload_analytes_form">
            <div class="analyte-names">
              <textarea  class="list-panel upload_list" name="lab_analyte_names" type="text"></textarea>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->