/**
 * jQuery listeners for the resource actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        resource_manager.retrieveResource(options.$trigger);
      } else if (key === "delete") {
        resource_manager.delete(parseInt(options.$trigger.attr("data-resource-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of resources
  var resource_ids = "";
  $("#active-list, #inactive-list").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-resource-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        resource_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        resource_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-inactive-list").click(function() {
    resource_manager.updateResources("active", resource_ids);
  });
  $(".from-active-list").click(function() {
    resource_manager.updateResources("inactive", resource_ids);
  });
  //************************************************//


//  $("#btn-add-resource-names").click(function() {
//    resource_manager.add($("textarea[name=\"resource_names\"]").val(), "resource", "add", false);
//  });//Add many resources

  $("#btn-add-resource-manual").click(function() {
    utils.redirect("resource/showForm?mode=add&test=true");
  });//Button click "add a resource"

  $("#btn_add_resource").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("resource_form", ["resource_name"]);
    if (post_data.resource_name !== undefined) {
      resource_manager.add(post_data, "resource", "add", true);
    }
  });//Add a resource

  $("#btn_edit_resource").click(function() {
    var post_data = utils.retrieveInputs("resource_form", ["resource_name"]);
    if (post_data.resource_name !== undefined) {
      resource_manager.edit(post_data, "resource", "edit");
    }
  });//Edit a resource

  $("#btn_delete_resource").click(function() {
    resource_manager.delete(parseInt(utils.getQueryVariable("resource_id")));
  });//Delete a resource

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".resource_add").hide();
    resource_manager.getItem(utils.getQueryVariable("resource_id"));
  }//Load resource

//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    resource_manager.fillFormWithRandomData();
//  }

  var alreadyHovered = false;
  $(".select_item").hover(function() {
    if (!alreadyHovered)
      toastr.info("Right-click to edit!");
    alreadyHovered = true;
  });//Show a resource tip

  $("#resource_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".resource_list").fadeIn('2000').removeClass("hide");
    resource_manager.getList();
  });//Show "List All" panel

});
/***********
 * resource_manager namespace 
 * Responsible to manage resources.
 */
(function(resource_manager) {
  resource_manager.add = function(userData, controller, action, isSingle) {
    var data = isSingle ? userData : {"names": userData};
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call Resource/Add WebService
      if (reply === null || reply.dataOut === undefined || reply.dataOut === null || parseInt(reply.dataOut) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("resource/listAll", 1000);
      }
    });
  };
  resource_manager.edit = function(resource, controller, action) {
    datacx.post(controller + "/" + action, resource).then(function(reply) {//call AJAX method to call Resource/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("resource/listAll", 1000);
      }
    });
  };
  resource_manager.getList = function() {
    datacx.post("resource/getlist", null).then(function(reply) {//call AJAX method to call Resource/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        resource_manager.buildOutputList(reply.lists.resources);
        //Now show the table
      }
    });
  };
  resource_manager.buildOutputList = function(resources) {
    var active_resources = "";
    var inactive_resources = "";
    for (i = 0; i < resources.length; i++) {
      if (parseInt(resources[i].active) !== 0) {
        active_resources += "<option value=\"" + resources[i].resource_name + "\">" + resources[i].resource_name + "</option>";
      } else {
        inactive_resources += "<option value=\"" + resources[i].resource_name + "\">" + resources[i].resource_name + "</option>";
      }
    }
    inactive_resources = utils.isNullOrEmpty(inactive_resources) ?
            "<option value=\"\">{empty}</option>" : inactive_resources;
    active_resources = utils.isNullOrEmpty(active_resources) ?
            "<option value=\"\">{empty}</option>" : active_resources;
    $("#resource-data-active, #resource-data-inactive").show();
    $("#resource-data-active").html(active_resources);
    $("#resource-data-inactive").html(inactive_resources);
  };
  resource_manager.retrieveResource = function(element) {
    utils.redirect("resource/showForm?mode=edit&resource_id=" + parseInt(element.attr("data-resource-id")));
  };
  resource_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"pm_id\"]").val(parseInt(dataWs.resource.pm_id));
    $("input[name=\"resource_id\"]").val(parseInt(dataWs.resource.resource_id));
    $("input[name=\"resource_name\"]").val(dataWs.resource.resource_name);
    $("input[name=\"resource_type\"]").val(dataWs.resource.resource_type);
    $("input[name=\"resource_url\"]").val(dataWs.resource.resource_url);
    $("input[name=\"resource_address\"]").val(dataWs.resource.resource_address);
    $("input[name=\"resource_contact_name\"]").val(dataWs.resource.resource_contact_name);
    $("input[name=\"resource_contact_phone\"]").val(dataWs.resource.resource_contact_phone);
    $("input[name=\"resource_contact_email\"]").val(dataWs.resource.resource_contact_email);
    $("input[name=\"resource_active\"]").val(dataWs.resource.resource_active);
  };
  resource_manager.delete = function(resource_id) {
    datacx.post("resource/delete", {"resource_id": resource_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("resource/listAll");
      }
    });
  };

  resource_manager.getItem = function(resource_id) {
    //get resource object from cache (PHP WS)
    datacx.post("resource/getItem", {"resource_id": resource_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("resource/listAll", 3000)
      } else {//success
        $(".resource_edit").show().removeClass("hide");
        toastr.success(reply.message);
        resource_manager.loadEditForm(reply);
      }
    });
  };

 /** resource_manager.fillFormWithRandomData = function() {
    utils.clearForm();
    var number = Math.floor((Math.random() * 100) + 1);
    $(".resource_form input[name=\"resource_name\"]").val("Resource " + number);
    $("input[name=\"resource_num\"]").val("n-" + number);
    $("input[name=\"resource_desc\"]").val("Description " + number);
    $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  };
*/
  resource_manager.updateResources = function(action, arrayId) {
    datacx.post("resource/updateItems", {"action": action, "resource_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("resource/listAll");
      }
    });
  };

}(window.resource_manager = window.resource_manager || {}));
