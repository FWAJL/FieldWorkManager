<?php  if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<!--<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <div class="task_checklist">
<h3>
      <?php //echo $current_project->project_name(); ?>
      <span class="glyphicon glyphicon-chevron-right"></span>
      <?php //echo $resx["task_checklist_header"] ?>
    </h3>
<div class="content-container container-fluid">
  <div class="row col-no-right-margin">
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php //echo $resx["h4_task_checklist_items"]; ?></span>
      <span class="glyphicon glyphicon-question-sign" id="activequestion-taskchecklist-uploadList-headerH4"></span>
      <?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\TaskChecklist::task_checklist_form]; ?>
    </div>
    <div class="col-lg-1 col-md-1">
      <?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\TaskChecklist::checklist_buttons]; ?>
    </div>
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php //echo $resx["h4_task_checklist"]; ?></span>
      <span class="glyphicon glyphicon-question-sign" id="inactivequestion-taskchecklist-uploadList-headerH4"></span>
      <?php //require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\TaskChecklist::task_checklist_list]; ?>
    </div>
          <?php //require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg] ?>
  </div>
</div>
  </div>
</div>-->


<!--Mockup page - to be deleted-->
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
<h3>
Checklist
    <span class="glyphicon glyphicon-chevron-right">
    </span>

</h3>

<div class="form_sections">

    <div id="tab-container" class="tab-container">
        <ul class="etabs">
            <li class="tab " data-form-id="task_info"><a>task Info</a></li>

            <li class="tab " data-form-id="task_technicians"><a>Technicians</a></li>

            <li class="tab " data-form-id="task_locations"><a>Locations</a></li>

            <li class="tab active" data-form-id="task_checklist"><a>Checklist</a></li>

        </ul>
        <div class="content-container container-fluid">
            
            <div class="row col-no-right-margin">
                
    <div class="col-lg-5 col-md-5">
      <span class="h4">Enter or paste a list of checklist items here.</span>
      <span class="glyphicon glyphicon-question-sign" id="activequestion-fieldanalyte-uploadList-headerH4" data-original-title="" title=""></span>

      
  <fieldset class="field_analyte_form form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input name="field_analyte_id" type="text">
      </li>
      <li style="display: none;">
        <input name="pm_id" type="text">
      </li>
      <li class="analyte-names">
        <textarea class="list-panel" name="field_analyte_name_unit"></textarea>
      </li>
    </ol>            
  </fieldset>

    </div>
    <div class="col-lg-1 col-md-1">
      
<!-- One set of buttons for all tabs -->
<input type="button" class="btn btn-default btn-add-analyte" value="Add" name="field_analyte_name_unit">
<input type="button" id="btn_edit_analyte" class="hide btn btn-default" value="Edit analyte">
<input type="button" id="btn_delete_analyte" class="hide btn btn-default" value="Delete analyte">      
<!-- One set of buttons for all tabs -->
<div class="buttons">
  <button title="Add Common Analyte(s) to Project" data-analyte-type="CFA" class="btn btn-warning from-common-field-analyte-list">
    <span class="glyphicon glyphicon-arrow-left"></span>
  </button>
  <button title="Add Common Analyte(s) to Project" data-analyte-type="CLA" class="btn btn-warning from-common-lab-analyte-list">
    <span class="glyphicon glyphicon-arrow-left"></span>
  </button>
</div>
    </div>
    <div class="col-lg-5 col-md-5">
      <span class="h4">Checklist - to be completed by Technicians</span>
      <span class="glyphicon glyphicon-question-sign" id="inactivequestion-fieldanalyte-uploadList-headerH4" data-original-title="" title=""></span>
  <fieldset class="field_analyte_form form list-panel">          
  <ol class="task_boxes">
        <li>

          <input class="cbox" name="task_trigger_pm" id="manual_box" type="checkbox">
          <span class="task_box_label">Checklist Item 1</span>
        </li>

        <li>
          <input class="cbox" name="task_trigger_ext" id="external_box" type="checkbox">
          <span class="task_box_label">Checklist Item 2</span>
        </li>
      </ol>

  </fieldset>
    </div>
  </div>
     </div>
</div></div></div>