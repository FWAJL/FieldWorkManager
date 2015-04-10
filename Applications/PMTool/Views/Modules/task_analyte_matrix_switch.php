<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<?php
	if(isset($task_show_lab_matrix) && $task_show_lab_matrix === true){
		?>
		<input type="hidden" id="show_lab_matrix_tab" value="1">
		<?php
	}
	if(isset($task_show_field_matrix) && $task_show_field_matrix === true){
		?>
		<input type="hidden" id="show_field_matrix_tab" value="1">
		<?php
	}

	//Status of other task tabs
	if($task_tab_status_keys[Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_service] == 1){
		?>
		<input type="hidden" id="tab7_status" value="1">
		<?php
	}

	if($task_tab_status_keys[Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_form] == 1){
		?>
		<input type="hidden" id="tab2_status" value="1">
		<?php
	}

	if($task_tab_status_keys[Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_field_analyte] == 1){
		?>
		<input type="hidden" id="tab3_status" value="1">
		<?php
	}

	if($task_tab_status_keys[Applications\PMTool\Resources\Enums\ViewVariables\Task::task_req_lab_analyte] == 1){
		?>
		<input type="hidden" id="tab5_status" value="1">
		<?php
	}
?>