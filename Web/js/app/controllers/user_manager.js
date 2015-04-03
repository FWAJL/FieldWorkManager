/**
 * IMPORTANT NOTICE (29-12-14):
 *   LOOK AT analyte_manager for the new implementation
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *
 * jQuery listeners for the user actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();

  $("#btn_edit_user").click(function() {
    var post_data = utils.retrieveInputs("user_form", ["user_type"]);
    user_manager.edit(post_data, "user", "editCurrent");
  });//Edit a user

  $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
  $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
  user_manager.getCurrent();


});
/***********
 * user_manager namespace
 * Responsible to manage users.
 */
(function(user_manager) {

  user_manager.edit = function(user, controller, action) {
    datacx.post(controller + "/" + action, user).then(function(reply) {//call AJAX method to call Pm/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        location.reload();
      }
    });
  };

  user_manager.loadPMEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"pm_id\"]").val(parseInt(dataWs.user.pm_id));
    $("input[name=\"user_type\"]").val(dataWs.user_type);
    $("input[name=\"pm_name\"]").val(dataWs.user.pm_name);
    $("input[name=\"pm_address\"]").val(dataWs.user.pm_address);
    $("input[name=\"pm_comp_name\"]").val(dataWs.user.pm_comp_name);
    $("input[name=\"pm_phone\"]").val(dataWs.user.pm_phone);
    $("input[name=\"pm_email\"]").val(dataWs.user.pm_email);
    $("input[name=\"pm_active\"]").val(dataWs.user.pm_active);
  };

  user_manager.getCurrent = function() {
    //get current user object from cache (PHP WS)
    datacx.post("user/getCurrent").then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
      } else {//success

        toastr.success(reply.message);
        if(reply.user_type=="pm_id") {
          user_manager.loadPMEditForm(reply);
          $(".pm_edit").show().removeClass("hide");
        }

      }
    });
  };


}(window.user_manager = window.user_manager || {}));
