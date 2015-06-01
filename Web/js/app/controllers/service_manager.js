/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the service actions
 */

//Autocomplete variables
  var timer = null;
  var autocompleteTimeout = 700;

$(document).ready(function() {
  var ajaxParams = {
    "ajaxUrl": "service/updateItems",
    "redirectUrl": "service/listAll",
    "action": "",
    "arrayOfValues": "",
    "itemId": ""
  };


  $("input[name='service_type']").keyup(function(e){
    // do nothing if it's an arrow key
    
    var code = (e.keyCode || e.which);
    if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13 || code == 9 || e.shiftKey) {
      return;
    } else if(e.shiftKey && e.keyCode == 9) {
      return;  
    }

    clearTimeout(timer);

    //else proceed to lookup
    timer = setTimeout(function(){
      service_manager.getServiceCategoryUsingAutoComplete($("input[name='service_type']"))
    }, autocompleteTimeout);
    
  });

  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      var params = {
        "targetUrl": "service/showForm?mode=edit&service_id=",
        "element": options.$trigger,
        "attrName": "data-service-id"
      };
      if (key === "edit") {
        utils.loadItem(params);
    } 
    },
    items: {
      "edit": {name: "Edit"}
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
	var msg = $('#confirmmsg-delete').val();
    if (typeof msg !== typeof undefined && msg !== false) {
      utils.showConfirmBox(msg, function(result) {
        if (result)
        {
          service_manager.delservice(ajaxParams);
        }
      });
    }
    else
    {
      service_manager.delservice(ajaxParams);
    }
	/*  
    ajaxParams.ajaxUrl = "service/delete";
    ajaxParams.itemId = parseInt(parseInt(utils.getQueryVariable("service_id")));
    datacx.delete(ajaxParams);
	*/
  });

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".service_add").hide();
    service_manager.getItem(utils.getQueryVariable("service_id"));
  }//Load service

  $("#service_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".service_list").fadeIn('2000').removeClass("hide");
    service_manager.getList();
  });//Show "List All" panel
  
   // Selection of service
var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-service-id",
    "dataAttrRight": "data-service-id"
  };
  utils.dualListSelection(selectionParams);

  $(".from-categorized-list-right").click(function() {
    ajaxParams.action = "add";
    ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParams.listRightId, selectionParams.dataAttrRight, true);
    datacx.updateItems(ajaxParams);
  });
  $(".from-categorized-list-left").click(function() {
    ajaxParams.action = "remove";
    ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParams.listLeftId, selectionParams.dataAttrLeft, true);
    datacx.updateItems(ajaxParams);
  });
});

/***********
 * service_manager namespace 
 * Responsible to manage services.
 */
(function(service_manager) {

  //Autocomplete vars
  var query = '';
  var autocompleteArrayTo = [];
  var selectedObjectTo;

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
  service_manager.delservice = function(ajaxParams) {
	ajaxParams.ajaxUrl = "service/delete";
    ajaxParams.itemId = parseInt(parseInt(utils.getQueryVariable("service_id")));
    datacx.delete(ajaxParams);
  }
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

  //auto complete fecth and render for service category
  service_manager.getServiceCategoryUsingAutoComplete = function(txtBoxObject){
    if (service_manager.query != $(txtBoxObject).val()) {
      service_manager.query = $(txtBoxObject).val();
      
      datacx.post("service/getServiceCategoriesAutoComplete", {"search": service_manager.query}).then(function(reply) {
        console.log(reply);
        service_manager.autocompleteArrayTo = [];
        if (reply === null || reply.result === 0) {//has an error
          //Do nothing
        } else {//success
          //Time for some autocomplete
          $.each(reply.matches, function (index, selectedObject) {
            service_manager.autocompleteArrayTo.push(selectedObject);
          });

          $("input[name='service_type']").autocomplete({
            source: service_manager.autocompleteArrayTo
          });
          $("input[name='service_type']").autocomplete( "search", $("input[name='service_type']").val() );
        }
      });
    }
  };

}(window.service_manager = window.service_manager || {}));
