<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<?php
/*if(!empty($task_field_analytes)){
	foreach($task_field_analytes as $analyte){

		//At this point separate the unit and the analyte name
		$splitted_analyte_name = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::splitAnalyteNameOnUnit($analyte->field_analyte_name_unit());
	}
}*/
?>
<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">
  <h3>
    <?php echo $current_project->project_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $current_task->task_name(); ?>
    <span class="glyphicon glyphicon-chevron-right"></span>    
    <?php echo $resx["tlm_parameter_label"] ?></h3>  
  	<div class="form_sections">
      <?php require $form_modules["task_tabs_open"]; ?> 
	  <div class="content-container container-fluid">
  		<div class="row col-no-right-margin">
		  <span class="h4"><?php echo $resx["task_field_matrix_label"] ?></span>
			<span id="active-taskFieldAnalyte-header" class="glyphicon glyphicon-info-sign"></span>
		</div>
  	<div class="row col-no-right-margin">
      <div class="col-lg-10 col-md-10">
      	<!-- Matrix congtainer starts -->
      	<div class="matrix-container">
    	  	<div class="matrix-row">
	    	  	<div class="matrix-cell">&nbsp;</div><div class="matrix-alalyte-header matrix-cell"><?php echo $resx["tlm_parameter_label"] ?></div>
	    	  </div>
	    	  <div class="matrix-scrollable-wrapper">
	    	  	<div class="matrix-fixed-locations">
	    	  		<div class="matrix-row">
	    	  			<div class="matrix-location-header matrix-cell">&nbsp;</div>
	    	  		</div>
	    	  		<div class="matrix-row">
	    	  			<div class="matrix-location-header matrix-cell"><?php echo $resx["tlm_locationhead_label"] ?></div>
	    	  		</div>
	    	  		<?php
	    				if(!empty($task_locations)){
	    	  			foreach ($task_locations as $location) {
	    	  				?>
	    	  				<div class="matrix-row">
		    		  	  	<div class="matrix-cell matrix-cell-data">
		    		  	  		<?php 
		    		  	  		\Applications\PMTool\Helpers\CommonHelper::generateEllipsisAndTooltipMarkupFor(
		                                $location->location_name(),
		                                $toolTips[Applications\PMTool\Resources\Enums\ViewVariables\Popup::ellipsis_tooltip_settings]['charlimit'], 
		                                $toolTips[Applications\PMTool\Resources\Enums\ViewVariables\Popup::ellipsis_tooltip_settings]['placement']);
		    		  	  		?>
		    		  	  	</div>
		    		  	  </div>
	    	  				<?php
	    	  			}
	    	  		}
    		  		?>
	    	  	</div>
	    	  	<div class="matrix-scrollable-window">
							
							<div class="matrix-row matrix-scroll-row">
								<?php
									$analyte_units = array();
						  		if(!empty($task_field_analytes)){
										foreach($task_field_analytes as $analyte){

											//At this point separate the unit and the analyte name
											$splitted_analyte_name = \Applications\PMTool\Helpers\TaskAnalyteMatrixHelper::splitAnalyteNameOnUnit($analyte->field_analyte_name_unit());

											$field_analyte_name = $splitted_analyte_name[0];
											//Store the unit into the array later use
											array_push($analyte_units, $splitted_analyte_name[1]);

								  		?>
								  		<div class="matrix-cell matrix-cell-data">
								  			<?php 
								  			\Applications\PMTool\Helpers\CommonHelper::generateEllipsisAndTooltipMarkupFor(
			                                        $field_analyte_name, 
			                                        $toolTips[Applications\PMTool\Resources\Enums\ViewVariables\Popup::ellipsis_tooltip_settings]['charlimit'], 
			                                        $toolTips[Applications\PMTool\Resources\Enums\ViewVariables\Popup::ellipsis_tooltip_settings]['placement']);
								  			?>
								  		</div>
								  		<?php
										}	
						  		}
						  	?>
					  	</div>
					  	<div></div>
					  	<!--The units if any  -->
							<div class="matrix-row matrix-scroll-row">
								<?php
									
						  		if(!empty($task_field_analytes)){
										foreach($analyte_units as $analyte_unit){
								  		?>
								  		<div class="matrix-cell matrix-cell-data">
								  			<?php 
								  			\Applications\PMTool\Helpers\CommonHelper::generateEllipsisAndTooltipMarkupFor(
			                                        $analyte_unit, 
			                                        $toolTips[Applications\PMTool\Resources\Enums\ViewVariables\Popup::ellipsis_tooltip_settings]['charlimit'], 
			                                        $toolTips[Applications\PMTool\Resources\Enums\ViewVariables\Popup::ellipsis_tooltip_settings]['placement']);
								  			?>
								  		</div>
								  		<?php
										}	
						  		}
						  	?>
					  	</div>
					  	<div></div>

	    	  		<?php
	    				if(!empty($task_locations)){
	    	  			foreach ($task_locations as $location) {
	    	  				?>
	    	  				<div class="matrix-row matrix-scroll-row">
		    		  	  	<?php
						  			foreach($task_field_analytes as $analyte){
						  				$id_pair = $location->location_id() . '_' . $analyte->field_analyte_id();
						  				?>
						  				<div class="matrix-cell matrix-cell-data">
						  					<input type="checkbox" name="matrix_checkbox" class="matrix-checkbox" value="<?php echo $id_pair ?>"<?php if(in_array($id_pair, $task_field_analytes_idmap)) echo "checked='checked'" ?> >
						  				</div>
						  	    	<?php
						    		}
						    		?>
		    		  	  </div>
		    		  	  <div></div>
	    	  				<?php
	    	  			}
	    	  		}
    		  		?>
	    	  	</div>
	    	  </div>
	    	  <div class="matrix-row">
	    	  	<div class="matrix-footer matrix-cell"><input type="checkbox" id="toggle_all"> <?php echo $resx["tlm_checkboxtoggle_label"] ?></div>
	    	  	<div class="matrix-cell"  style="margin-top:10px;">
	    	  		<input type="button" value="<?php echo $resx["tlm_savebtn_label"] ?>" class="btn btn-default" id="btn_save_fieldmatrix" style="display: inline-block;">
	    	  	</div>
	    	  </div>
      	</div>
      	<!-- Matrix container Ends -->
    	</div>
	  </div>
	  <?php 
		require $form_modules["tabs_close"]; 
		require $form_modules[\Applications\PMTool\Resources\Enums\PhpModuleKeys::tooltip_msg]; 
		require $form_modules[Applications\PMTool\Resources\Enums\ViewVariables\Task::task_analyte_matrix_switch];
	  ?>
	</div>
  </div>
</div>
      
