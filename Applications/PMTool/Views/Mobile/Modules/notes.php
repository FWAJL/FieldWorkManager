<div class="content-container container-fluid">
  <div class="row col-no-right-margin">
    <div  class="col-lg-5 col-md-5">
      <span class="h4"><?php echo $resx['h4_tasknotes_leftcol'] ?></span>
      <div>

        <textarea  class="upload_list" id="task_notes_message" name="task_notes_message" type="text"></textarea>
        <?php require $upload_file; ?>
    <div class="buttons">
        <input type="button" id="btn_savenotes" class="at at-status btn btn-default" value="<?php echo $resx["button_savenote"]; ?>" />
    </div>
        <div class="list-panel upload_list notes" name="task_notes" id="task-notes"></div>
       <input type="text" disabled id="task-instructions" value="<?php echo $task_instructions; ?>"/>
      </div>


    <?php if(isset($current_location)): ?>
      <input type="hidden" id="current-location-name" value="<?php echo $current_location->location_name(); ?>" />
    <?php endif; ?>
      </div>
    </div>
</div>