/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the technician actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $("#document-upload").hide();
  $("#documents").hide();
  Dropzone.autoDiscover = false;
  if($("#document-upload").length>0){
    var dropzone = new Dropzone("#document-upload");
    dropzone.on("success", function(event,res) {
      if(res.result == 0) {
        toastr.error(res.message);
        dropzone.removeAllFiles();
      } else {
        toastr.success(res.message);
        $("#document-upload").hide();
        dropzone.removeAllFiles();
        technician_manager.loadPhoto('technician_id',parseInt( $("input[name=\"itemId\"]").val()));
      }
    });
  }
  $("#tech_photo_upload").on('click',function(e){
    e.preventDefault();
    $("#document-upload").show();
  });
  $(document).on('click','.remove-image',function(e){
    e.preventDefault();
    technician_manager.removePhoto($(this).data("id"));
  });
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        technician_manager.retrieveTechnician(options.$trigger);
      } 
    },
    items: {
      "edit": {name: "Edit"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of technicians
  var technician_ids = "";
  $("#active-list, #inactive-list").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-technician-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        technician_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        technician_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-inactive-list").click(function() {
		var msg = $('#confirmmsg-activate').val();
		if (typeof msg !== typeof undefined && msg !== false) {
			utils.showConfirmBox(msg, function(result){
				if(result)
				{
					technician_manager.updateTechnicians("active", technician_ids);
				}
			});
		}
		else
		{
			technician_manager.updateTechnicians("active", technician_ids);
		}
    
  });
  $(".from-active-list").click(function() {
    technician_manager.updateTechnicians("inactive", technician_ids);
  });
  //************************************************//


  $("#btn-add-technician-names").click(function() {
    technician_manager.add($("textarea[name=\"technician_names\"]").val(), "technician", "add", false);
  });//Add many technicians

  $("#btn-add-technician-manual").click(function() {
    utils.redirect("technician/showForm?mode=add&test=true");
  });//Button click "add a technician"

  $("#btn_add_technician").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("technician_form", ["technician_name","technician_email"]);
    if (post_data.technician_name !== undefined || post_data.required_field_missing != true) {
      technician_manager.add(post_data, "technician", "add", true);
    }
  });//Add a technician

  $("#btn_edit_technician").click(function() {
    var user_post_data = {technician_id:parseInt(utils.getQueryVariable("technician_id")),user_password:$("input[name=\"user_password\"]").val(),user_hint:$("input[name=\"user_hint\"]").val()};
    technician_manager.editUser(user_post_data,"user","editTechnician");
    var post_data = utils.retrieveInputs("technician_form", ["technician_name"]);
    if (post_data.technician_name !== undefined) {
      technician_manager.edit(post_data, "technician", "edit");
    }
  });//Edit a technician

  $("#btn_delete_technician").click(function() {
	var msg = $('#confirmmsg-delete').val();
	if (typeof msg !== typeof undefined && msg !== false) {
	  utils.showConfirmBox(msg, function(result){
		if(result)
		{
			technician_manager.delete(parseInt(utils.getQueryVariable("technician_id")));
		}
	  });
	}
	else
	{
	  technician_manager.delete(parseInt(utils.getQueryVariable("technician_id")));
	}
  });//Delete a technician

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".technician_add").hide();
    technician_manager.getItem(utils.getQueryVariable("technician_id"));
  } else {
    $("#document-upload").hide();
    $("#tech_photo_upload").parent("li").hide();
  }//Load technician

//  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
//    technician_manager.fillFormWithRandomData();
//  }

  $("#technician_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".technician_list").fadeIn('2000').removeClass("hide");
    technician_manager.getList();
  });//Show "List All" panel
});
/***********
 * technician_manager namespace 
 * Responsible to manage technicians.
 */
(function(technician_manager) {
  technician_manager.add = function(userData, controller, action, isSingle) {
    var data = isSingle ? userData : {"names": userData};
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call Technician/Add WebService
      if (reply === null || reply.dataId === undefined || reply.dataId === null || parseInt(reply.dataId) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("technician/listAll", 1000);
      }
    });
  };
  technician_manager.edit = function(technician, controller, action) {
    datacx.post(controller + "/" + action, technician).then(function(reply) {//call AJAX method to call Technician/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //utils.redirect("technician/listAll", 1000);
      }
    });
  };
  technician_manager.editUser = function(technician, controller, action) {
    datacx.post(controller + "/" + action, technician).then(function(reply) {//call AJAX method to call Technician/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //utils.redirect("technician/listAll", 1000);
      }
    });
  };
  technician_manager.getList = function() {
    datacx.post("technician/getlist", null).then(function(reply) {//call AJAX method to call Technician/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        technician_manager.buildOutputList(reply.lists.technicians);
        //Now show the table
      }
    });
  };
  technician_manager.buildOutputList = function(technicians) {
    var active_technicians = "";
    var inactive_technicians = "";
    for (i = 0; i < technicians.length; i++) {
      if (parseInt(technicians[i].active) !== 0) {
        active_technicians += "<option value=\"" + technicians[i].technician_name + "\">" + technicians[i].technician_name + "</option>";
      } else {
        inactive_technicians += "<option value=\"" + technicians[i].technician_name + "\">" + technicians[i].technician_name + "</option>";
      }
    }
    inactive_technicians = utils.isNullOrEmpty(inactive_technicians) ?
            "<option value=\"\">{empty}</option>" : inactive_technicians;
    active_technicians = utils.isNullOrEmpty(active_technicians) ?
            "<option value=\"\">{empty}</option>" : active_technicians;
    $("#technician-data-active, #technician-data-inactive").show();
    $("#technician-data-active").html(active_technicians);
    $("#technician-data-inactive").html(inactive_technicians);
  };
  technician_manager.retrieveTechnician = function(element) {
    utils.redirect("technician/showForm?mode=edit&technician_id=" + parseInt(element.attr("data-technician-id")));
  };
  technician_manager.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"pm_id\"]").val(parseInt(dataWs.technician.pm_id));
    $("input[name=\"technician_id\"]").val(parseInt(dataWs.technician.technician_id));
    $("input[name=\"technician_name\"]").val(dataWs.technician.technician_name);
    $("input[name=\"technician_phone\"]").val(dataWs.technician.technician_phone);
    $("input[name=\"technician_email\"]").val(dataWs.technician.technician_email);
    $("input[name=\"itemCategory\"]").val('technician_id');
    $("input[name=\"itemId\"]").val(parseInt(dataWs.technician.technician_id));
    $("input[name=\"itemReplace\"]").val(true);
    technician_manager.loadPhoto('technician_id',parseInt(dataWs.technician.technician_id));
    $("input[name=\"technician_active\"]").prop('checked', utils.setCheckBoxValue(dataWs.technician.technician_active));
  };
  technician_manager.delete = function(technician_id) {
    datacx.post("technician/delete", {"technician_id": technician_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("technician/listAll");
      }
    });
  };

  technician_manager.getItem = function(technician_id) {
    //get technician object from cache (PHP WS)
    datacx.post("technician/getItem", {"technician_id": technician_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("technician/listAll", 3000)
      } else {//success
        $(".technician_edit").show().removeClass("hide");
        toastr.success(reply.message);
        technician_manager.loadEditForm(reply);
        technician_manager.getUserItem(technician_id);
      }
    });
  };

  technician_manager.updateTechnicians = function(action, arrayId) {
    datacx.post("technician/updateItems", {"action": action, "technician_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("technician/listAll");
      }
    });
  };

  technician_manager.loadPhoto = function(itemCategory, itemId) {
    datacx.post("file/load", {"itemCategory": itemCategory, "itemId": itemId}).then(function(reply){
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        if(reply.fileResults.length>0){
          $.each(reply.fileResults, function(key, file){
            var lightboxImage = utils.createImageLightboxElement(file.webPath, itemId, file.title);
            var remove = utils.createRemoveFileElement(file.document_id);
            $("#documents").append('<div class="document-block" id="document-'+file.document_id+'">'+lightboxImage+remove+'</div>');
            $("#documents").show();
          });
        } else {
          $("#document-upload").show();
        }
      }
    });
  }

  technician_manager.removePhoto = function(document_id) {
    datacx.post("file/remove", {"document_id": document_id, "itemCategory": 'technician_id'}).then(function(reply){
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        $("#documents").hide();
        $("#document-"+document_id).remove();
        $("#document-upload").show();
      }
    });
  }

  technician_manager.getUserItem = function(technician_id) {
    datacx.post("user/getTechnicianItem",{technician_id:technician_id}).then(function(reply) {
      if(reply == null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        technician_manager.loadUserEditForm(reply);
        $("#user_info").show();
      }

    });
  };

  technician_manager.loadUserEditForm = function(dataWs) {
    $("input[name=\"user_hint\"]").val(dataWs.user.user_hint);
  };

}(window.technician_manager = window.technician_manager || {}));
