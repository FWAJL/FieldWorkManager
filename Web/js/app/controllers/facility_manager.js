$(document).ready(function() {
//  validator.requiredInput();//validate the inputs
  $("#btn_add_facility").click(function() {
    var post_data = facility_manager.retrieveInputs();
    if (post_data.facility_name !== undefined && post_data.facility_address !== undefined) {
      facility_manager.send("facility/add",post_data);
      utils.clearForm();
    }
  });
  $("#btn_delete_facility").click(function() {
    facility_manager.delete($(this));
  });
  $("#btn_edit_facility").click(function() {
    var post_data = facility_manager.retrieveInputs();
    if (post_data.facility_name !== undefined) {
      facility_manager.send("facility/edit",post_data);
      utils.clearForm();
    }
  });

  $(".select_facility").click(function() {
    facility_manager.retrieveProject($(this));
  });
  $("#facility_add_left_menu").click(function() {
    utils.clearForm();
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
  facility_manager.send = function(ws_url,facility) {
    datacx.post(ws_url, facility).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message.replace("facility", "facility (ID:" + reply.dataId + ")"));
        //utils.redirect("project/listAll");
      }
    });
  };
  facility_manager.retrieveFacility = function(element) {
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
    $(".facility_form input[name=\"facility_id\"]").val(parseInt(dataWs.facility_obj.facility_id));
    $(".facility_form input[name=\"project_id\"]").val(parseInt(dataWs.facility_obj.project_id));
    $(".facility_form input[name=\"facility_name\"]").val(dataWs.facility_obj.facility_name);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(dataWs.facility_obj.facility_address);
    $(".facility_form input[name=\"facility_lat\"]").val(dataWs.facility_obj.facility_lat);
    $(".facility_form input[name=\"facility_long\"]").val(dataWs.facility_obj.facility_long);
    $(".facility_form input[name=\"facility_contact_name\"]").val(dataWs.facility_obj.facility_contact_name);
    $(".facility_form input[name=\"facility_contact_phone\"]").val(dataWs.facility_obj.facility_contact_phone);
    $(".facility_form input[name=\"facility_contact_email\"]").val(dataWs.facility_obj.facility_contact_email);
    $(".facility_form input[name=\"facility_id_num\"]").val(dataWs.facility_obj.facility_id_num);
    $(".facility_form input[name=\"facility_sector\"]").val(dataWs.facility_obj.facility_sector);
    $(".facility_form input[name=\"facility_sic\"]").val(dataWs.facility_obj.facility_sic);   
    
  };
  facility_manager.delete = function() {
    //get facility object from cache (PHP WS)
    datacx.post("facility/delete", {"facility_id": parseInt($(".facility_form input[name=\"facility_id\"]").val())}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("project/listAll");
      }
    });
  };
}(window.facility_manager = window.facility_manager || {}));