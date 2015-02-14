/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the service actions
 */
$(document).ready(function() {
  var ajaxParams = {
    "ajaxUrl": "service/updateItems",
    "redirectUrl": "service/listAll",
    "action": "",
    "arrayOfValues": "",
    "itemId": ""
  };
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
      "edit": {name: "View Info"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  $("#btn-add-service-manual").click(function() {
    utils.redirect("service/showForm?mode=add&test=true");
  });//Button click "add a service"

  $("#btn_add_service").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("service_form", ["service_name", "service_type"]);

    var msgNullCheck = $('#confirmmsg-addNullCheck').val();
    var msgUniqueCheck = $('#confirmmsg-addUniqueCheck').val();
    if (typeof msgNullCheck !== typeof undefined && msgNullCheck !== false &&
            typeof msgUniqueCheck !== typeof undefined && msgUniqueCheck !== false) {
      if (post_data.service_name !== undefined && post_data.service_type !== undefined) {
        //Check uniqueness
        service_manager.ifServiceProviderExists(post_data.service_name, function(record_count) {
          if (record_count > 0)
          {
            utils.showAlert(msgUniqueCheck.replace("{0}", post_data.service_name));
          }
          else
          {
            if (post_data.service_name !== undefined) {
              service_manager.add(post_data, "service", "add", true);
            }
          }

        });
      }
      else
      {
        utils.showAlert(msgNullCheck);
      }
    }
    else
    {
      //Old code
      if (post_data.service_name !== undefined) {
        service_manager.add(post_data, "service", "add", true);
      }
    }

    return false;


  });//Add a service

  $("#btn_edit_service").click(function() {
    var post_data = utils.retrieveInputs("service_form", ["service_name"]);
    if (post_data.service_name !== undefined) {
      service_manager.edit(post_data, "service", "edit");
    }
  });//Edit a service

  $("#btn_delete_service").click(function() {
    ajaxParams.ajaxUrl = "service/delete";
    ajaxParams.itemId = parseInt(parseInt(utils.getQueryVariable("service_id")));
    datacx.delete(ajaxParams);
  });

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".service_add").hide();
    service_manager.getItem(utils.getQueryVariable("service_id"));
  }//Load service

//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    service_manager.fillFormWithRandomData();
//  }

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
  service_manager.updateServices = function(action, arrayId) {
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

  service_manager.ifServiceProviderExists = function(providerName, decision) {
    datacx.post("service/ifProviderExists", {service_name: providerName}).then(function(reply) {
      //alert(reply.record_count);
      decision(reply.record_count);
    });
  };

}(window.service_manager = window.service_manager || {}));
