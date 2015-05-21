<!--Prompt box: genrates a project info prompt box-->
<div class="modal fade prompt-modal" id="location-info-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="location-window-title"><span id="location-window-title-project_name"><?php echo isset($current_project)?$current_project->project_name():''; ?></span><span class="glyphicon glyphicon-chevron-right"></span><span id="location-window-title-location_name"></span></h4>
      </div>

      <div class="modal-body">
          <li><label for="location-info-modal-location_name"><?php echo $resx['location_window_location_name']; ?></label>
              <input class="" type="text" id="location-info-modal-location_name" name="location_name"></li>
          <li><label for="location-info-modal-location_desc"><?php echo $resx['location_window_location_desc']; ?></label>
              <textarea class="" type="text" id="location-info-modal-location_desc" name="location_desc"></textarea></li>
          <li><label for="location-info-modal-location_lat"><?php echo $resx['location_window_location_lat']; ?></label>
              <input class="" type="text" id="location-info-modal-location_lat" name="location_lat"></li>
          <li><label for="location-info-modal-location_long"><?php echo $resx['location_window_location_long']; ?></label>
          <input class="" type="text" id="location-info-modal-location_long" name="location_long"><li>
          <li><a href="#" id="location-info-modal-zoom"><span class="glyphicon glyphicon-zoom-in"></span> <?php echo $resx['location_window_zoom']; ?></a></li>
          <li><a href="#" id="location-info-modal-place"><span class="glyphicon glyphicon-screenshot"></span> <?php echo $resx['location_place_onto_the_map']; ?></a></li>
          <li><a href="#" id="location-info-modal-photos"><span class="glyphicon glyphicon-camera"></span> <?php echo $resx['location_window_photos']; ?><span id="location-info-modal-photos-count"></span></a></li>
          <br>
        <?php require $form_modules["upload_file"]; ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary confirmbuttons modal-update"><?php echo $resx['location_window_edit_update']; ?></button>
        <button type="button" class="btn btn-default confirmbuttons" data-dismiss="modal"><?php echo $resx['location_window_edit_cancel']; ?></button>
      </div>

    </div>
  </div>
</div>
<div class="lightbox-content"></div>
<!--Promt box-->