<!--Promt box: genrates a prompt box for selecting tasl location forms-->

<!--Effective 8 6 2015 we are deactivating the PDF form section
  but we intend to keep the code which is the following commented
  out block-->
<!--<div class="modal fade tlf-selector-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <?php
  /*if (isset($prompt_message) && !empty($prompt_message)) {
    foreach ($prompt_message as $the_msg) {
      ?>
      <input type="hidden" id="promptmsg-<?php echo $the_msg['promptmsg']['operation'] ?>" value="<?php echo $the_msg['promptmsg']['value'] ?>" />
      <?php
    }
  }*/
  ?>
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="tlf_prompt_title"></h4>
      </div>

      <div class="modal-body">
        <?php //require $popup_prompt_list; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary confirmbuttons" id="prompt_ok">Ok</button>
        <button type="button" class="btn btn-default confirmbuttons" data-dismiss="modal" id="prompt_cancel">Cancel</button>
      </div>

    </div>

  </div>
</div> -->

<!-- New code for the web form -->
<div class="modal fade tlf-selector-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="tlf_prompt_title">
          <span id="task-location-window-title-project_name">
            <?php echo isset($current_project)?$current_project->project_name():''; ?>
          </span>
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span id="task-location-window-title-task_name">
            <?php echo isset($current_task)?$current_task->task_name():''; ?>
          </span>
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span id="task-location-window-title-task_location_name">
            <?php echo $resx['web_form_h4']; ?>
          </span>
        </h4>
      </div>
      <div class="modal-body">
        <!--This is where the Web form would be installed dynamically using javascript-->
        <!--<div style="height: 33px; margin-top: 6px; display: inline-block; width: 100%;">
          <div style="float: left; height: 100%; margin: 4px; text-align: right; width: 28%;">FA Name</div>
          <div style="float: right; width: 65%;"><input type="text" name="field_data_result_56_16"></div>
        </div>-->
        
      </div>
      <div id="matrix_no_recs" style="display:none;">
        <div style="height: 33px; margin-top: 6px; display: inline-block; width: 100%;">
          <?php echo $resx['web_form_no_rec']; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary confirmbuttons" id="matrix_prompt_ok"><?php echo $resx['web_form_ok_label']; ?></button>
        <button type="button" class="btn btn-default confirmbuttons" data-dismiss="modal" id="prompt_cancel"><?php echo $resx['web_form_cancel_label']; ?></button>
      </div>
    </div>
  </div>
</div>
<!--Promt box-->