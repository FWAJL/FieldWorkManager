<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="field_analyte_list">
    <h3>
      <?php echo $current_project->project_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php echo $resx["field_analyte_list_all_header"] ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_field_analyte_names_form"]; ?></h4>
          <div class="add-new-field_analytes">
            <div class="field_analyte-names"><textarea name="field_analyte_names" type="text"></textarea></div>
            <div class="field_analyte-active">
              <!--<label><?php echo $resx["field_analyte_active_many"]; ?></label>-->
              <!--<input name="field_analyte_active" type="checkbox" checked />-->
            </div>
            <input id="btn-add-field_analyte-names" type="button" value="<?php echo $resx["btn_add_field_analyte_names"] ?>" class="btn btn-primary" />
          </div>
        </div>
        <div  class="col-lg-2 col-md-2"></div>
        <div  class="col-lg-2 col-md-5">
          <h4><?php echo $resx["h3_field_analyte_add_buttons"]; ?></h4>
          <div class="field_analyte-add-buttons">
            <input id="btn-add-field_analyte-gmap" type="button" value="<?php echo $resx["btn_add_field_analyte_gmap"] ?>" class="btn btn-primary" />
            <input id="btn-add-field_analyte-manual" type="button" value="<?php echo $resx["btn_add_field_analyte_manual"] ?>" class="btn btn-primary" />
          </div>
        </div>
      </div>
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_field_analytes_active"]; ?></h4>
          <?php require $active_list_module; ?>              
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input type="button" value="<?php echo $resx["btn_to_inactive_list"]; ?>" class="btn btn-warning from-active-list" />
            <input type="button" value="<?php echo $resx["btn_to_active_list"]; ?>"  class="btn btn-warning from-inactive-list" />            
          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_field_analytes_inactive"]; ?></h4>
          <?php require $inactive_list_module; ?>              
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->