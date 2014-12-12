$(document).ready(function() {
//  validator.requiredInput();//validate the inputs
  $("#btn_add_client").click(function() {
    var post_data = client_manager.retrieveInputs();
    if (post_data.client_name !== undefined && post_data.client_address !== undefined) {
      client_manager.send("client/add",post_data);
      utils.clearForm();
    }
  });
  $("#btn_delete_client").click(function() {
    client_manager.delete($(this));
  });
  $("#btn_edit_client").click(function() {
    var post_data = client_manager.retrieveInputs();
    if (post_data.client_name !== undefined) {
      client_manager.send("client/edit",post_data);
      utils.clearForm();
    }
  });

  $(".select_client").click(function() {
    client_manager.retrieveProject($(this));
  });
  $("#client_add_left_menu").click(function() {
    utils.clearForm();
    $(".client_welcome").fadeOut('2000').removeClass("active").removeClass("show");
    $(".form_sections").fadeIn('2000').removeClass("hide");
    $("#client_add_left_menu").addClass("active");
    $(".client_add").show();
    $(".client_edit").hide();
  });
});
/***********
 * client_manager namespace 
 * Responsible to add a client.
 */
(function(client_manager) {
  client_manager.send = function(ws_url,client) {
    datacx.post(ws_url, client).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message.replace("client", "client (ID:" + reply.dataId + ")"));
        utils.redirect("project/listAll", 3000);
      }
    });
  };  
  client_manager.loadEditForm = function(dataWs) {
    $(".client_form input[name=\"client_id\"]").val(parseInt(dataWs.client_obj.client_id));
    $(".client_form input[name=\"project_id\"]").val(parseInt(dataWs.client_obj.project_id));
    $(".client_form input[name=\"client_company_name\"]").val(dataWs.client_obj.client_company_name);
    $(".client_form .add-new-item textarea[name=\"client_address\"]").val(dataWs.client_obj.client_address);
    $(".client_form input[name=\"client_contact_name\"]").val(dataWs.client_obj.client_contact_name);
    $(".client_form input[name=\"client_contact_phone\"]").val(dataWs.client_obj.client_contact_phone);
    $(".client_form input[name=\"client_contact_email\"]").val(dataWs.client_obj.client_contact_email);   
    
  };
  client_manager.delete = function() {
    //get client object from cache (PHP WS)
    datacx.post("client/delete", {"client_id": parseInt($(".client_form input[name=\"client_id\"]").val())}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("project/listAll");
      }
    });
  };
}(window.client_manager = window.client_manager || {}));