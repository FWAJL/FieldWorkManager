/**
 * jQuery listeners for the service actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        service_manager.retrieveResource(options.$trigger);
      } else if (key === "delete") {
        service_manager.delete(parseInt(options.$trigger.attr("data-service-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of services
//  var service_ids = "";
//  $("#active-list, #inactive-list").selectable({
//    stop: function() {
//      var tmpSelection = "";
//      $(".ui-selected", this).each(function() {
//        tmpSelection += $(this).attr("data-service-id") + ",";
//      });
//      tmpSelection = utils.removeLastChar(tmpSelection);
//      if (tmpSelection.length > 0) {
//        service_ids = tmpSelection;
//        //Show the button to appropriate button
//        $(".from-" + $(this).attr("id")).show();
//      } else {
//        service_ids = [];
//        $(".from-" + $(this).attr("id")).hide();
//      }
//    }
//  });
//  $(".from-inactive-project-list").click(function() {
//    service_manager.updateResources("active", service_ids);
//  });
//  $(".from-active-project-list").click(function() {
//    service_manager.updateResources("inactive", service_ids);
//  });
  //************************************************//


//  $("#btn-add-service-names").click(function() {
//    service_manager.add($("textarea[name=\"service_names\"]").val(), "service", "add", false);
//  });//Add many services

  $("#btn-add-service-manual").click(function() {
    utils.redirect("service/showForm?mode=add&test=true");
  });//Button click "add a service"

  $("#btn_add_service").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("service_form", ["service_name"]);
    if (post_data.service_name !== undefined) {
      service_manager.add(post_data, "service", "add", true);
    }
  });//Add a service

  $("#btn_edit_service").click(function() {
    var post_data = utils.retrieveInputs("service_form", ["service_name"]);
    if (post_data.service_name !== undefined) {
      service_manager.edit(post_data, "service", "edit");
    }
  });//Edit a service

  $("#btn_delete_service").click(function() {
    service_manager.delete(parseInt(utils.getQueryVariable("service_id")));
  });//Delete a service

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".service_add").hide();
    service_manager.getItem(utils.getQueryVariable("service_id"));
  }//Load service

//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    service_manager.fillFormWithRandomData();
//  }

  var alreadyHovered = false;
  $(".select_item").hover(function() {
    if (!alreadyHovered)
      toastr.info("Right-click to edit!");
    alreadyHovered = true;
  });//Show a service tip

  $("#service_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".service_list").fadeIn('2000').removeClass("hide");
    service_manager.getList();
  });//Show "List All" panel

});
/***********
 * service_manager namespace 
 * Responsible to manage services.
 */
(function(service_manager) {
  service_manager.add = function(data, controller, action) {
 
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call Resource/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("service/listAll", 1000);
      }
    });
  };
  service_manager.edit = function(service, controller, action) {
    datacx.post(controller + "/" + action, service).then(function(reply) {//call AJAX method to call Resource/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("service/listAll", 1000);
      }
    });
  };
  service_manager.getList = function() {
    datacx.post("service/getlist", null).then(function(reply) {//call AJAX method to call Resource/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        service_manager.buildOutputList(reply.lists.services);
        //Now show the table
      }
    });
  };
  service_manager.buildOutputList = function(services) {
    var active_services = "";
    var inactive_services = "";
    for (i = 0; i < services.length; i++) {
      if (parseInt(services[i].active) !== 0) {
        active_services += "<option value=\"" + services[i].service_name + "\">" + services[i].service_name + "</option>";
      } else {
        inactive_services += "<option value=\"" + services[i].service_name + "\">" + services[i].service_name + "</option>";
      }
    }
    inactive_services = utils.isNullOrEmpty(inactive_services) ?
            "<option value=\"\">{empty}</option>" : inactive_services;
    active_services = utils.isNullOrEmpty(active_services) ?
            "<option value=\"\">{empty}</option>" : active_services;
    $("#service-data-active, #service-data-inactive").show();
    $("#service-data-active").html(active_services);
    $("#service-data-inactive").html(inactive_services);
  };
  service_manager.retrieveResource = function(element) {
    utils.redirect("service/showForm?mode=edit&service_id=" + parseInt(element.attr("data-service-id")));
  };
  service_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"pm_id\"]").val(parseInt(dataWs.service.pm_id));
    $("input[name=\"service_id\"]").val(parseInt(dataWs.service.service_id));
    $("input[name=\"service_name\"]").val(dataWs.service.service_name);
    $("input[name=\"service_type\"]").val(dataWs.service.service_type);
    $("input[name=\"service_url\"]").val(dataWs.service.service_url);
    $("input[name=\"service_address\"]").val(dataWs.service.service_address);
    $("input[name=\"service_contact_name\"]").val(dataWs.service.service_contact_name);
    $("input[name=\"service_contact_phone\"]").val(dataWs.service.service_contact_phone);
    $("input[name=\"service_contact_email\"]").val(dataWs.service.service_contact_email);
    $("input[name=\"service_active\"]").prop('checked', utils.setCheckBoxValue(dataWs.service.service_active));
  };
  service_manager.delete = function(service_id) {
    datacx.post("service/delete", {"service_id": service_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("service/listAll");
      }
    });
  };

  service_manager.getItem = function(service_id) {
    //get service object from cache (PHP WS)
    datacx.post("service/getItem", {"service_id": service_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("service/listAll", 3000)
      } else {//success
        $(".service_edit").show().removeClass("hide");
        toastr.success(reply.message);
        service_manager.loadEditForm(reply);
      }
    });
  };

 /** service_manager.fillFormWithRandomData = function() {
    utils.clearForm();
    var number = Math.floor((Math.random() * 100) + 1);
    $(".service_form input[name=\"service_name\"]").val("Resource " + number);
    $("input[name=\"service_num\"]").val("n-" + number);
    $("input[name=\"service_desc\"]").val("Description " + number);
    $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  };
*/
  service_manager.updateResources = function(action, arrayId) {
    datacx.post("service/updateItems", {"action": action, "service_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("service/listAll");
      }
    });
  };

}(window.service_manager = window.service_manager || {}));
