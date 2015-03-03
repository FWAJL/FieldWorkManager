/**
 * IMPORTANT NOTICE (29-12-14):
 *   LOOK AT analyte_manager for the new implementation
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *
 * jQuery listeners for the task actions
 */

  
  //************************************************//
  
  //Adding task through promptbox from anywhere
  if($('#promptmsg-addNullCheckAddPrompt').length !== 0)
  {
	  var post_data = {};
	  utils.showPromptBox("addNullCheckAddPrompt", function(){
		if($('#text_input').val() !== '')
		{
		  //Check unique
		  task_manager.ifTaskExists($('#text_input').val(), function(record_count) {
			if(record_count == 0)
			{
			  //Ok to add
			  post_data["task"] = {};
			  post_data["task"]["task_name"] = $('#text_input').val();
			  task_manager.add(post_data, "task", "add");
			}
			else
			{
			  //Show alert, that task is already taken, choose new
			  utils.togglePromptBox();
			  utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function(){
				  utils.togglePromptBox();
			  });
			}
		  });
		}
		else
		{
		  $('#text_input').focus();
		}
	  }, "promptmsg-addNullCheckAddPrompt", function(){
		utils.redirect("task/listAll");  
	  });
	  
  }

//  if (utils.getQueryVariable("mode") === "edit") {
//    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
//    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
//    $(".task_add").hide();
//    task_manager.getItem(utils.getQueryVariable("task_id"));
//  }//Load task
//
//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    task_manager.fillFormWithRandomData();
//  }

/***********
 * task_manager namespace
 * Responsible to manage tasks.
 */
(function (task_manager) {	
 //To keep the original msg from the hidden intact
 task_manager.prompt_box_msg;	

  task_manager.retrieveTask = function (element) {
    utils.redirect("task/showForm?mode=edit&task_id=" + parseInt(element.attr("data-task-id")));
  };
    task_manager.retrieveActiveTask = function (element) {
    utils.redirect("activetask/showForm?mode=edit&task_id=" + parseInt(element.attr("data-task-id")));
  };

}(window.task_manager = window.task_manager || {}));
