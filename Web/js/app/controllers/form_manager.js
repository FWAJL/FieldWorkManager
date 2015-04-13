/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web forms. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the form actions
 */
$(document).ready(function() {
    var ajaxParams = {
    "ajaxUrl": "form/getList",
    "redirectUrl": "form/listAll",
    "action": "",
    "arrayOfValues": "",
    "itemId": ""
  };
  Dropzone.autoDiscover = false;
  if($("#document-upload").length>0){
    var dropzone = new Dropzone("#document-upload");
    dropzone.on("success", function(event,res) {
      if(res.result == 0) {
        toastr.error(res.message);
        dropzone.removeAllFiles();
      } else {
        toastr.success(res.message);
        utils.redirect('form/listAll',300);
      }
    });
  }

  if($("#document-upload-master").length>0){
    var dropzone = new Dropzone("#document-upload-master");
    dropzone.on("success", function(event,res) {
      if(res.result == 0) {
        toastr.error(res.message);
        dropzone.removeAllFiles();
      } else {
        toastr.success(res.message);
        dropzone.removeAllFiles();
      }
    });
  }

  $(".btn-warning").hide();
  $.contextMenu({
    selector: 'li[data-object="user_form"]',
    callback: function(key, options) {
      if (key === "edit") {
        form_manager.retrieveResource(options.$trigger);
      } else if (key === "delete") {
        form_manager.delete(parseInt(options.$trigger.data("form-id")),'user_form');
      }
    },
    items: {
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  $("#show-form-form").on('click',function(){
    utils.redirect('form/showForm');
  });

  //************************************************//
  // Selection of forms
  /*var user_form_ids = "";
  var master_form_ids = "";
  $("#group-list-left, #group-list-right").selectable({
    stop: function() {
      var tmpUserFormSelection = "";
      var tmpMasterFormSelection = "";
      $(".ui-selected", this).each(function() {
        if($(this).data('object')=='user_form'){
          tmpUserFormSelection+= $(this).data("form-id") + ",";
        } else if($(this).data('object')=='master_form') {
          tmpMasterFormSelection+= $(this).data("form-id") + ",";
        }
      });
      tmpMasterFormSelection = utils.removeLastChar(tmpMasterFormSelection);
      tmpUserFormSelection = utils.removeLastChar(tmpUserFormSelection);
      if (tmpMasterFormSelection.length > 0 || tmpUserFormSelection.length > 0) {
        if(tmpMasterFormSelection.length > 0) {
          master_form_ids = tmpMasterFormSelection;
        } else {
          master_form_ids = "";
        }
        if(tmpUserFormSelection.length > 0) {
          user_form_ids = tmpUserFormSelection;
        } else {
          user_form_ids = "";
        }
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        user_form_ids = master_form_ids ="";
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  }); */

  var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-form-id",
    "dataAttrRight": "data-form-id",
    "dataObjAttrLeft": "data-object",
    "dataObjAttrRight": "data-object"
  };
  utils.dualListSelection(selectionParams);

  $(".from-categorized-list-right").click(function() {
    var ajaxParams = {};
    ajaxParams.action = "add";
    ajaxParams.arrayOfValues = utils.getValuesFromListGroupedByObject(selectionParams.listRightId, selectionParams.dataAttrRight, selectionParams.dataObjAttrRight, true);
    if(typeof(ajaxParams.arrayOfValues.user_form) === 'undefined') {
      ajaxParams.arrayOfValues.user_form = "";
    }
    if(typeof(ajaxParams.arrayOfValues.master_form) === 'undefined') {
      ajaxParams.arrayOfValues.master_form = "";
    }
    form_manager.updateProjectForms(ajaxParams.action, ajaxParams.arrayOfValues.master_form, ajaxParams.arrayOfValues.user_form);
    //datacx.updateItems(ajaxParams);
  });
  $(".from-categorized-list-left").click(function() {
    var ajaxParams = {};
    ajaxParams.action = "remove";
    ajaxParams.arrayOfValues = utils.getValuesFromListGroupedByObject(selectionParams.listLeftId, selectionParams.dataAttrLeft, selectionParams.dataObjAttrLeft, true);
    if(typeof(ajaxParams.arrayOfValues.user_form) === 'undefined') {
      ajaxParams.arrayOfValues.user_form = "";
    }
    if(typeof(ajaxParams.arrayOfValues.master_form) === 'undefined') {
      ajaxParams.arrayOfValues.master_form = "";
    }
    form_manager.updateProjectForms(ajaxParams.action, ajaxParams.arrayOfValues.master_form, ajaxParams.arrayOfValues.user_form);
    //datacx.updateItems(ajaxParams);
  });


  /*
  $(".from-group-list-right").click(function() {
    form_manager.updateProjectForms("add", master_form_ids, user_form_ids);
  });
  $(".from-group-list-left").click(function() {
    form_manager.updateProjectForms("remove", master_form_ids, user_form_ids);
  });*/
  //************************************************//

  $("#btn-add-form-manual").click(function() {
    utils.redirect("form/listAll");
  });//Button click "add a form"

  $("#btn_add_form").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("form_form", ["title"]);
    if (post_data.title !== undefined) {
      form_manager.add(post_data, "form", "add");
    }
  });//Add a form

  $("#btn_delete_form").click(function() {
    ajaxParams.ajaxUrl = "form/delete";
    ajaxParams.itemId = parseInt(parseInt(utils.getQueryVariable("form_id")));
    datacx.delete(ajaxParams);
  });

  $("#btn_edit_form").click(function() {
    var post_data = utils.retrieveInputs("form_form", ["title"]);
    if (post_data.task_name !== undefined) {
      form_manager.edit(post_data, "form", "edit");
    }
  });//Edit a task

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".form_add").hide();
    form_manager.getItem(utils.getQueryVariable("form_id"));
  }//Load form

//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    form_manager.fillFormWithRandomData();
//  }

  $("#form_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".form_list").fadeIn('2000').removeClass("hide");
    form_manager.getList();
  });//Show "List All" panel

});
/***********
 * form_manager namespace 
 * Responsible to manage forms.
 */
(function(form_manager) {
  form_manager.add = function(data, controller, action) {
 
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call Resource/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("form/listAll", 1000);
      }
    });
  };

  form_manager.getList = function() {
    datacx.post("form/getlist", null).then(function(reply) {//call AJAX method to call Resource/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        form_manager.buildOutputList(reply.lists.forms);
        //Now show the table
      }
    });
  };

  form_manager.getItem = function(form_id) {
    //get task object from cache (PHP WS)
    datacx.post("form/getItem", {"form_id": form_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("form/listAll", 3000)
      } else {//success
        $(".form_edit").show().removeClass("hide");
        toastr.success(reply.message);
        form_manager.loadEditForm(reply.task);
      }
    });
  };

  form_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"form_id\"]").val(parseInt(dataWs.form_info_obj.form_id));
    $("input[name=\"title\"]").val(parseInt(dataWs.task_info_obj.title));
  };

//  form_manager.buildOutputList = function(forms) {
//    var active_forms = "";
//    var inactive_forms = "";
//    for (i = 0; i < forms.length; i++) {
//      if (parseInt(forms[i].active) !== 0) {
//        active_forms += "<option value=\"" + forms[i].form_name + "\">" + forms[i].form_name + "</option>";
//      } else {
//        inactive_forms += "<option value=\"" + forms[i].form_name + "\">" + forms[i].form_name + "</option>";
//      }
//    }
//    inactive_forms = utils.isNullOrEmpty(inactive_forms) ?
//            "<option value=\"\">{empty}</option>" : inactive_forms;
//    active_forms = utils.isNullOrEmpty(active_forms) ?
//            "<option value=\"\">{empty}</option>" : active_forms;
//    $("#form-data-active, #form-data-inactive").show();
//    $("#form-data-active").html(active_forms);
//    $("#form-data-inactive").html(inactive_forms);
//  };

  form_manager.delete = function(form_id, formType) {
    datacx.post("form/delete", {"form_id": form_id,"form_type": formType}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("form/listAll");
      }
    });
  };

  form_manager.updateProjectForms = function(action, masterFormIds, userFormIds) {
    datacx.post("form/updateItems", {"action": action, "masterFormIds": masterFormIds, "userFormIds": userFormIds}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("form/listAll");
      }
    });
  };

  form_manager.edit = function(task, controller, action) {
    datacx.post(controller + "/" + action, task).then(function(reply) {//call AJAX method to call Task/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("form/listAll", 1000);
      }
    });
  };

}(window.form_manager = window.form_manager || {}));
