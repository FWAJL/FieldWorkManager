<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="map_legend">
 <ul>
    <li>
         <h3>LEGEND</h3>
    </li>
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
            <?php echo $resx["inactive_location"]; ?>
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
       <span class="map-info-icon-image">
           <div id="map-info-ruler-icon"></div>
<?php echo $resx["ruler"]; ?>
       </span>
     </li>
         <li>
       <span class="map-info-icon-image">
           <div id="map-info-add-icon"></div>
       </span>
       <span>
            <?php echo $resx["add-icon"]; ?>
       </span>
     </li>
         <li>
       <span class="map-info-icon-image">
       <div id="map-info-shape-icon"></div>
       </span>
       <span>
            <?php echo $resx["boundary"]; ?>
       </span>
     </li>
 </ul>
</div><!-- END RIGHT ASIDE MAIN -->