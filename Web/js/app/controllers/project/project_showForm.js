$(document).ready(function() {
  $("#btn_add_project").click(function() {
    var post_data = {};
    post_data["project"] = utils.retrieveInputs("project_form", ["project_name"]);
    post_data["facility"] = utils.retrieveInputs("facility_form", ["facility_name","facility_address"]);
    //toastr.success("name: " + post_data.project_name + "; number: " + post_data.project_num + "; desc: " + post_data.project_desc + "; active: " + post_data.project_active_flag + " ; visible: " + post_data.project_visible_flag);
    if (post_data["project"].project_name !== undefined &&
            post_data["facility"].facility_name !== undefined && post_data["facility"].facility_address !== undefined) {
      project_manager.add(post_data, "project", "add");
    }
  });//Add a project
  
  $("#btn_edit_project").click(function() {
    var post_data = utils.retrieveInputs("project_form", ["project_name"]);
    if (post_data.project_name !== undefined) {
      project_manager.edit(post_data, "project", "edit");
    }
  });//Edit a project
  
  $("#btn_delete_project").click(function() {
    project_manager.delete($(this));
  });//Delete a project
    
  $("#project_add_left_menu").click(function() {
    project_manager.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".form_sections").fadeIn('2000').removeClass("hide");
    $("#project_add_left_menu").addClass("active");
    $(".project_add").show();
    $(".project_edit").hide();
  });//Show "add a project" panel
  
});
/***********
 * project_manager namespace 
 * Responsible to manage projects.
 */
(function(project_manager) {
  project_manager.add = function(data, controller, action) {
    datacx.post(controller + "/" + action, data["project"]).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.dataOut === undefined || reply.dataOut === null || parseInt(reply.dataOut) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        var post_data = utils.retrieveInputs("facility_form", ["facility_name","facility_address"]);
        data["facility"]['project_id'] = reply.dataOut;
        facility_manager.send("facility/" + action, data["facility"]);
      }
    });
  };
  project_manager.edit = function(project, controller, action) {
    datacx.post(controller + "/" + action, project).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        var post_data = utils.retrieveInputs("facility_form", ["facility_name","facility_address"]);
        if (post_data.facility_name !== undefined && post_data.facility_address !== undefined) {
          facility_manager.send("facility/" + action, post_data);
        }
      }
    });
  };
  project_manager.retrieveProject = function(element) {
    //get project object from cache (PHP WS)
    datacx.post("project/getItem", {"project_id": parseInt(element.attr("data-project-id"))}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        project_manager.loadEditForm(reply);
      }
    });
  };
  project_manager.loadEditForm = function(dataWs) {
    project_manager.clearForm();
    $(".project_form input[name=\"project_id\"]").val(parseInt(dataWs.project.project_id));
    $(".project_form .add-new-p input[name=\"project_name\"]").val(dataWs.project.project_name);
    $(".project_form .add-new-p input[name=\"project_num\"]").val(dataWs.project.project_number);
    $(".project_form .add-new-p input[name=\"project_desc\"]").val(dataWs.project.project_desc);
    facility_manager.loadEditForm(dataWs);
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".project_add").hide();
    $(".project_edit").show().removeClass("hide");
  };
  project_manager.delete = function() {
    //get project object from cache (PHP WS)
    datacx.post("project/delete", {"project_id": parseInt($(".project_form input[name=\"project_id\"]").val())}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("project");
      }
    });
  };
  project_manager.clearForm = function() {
    $(":checked, :text, textarea").each(function(i, data) {
      $(this).val("");
    });
  };
}(window.project_manager = window.project_manager || {}));
