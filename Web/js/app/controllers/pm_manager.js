/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the pm actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_pm',
    callback: function(key, options) {
      if (key === "edit") {
        pm_manager.retrievePm(options.$trigger);
      } else if (key === "delete") {
        pm_manager.delete(parseInt(options.$trigger.attr("data-pm-id")));
      }
    },
    items: {
      "edit": {name: "View Info"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

//  $("#btn-add-pm-names").click(function() {
//    pm_manager.add($("textarea[name=\"pm_names\"]").val(), "pm", "add", false);
//  });//Add many pms

  $("#btn-add-pm-manual").click(function() {
    utils.redirect("pm/showForm?mode=add&test=true");
  });//Button click "add a pm"

  $("#btn_add_pm").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("pm_form", ["pm_name"]);
    if (post_data.pm_name !== undefined) {
      pm_manager.add(post_data, "pm", "add", true);
    }
  });//Add a pm

  $("#btn_edit_pm").click(function() {
    var post_data = utils.retrieveInputs("pm_form", ["pm_name"]);
    if (post_data.pm_name !== undefined) {
      pm_manager.edit(post_data, "pm", "edit");
    }
  });//Edit a pm

  $("#btn_delete_pm").click(function() {
    pm_manager.delete(parseInt(utils.getQueryVariable("pm_id")));
  });//Delete a pm

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".pm_add").hide();
    pm_manager.getItem(utils.getQueryVariable("pm_id"));
  }//Load pm

//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    pm_manager.fillFormWithRandomData();
//  }

  $("#view_pm_info").click(function() {
    var pm_id = $(this).attr("data-pm-id");
    utils.redirect("pm?mode=edit&pm_id="+pm_id);
  });//Show pm info

  $("#pm_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".pm_list").fadeIn('2000').removeClass("hide");
    pm_manager.getList();
  });//Show "List All" panel

});
/***********
 * pm_manager namespace 
 * Responsible to manage pms.
 */
(function(pm_manager) {
  pm_manager.add = function(userData, controller, action, isSingle) {
    var data = isSingle ? userData : {"names": userData};
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call Pm/Add WebService
      if (reply === null || reply.dataId === undefined || reply.dataId === null || parseInt(reply.dataId) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("pm/listAll", 1000);
      }
    });
  };
  pm_manager.edit = function(pm, controller, action) {
    datacx.post(controller + "/" + action, pm).then(function(reply) {//call AJAX method to call Pm/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("pm/listAll", 1000);
      }
    });
  };
  pm_manager.getList = function() {
    datacx.post("pm/getlist", null).then(function(reply) {//call AJAX method to call Pm/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        pm_manager.buildOutputList(reply.lists.pms);
        //Now show the table
      }
    });
  };
  pm_manager.buildOutputList = function(pms) {
    var active_pms = "";
    var inactive_pms = "";
    for (i = 0; i < pms.length; i++) {
      if (parseInt(pms[i].active) !== 0) {
        active_pms += "<option value=\"" + pms[i].pm_name + "\">" + pms[i].pm_name + "</option>";
      } else {
        inactive_pms += "<option value=\"" + pms[i].pm_name + "\">" + pms[i].pm_name + "</option>";
      }
    }
    inactive_pms = utils.isNullOrEmpty(inactive_pms) ?
            "<option value=\"\">{empty}</option>" : inactive_pms;
    active_pms = utils.isNullOrEmpty(active_pms) ?
            "<option value=\"\">{empty}</option>" : active_pms;
    $("#pm-data-active, #pm-data-inactive").show();
    $("#pm-data-active").html(active_pms);
    $("#pm-data-inactive").html(inactive_pms);
  };
  pm_manager.retrievePm = function(element) {
    utils.redirect("pm/showForm?mode=edit&pm_id=" + parseInt(element.attr("data-pm-id")));
  };
  pm_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"pm_id\"]").val(parseInt(dataWs.pm.pm_id));
    $("input[name=\"username\"]").val(dataWs.pm.username);
    $("input[name=\"password\"]").val(dataWs.pm.password);
    $("input[name=\"hint\"]").val(dataWs.pm.hint);
    $("input[name=\"pm_address\"]").val(dataWs.pm.pm_address);
    $("input[name=\"pm_comp_name\"]").val(dataWs.pm.pm_comp_name);
    $("input[name=\"pm_phone\"]").val(dataWs.pm.pm_phone);
    $("input[name=\"pm_email\"]").val(dataWs.pm.pm_email);
    $("input[name=\"pm_active\"]").val(dataWs.pm.pm_active);
  };
  pm_manager.delete = function(pm_id) {
    datacx.post("pm/delete", {"pm_id": pm_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("pm/listAll");
      }
    });
  };

  pm_manager.getItem = function(pm_id) {
    //get pm object from cache (PHP WS)
    datacx.post("pm/getItem", {"pm_id": pm_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        //utils.redirect("pm/listAll", 3000)
      } else {//success
        $(".pm_edit").show().removeClass("hide");
        toastr.success(reply.message);
        pm_manager.loadEditForm(reply);
      }
    });
  };

 /** pm_manager.fillFormWithRandomData = function() {
    utils.clearForm();
    var number = Math.floor((Math.random() * 100) + 1);
    $(".pm_form input[name=\"pm_name\"]").val("Pm " + number);
    $("input[name=\"pm_num\"]").val("n-" + number);
    $("input[name=\"pm_desc\"]").val("Description " + number);
    $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  };
*/
  pm_manager.updatePms = function(action, arrayId) {
    datacx.post("pm/updateItems", {"action": action, "pm_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("pm/listAll");
      }
    });
  };

}(window.pm_manager = window.pm_manager || {}));
