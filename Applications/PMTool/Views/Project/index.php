<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside">
  <h2></h2>
  <section class="welcome <?php echo $display_project_welcome; ?>">
    <h1 class="legend"><?php echo $resx["project_h1"]; ?></h1>
    <p><?php echo $resx["project_welcome_message"]; ?></p>
  </section>
  <section class="form_sections <?php echo $display_add_project; ?>">
      <div id="tab-container" class="tab-container">
  <ul class='etabs'>
    <li class='tab'><a href="#project_info">Project Info</a></li>
    <li class='tab'><a href="#facility_info">Facility Info</a></li>
    <li class='tab'><a href="#client_info">Client Info</a></li>
  </ul>

    <?php
    foreach ($form_modules as $key => $module_path) {
      require $module_path;
    }
    ?>
          <!-- One set of buttons for all tabs -->
<input type="button" id="btn_add_project" class="project_add" value="<?php echo $resx["project_button_add"]; ?>" />
<input type="button" id="btn_edit_project" class="project_edit hide" value="<?php echo $resx["project_button_edit"]; ?>" />
<input type="button" id="btn_delete_project" class="project_edit hide" value="<?php echo $resx["project_button_delete"]; ?>" />
       
<!-- This div closes out the tab-container -->
      </div> 
      
  </section>

</section>	
</div><!-- END CONTENT CONTAINER -->
