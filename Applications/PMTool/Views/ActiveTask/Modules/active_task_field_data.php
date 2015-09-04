<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); 
  //\Applications\PMTool\Helpers\CommonHelper::pr($form_modules);
?>
<!--<div class="right-aside col-no-right-pad main col-lg-10 col-md-10 col-sm-10">-->
<div class="content-container container-fluid">
	<div class="row col-no-right-margin">
		<span class="h4"><?php echo $resx["at_fdm_h4_label"] ?></span>
		<span id="active-taskFieldAnalyte-header" class="glyphicon glyphicon-info-sign"></span>
	</div>	
	<div class="row col-no-right-margin">
		<div class="col-lg-10 col-md-10">
			
			<!-- Matrix Container -->
			<div class="matrix-container">	 
				<div class="matrix-row">
					<div class="matrix-cell">&nbsp;</div>
					<div class="matrix-alalyte-header matrix-cell"><?php echo $resx["at_fdm_analyte_heading"] ?></div>
				</div>

				<div class="matrix-scrollable-wrapper">

					<div class="matrix-fixed-locations">
    	  		<div class="matrix-row">
    	  			<div class="matrix-location-header matrix-cell">&nbsp;</div>
    	  		</div>
    	  		<div class="matrix-row">
    	  			<div class="matrix-location-header matrix-cell"><?php echo $resx["at_fdm_location_heading"] ?></div>
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
						if(!empty($task_locations) && is_array($task_locations)){
				  		foreach ($task_locations as $location) {
				  		?>
				    	<div class="matrix-row matrix-scroll-row">
						  	<?php
					  			foreach($task_field_analytes as $analyte){
					  				$id_pair = $location->location_id() . '_' . $analyte->field_analyte_id();
					  				?>
					  				<div class="matrix-cell matrix-cell-data">
					  					<?php					  					
					  						$key = array_search($id_pair, $task_field_analytes_idmap['id_map']);
					  						if($key !== false) {
					  							?>
					  							<input disabled type="text" name="field_data_result_<?php echo $id_pair ?>" value="<?php echo $task_field_analytes_idmap['result_map'][$key] ?>" style="width:90%;" placeholder="<?php echo $resx["not_yet_sampled"] ?>">
					  							<?php
					  						} else {
					  							echo $resx["do_not_sample_label"];
					  						}
					  					
					  					?>
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

			</div>
			<!--End of Matrix container -->

		</div>
	</div>
</div>