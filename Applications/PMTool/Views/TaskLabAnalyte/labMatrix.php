<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<div class="right-aside main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $current_task->task_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $resx["task_lab_analytes_header"] ?></h3>  
  	<div class="form_sections">
      <?php require $form_modules["task_tabs_open"]; ?> 
	  <div class="content-container container-fluid">
  		<div class="row">
		  <span class="h4"><?php echo $resx["task_lab_matrix_label"] ?></span>
			<span id="active-taskFieldAnalyte-header" class="glyphicon glyphicon-info-sign"></span>
		</div>
	  	<div class="row">
	      <div class="col-lg-10 col-md-10">
	      	<div class="matrix-container">
	    	  <div class="matrix-row">
	    	  	<div class="matrix-cell">&nbsp;</div><div class="matrix-alalyte-header matrix-cell"><?php echo $resx["tlm_analyte_label"] ?></div>
	    	  </div>
	    	  <div class="matrix-row">
	    	  	<div class="matrix-location-header matrix-cell"><?php echo $resx["tlm_locationhead_label"] ?></div>
			  	<?php
			  		if(!empty($task_lab_analytes)){
						foreach($task_lab_analytes as $analyte){
				  		?>
				  		<div class="matrix-cell matrix-cell-data"><?php echo $analyte->lab_analyte_name(); ?></div>
				  		<?php
						}	
			  		}
			  	?>
	    	  </div>
	    	  <?php
	    		if(!empty($task_locations)){
	    	  		foreach ($task_locations as $location) {
    		  		?>
    		    	<div class="matrix-row">
    		  	  		<div class="matrix-cell matrix-cell-data"><?php echo $location->location_name(); ?></div>
		    		  	<?php
					  	foreach($task_lab_analytes as $analyte){
					  		$id_pair = $location->location_id() . '_' . $analyte->lab_analyte_id();
					  		?>
					  		<div class="matrix-cell matrix-cell-data">
					  			<input type="checkbox" name="matrix_checkbox" class="matrix-checkbox" value="<?php echo $id_pair ?>"<?php if(in_array($id_pair, $task_lab_analytes_idmap)) echo "checked='checked'" ?> >
					  		</div>
					  	    <?php
					    }
					    ?>
    		  		</div>
    		  		<?php
	    	  		}	
	    		}
	    	  ?>
	    	  <div class="matrix-row">
	    	  	<div class="matrix-footer matrix-cell"><input type="checkbox" id="toggle_all"> <?php echo $resx["tlm_checkboxtoggle_label"] ?></div>
	    	  	<div class="matrix-cell matrix-cell">
	    	  		<input type="button" value="<?php echo $resx["tlm_savebtn_label"] ?>" class="btn btn-default" id="btn_save_labmatrix" style="display: inline-block;">
	    	  	</div>
	    	  </div>
	      	</div>
	    </div>
	  </div>
	  <?php 
		require $form_modules["tabs_close"]; 
		require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]; 
	  ?>
	</div>
  </div>
</div>
      
