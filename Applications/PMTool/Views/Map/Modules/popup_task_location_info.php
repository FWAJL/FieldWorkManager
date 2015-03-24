<!--Prompt box: genrates a project info prompt box-->
<div class="modal fade prompt-modal" id="task-location-info-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="task-location-window-title"><span id="task-location-window-title-project_name"><?php echo isset($current_project)?$current_project->project_name():''; ?></span><span class="glyphicon glyphicon-chevron-right"></span><span id="task-location-window-title-task_name"><?php echo isset($current_task)?$current_task->task_name():''; ?></span><span class="glyphicon glyphicon-chevron-right"></span><span id="task-location-window-title-task_location_name"></span></h4>
      </div>

      <div class="modal-body">
        <label for="task-location-info-modal-location_name"><?php echo $resx['task_location_window_location_name']; ?></label>
        <input class="form-control" type="text" id="task-location-info-modal-location_name" name="location_name">
        <a href="#" id="task-location-info-modal-zoom"><span class="glyphicon glyphicon-zoom-in"></span> <?php echo $resx['task_location_window_zoom']; ?></a>
        <a href="#" id="task-location-info-modal-remove" class="task-location-info-modal-action"><span class="glyphicon glyphicon-remove"></span> <?php echo $resx['task_location_window_remove']; ?></a>
        <a href="#" id="task-location-info-modal-add" class="task-location-info-modal-action"><span class="glyphicon glyphicon-plus"></span> <?php echo $resx['task_location_window_add']; ?></a>
        <a href="#" id="location-info-modal-photos"><span class="glyphicon glyphicon-camera"></span> <?php echo $resx['location_window_photos']; ?><span id="location-info-modal-photos-count"></span></a>
        <?php require $form_modules["upload_file"]; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary confirmbuttons modal-update"><?php echo $resx['task_location_window_edit_update']; ?></button>
        <button type="button" class="btn btn-default confirmbuttons" data-dismiss="modal"><?php echo $resx['task_location_window_edit_cancel']; ?></button>
      </div>

    </div>
  </div>
</div>
<div class="lightbox-content"></div>
<!--Promt box-->