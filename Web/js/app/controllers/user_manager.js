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
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "delete") {
        user_manager.delete(parseInt(options.$trigger.data("user-id")));
      } else if (key === "edit") {
        utils.redirect("user/showForm?mode=edit&user_id="+parseInt(options.$trigger.data("user-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu



  $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
  $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
  if($(".user_details").length>0) {
    user_manager.getCurrent();
  } else if ($(".add_delete_user").length>0) {
    $("#user_info").show();
  }

  $("#user-type").change(function(){
    //add for each form
    if($(this).val() === 'pm_id') {
      $(".user-type-form fieldset").removeClass('user_form');
      $(".user-type-form").hide();
      $("#pm_edit_info").show();
      $(".pm_form").addClass('user_form');
    } else {
      $(".user-type-form fieldset").removeClass('user_form');
      $(".user-type-form").hide();
    }
  });
  $("#btn_add_user").on('click',function(){
    var requiredFields = ["user_login","user_password","user_hint"];
    if($("#user-type").val()==='pm_id'){
      requiredFields.push("pm_name","pm_email");
    }
    var post_data = utils.retrieveInputs("user_form",requiredFields);
    if(!post_data.required_field_missing) {
      user_manager.add(post_data);
    }
  });

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".user_add").hide();
    $(".user_delete").show();
    $(".user_edit").show();
    user_manager.getItem(parseInt(utils.getQueryVariable("user_id")));

    $("#btn_delete_user").on('click',function(){
      var user_id = parseInt($("input[name=\"user_id\"]").val());
      user_manager.delete(user_id);
    });

    $("#btn_edit_user").on('click',function(){
      var requiredFields = ["user_login","user_hint"];
      if($("#user-type").val()==='pm_id'){
        requiredFields.push("pm_name","pm_email");
      }
      var post_data = utils.retrieveInputs("user_form",requiredFields);
      if(!post_data.required_field_missing) {
        user_manager.edit(post_data,'user','edit');
      }
    });

  } else {
    $("#btn_edit_user").click(function() {
      var post_data = utils.retrieveInputs("pm_form", ["pm_name"]);
      post_data.user_password=$("input[name=\"user_password\"]").val();
      post_data.user_hint=$("input[name=\"user_hint\"]").val();
      if(!post_data.required_field_missing) {
        user_manager.edit(post_data, "user", "editCurrent");
      }

    });//Edit current user
  }

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

  user_manager.delete = function(user_id) {
    datacx.post("user/delete",{user_id:user_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect('user/listAll',500);
      }
    });
  };

  user_manager.loadPMEditForm = function(dataWs) {
    $("input[name=\"pm_id\"]").val(parseInt(dataWs.pm.pm_id));
    $("input[name=\"pm_name\"]").val(dataWs.pm.pm_name);
    $("input[name=\"pm_address\"]").val(dataWs.pm.pm_address);
    $("input[name=\"pm_comp_name\"]").val(dataWs.pm.pm_comp_name);
    $("input[name=\"pm_phone\"]").val(dataWs.pm.pm_phone);
    $("input[name=\"pm_email\"]").val(dataWs.pm.pm_email);
  };

  user_manager.loadUserEditForm = function(dataWs) {
    $("input[name=\"user_id\"]").val(parseInt(dataWs.user.user_id));
    $("input[name=\"user_login\"]").val(dataWs.user.user_login);
    $("input[name=\"user_hint\"]").val(dataWs.user.user_hint);
  };

  user_manager.getCurrent = function() {
    //get current user object from cache (PHP WS)
    datacx.post("user/getCurrent").then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
      } else {//success
        utils.clearForm();
        toastr.success(reply.message);
        user_manager.loadUserEditForm(reply);
        if(reply.user_type=="pm_id") {
          user_manager.loadPMEditForm(reply);
          $(".pm_edit").show().removeClass("hide");
        }

      }
    });
  };

  user_manager.getItem = function(user_id) {
    //get current user object from cache (PHP WS)
    datacx.post("user/getItem",{user_id:user_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect('user/listAll',300);
      } else {//success
        utils.clearForm();
        toastr.success(reply.message);
        user_manager.loadUserEditForm(reply);
        $(".user_edit").show().removeClass("hide");
        if(reply.user.user_type=="pm_id") {
          user_manager.loadPMEditForm(reply);
          $(".user-type-form fieldset").removeClass('user_form');
          $(".user-type-form").hide();
          $("#pm_edit_info").show();
          $(".pm_form").addClass('user_form');
          $(".pm_edit").show().removeClass("hide");
          $("#user-type").hide();
        }

      }
    });
  };

  user_manager.add = function(post_data) {
    datacx.post('user/add',post_data).then(function(reply){
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
      } else {//success
        toastr.success(reply.message);
        utils.redirect("user/listAll",300);
      }
    });
  };


}(window.user_manager = window.user_manager || {}));
