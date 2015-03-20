<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_info"  class="data-form">
  <fieldset class="task_form">
    <ol class="add-new-item">
      <li style="display: none;">
        <input name="task_id" type="text" />
      </li>
      <li style="display: none;">
        <input name="project_id" type="text" value="<?php echo $current_project->project_id() ?>" />
      </li>
      <br />
      <li>
        <label><?php echo $resx["task_name"]; ?></label>
        <input name="task_name" type="text" />
      </li>
      <li>
        <label><?php echo $resx["task_deadline"]; ?></label>
        <input type="text"  name="task_deadline" id="datepicker"/>
      </li>
      <li>
        <label><?php echo $resx["task_instructions"]; ?></label>
        <textarea name="task_instructions" type="text"></textarea>
      </li>
      <li class="hide">
        <label><?php echo $resx["task_active"]; ?></label>
        <input name="task_active" type="checkbox"></textarea>
      </li>
    </ol>
  </fieldset>

  <div class="task_info_input">
    <h4><?php echo $resx["triggers"];?></h4>
    <fieldset class="task_form">
      <ol class="task_boxes">
        <li>
          <input class="cbox" name="task_trigger_cal" id="freq_list_box" type="checkbox" />
          <span class="task_box_label"><?php echo $resx["task_trigger_cal"]; ?></span>
          <select name="task_trigger_cal_value" id="freq_list">
            <option value="" selected='selected'>Select</option>
            <option value="Daily" >Daily</option>
            <option value="Weekly">Weekly</option>
            <option value="Two Weeks" >Two Weeks</option>
            <option value="Monthly" >Monthly</option>
            <option value="Two Months" >Two Months</option>
            <option value="Quarterly" >Quarterly</option>
            <option value="semi-Annual" >semi-Annual</option>
            <option value="Annual" >Annual</option>
          </select>
        </li>

        <li>
          <input class="cbox" name="task_trigger_pm" id="manual_box" type="checkbox" />
          <span class="task_box_label"><?php echo $resx["task_trigger_pm"]; ?></span>
          <span class="glyphicon glyphicon-question-sign" id="question-task-showForm-manual-box"></span>
        </li>

        <li>
          <input class="cbox" name="task_trigger_ext" id="external_box" type="checkbox" />
          <span class="task_box_label"><?php echo $resx["task_trigger_ext"]; ?></span>
          <span class="glyphicon glyphicon-question-sign" id="question-task-showForm-external-box"></span>
        </li>
      </ol>
    </fieldset>
  </div>

  <div class="task_info_input">
    <h4><?php echo $resx["data_collect"];?></h4>
    <fieldset class="task_form">
      <ol class="task_boxes">
        <li>
          <input class="cbox" name="task_req_form" id="insp_note_box" type="checkbox" />
          <span class="task_box_label tab_cbox"><?php echo $resx["inspection"]; ?></span>
        </li>

        <li>
          <input class="cbox" name="task_req_field_analyte" id="field_data_box" type="checkbox" />
          <span class="task_box_label tab_cbox"><?php echo $resx["field_sample"]; ?></span>
        </li>

        <li>
          <input class="cbox" name="task_req_lab_analyte" id="lab_sample_box" type="checkbox" />
          <span class="task_box_label tab_cbox"><?php echo $resx["lab_sample"]; ?></span>
        </li>

        <li>
          <input class="cbox" name="task_req_service" id="service_providers_box" type="checkbox" />
          <span class="task_box_label tab_cbox"><?php echo $resx["service_provider"]; ?></span>
        </li>
      </ol>
    </fieldset>
  </div>
  <?php require($form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]) ?>
</div>
