$(document).ready(function() {
    
    $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "view") {
        location_manager.retrieveLocation(options.$trigger);
    } 
    },
    items: {
      "view": {name: "View"}
    }
      });//Manages the context menu
    
  $(".btn-warning").hide();


  //************************************************//
  // Selection of task technicians
  var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-taskform-id",
    "dataAttrRight": "data-taskform-id",
    "dataObjAttrLeft": "data-object",
    "dataObjAttrRight": "data-object"
  };
  utils.dualListSelection(selectionParams);

  $(".from-categorized-list-right").click(function() {
    var formIds = utils.getValuesFromListGroupedByObject(selectionParams.listRightId, selectionParams.dataAttrRight, selectionParams.dataObjAttrLeft, true);
    if(typeof(formIds.user_form) === 'undefined') {
      formIds.user_form = "";
    }
    if(typeof(formIds.master_form) === 'undefined') {
      formIds.master_form = "";
    }
    taskform_manager.updateTaskForms("add", formIds.master_form, formIds.user_form);
  });
  $(".from-categorized-list-left").click(function() {
    var formIds = utils.getValuesFromListGroupedByObject(selectionParams.listLeftId, selectionParams.dataAttrLeft, selectionParams.dataObjAttrRight, true);
    if(typeof(formIds.user_form) === 'undefined') {
      formIds.user_form = "";
    }
    if(typeof(formIds.master_form) === 'undefined') {
      formIds.master_form = "";
    }
    taskform_manager.updateTaskForms("remove", formIds.master_form, formIds.user_form);
  });

  //************************************************//
});

(function(taskform_manager) {

  taskform_manager.updateTaskForms = function(action, masterFormIds, userFormIds) {
    datacx.post("task/form/updateItems", {"action": action, "masterFormIds": masterFormIds, "userFormIds": userFormIds}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/forms");
      }
    });
  };

}(window.taskform_manager = window.taskform_manager || {}));