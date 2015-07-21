<!--Prompt box: genrates a project info prompt box-->
<div class="modal fade prompt-modal" id="project-info-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <?php
  if (isset($prompt_message) && !empty($prompt_message)) {
    foreach($prompt_message as $the_msg){
      ?>
      <input type="hidden" id="promptmsg-<?php echo $the_msg['promptmsg']['operation'] ?>" value="<?php echo $the_msg['promptmsg']['value'] ?>" />
      <?php
    }
  }

  ?>
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="project-window-title"><span id="project-window-title-project_name"></span><span class="glyphicon glyphicon-chevron-right"></span><span id="project-window-title-facility_name"></span></h4>
      </div>

      <div class="modal-body">
          <li><label for="project-info-modal-project_name"><?php echo $resx['project_window_project_name']; ?></label>
        <input class="" type="text" id="project-info-modal-project_name" name="project_name"></li>
          <li><label for="project-info-modal-facility_name"><?php echo $resx['project_window_facility_name']; ?></label>
              <input class="" type="text" id="project-info-modal-facility_name" name="facility_name"></li>
          <li><label for="project-info-modal-latitude"><?php echo $resx['project_window_latitude']; ?></label>
              <input class="" type="text" id="project-info-modal-latitude" name="latitude"></li>
          <li><label for="project-info-modal-longitude"><?php echo $resx['project_window_longitude']; ?></label>
              <input class="" type="text" id="project-info-modal-longitude" name="longitude"></li>
          <li><a href="#" id="project-info-modal-update-coordinates"><span class="glyphicon glyphicon-screenshot"></span> <?php echo $resx['project_window_update_coordinates']; ?></a></li>
          <li><a href="#" id="project-info-modal-zoom"><span class="glyphicon glyphicon-zoom-in"></span> <?php echo $resx['project_window_zoom']; ?></a></li>
<!--          <li><a href="#" id="project-info-modal-edit-boundary"><span class="glyphicon glyphicon-pencil"></span> <?php //echo $resx['project_window_edit_boundary']; ?></a></li>-->
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary confirmbuttons modal-update"><?php echo $resx['project_window_edit_update']; ?></button>
        <button type="button" class="btn btn-default confirmbuttons" data-dismiss="modal"><?php echo $resx['project_window_edit_cancel']; ?></button>
      </div>

    </div>
  </div>
</div>
<!--Promt box-->