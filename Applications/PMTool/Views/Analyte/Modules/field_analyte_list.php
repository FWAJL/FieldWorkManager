<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>  
    <?php echo $resx["field_analytes_header"] ?></h3>  
  <div class="task_form_sections">         
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_project_analytes"]; ?></h4>
      <?php require $form_modules["group_list_left"]; ?>              
    </div>
    <div class="col-lg-2 col-md-2">
      <div class="buttons">
        <p><input type="button" value="<?php echo $resx["btn_add_to_project"]; ?>" class="btn btn-warning from-group-list-right" /></p>
        <p><input type="button" value="<?php echo $resx["btn_remove_from_project"]; ?>"  class="btn btn-warning from-group-list-left" /></p>
      </div>
    </div>
    <div  class="col-lg-5 col-md-5">
      <h4><?php echo $resx["h4_analytes"]; ?></h4>
      <?php require $form_modules["group_list_right"]; ?>              
    </div>            
  </div
</div>
