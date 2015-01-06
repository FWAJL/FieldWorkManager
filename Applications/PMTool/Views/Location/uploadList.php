<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <div class="location_list">
    <h3>
      <?php echo $current_project->project_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php echo $resx["location_list_all_header"] ?>
    </h3>
    <div class="content-container container-fluid">
      <div class="row">
        <div  class="col-lg-5 col-md-5">
          <h4><?php echo $resx["h3_upload_list"]; ?></h4>
 <fieldset class="location_form form" id="upload_locations_form">
        <div class="location-names">
            <textarea  class="list-panel upload_list" name="location_names" type="text"></textarea>
        </div>
  </fieldset>
        </div>
        <div class="col-lg-2 col-md-2">
          <div class="buttons">
            <input id="btn-add-location-names" type="button" value="<?php echo $resx["btn_add_location_names"] ?>" class="btn btn-primary" />
            <br/> 
            <ul>
               <li style="display: none">
                  <input id="location_upload_list" name="location_active" type="checkbox" checked />
              </li>
            </ul>
<!--              <label for="location_upload_list"><?php //echo $resx["location_active_many"]; ?></label>-->

          </div>
        </div>
        <div  class="col-lg-5 col-md-5">
            
        </div>
      </div>
    </div>
  </div>
</div><!-- END RIGHT ASIDE MAIN -->