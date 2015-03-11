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
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        form_manager.retrieveResource(options.$trigger);
      } else if (key === "delete") {
        form_manager.delete(parseInt(options.$trigger.attr("data-form-id")));
      }
    },
    items: {
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  $("#btn-add-form-manual").click(function() {
    utils.redirect("form/listAll");
  });//Button click "add a form"

  $("#btn_add_form").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("form_form", ["form_name"]);
    if (post_data.form_name !== undefined) {
      form_manager.add(post_data, "form", "add", true);
    }
  });//Add a form

  $("#btn_delete_form").click(function() {
    ajaxParams.ajaxUrl = "form/delete";
    ajaxParams.itemId = parseInt(parseInt(utils.getQueryVariable("form_id")));
    datacx.delete(ajaxParams);
  });

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

  form_manager.delete = function(form_id) {
    datacx.post("form/delete", {"form_id": form_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("form/listAll");
      }
    });
  };

}(window.form_manager = window.form_manager || {}));
