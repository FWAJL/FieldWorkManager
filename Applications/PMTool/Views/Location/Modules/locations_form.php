<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="location-list">
<h3><?php echo $resx["h3_location_names_form"]; ?></h3>
<div class="content-container">
    <div class="row">  
        <div  class="col-lg-5 col-md-5">    
            <div id="location_info">
                <fieldset class="location_form form">
        <div class="location-names"><textarea  class="list-panel" name="location_names" type="text"></textarea></div>
          <label><?php echo $resx["location_active_many"]; ?></label>
          <input name="location_active" type="checkbox" checked />
        <input id="btn-add-location-names" type="button" value="<?php echo $resx["btn_add_location_names"] ?>" class="btn btn-primary" />
  </fieldset>
</div>
</div>
</div>       
</div>    
</div>
</div>