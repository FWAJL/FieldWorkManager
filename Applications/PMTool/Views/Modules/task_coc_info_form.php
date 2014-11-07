<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="coc_info"  class="data-form">
<fieldset class="task_coc_info_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="task_id" type="text" />
    </li>
        <li style="display: none;">
      <input name="resource_id" type="text" />
    </li>
     <li><br /></li>
    <li>
      <label><?php echo $resx["po_number"]; ?></label>
      <input name="po_number" type="text" />
    </li>
    <li>
      <label><?php echo $resx["lab_instructions"]; ?></label>
      <textarea name="lab_instructions" type="text"></textarea>
    </li>
    <li>
      <label><?php echo $resx["lab_sample_type"]; ?></label>
      <select class="coc_list" name="lab_sample_type">
                                                        <option value="" selected="selected">Groundwater</option>
                                                        <option value="">Surface Water</option>
                                                        <option value="">Soil</option>
                                                        <option value="">Air</option>
                                                        <option value="">Other</option>
                                                    </select>
    </li>
        <li>
      <label><?php echo $resx["lab_sample_tat"]; ?></label>
      <select class="coc_list" name="lab_sample_tat">
                                                        <option value="" selected="selected">Standard</option>
                                                        <option value="">1 week</option>
                                                        <option value="">2 day</option>
                                                        <option value="">see comments</option>
                                                    </select>
    </li>
        <li>
      <label><?php echo $resx["project_id_num"]; ?></label>
      <input name="project_id_num" type="text" />
    </li>
    <li><br /></li>
    <li><h3>Send Results to:</h3></li>
    <li>
      <label><?php echo $resx["results_to_name"]; ?></label>
      <input name="results_to_name" type="text" />
    </li>        
    <li>
      <label><?php echo $resx["results_to_company"]; ?></label>
      <input name="results_to_company" type="text" />
    </li>        
    <li>
      <label><?php echo $resx["results_to_address"]; ?></label>
      <textarea name="results_to_address" type="text"></textarea>
    </li>
    <li>
      <label><?php echo $resx["results_to_phone"]; ?></label>
      <input name="results_to_phone" type="text" />
    </li> 

    <li>
      <label><?php echo $resx["results_to_email"]; ?></label>
      <input name="results_to_email" type="text" />
    </li>        
  </ol>
</fieldset>
    
</div>
 