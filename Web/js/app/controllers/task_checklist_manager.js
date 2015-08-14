/**
 * jQuery listeners for the task_checklist_manager actions
 */
$(document).ready(function(){

	// Selection in the dual lists
	//Pending for the time being
	/*
  var selectionParamsCheckLists = {
    "listRightId": "task-check-list-list",
    "dataAttrRight": "data-task_check_list-id"
  };
  utils.dualListSelection(selectionParamsCheckLists);
  */

  $.contextMenu({
    selector: '#task-check-list-list>li',
    callback: function(key, options) {
    	var data_cl_id = options.$trigger.attr("data-task_check_list-id");
    	
      if (key === "edit") {
      	//$('#promptmsg-edit').val('Butum bollo Kutum ke');
        $('#text_input').val(options.$trigger.text());
        utils.showPromptBox("edit", function() {
          //check if existing
          datacx.post('task/isCheckListExisting', {'checklist_detail': $('#text_input').val()}).then(function(reply) {//call AJAX method to call Project/Add WebService
            if (reply === null || reply.result === 0) {//has an error

              utils.togglePromptBox();
              utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function(){
                utils.togglePromptBox();
              });
              
            } else {//success
              
              
              //Edit the common lab analyte
              datacx.post('task/editCheckList', {'check_list_id': data_cl_id, 'checklist_detail': $('#text_input').val()}).then(function(reply) {
                if (reply === null || reply.result === 0) {//has an error
                  //toastr.error(reply.message);
                } else {//success
                  //toastr.success(reply.message);
                  window.location.reload();
                }      
              });
              
            }
          });
        }, 'promptmsg-edit');

      } else if (key === "delete") {
      	var msg = $('#confirmmsg-deleteCheckList').val();
        if (typeof msg !== typeof undefined && msg !== false) {
          utils.showConfirmBox(msg, function(result) {
            if (result){
							datacx.post('task/delCheckList', {'check_list_id': data_cl_id}).then(function(reply) {//call AJAX method to call Location/Add WebService
					      if (reply === null || reply.result === 0) {//has an error
					        //toastr.error(reply.message);
					      } else {//success
					        //toastr.success(reply.message);
					    		window.location.reload();
					      }
					    });            	
            }
          });
        }
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });


	$('.btn-add-checklist').click(function(){

		var data = {
			'checklists': $( "textarea[name='task_check_list_detail']" ).val()
		};

		//ajaxParams.arrayOfValues = utils.getValuesFromTextArea(getValuesParams);

		datacx.post('task/addCheckList', data).then(function(reply) {//call AJAX method to call Location/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
      } else {//success
        //toastr.success(reply.message);
    		window.location.reload();
      }
    });
	});
});
