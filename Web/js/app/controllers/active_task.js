/**
 * IMPORTANT NOTICE (29-12-14):
 *   LOOK AT analyte_manager for the new implementation
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *
 * jQuery listeners for the task actions
 */

  
//************************************************//
var task_technician_ids = "";

$(document).ready(function(){
	
  //active task sub module specific UI cleanups
  if($('#modforjs').length > 0) {
    switch($('#modforjs').val()) {
      case 'taskstatus':
        $('.at').hide();
		    $('#btn_refreshnotes').show();
		    $('#task_status_notes').css('height', '150px');
		
    		//load task notes
    		activetask_manager.getNotes('activetask', 'getNotes', function(reply){
    		  if(reply.notes.length > 0) {
      			for(i in reply.notes) {
      			  var classStr = (i % 2 == 0) ? 'note-user1' : 'note-user2';
       			  var str = '<div class="msg-row"><div class="' + classStr + '">' + reply.users[i] + ': </div><div class="user-msg">' + reply.notes[i].task_note_value + '</div></div>';
      			  $('#messages').append(str);
      			}
    		  }
    		});
        break;
      case 'taskforms':
        //Item selection for documents
        $("#group-list-left, #group-list-right").selectable({
          stop: function() {
            var tmpSelection = "";
            $(".ui-selected", this).each(function() {
              tmpSelection += $(this).attr("data-tasklocation-id") + ",";
            });
            tmpSelection = utils.removeLastChar(tmpSelection);
            if (tmpSelection.length > 0) {
              task_location_ids = tmpSelection;
              //Show the button to appropriate button
              $(".from-" + $(this).attr("id")).show();
            } else {
              task_location_ids = [];
              $(".from-" + $(this).attr("id")).hide();
            }
          }
        });

        //Setup contextual menu
        $.contextMenu({
          selector: '.select_item',
          callback: function(key, options) {
            if (key === "view") {
              datacx.post("form/getPdfFileFor", {"form_id": parseInt(options.$trigger.attr("data-activetask-id")), "form_type": options.$trigger.attr("data-object")}).then(function(reply) {
                
                if (reply === null || reply.result === 0) {//has an error
                  toastr.error(reply.message);
                  
                } else {//success
                  toastr.success(reply.message);
                  $.fancybox({ 
                    href: reply.form_path,
                    type: 'iframe', 
                    openEffect : 'none', 
                    closeEffect : 'none', 
                    iframe : { preload: false } 
                  });
                }
                
              });
            } 
          },
          items: {
            "view": {name: "View"}
          }
        });//Manages the context menu

        break;
      case 'taskcomm':
        if($('#categorized-list-left').length > 0) {
          $('#categorized-list-left').css('height', '200');
        }
        if($('#group-list-left').length > 0) {
          $('#group-list-left').css('height', '200');
        }
        Dropzone.autoDiscover = false;
        $("input[name=\"itemCategory\"]").val('discussion_id');
        $("#document-upload").hide();
        activetask_manager.getThread();
        break;
      default:
        //nothing
    }	 
  }
  
  
  //************************************************//
  // Selection of task services
  var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-activetask-id",
    "dataAttrRight": "data-activetask-id"
  };
  activetask_manager.dualListSelection(selectionParams);
  //************************************************//
  
  
  //************************************************//
  // Selection of task technicians
  $("#group-list-left").selectable({
    stop: function() {
	  //unselect other list
	  $('#categorized-list-left .ui-selected').removeClass('ui-selected');
	  $(".from-group-list-right").hide();
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-activetask-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        task_technician_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-group-list-right").show();
      } else {
        task_technician_ids = [];
        $(".from-group-list-right").hide();
      }
    }
  });
  //************************************************//
  
  $('.from-group-list-right').click(function(){
  	var id = utils.getValuesFromList(selectionParams.listLeftId, selectionParams.dataAttrLeft, true);
  	var selection_type = '';
      if(id !== '') {
  	  selection_type = 'service_id';
  	}
  	else {
  	  id = task_technician_ids;
  	  selection_type = 'technician_id';
  	}
  	
  	activetask_manager.updateTaskTechnicians(selection_type, id);
  });
  
  //at status note onfocus
  $('#task_status_notes').focus(function(){
    $('#btn_savenotes').show();
  });
  
  $('#task_status_notes').blur(function(){
  	if($('#task_status_notes').val() == ''){
  		$('#btn_savenotes').hide();
  	}
  });
  
  $('#btn_savenotes').click(function(){
  	activetask_manager.postNote($('#task_status_notes').val(), 'activetask', 'postNote', function(){
  	  $('#task_status_notes').val('');
  	  $('#task_status_notes').focus();
  	});
  });
  
  //refresh notes
  $('#btn_refreshnotes').click(function(){
    //load task notes
    activetask_manager.getNotes('activetask', 'getNotes', function(reply){
      if(reply.notes.length > 0) {
        $('#messages').html('');
        for(i in reply.notes) {
          var classStr = (i % 2 == 0) ? 'note-user1' : 'note-user2';
          var str = '<div class="msg-row"><div class="' + classStr + '">' + reply.users[i] + ': </div><div class="user-msg">' + reply.notes[i].task_note_value + '</div></div>';
		      $('#messages').append(str);
		    }
      }
    });
  });
  $("#btn_send_message").on('click',function() {
    var msg = $("textarea[name=\"task_comm_message\"]").val();
    if(msg.trim() != '') {
      activetask_manager.sendMessage(msg.trim());
    }
  });
});  


/***********
 * task_manager namespace
 * Responsible to manage tasks.
 */
(function (activetask_manager) {	
  //To keep the original msg from the hidden intact
  activetask_manager.prompt_box_msg;	

  activetask_manager.retrieveTask = function (element) {
    utils.redirect("task/showForm?mode=edit&task_id=" + parseInt(element.attr("data-task-id")));
  };
  activetask_manager.retrieveActiveTask = function (element) {
    utils.redirect("activetask/showForm?mode=edit&task_id=" + parseInt(element.attr("data-task-id")));
  };
  
  activetask_manager.dualListSelection = function(params) {
    var selector = activetask_manager.buildDualListSelector(params);
    if (selector === "") {
      print("<!-- No list set as selectable -->");
    } else {
      $(selector).selectable({
        stop: function() {
		  //unselect other list
		  $('#group-list-left .ui-selected').removeClass('ui-selected');
		  $(".from-group-list-right").hide();
		  task_technician_ids = '';
		  //continue with this selection
          var tmpSelection = "";
          $(".ui-selected", this).each(function() {
            if ($(this).attr(params.dataAttrLeft) !== undefined) {
              tmpSelection += $(this).attr(params.dataAttrLeft) + ",";
            } else {
              tmpSelection += $(this).attr(params.dataAttrRight) + ",";
            }
          });
          tmpSelection = utils.removeLastChar(tmpSelection);
          if (tmpSelection.length > 0) {
            $(".from-group-list-right").show();
          } else {
            $(".from-group-list-right").hide();
          }
        }
      });
    }
  };
  activetask_manager.buildDualListSelector = function(params) {
    var selector = params.listLeftId !== "" ? "#" + params.listLeftId : "";
    selector += (selector !== "") ?
      (params.listRightId !== "" ? ", #" + params.listRightId : "")
      :
      (params.listRightId !== "" ? "#" + params.listRightId : "");
    return selector;
  };
  
  activetask_manager.updateTaskTechnicians = function(selection_type, id) {
    datacx.post("activetask/startCommWith", {"selection_type": selection_type, "id": id}).then(function(reply) {
      console.log(reply);
	  if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("activetask/communications?task_id=" + utils.getQueryVariable("task_id"));
      }
    });
  };
  
  activetask_manager.postNote = function(note, controller, action, clbk){
	datacx.post(controller + "/" + action, {"note": note}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        clbk();
      }
    });
  };
  
  activetask_manager.getNotes = function(controller, action, clbk){
    datacx.post(controller + "/" + action, {/*empty JSON*/}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        clbk(reply);
      }
    });
  };

  activetask_manager.sendMessage = function(msg) {
    datacx.post('activetask/sendMessage',{discussion_content_message:msg}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        $("#task-comm-chatbox").prepend(activetask_manager.formatChatMessage(reply.data.user_name,reply.data.discussion_content_message,reply.data.discussion_content_time)+"<br/>");
        $("textarea[name=\"task_comm_message\"]").val('');
      }
    });
  };

  activetask_manager.getThread = function() {
    datacx.post('activetask/getDiscussionThread',{}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        $("input[name=\"itemId\"]").val(reply.discussion.discussion_id);
        if(reply.user_type == 'technician_id') {
          $("#file-attach").hide();
        }
        var dropzone = new Dropzone("#document-upload");
        dropzone.on("success", function(event,res) {
          if(res.result == 0) {
            toastr.error(res.message);
            dropzone.removeAllFiles();
          } else {
            toastr.success(res.message);
            $("#document-upload").hide();
            dropzone.removeAllFiles();
            $("textarea[name=\"task_comm_message\"]").val($("textarea[name=\"task_comm_message\"]").val()+"\n"+res.filepath);
          }
        });
        if(reply.thread !== undefined) {
          $.each(reply.thread, function(index, value) {
            $("#task-comm-chatbox").append(activetask_manager.formatChatMessage(value.user_name,value.discussion_content_message,value.discussion_content_time)+"<br/>");
          });
        }
        $("#group-list-right").selectable({
          stop: function() {
            var tmpSelection = "";
            $(".ui-selected", this).each(function() {
              tmpSelection += $(this).attr("data-document-id") + ",";
            });
            tmpSelection = utils.removeLastChar(tmpSelection);
          }
        });
        $("#btn_attach_file").on('click',function(e){
          if($("input[name=\"local-server\"]:checked").val()=='local') {
            $("#document-upload").show();
          } else {
            $("#document-upload").hide();
            $('#prompt_ok').off('click');
            utils.showSelectEntityPrompt(function(){
              if($("#group-list-right .ui-selected")!==undefined) {
                activetask_manager.loadPhoto($("#group-list-right .ui-selected").data('document-id'));
              }
            }, function(){});
          }
        });
      }
    });
  };

  activetask_manager.formatChatMessage = function(name,message,time) {
    var messageFormatted = '<strong>'+name+'</strong>: '+message+' <small>@'+time+'</small>';
    return messageFormatted;
  };

  activetask_manager.loadPhoto = function(id) {
    datacx.post("file/loadOne", {"id": id}).then(function(reply){
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        console.log(reply.filepath.length);
        if(reply.filepath.length>0){
          $("textarea[name=\"task_comm_message\"]").val($("textarea[name=\"task_comm_message\"]").val()+"\n"+reply.filepath);
        }
      }
      $('.pselector-modal').modal('hide');
    });
  }

}(window.activetask_manager = window.activetask_manager || {}));
