/**
 * jQuery listeners for the analyte_manager actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        analyte_manager.retrieveFieldAnalyte(options.$trigger);
      } else if (key === "delete") {
        analyte_manager.delete(parseInt(options.$trigger.attr("data-fieldanalyte-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of analyte_managers
  var analyte_manager_ids = "";
  $("#active-list, #inactive-list").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-analyte_manager-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        analyte_manager_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        analyte_manager_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-inactive-list").click(function() {
    analyte_manager.updateFieldAnalytes("active", analyte_manager_ids);
  });
  $(".from-active-list").click(function() {
    analyte_manager.updateFieldAnalytes("inactive", analyte_manager_ids);
  });
  //************************************************//


  $("#btn-add-analyte_manager-names").click(function() {
    var data = { "names": $("textarea[name=\"analyte_manager_names\"]").val() };
    analyte_manager.add(data, "analyte_manager", "add");
  });//Add many analyte_managers

  $("#btn-add-analyte_manager-manual").click(function() {
    utils.redirect("analyte_manager/showForm?mode=add&test=true");
  });//Button click "add a analyte_manager"

  $("#btn_add_analyte_manager").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("analyte_manager_form", ["analyte_manager_name"]);
    if (post_data.analyte_manager_name !== undefined) {
      analyte_manager.add(post_data, "analyte_manager", "add", true);
    }
  });//Add a analyte_manager

  $("#btn_edit_analyte_manager").click(function() {
    var post_data = utils.retrieveInputs("analyte_manager_form", ["analyte_manager_name"]);
    if (post_data.analyte_manager_name !== undefined) {
      analyte_manager.edit(post_data, "analyte_manager", "edit");
    }
  });//Edit a analyte_manager

  $("#btn_delete_analyte_manager").click(function() {
    analyte_manager.delete(parseInt(utils.getQueryVariable("analyte_manager_id")));
  });//Delete a analyte_manager

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".analyte_manager_add").hide();
    analyte_manager.getItem(utils.getQueryVariable("analyte_manager_id"));
  }//Load analyte_manager

  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
    analyte_manager.fillFormWithRandomData();
  }

  var alreadyHovered = false;
  $(".select_item").hover(function() {
    if (!alreadyHovered)
      toastr.info("Right-click to edit!");
    alreadyHovered = true;
  });//Show a analyte_manager tip

  $("#analyte_manager_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".analyte_manager_list").fadeIn('2000').removeClass("hide");
    analyte_manager.getList();
  });//Show "List All" panel

});
/***********
 * analyte_manager namespace 
 * Responsible to manage analyte_managers.
 */
(function(analyte_manager) {
  analyte_manager.add = function(data, controller, action, isSingle) {
//    var data = isSingle ? userData : {"names": userData};
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call FieldAnalyte/Add WebService
      if (reply === null || reply.dataId === undefined || reply.dataId === null || parseInt(reply.dataId) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("analyte_manager/listAll", 1000);
      }
    });
  };
  analyte_manager.edit = function(analyte_manager, controller, action) {
    datacx.post(controller + "/" + action, analyte_manager).then(function(reply) {//call AJAX method to call FieldAnalyte/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("analyte_manager/listAll", 1000);
      }
    });
  };
  analyte_manager.getList = function() {
    datacx.post("analyte_manager/getlist", null).then(function(reply) {//call AJAX method to call FieldAnalyte/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        analyte_manager.buildOutputList(reply.lists.analyte_managers);
        //Now show the table
      }
    });
  };
  analyte_manager.buildOutputList = function(analyte_managers) {
    var active_analyte_managers = "";
    var inactive_analyte_managers = "";
    for (i = 0; i < analyte_managers.length; i++) {
      if (parseInt(analyte_managers[i].active) !== 0) {
        active_analyte_managers += "<option value=\"" + analyte_managers[i].analyte_manager_name + "\">" + analyte_managers[i].analyte_manager_name + "</option>";
      } else {
        inactive_analyte_managers += "<option value=\"" + analyte_managers[i].analyte_manager_name + "\">" + analyte_managers[i].analyte_manager_name + "</option>";
      }
    }
    inactive_analyte_managers = utils.isNullOrEmpty(inactive_analyte_managers) ?
            "<option value=\"\">{empty}</option>" : inactive_analyte_managers;
    active_analyte_managers = utils.isNullOrEmpty(active_analyte_managers) ?
            "<option value=\"\">{empty}</option>" : active_analyte_managers;
    $("#analyte_manager-data-active, #analyte_manager-data-inactive").show();
    $("#analyte_manager-data-active").html(active_analyte_managers);
    $("#analyte_manager-data-inactive").html(inactive_analyte_managers);
  };
  analyte_manager.retrieveFieldAnalyte = function(element) {
    utils.redirect("analyte_manager/showForm?mode=edit&analyte_manager_id=" + parseInt(element.attr("data-fieldanalyte-id")));
  };
  analyte_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"analyte_manager_id\"]").val(parseInt(dataWs.analyte_manager.analyte_manager_id));
    $("input[name=\"analyte_manager_name_unit\"]").val(dataWs.analyte_manager.analyte_manager_name_unit);
  };
  analyte_manager.delete = function(analyte_manager_id) {
    datacx.post("analyte_manager/delete", {"analyte_manager_id": analyte_manager_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        //$("li[data-analyte_manager-id="+ analyte_manager_id +"]").remove();
        utils.redirect("analyte_manager/listAll");
      }
    });
  };

  analyte_manager.getItem = function(analyte_manager_id) {
    //get analyte_manager object from cache (PHP WS)
    datacx.post("analyte_manager/getItem", {"analyte_manager_id": analyte_manager_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("analyte_manager/listAll", 3000)
      } else {//success
        $(".analyte_manager_edit").show().removeClass("hide");
        toastr.success(reply.message);
        analyte_manager.loadEditForm(reply);
      }
    });
  };

  analyte_manager.fillFormWithRandomData = function() {
    utils.clearForm();
    var number = Math.floor((Math.random() * 100) + 1);
    $(".analyte_manager_form input[name=\"analyte_manager_name\"]").val("FieldAnalyte " + number);
    $("input[name=\"analyte_manager_num\"]").val("n-" + number);
    $("input[name=\"analyte_manager_desc\"]").val("Description " + number);
    $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  };

  analyte_manager.updateFieldAnalytes = function(action, arrayId) {
    datacx.post("analyte_manager/updateItems", {"action": action, "analyte_manager_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("analyte_manager/listAll");
      }
    });
  };

}(window.analyte_manager = window.analyte_manager || {}));
