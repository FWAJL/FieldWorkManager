$(document).ready(function() {
  $(".select_item").click(function() {
    project_manager.clearForm();
    project_manager.retrieveProject($(this));
  });//Select a project
  
  $("#project_list_all").click(function() {
    project_manager.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".project_list").fadeIn('2000').removeClass("hide");
    project_manager.getList();
  });//Show "List All" panel
  
});
/***********
 * project_manager namespace 
 * Responsible to manage projects.
 */
(function(project_manager) {
  project_manager.getList = function() {
    datacx.post("project/getlist", null).then(function(reply) {//call AJAX method to call Project/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        project_manager.buildOutputList(reply.lists.projects);
        //Now show the table
      }
    });
  };
  project_manager.buildOutputList = function(projects) {
    var active_projects = "";
    var inactive_projects = "";
    for (i = 0; i < projects.length; i++) {
      if (parseInt(projects[i].active) !== 0) {
       active_projects += "<option value=\""+ projects[i].project_name +"\">" + projects[i].project_name + "</option>"; 
      } else {
       inactive_projects += "<option value=\""+ projects[i].project_name +"\">" + projects[i].project_name + "</option>"; 
      }
    }
    inactive_projects = utils.isNullOrEmpty(inactive_projects) ? 
      "<option value=\"\">{empty}</option>" : inactive_projects;
    active_projects = utils.isNullOrEmpty(active_projects) ? 
      "<option value=\"\">{empty}</option>" : active_projects;
    $("#project-data-active, #project-data-inactive").show();
    $("#project-data-active").html(active_projects);
    $("#project-data-inactive").html(inactive_projects);
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
}(window.project_manager = window.project_manager || {}));
