<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_coc_info"  class="data-form">
  <fieldset class="task_coc_form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input name="task_id" type="text" />
      </li>
      <li style="display: none;">
        <input name="task_coc_id" type="text" />
      </li>
      <li>
        <label><?php echo $resx["po_number"]; ?></label>
        <input name="po_number" type="text" />
      </li>
      <li>
        <label><?php echo $resx["lab_instructions"]; ?></label>
        <textarea name="lab_instructions" type="text"></textarea>
      </li>

      <li>
        <label><?php echo $resx["coc_service_id"]; ?></label>
        <div class="coc_list_services">
          <select name="service_id">
            <?php
            foreach ($lab_services as $service) {
              ?>
              <option value="<?php echo $service['service_id'] ?>"><?php echo $service['service_name'] ?></option>
              <?php
            }
            ?>                                    
          </select>
        </div>
      </li>
      <li>
        <label><?php echo $resx["lab_sample_type"]; ?></label>
        <select class="coc_list" name="lab_sample_type">
          <option value="Groundwater">Groundwater</option>
          <option value="Surface Water">Surface Water</option>
          <option value="Soil">Soil</option>
          <option value="Air">Air</option>
          <option value="Other">Other</option>
        </select>
      </li>
      <li>
        <label><?php echo $resx["lab_sample_tat"]; ?></label> 
        <select class="coc_list" name="lab_sample_tat">
          <option value="Standard">Standard</option>
          <option value="1 week">1 week</option>
          <option value="2 day">2 day</option>
          <option value="see comments">see comments</option>
        </select>
      </li>
      <li>
        <label><?php echo $resx["project_number"]; ?></label>
        <input name="project_number" type="text" value="<?php echo $current_project->project_number(); ?>"/>
      </li>
      <li><br /></li>
      <li><h4>Send Results to:</h4></li>
      <li>
        <label><?php echo $resx["results_to_name"]; ?></label>
        <input name="results_to_name" type="text" value="<?php echo $current_pm['pm_obj']['pm_name'] ?>" />
      </li>        
      <li>
        <label><?php echo $resx["results_to_company"]; ?></label>
        <input name="results_to_company" type="text" value="<?php echo $current_pm['pm_obj']['pm_comp_name'] ?>" />
      </li>        
      <li>
        <label><?php echo $resx["results_to_address"]; ?></label>
        <textarea name="results_to_address" type="text"><?php echo $current_pm['pm_obj']['pm_address'] ?></textarea>
      </li>
      <li>
        <label><?php echo $resx["results_to_phone"]; ?></label>
        <input name="results_to_phone" type="text" value="<?php echo $current_pm['pm_obj']['pm_phone'] ?>" />
      </li> 

      <li>
        <label><?php echo $resx["results_to_email"]; ?></label>
        <input name="results_to_email" type="text" value="<?php echo $current_pm['pm_obj']['pm_email'] ?>" />
      </li>        
    </ol>
  </fieldset>

</div>
