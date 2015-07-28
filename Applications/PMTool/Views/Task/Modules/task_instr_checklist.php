<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_info_instr_checklist"  class="data-form1">
  <fieldset class="task_form">
    <ol class="add-new-item">
      <label><?php echo $resx["task_instructions"]; ?></label>
      <li id="task_instructions">
          <textarea class="instructions" name="task_instructions" type="text"></textarea>
      </li>
    </ol>
  </fieldset>      
      <label><?php echo $resx["task_checklist"]; ?></label>
     
<ol id="task_checklist" class="list_panel">
<li>[] Checklist Item 1</li>
<li>[] Checklist Item 2</li>
<li>[] Checklist Item 3</li>
  <?php //
//  foreach ($data_task_checklist[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::objects] as $object) {
//    $prop_id =
//            $data_task_checklist
//            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
//            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_id];
//    $prop_name =
//            $data_task_checklist
//            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::properties]
//            [\Applications\PMTool\Resources\Enums\ViewVariablesKeys::property_name];
//    echo
//    "<li data-"
//    . $data_task_checklist[\Applications\PMTool\Resources\Enums\ViewVariablesKeys::module]
//    . "-id=\"" . $object->$prop_id() . "\" class=\"ui-widget-content\">"
//    . $object->$prop_name()
//    . "</li>";
//  }
//  ?>      
            
</ol> 
    <label><?php echo $resx["task_checklist_add"]; ?></label>
  
    <fieldset class="task_checklist_form form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input name="" type="text" />
      </li>
      <li style="display: none;">
        <input name="task_id" type="text" />
      </li>
      <li>
        <textarea class="instructions" name="task_checklist"></textarea>
      </li>
    </ol>            
  </fieldset>
</div>
