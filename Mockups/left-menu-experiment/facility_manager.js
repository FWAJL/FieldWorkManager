$(document).ready(function() {
//  validator.requiredInput();//validate the inputs
  $("#btn_add_facility").click(function() {
    var post_data = facility_manager.retrieveInputs();
    if (post_data.facility_name !== undefined && post_data.facility_address !== undefined) {
      facility_manager.send("facility/add",post_data);
      facility_manager.clearForm();
    }
  });
  $("#btn_delete_facility").click(function() {
    facility_manager.delete($(this));
  });
  $("#btn_edit_facility").click(function() {
    var post_data = facility_manager.retrieveInputs();
    if (post_data.facility_name !== undefined) {
      facility_manager.send("facility/edit",post_data);
      facility_manager.clearForm();
    }
  });

  $(".select_facility").click(function() {
    facility_manager.retrieveProject($(this));
  });
  $("#facility_add_left_menu").click(function() {
    facility_manager.clearForm();
    $(".facility_welcome").fadeOut('2000').removeClass("active").removeClass("show");
    $(".form_sections").fadeIn('2000').removeClass("hide");
    $("#facility_add_left_menu").addClass("active");
    $(".facility_add").show();
    $(".facility_edit").hide();
  });
});
/***********
 * facility_manager namespace 
 * Responsible to add a facility.
 */
(function(facility_manager) {
  facility_manager.retrieveInputs = function() {
    var user_inputs = {};
    //user_inputs[$(".project_form input[name=\"project_id\"]").attr("name")] = $(".project_form input[name=\"project_id\"]").val();
    $(".facility_form input, .facility_form textarea").each(function(i, data) {
      if (facility_manager.checkLiElement($(this))) {
        if ($(this).attr("type") === "text") {
          user_inputs[$(this).attr("name")] = $(this).val();
        } else {//checkbox
          user_inputs[$(this).attr("name")] = $(this).is(":checked");
        }
      } else {
        toastr.error("The field " + $(this).attr("name") + " is empty. Please fill out all fields.");
        return null;
      }
    });
    return user_inputs;
  };
  facility_manager.send = function(ws_url,facility) {
    datacx.post(ws_url, facility).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("project");
      }
    });
  };
  facility_manager.checkLiElement = function(element) {
    if (element.attr("name") === "facility_name" || element.attr("name") === "facility_address") {
      return element.val() !== "" ? true : false;
    } else {
      return true;
    }
  };
  facility_manager.retrieveProject = function(element) {
    //get facility object from cache (PHP WS)
    datacx.post("facility/getItem", {"facility_id": parseInt(element.attr("data-facility-id"))}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        facility_manager.loadEditForm(reply.facility);
      }
    });
  };
  facility_manager.loadEditForm = function(dataWs) {
    facility_manager.clearForm();
    $(".facility_form input[name=\"facility_id\"]").val(parseInt(dataWs.facility.facility_id));
    $(".facility_form .add-new-item input[name=\"facility_name\"]").val(dataWs.facility.facility_name);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(dataWs.facility.facility_address);
  };
  facility_manager.delete = function() {
    //get facility object from cache (PHP WS)
    datacx.post("facility/delete", {"facility_id": parseInt($(".facility_form input[name=\"facility_id\"]").val())}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("facility");
      }
    });
  };
  facility_manager.clearForm = function() {
    $(".facility_form input").each(function(i, data) {
      $(this).val("");
    });
  };
}(window.facility_manager = window.facility_manager || {}));