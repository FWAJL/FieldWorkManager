/**
 * jQuery listeners for the location actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        location_manager.retrieveLocation(options.$trigger);
      } else if (key === "delete") {
        location_manager.delete(parseInt(options.$trigger.attr("data-location-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of locations
  var location_ids = "";
  $("#active-list, #inactive-list").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-location-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        location_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-"+$(this).attr("id")).show();
      } else {
        location_ids = [];
        $(".from-"+$(this).attr("id")).hide();
      }
    }
  });
  $(".from-inactive-list").click(function() {
    location_manager.updateLocations("active",location_ids);
  });
  $(".from-active-list").click(function() {
    location_manager.updatelocations("inactive",location_ids);
  });
  //************************************************//


  $("#btn_add_location").click(function() {
    var post_data = {};
    post_data["location"] = utils.retrieveInputs("location_form", ["location_name"]);
    post_data["facility"] = utils.retrieveInputs("facility_form", ["facility_name", "facility_address"]);
    //TO BE IMPLEMENTED
    //post_data["client"] = utils.retrieveInputs("client_form", []);
    if (post_data["location"].location_name !== undefined &&
            post_data["facility"].facility_name !== undefined && post_data["facility"].facility_address !== undefined) {
      location_manager.add(post_data, "location", "add");
    }
  });//Add a location

  $("#btn_edit_location").click(function() {
    var post_data = utils.retrieveInputs("location_form", ["location_name"]);
    if (post_data.location_name !== undefined) {
      location_manager.edit(post_data, "location", "edit");
    }
  });//Edit a location

  $("#btn_delete_location").click(function() {
    location_manager.delete(parseInt(utils.getQueryVariable("location_id")));
  });//Delete a location

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".location_add").hide();
    location_manager.getItem(utils.getQueryVariable("location_id"));
  }//Load location

  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
    location_manager.fillFormWithRandomData();
  }

  var alreadyHovered = false;
  $(".select_item").hover(function() {
    if (!alreadyHovered)
      toastr.info("Right-click to edit!");
    alreadyHovered = true;
  });//Show a location tip

  $("#location_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".location_list").fadeIn('2000').removeClass("hide");
    location_manager.getList();
  });//Show "List All" panel

});
/***********
 * location_manager namespace 
 * Responsible to manage locations.
 */
(function(location_manager) {
  location_manager.add = function(data, controller, action) {
    datacx.post(controller + "/" + action, data["location"]).then(function(reply) {//call AJAX method to call Location/Add WebService
      if (reply === null || reply.dataOut === undefined || reply.dataOut === null || parseInt(reply.dataOut) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        var post_data = utils.retrieveInputs("facility_form", ["facility_name", "facility_address"]);
        data["facility"]['location_id'] = reply.dataOut;
        facility_manager.send("facility/" + action, data["facility"]);
      }
    });
  };
  location_manager.edit = function(location, controller, action) {
    datacx.post(controller + "/" + action, location).then(function(reply) {//call AJAX method to call Location/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        var post_data = utils.retrieveInputs("facility_form", ["facility_name", "facility_address"]);
        if (post_data.facility_name !== undefined && post_data.facility_address !== undefined) {
          facility_manager.send("facility/" + action, post_data);
        }
      }
    });
  };
  location_manager.getList = function() {
    datacx.post("location/getlist", null).then(function(reply) {//call AJAX method to call Location/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        location_manager.buildOutputList(reply.lists.locations);
        //Now show the table
      }
    });
  };
  location_manager.buildOutputList = function(locations) {
    var active_locations = "";
    var inactive_locations = "";
    for (i = 0; i < locations.length; i++) {
      if (parseInt(locations[i].active) !== 0) {
        active_locations += "<option value=\"" + locations[i].location_name + "\">" + locations[i].location_name + "</option>";
      } else {
        inactive_locations += "<option value=\"" + locations[i].location_name + "\">" + locations[i].location_name + "</option>";
      }
    }
    inactive_locations = utils.isNullOrEmpty(inactive_locations) ?
            "<option value=\"\">{empty}</option>" : inactive_locations;
    active_locations = utils.isNullOrEmpty(active_locations) ?
            "<option value=\"\">{empty}</option>" : active_locations;
    $("#location-data-active, #location-data-inactive").show();
    $("#location-data-active").html(active_locations);
    $("#location-data-inactive").html(inactive_locations);
  };
  location_manager.retrieveLocation = function(element) {
    utils.redirect("location/showForm?mode=edit&location_id=" + parseInt(element.attr("data-location-id")));
  };
  location_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $(".location_form input[name=\"location_id\"]").val(parseInt(dataWs.location.location_id));
    $(".location_form .add-new-p input[name=\"location_name\"]").val(dataWs.location.location_name);
    $(".location_form .add-new-p input[name=\"location_num\"]").val(dataWs.location.location_number);
    $(".location_form .add-new-p input[name=\"location_desc\"]").val(dataWs.location.location_desc);
    $(".location_form .add-new-p input[name=\"active\"]").val(dataWs.location.active);
    $(".location_form .add-new-p input[name=\"visible\"]").val(dataWs.location.visible);
    facility_manager.loadEditForm(dataWs);
  };
  location_manager.delete = function(location_id) {
    datacx.post("location/delete", {"location_id": location_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("location/listAll");
      }
    });
  };

  location_manager.getItem = function(location_id) {
    //get location object from cache (PHP WS)
    datacx.post("location/getItem", {"location_id": location_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("location/listAll", 3000)
      } else {//success
        $(".location_edit").show().removeClass("hide");
        toastr.success(reply.message);
        location_manager.loadEditForm(reply);
      }
    });
  };

  location_manager.fillFormWithRandomData = function() {
    utils.clearForm();
    var number = Math.floor((Math.random() * 100) + 1);
    $(".location_form input[name=\"location_name\"]").val("Location " + number);
    $(".location_form .add-new-p input[name=\"location_num\"]").val("n-" + number);
    $(".location_form .add-new-p input[name=\"location_desc\"]").val("Description " + number);
    $(".facility_form .add-new-p input[name=\"facility_name\"]").val("Facility " + number);
    $(".facility_form .add-new-p textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  };
  
  location_manager.updateLocations = function(action, arrayId) {
    datacx.post("location/updateItems", {"action": action, "location_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("location/listAll");
     }
    });
  };

}(window.location_manager = window.location_manager || {}));
