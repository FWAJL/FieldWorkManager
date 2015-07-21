<!--Promt box: genrates a prompt box for selecting project-->
<div class="modal fade maplegend-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-upload">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="prompt_title"><?php echo $resx["h4_legend_heading"]; ?></h4>
      </div>

      <div class="modal-body">
        <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/purple-stars_maps.png">
           </span>
           <span>
                <?php echo $resx["active_project"]; ?>
           </span>
         </li>
         <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/wht-stars_maps.png">
           </span>
           <span>
                <?php echo $resx["inactive_project"]; ?>
           </span>
         </li>
		 <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/blu-circle_maps.png">
           </span>
           <span>
                <?php echo $resx["active_location"]; ?>
           </span>
         </li>
         <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/measle_blue.png">
           </span>
           <span>
                <?php echo $resx["inactive_location"]; ?>
           </span>
         </li>
                       <li>
       <span class="map-info-icon-image">
           <img src="../Web/images/ltblu-blank_maps.png">
       </span>
       <span>
            <?php echo $resx["no_coords_location"]; ?>
       </span>
     </li>
         <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/red-circle_maps.png">
           </span>
           <span>
                <?php echo $resx["task_location_start"]; ?>
           </span>
         </li>   
         <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/yellow-dot_maps.png">
           </span>
           <span>
                <?php echo $resx["task_location_inprocess"]; ?>
           </span>
         </li>
         <li>
           <span class="map-info-icon-image">
               <img src="../Web/images/green-dot_maps.png">
           </span>
           <span>
                <?php echo $resx["task_location_complete"]; ?>
           </span>
         </li>
             <li>
           <span class="map-info-icon-image1">
<!--               <div id="map-info-ruler-icon"></div>-->
<img src="../Web/images/tmp_line_icon.png">
    			<?php echo $resx["ruler"]; ?>
           </span>
         </li>
<!--             <li>
           <span class="map-info-icon-image">
               <div id="map-info-add-icon"></div>
           </span>
           <span>
                <?php //echo $resx["add-icon"]; ?>
           </span>
         </li>-->
             <li>
           <span class="map-info-icon-image1">
<!--           <div id="map-info-shape-icon"></div>-->
<img src="../Web/images/tmp_area_icon.png">
           </span>
           <span>
                <?php echo $resx["boundary"]; ?>
           </span>
         </li>
      </div>
      <div class="modal-footer">
        <button type="button" id="mlalert_ok" class="btn btn-primary confirmbuttons" data-bb-handler="cancel"><?php echo $resx["popup_ok_button"] ?></button>
      </div>

    </div>

  </div>
</div>
<!--Promt box-->