/**
 * IMPORTANT NOTICE (29-12-14):
 *   LOOK AT analyte_manager for the new implementation
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *
 * jQuery listeners for the task actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '#inactive-list .select_item',
    callback: function(key, options) {
      if (key === "edit") {
        task_manager.retrieveTask(options.$trigger);
      } else if (key === "set") {
        task_manager.set(options.$trigger);
      }
    },
    items: {
      "edit": {name: "Edit"},
      "set": {name: "Select (as current Task)"}
    }
  });//Manages the context menu for inactive Tasks

  //ol-li selection patch
  if ($('#active-list').length !== 0) {
    $('#active-list li').click(function() {
      $('#active-list li').removeClass('ui-selected');
      $(this).addClass('ui-selected');
    });
  }

  //Auto open prompt when on selectTask view
  utils.showSelectEntityPrompt(
    function() {
      //utils.redirect($("#redirectOnSuccess").val());
      if ($(".ui-selected").html() !== undefined)
      {
        //task_manager.set($(".ui-selected"));
        utils.redirect("task/listAll?task_id=" + parseInt($(".ui-selected").attr("data-task-id")) + "&onSuccess=" + $("#redirectOnSuccess").val());
      }
      else
      {
        $("#active-list").focus();
      }
    },
    function() {
      utils.redirect("task/listAll");
    }
  );


  $(".btn-warning").hide();
  $.contextMenu({
    selector: '#active-list .select_item',
    callback: function(key, options) {
      if (key === "monitor") {
        task_manager.retrieveActiveTask(options.$trigger);
      } else if (key === "set") {
        task_manager.set(options.$trigger);
      }
    },
    items: {
      "monitor": {name: "Check Task Status"},
      "set": {name: "Select (as current Task)"}
    }
  });//Manages the context menu for active Tasks

  //************************************************//

  //Adding task through promptbox from anywhere
  if ($('#promptmsg-addNullCheckAddPrompt').length !== 0)
  {
    var post_data = {};
    utils.showPromptBox("addNullCheckAddPrompt", function() {
      if ($('#text_input').val() !== '')
      {
        //Check unique
        task_manager.ifTaskExists($('#text_input').val(), function(record_count) {
          if (record_count == 0)
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
            utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function() {
              utils.togglePromptBox();
            });
          }
        });
      }
      else
      {
        $('#text_input').focus();
      }
    }, "promptmsg-addNullCheckAddPrompt", function() {
      utils.redirect("task/listAll");
    });

  }

//  // If only one Active Task - auto set to current Task
//  $("#active-list .select_item").ready(function () {
//   if($("#active-list li").length === 1) {
//   if ($(".noCT").length ) { 
//       onetaskid = (parseInt($(".select_item").attr("data-task-id")));
//     utils.redirect("task/listAll?task_id=" + onetaskid);   
//   }
//   }
//});

  // Selection of tasks for de-activation
  var task_ids = "";
  $("#active-list .select_item, #inactive-list .select_item").click(function() {
    $(this).siblings().removeClass("ui-selected");
    $(this).addClass("ui-selected");
    task_ids = $(this).attr("data-task-id");
    $(".from-" + $(this).parent().attr("id")).show();
  });
  $(".from-active-list").click(function() {
    task_manager.updateTasks("inactive", task_ids);
  });
  $(".from-inactive-list").click(function() {
    var msg = $('#confirmmsg-activate').val();
    if (typeof msg !== typeof undefined && msg !== false) {
      utils.showConfirmBox(msg, function(result) {
        if (result)
        {
          task_manager.updateTasks("active", task_ids);
        }
      });
    }
    else
    {
      task_manager.updateTasks("active", task_ids);
    }
  });

  $("#btn_add_task").click(function() {
    var post_data = {};
    //post_data["task"] = utils.retrieveInputs("task_form", ["task_name"]);
    post_data["task"] = utils.retrieveInputs("task_form", []);
    //check if task name is not empty
    if (post_data["task"].task_name !== undefined && post_data["task"].task_name !== '') {
      //check if unique
      task_manager.ifTaskExists(post_data["task"].task_name, function(record_count) {
        if (record_count == 0)
        {
          //Ok to add
          task_manager.add(post_data, "task", "add");
        }
        else
        {
          //Show alert, that task is already taken, choose new
          utils.showAlert($('#confirmmsg-addUniqueCheck').val());
        }
      });
    }
    else
    {
      utils.showPromptBox("addNullCheck", function() {
        if ($('#text_input').val() !== '')
        {
          //Check unique
          task_manager.ifTaskExists($('#text_input').val(), function(record_count) {
            if (record_count == 0)
            {
              //Ok to add
              post_data["task"].task_name = $('#text_input').val();
              task_manager.add(post_data, "task", "add");
            }
            else
            {
              //Show alert, that task is already taken, choose new
              utils.togglePromptBox();
              utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function() {
                utils.togglePromptBox();
              });
            }
          });
        }
        else
        {
          $('#text_input').focus();
        }
      });
    }
  });//Add a task

  $("#btn_edit_task").click(function() {
    var post_data = utils.retrieveInputs("task_form", ["task_name"]);
    if (post_data.task_name !== undefined) {
      task_manager.edit(post_data, "task", "edit");
    }
  });//Edit a task

  $("#btn_copy_task").click(function() {
    //alert(options.$trigger.html());
    if (task_manager.prompt_box_msg == null || task_manager.prompt_box_msg == '') {
      task_manager.prompt_box_msg = $('#promptmsg-addNullCheckForCopy').val();
    }

    $('#promptmsg-addNullCheckForCopy').val(task_manager.prompt_box_msg.replace('{0}', $('input[name=task_name]').val()));
    utils.showPromptBox("addNullCheck", function() {
      if ($('#text_input').val() !== '')
      {
        //Check unique
        task_manager.ifTaskExists($('#text_input').val(), function(record_count) {
          if (record_count == 0)
          {
            task_manager.getItemforCopy(parseInt(utils.getQueryVariable("task_id")), function(reply) {
              var post_data = {};
              post_data["task"] = reply.task.task_info_obj;
              //Remove some attributes
              delete(post_data["task"]['task_id']);
              //Set some new attributes
              post_data["task"]['task_name'] = $('#text_input').val();

              //Add
              task_manager.copyWithNewName(post_data, "task", "add");
            });
          }
          else
          {
            utils.togglePromptBox();
            var confirmMsg = $('#confirmmsg-addUniqueCheck').val().replace('{0}', $('#text_input').val());
            utils.showAlert(confirmMsg, function() {
              utils.togglePromptBox();
            });
          }
        });

      }
      else
      {
        $('#text_input').focus();
      }

    }, "promptmsg-addNullCheckForCopy");

  });//Copy a task

  $("#btn_delete_task").click(function() {
	if($('#confirmmsg-delete').length > 0) {
	  utils.showConfirmBox($('#confirmmsg-delete').val(), function(result) {
	    if (result)
	    {
		  task_manager.delete(parseInt(utils.getQueryVariable("task_id")));
		}
	  });
	}
	else
	{
	  task_manager.delete(parseInt(utils.getQueryVariable("task_id")));
	}
  });//Delete a task

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".task_add").hide();
    task_manager.getItem(utils.getQueryVariable("task_id"));
  }//Load task

  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
    task_manager.fillFormWithRandomData();
  }

  var alreadyHovered = false;
  $(".select_item").hover(function() {
    if (!alreadyHovered)
      toastr.info("Right-click to edit!");
    alreadyHovered = true;
  });//Show a task tip
});
/***********
 * task_manager namespace
 * Responsible to manage tasks.
 */
(function(task_manager) {
  //To keep the original msg from the hidden intact
  task_manager.prompt_box_msg;

  task_manager.add = function(data, controller, action) {
//      alert(data["task"] + ", " + controller + ", " + action);
    datacx.post(controller + "/" + action, data["task"]).then(function(reply) {//call AJAX method to call Task/Add WebService
      if (reply === null || reply.dataOut === undefined || reply.dataOut === null || parseInt(reply.dataOut) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/showForm?mode=edit&task_id=" + reply.dataOut, 1000);
      }
    });
  };

  task_manager.copyWithNewName = function(data, controller, action) {
    datacx.post(controller + "/" + action, data["task"]).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/showForm?mode=edit&task_id=" + reply.dataOut, 1000);
      }
    });
  };

  task_manager.edit = function(task, controller, action) {
    datacx.post(controller + "/" + action, task).then(function(reply) {//call AJAX method to call Task/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task", 1000);
      }
    });
  };

  task_manager.set = function(element) {
    utils.redirect("task/listAll?task_id=" + parseInt(element.attr("data-task-id")));
  };

  task_manager.getList = function() {
    datacx.post("task/getlist", null).then(function(reply) {//call AJAX method to call Task/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        task_manager.buildOutputList(reply.lists.tasks);
        //Now show the table
      }
    });
  };
  task_manager.buildOutputList = function(tasks) {
    var active_tasks = "";
    var inactive_tasks = "";
    for (i = 0; i < tasks.length; i++) {
      if (parseInt(tasks[i].active) !== 0) {
        active_tasks += "<option value=\"" + tasks[i].task_name + "\">" + tasks[i].task_name + "</option>";
      } else {
        inactive_tasks += "<option value=\"" + tasks[i].task_name + "\">" + tasks[i].task_name + "</option>";
      }
    }
    inactive_tasks = utils.isNullOrEmpty(inactive_tasks) ?
            "<option value=\"\">{empty}</option>" : inactive_tasks;
    active_tasks = utils.isNullOrEmpty(active_tasks) ?
            "<option value=\"\">{empty}</option>" : active_tasks;
    $("#task-data-active, #task-data-inactive").show();
    $("#task-data-active").html(active_tasks);
    $("#task-data-inactive").html(inactive_tasks);
  };
  task_manager.retrieveTask = function(element) {
    utils.redirect("task/showForm?mode=edit&task_id=" + parseInt(element.attr("data-task-id")));
  };
  task_manager.retrieveActiveTask = function(element) {
    utils.redirect("activetask/showForm?mode=edit&task_id=" + parseInt(element.attr("data-task-id")));
  };
  task_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"project_id\"]").val(parseInt(dataWs.task_info_obj.project_id));
    $("input[name=\"task_id\"]").val(parseInt(dataWs.task_info_obj.task_id));
    $("input[name=\"task_name\"]").val(dataWs.task_info_obj.task_name);
    $("input[name=\"task_deadline\"]").val(dataWs.task_info_obj.task_deadline);
    if(dataWs.task_info_obj.task_trigger_cal == 1) {
      $("input[name=\"task_trigger_cal\"]").attr('checked',true);
      if ($("input[name=\"task_trigger_cal\"]").is(":checked")) {
        $("#freq_list").show()
      }
    }
    $("select[name=\"task_trigger_cal_value\"]").val(dataWs.task_info_obj.task_trigger_cal_value)
    if(dataWs.task_info_obj.task_trigger_pm == 1) {
    $("input[name=\"task_trigger_pm\"]").attr('checked',true);
    }
    if(dataWs.task_info_obj.task_trigger_ext == 1) {
    $("input[name=\"task_trigger_ext\"]").attr('checked',true);
    }
    if(dataWs.task_info_obj.task_req_form == 1) {
      $("input[name=\"task_req_form\"]").attr('checked',true);
      //$("#tab2").show();
    }
    if(dataWs.task_info_obj.task_req_field_analyte == 1) {
      $("input[name=\"task_req_field_analyte\"]").attr('checked',true);
      //$("#tab2").show();
      //$("#tab3").show();
      //$("#tab3a").show();
      if($('#show_field_matrix_tab').length > 0) {
        if($('#show_field_matrix_tab').val() === '1') {
          //$("#tab3a").show();
        }
      }
    }
    if(dataWs.task_info_obj.task_req_lab_analyte == 1) {
      $("input[name=\"task_req_lab_analyte\"]").attr('checked',true);
      //$("#tab4").show();
      //$("#tab5").show();
      //$("#tab5a").show();
      if($('#show_lab_matrix_tab').length > 0) {
        if($('#show_lab_matrix_tab').val() === '1') {
          //$("#tab5a").show();
        }
      }
    }
    if(dataWs.task_info_obj.task_req_service == 1) {
      $("input[name=\"task_req_service\"]").attr('checked',true);
    }
    $("textarea[name=\"task_instructions\"]").val(dataWs.task_info_obj.task_instructions);
//    $("input[name=\"task_trigger_cal\"]").val(dataWs.task_info_obj.task_trigger_cal);
//    $("input[name=\"task_trigger_pm\"]").val(dataWs.task_info_obj.task_trigger_pm);
    $("input[name=\"task_active\"]").prop('checked', utils.setCheckBoxValue(dataWs.task_info_obj.task_active));
//    $("input[name=\"task_trigger_ext\"]").val(dataWs.task_info_obj.task_trigger_ext);
//    Other forms called here
  };
  task_manager.delete = function(task_id) {
    datacx.post("task/delete", {"task_id": task_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/listAll");
      }
    });
  };

  task_manager.getItem = function(task_id) {
    //get task object from cache (PHP WS)
    datacx.post("task/getItem", {"task_id": task_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("task/listAll", 3000)
      } else {//success
        $(".task_edit").show().removeClass("hide");
        toastr.success(reply.message);
        task_manager.loadEditForm(reply.task);
      }
    });
  };

  task_manager.getItemforCopy = function(task_id, executeCopy) {
    //get task object from cache (PHP WS)
    datacx.post("task/getItem", {"task_id": task_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        //return reply;
        executeCopy(reply);
      }
    });
  };

  task_manager.fillFormWithRandomData = function() {
//    utils.clearForm();
//    var number = Math.floor((Math.random() * 100) + 1);
//    $("input[name=\"task_name\"]").val("Task " + number);
//    $("input[name=\"task_deadline\"]").val(moment.today());
//    $("input[name=\"task_instructions\"]").val("TO DO");
  };

  task_manager.updateTasks = function(action, arrayId) {
    datacx.post("task/updateItems", {"action": action, "task_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/listAll");
      }
    });
  };

  task_manager.ifTaskExists = function(taskName, decision) {
    datacx.post("task/ifTaskExists", {task_name: taskName}).then(function(reply) {
      decision(reply.record_count);
    });
  };

}(window.task_manager = window.task_manager || {}));
