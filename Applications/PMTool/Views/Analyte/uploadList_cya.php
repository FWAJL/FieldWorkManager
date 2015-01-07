<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="analyte_list">
    <h3>
      <?php echo $resx["h3_pm_analytes"] ?>
    </h3>     
  <div class="form_sections">
    <?php
    foreach ($form_modules as $key => $module_path) {
      require $module_path;
    }
    ?>
  </div>
<!--        <div class="col-lg-2 col-md-2">
          <div class="buttons">
                            <br/>
                            <br/>
                            <br/>
            <input id="btn-add-location-names" type="button" value="<?php //echo $resx["btn_add_location_names"] ?>" class="btn btn-primary" />
            <br/> 
            <ul>
               <li style="display: none">
                  <input id="location_upload_list" name="location_active" type="checkbox" checked />
              </li>
            </ul>
              <label for="location_upload_list"><?php //echo $resx["location_active_many"]; ?></label>

          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
            
        </div>-->

  </div>
</div><!-- END RIGHT ASIDE MAIN -->