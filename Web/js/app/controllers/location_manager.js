/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the location actions
 */
$(document).ready(function() {
 var temp;
 $(".btn-warning").hide();
 $("#document-upload").hide();
 $("#documents").hide();
 Dropzone.autoDiscover = false;
 if ($("#document-upload").length > 0) {
  var dropzone = new Dropzone("#document-upload");
  dropzone.on("sending", function(event, xhr, formData) {
   $("#btn_edit_location").attr('disabled', 'disabled');
  });
  dropzone.on("success", function(event, res) {
   if (res.result == 0) {
    //toastr.error(res.message);
    dropzone.removeAllFiles();
   } else {
    //toastr.success(res.message);
    dropzone.removeAllFiles();
    $("#documents").html("");
    location_manager.loadPhoto('location_id', parseInt($("input[name=\"itemId\"]").val()));
   }
   $("#btn_edit_location").removeAttr('disabled');
  });
 }
 $(document).on('click', '.remove-image', function(e) {
  e.preventDefault();
  location_manager.removePhoto($(this).data("id"));
 });

 $.contextMenu({
  selector: '.select_item',
  callback: function(key, options) {
   if (key === "edit") {
    location_manager.retrieveLocation(options.$trigger);
   }
  },
  items: {
   "edit": {name: "Edit"}
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
    $(".from-" + $(this).attr("id")).show();
   } else {
    location_ids = [];
    $(".from-" + $(this).attr("id")).hide();
   }
  }
 });
 $(".from-inactive-list").click(function() {
  location_manager.updateLocations("active", location_ids);
 });
 $(".from-active-list").click(function() {
  location_manager.updateLocations("inactive", location_ids);
 });
 //************************************************//

 $("#btn-add-location-names").click(function() {
  var data = {
   "names": $("textarea[name=\"location_names\"]").val(),
   "active": $("input[name=\"location_active\"]").prop("checked")
  };
  //Check for uniqueness
  location_manager.isAllLocationsExisting(data, function(reply) {
   if (reply.record_count === 0) {
    //Save the entire lot
    location_manager.add(data, "location", "add");
   } else {
    //utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function(){
    utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function() {
    });
    var errHtml = '<ul style="color:#FF0000; margin:15px 0 15px 10px;">';
    for (i in reply.duplicate_locations) {
     errHtml += '<li>' + reply.duplicate_locations[i] + '</li>';
    }
    errHtml += '</ul>';
    $('.bootbox-body').append(errHtml);
   }
  });

 });//Add many locations

 $("#btn-add-location-manual").click(function() {
  utils.redirect("location/showForm?mode=add&test=true");
 });//Button click "add a location"

 $("#btn_add_location").click(function() {
  var data = {};
  var loc_name = $('[name="location_name"]').val();
  if (loc_name === '') {
   //Show Prompt to enter location
   utils.showPromptBox('promptEnterLocation', function() {
    if ($('#text_input').val() !== '')
    {
     location_manager.isLocationForProjectExists($('#text_input').val(), function(record_count) {
      if (record_count == 0)
      {
       utils.dissmissModal();
       //Add loc
       $('[name="location_name"]').val($('#text_input').val());
       post_data = utils.retrieveInputs("location_form", ["location_name"]);
       if (post_data.location_name !== undefined) {
        location_manager.add(post_data, "location", "add", true);
       }

      }
      else
      {
       //Show alert, that location is already taken, choose new
       utils.togglePromptBox();
       utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function() {
        utils.togglePromptBox();
       });
      }
     });
    }
    else {
     $('#text_input').focus();
    }
   },
           'promptmsg-promptEnterLocation', function() {
            //cancel button callback
            $('#text_input').val('');
            if (temp !== '') {
             $('[name="location_name"]').val(temp);
             temp = '';
            }

           });
  } else {
   //Check if unique
   location_manager.isLocationForProjectExists(loc_name, function(record_count) {
    if (record_count == 0)
    {
     //Add loc
     post_data = utils.retrieveInputs("location_form", ["location_name"]);
     if (post_data.location_name !== undefined) {
      location_manager.add(post_data, "location", "add", true);
     }

    }
    else
    {
     $('#text_input').val($('[name="location_name"]').val());
     temp = loc_name;
     $('[name="location_name"]').val('');
     $("#btn_add_location").click();
    }
   });
  }


 });//Add a location

 $("#btn_edit_location").click(function() {
  var post_data = utils.retrieveInputs("location_form", ["location_name"]);
  if (post_data.location_name !== undefined) {
   location_manager.edit(post_data, "location", "edit");
  }
 });//Edit a location

 $("#btn_delete_location").click(function() {
  var msg = $('#confirmmsg-delete').val();
  if (typeof msg !== typeof undefined && msg !== false) {
   utils.showConfirmBox(msg, function(result) {
    if (result)
    {
     location_manager.delete(parseInt(utils.getQueryVariable("location_id")));
    }
   });
  }
  else
  {
   location_manager.delete(parseInt(utils.getQueryVariable("location_id")));
  }
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
 location_manager.add = function(userData, controller, action, openEdit) {
  var origin = utils.getQueryVariable("origin");
  var originid = utils.getQueryVariable("originid");
  if (!origin && !originid) {
   data = userData;
  } else {
   data = {"userData": userData, "origin": origin, "originid": originid};
  }
//  var data = isSingle ? userData : {"names": userData};
  datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call Location/Add WebService
   if (reply === null || reply.dataId === undefined || reply.dataId === null || parseInt(reply.dataId) === 0) {//has an error
    //toastr.error(reply.message);
   } else {//success
    //toastr.success(reply.message);
    if (parseInt(originid) > 0) {
     utils.redirect("task/locations", 1000);
    } else if (openEdit === true) {
     utils.redirect("location/showForm?mode=edit&location_id=" + reply.dataIn[0].location_id, 1000);
    } else {
     utils.redirect("location/listAll", 1000);
    }
   }
  });
 };
 location_manager.edit = function(location, controller, action) {
  datacx.post(controller + "/" + action, location).then(function(reply) {//call AJAX method to call Location/Add WebService
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
   } else {//success
    //toastr.success(reply.message);
    utils.redirect("location/listAll", 1000);
   }
  });
 };
 location_manager.getList = function() {
  datacx.post("location/getlist", null).then(function(reply) {//call AJAX method to call Location/GetList WebService
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
   } else {//success
    //toastr.success(reply.message);
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
  isEditing = true;
  utils.clearForm();
  $("input[name=\"project_id\"]").val(parseInt(dataWs.location.project_id));
  $("input[name=\"location_id\"]").val(parseInt(dataWs.location.location_id));
  $("input[name=\"location_name\"]").val(dataWs.location.location_name);
  $("input[name=\"location_category\"]").val(dataWs.location.location_category);
  $("input[name=\"location_address\"]").val(dataWs.location.location_address);
  $("input[name=\"location_lat\"]").val(dataWs.location.location_lat);
  $("input[name=\"location_long\"]").val(dataWs.location.location_long);
  $("input[name=\"location_desc\"]").val(dataWs.location.location_desc);
  $("input[name=\"location_active\"]").prop('checked', utils.setCheckBoxValue(dataWs.location.location_active));

  $("input[name=\"itemCategory\"]").val('location_id');
  $("input[name=\"itemId\"]").val(parseInt(dataWs.location.location_id));
  location_manager.loadPhoto('location_id', parseInt(dataWs.location.location_id));
//    $("input[name=\"location_visible\"]").val(dataWs.location.location_visible);
 };
 location_manager.delete = function(location_id) {
  datacx.post("location/delete", {"location_id": location_id}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
    return undefined;
   } else {//success
    //toastr.success(reply.message);
    //$("li[data-location-id="+ location_id +"]").remove();
    datacx.post("file/removeMany", {"itemCategory": $("input[name=\"itemCategory\"]").val(), "itemId": $("input[name=\"itemId\"]").val()}).then(function(reply2) {
     utils.redirect("location/listAll");
    });

   }
  });
 };

 location_manager.getItem = function(location_id) {
  //get location object from cache (PHP WS)
  datacx.post("location/getItem", {"location_id": location_id}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
    $(".form_sections").hide();
    utils.redirect("location/listAll", 3000)
   } else {//success
    $(".location_edit").show().removeClass("hide");
    //toastr.success(reply.message);
    location_manager.loadEditForm(reply);
   }
  });
 };

 location_manager.fillFormWithRandomData = function() {
  utils.clearForm();
  var number = Math.floor((Math.random() * 100) + 1);
  $(".location_form input[name=\"location_name\"]").val("Location " + number);
  $("input[name=\"location_num\"]").val("n-" + number);
  $("input[name=\"location_desc\"]").val("Description " + number);
  $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
  $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " Somewhere");
 };

 location_manager.updateLocations = function(action, arrayId) {
  datacx.post("location/updateItems", {"action": action, "location_ids": arrayId}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
    return undefined;
   } else {//success
    //toastr.success(reply.message);
    utils.redirect("location/listAll");
   }
  });
 };

 location_manager.isLocationForProjectExists = function(locationName, decision) {
  datacx.post("location/ifLocationExists", {location_name: locationName}).then(function(reply) {
   decision(reply.record_count);
  });
 }

 location_manager.isAllLocationsExisting = function(data, decision) {
  datacx.post("location/ifAllLocationsExist", {location_names: data.names}).then(function(reply) {
   decision(reply);
  });
 }

 location_manager.loadPhoto = function(itemCategory, itemId) {
  datacx.post("file/load", {"itemCategory": itemCategory, "itemId": itemId}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
    return undefined;
   } else {//success
    //toastr.success(reply.message);
    if (reply.fileResults.length > 0) {
     $.each(reply.fileResults, function(key, file) {
      var lightboxImage = utils.createImageLightboxElement(file.filePath, itemId, file.document_title);
      var remove = utils.createRemoveFileElement(file.document_id);
      $("#documents").append('<div class="col-md-4 col-lg-4 document-block" id="document-' + file.document_id + '">' + lightboxImage + remove + '</div>');
      $("#documents").show();
     });
    }
    $("#document-upload").show();
   }
  });
 }

 location_manager.removePhoto = function(document_id) {
  datacx.post("file/remove", {"document_id": document_id, "itemCategory": 'location_id'}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    //toastr.error(reply.message);
    return undefined;
   } else {//success
    //toastr.success(reply.message);
    $("#document-" + document_id).remove();
   }
  });
 }

}(window.location_manager = window.location_manager || {}));
