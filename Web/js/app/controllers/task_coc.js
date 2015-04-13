$(document).ready(function(){
  var task_id = parseInt(utils.getQueryVariable("task_id"));
  
  //Fetch and show taskCoc
  task_coc.getTaskCoc(task_id);

  $('#btn_save_taskcoc').click(function(){
  	$('[name="task_id"]').val(task_id);
	var post_data = utils.retrieveInputs(
      "task_coc_form", 
      [
        "po_number",
        "lab_instructions",
        "service_id",
        "lab_sample_type",
        "lab_sample_tat",
        "project_number",
        "results_to_name",
        "results_to_company",
        "results_to_address",
        "results_to_phone",
        "results_to_email"
      ]
    );
    if (post_data.po_number !== undefined) {
      if(task_coc.editing) {
  		task_coc.editCoc(post_data, "task", "editCoc");
  	  }
  	  else{
  	    task_coc.addCoc(post_data, "task", "addCoc");	
  	  }
    }
  });
});

/***********
 * task_manager namespace
 * Responsible to manage tasks.
 */
(function(task_coc) {
  task_coc.editing = false;
  
  task_coc.getTaskCoc = function(task_id) {
    //get task coc object
    datacx.post("task/getTaskCoc", {"task_id": task_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
        //$(".form_sections").hide();
        //utils.redirect("task/listAll", 3000)
      } else {//success
        //$(".task_edit").show().removeClass("hide");
        toastr.success(reply.message);
        if(reply.task_coc === '') {
          task_coc.editing = false;	
        }
        else {
          task_coc.loadData(reply);
          task_coc.editing = true;
        }
      }
    });
  };
  
  task_coc.addCoc = function(taskcoc, controller, action){
	datacx.post(controller + "/" + action, taskcoc).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/coc?mode=edit&task_id=" + taskcoc.task_id, 1000);
      }
    });
  };
  
  task_coc.editCoc = function(taskcoc, controller, action){
	datacx.post(controller + "/" + action, taskcoc).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/coc?mode=edit&task_id=" + taskcoc.task_id, 1000);
      }
    });
  };
  
  task_coc.loadData = function(dataWs) {
  	utils.clearForm();
  	$("input[name=\"task_coc_id\"]").val(parseInt(dataWs.task_coc.task_coc_id));
  	$("input[name=\"po_number\"]").val(dataWs.task_coc.po_number);
  	$("textarea[name=\"lab_instructions\"]").val(dataWs.task_coc.lab_instructions);
  	$(".coc_list_services option:eq(" + dataWs.task_coc.service_id + ")").prop('selected', true)
  	//$("select[name=\"lab_sample_type\"] option:eq(" + dataWs.task_coc.lab_sample_type + ")").prop('selected', true)
  	//$("select[name=\"lab_sample_tat\"] option:eq(" + dataWs.task_coc.lab_sample_tat + ")").prop('selected', true)
  	$("select[name=\"lab_sample_type\"]").val(dataWs.task_coc.lab_sample_type);
  	$("select[name=\"lab_sample_tat\"]").val(dataWs.task_coc.lab_sample_tat);
  	$("input[name=\"project_number\"]").val(dataWs.task_coc.project_number);
  	$("input[name=\"results_to_name\"]").val(dataWs.task_coc.results_to_name);
  	$("input[name=\"results_to_company\"]").val(dataWs.task_coc.results_to_company);
  	$("textarea[name=\"results_to_address\"]").val(dataWs.task_coc.results_to_address);
  	$("input[name=\"results_to_phone\"]").val(dataWs.task_coc.results_to_phone);
  	$("input[name=\"results_to_email\"]").val(dataWs.task_coc.results_to_email);
  }
  
}(window.task_coc = window.task_coc || {}));