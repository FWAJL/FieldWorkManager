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

?>