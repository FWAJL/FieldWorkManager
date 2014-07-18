$(document).ready(function() {
//  validator.requiredInput();//validate the inputs
  $("#btn_add_project").click(function() {
    var post_data = project_manager.retrieveInputs();
    toastr.success("name: " + post_data.project_name + "; number: " + post_data.project_num + "; desc: " + post_data.project_desc + "; active: " + post_data.project_active_flag + " ; visible: " + post_data.project_visible_flag);
    if (post_data.project_name !== undefined) {
     project_manager.send(post_data);
     project_manager.clearForm();
    }
  });
});
/***********
 * project_manager namespace 
 * Responsible to add a project.
 */
(function(project_manager) {
  project_manager.retrieveInputs = function() {
    var user_inputs = {};
    $(".project_form input").each(function(i, data) {
      if (project_manager.checkLiElement($(this))) {
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
  project_manager.send = function(project) {
    datacx.post("project/add", project).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        project_manager.getList();
      }
    });
  };
  project_manager.getList = function() {
    datacx.post("project/getlist", null).then(function(reply) {//call AJAX method to call Project/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        project_manager.buildTableList(reply.projects);
        //Now show the table
        $(".form_sections").fadeOut('2000').removeClass("active").removeClass("show");
        $(".project_welcome").fadeIn('2000').addClass("active").removeClass("hide");
        $("#project_add").removeClass("active");
      }
    });
  };
  project_manager.buildTableList = function(projects) {
  };
  project_manager.checkLiElement = function(element) {
    if (element.attr("name") === "project_name") {
      return element.val()  !== "" ? true : false;
    } else {
      return true;
    }
  };
  project_manager.clearForm = function() {
    $(".project_form input").each(function(i, data) {
      $(this).val("");
    });
  };
}(window.project_manager = window.project_manager || {}));