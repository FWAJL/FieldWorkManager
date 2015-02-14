/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the project actions
 */
$(document).ready(function() {
 $(".btn-warning").hide();
 $.contextMenu({
  selector: '.select_item',
  callback: function(key, options) {
   if (key === "edit") {
    project_manager.retrieveProject(options.$trigger);
   } else if (key === "delete") {
    var msg = $('#confirmmsg-delete').val();
    if (typeof msg !== typeof undefined && msg !== false) {
     utils.showConfirmBox(msg, function(result) {
      if (result)
      {
       project_manager.delete(parseInt(options.$trigger.attr("data-project-id")));
      }
     });
    }
    else
    {
     project_manager.delete(parseInt(options.$trigger.attr("data-project-id")));
    }
   }
  },
  items: {
   "edit": {name: "View Info"},
   "delete": {name: "Delete"},
   "copy": {name: "Copy"}
  }
 });//Manages the context menu

 //************************************************//
 // Selection of projects
 var project_ids = [];
 $("#active-list, #inactive-list").selectable({
  stop: function() {
   var tmpSelection = "";
   $(".ui-selected", this).each(function() {
    tmpSelection += $(this).attr("data-project-id") + ",";
   });
   tmpSelection = utils.removeLastChar(tmpSelection);
   if (tmpSelection.length > 0) {
    project_ids = tmpSelection;
    project_manager.hideSetCurrentProject(project_ids.split(",").length === 1);
    $(".from-" + $(this).attr("id")).show();
   } else {
    project_ids = [];
    project_manager.hideSetCurrentProject(project_ids.length === 1);
    $(".from-" + $(this).attr("id")).hide();
   }
  }
 });
 $(".from-inactive-list").click(function() {
  var msg = $('#confirmmsg-activate').val();
  if (typeof msg !== typeof undefined && msg !== false) {
   utils.showConfirmBox(msg, function(result) {
    if (result)
    {
     project_manager.updateProjects("active", project_ids);
    }
   });
  }
  else
  {
   project_manager.updateProjects("active", project_ids);
  }
 });
 $(".from-active-list").click(function() {
  project_manager.updateProjects("inactive", project_ids);
 });

 //************************************************//
 $(".btn_set_current_project").click(function() {
  project_manager.setCurrentProject(project_ids.split(",")[0]);
 });


 $("#btn_add_project").click(function() {

  var post_data = {};
  post_data["project"] = utils.retrieveInputs("project_form", ["project_name"]);
  post_data["facility"] = utils.retrieveInputs("facility_form", ["facility_name"]);
  post_data["client"] = utils.retrieveInputs();

  var msgNullCheck = $('#confirmmsg-addNullCheck').val();
  var msgUniqueCheck = $('#confirmmsg-addUniqueCheck').val();
  if (typeof msgNullCheck !== typeof undefined && msgNullCheck !== false &&
          typeof msgUniqueCheck !== typeof undefined && msgUniqueCheck !== false) {
   if (post_data["project"].project_name !== undefined &&
           post_data["facility"].facility_address !== undefined &&
           post_data["facility"].facility_address !== "")
   {
    project_manager.ifProjectExists(post_data["project"]['project_name'], function(record_count) {
     if (record_count > 0 || post_data["project"].project_name === undefined)
     {
      utils.showAlert(msgUniqueCheck.replace("{0}", post_data["project"].project_name));
     }
     else
     {
      if (post_data["project"].project_name !== undefined &&
              post_data["facility"].facility_name !== undefined && post_data["facility"].facility_address !== undefined) {
       project_manager.add(post_data, "project", "add");
      }
     }
    });
   }
   else
   {
    utils.showAlert(msgNullCheck);
   }
  }
  else
  {
   if (post_data["project"].project_name !== undefined &&
           post_data["facility"].facility_name !== undefined && post_data["facility"].facility_address !== undefined) {
    project_manager.add(post_data, "project", "add");
   }
  }
 });//Add a project

 $("#btn_edit_project").click(function() {
  var post_data = utils.retrieveInputs("project_form", ["project_name"]);
  if (post_data.project_name !== undefined) {
   project_manager.edit(post_data, "project", "edit");
  }
 });//Edit a project

 $("#btn_delete_project").click(function() {
  var msg = $('#confirmmsg-delete').val();
  if (typeof msg !== typeof undefined && msg !== false) {
   utils.showConfirmBox(msg, function(result) {
    if (result)
    {
     project_manager.delete(parseInt(utils.getQueryVariable("project_id")));
    }
   });
  }
  else
  {
   project_manager.delete(parseInt(utils.getQueryVariable("project_id")));
  }
 });//Delete a project

 if (utils.getQueryVariable("mode") === "edit") {
  $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
  $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
  $(".project_add").hide();
  project_manager.getItem(utils.getQueryVariable("project_id"));
 }//Load project

 if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
  project_manager.fillFormWithRandomData();
 }

 $("#project_list_all").click(function() {
  utils.clearForm();
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
 project_manager.add = function(data, controller, action) {
  datacx.post(controller + "/" + action, data["project"]).then(function(reply) {//call AJAX method to call Project/Add WebService
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
   } else {//success
    toastr.success(reply.message.replace("project", "project (ID:" + reply.dataId + ")"));

    var facility_data = utils.retrieveInputs("facility_form", ["facility_name", "facility_address"]);
    if (facility_data.facility_name !== undefined && facility_data.facility_address !== undefined) {
     facility_data["project_id"] = reply.dataId;
     facility_manager.send("facility/" + action, facility_data);
    }
    var client_data = utils.retrieveInputs("client_form", []);
    client_data["project_id"] = reply.dataId;
    client_manager.send("client/" + action, client_data);
    project_manager.fillFormWithRandomData();
   }
  });
 };
 project_manager.edit = function(project, controller, action) {
  datacx.post(controller + "/" + action, project).then(function(reply) {//call AJAX method to call Project/Add WebService
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
   } else {//success
    toastr.success(reply.message.replace("project", "project (ID:" + reply.dataId + ")"));

    var post_data = utils.retrieveInputs("facility_form", ["facility_name", "facility_address"]);
    if (post_data.facility_name !== undefined && post_data.facility_address !== undefined) {
     facility_manager.send("facility/" + action, post_data);
    }
    var client_data = utils.retrieveInputs("client_form", []);
    client_manager.send("client/" + action, client_data);
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
    active_projects += "<option value=\"" + projects[i].project_name + "\">" + projects[i].project_name + "</option>";
   } else {
    inactive_projects += "<option value=\"" + projects[i].project_name + "\">" + projects[i].project_name + "</option>";
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
  utils.redirect("project/showForm?mode=edit&project_id=" + parseInt(element.attr("data-project-id")));
 };
 project_manager.show_on_map = function(id) {
  utils.redirect("map/showOne?id=" + id);
 };
 project_manager.loadEditForm = function(dataWs) {
  utils.clearForm();
  $(".project_form input[name=\"project_id\"]").val(parseInt(dataWs.project_obj.project_id));
  $(".project_form .add-new-item input[name=\"project_name\"]").val(dataWs.project_obj.project_name);
  $(".project_form .add-new-item input[name=\"project_number\"]").val(dataWs.project_obj.project_number);
  $(".project_form .add-new-item input[name=\"project_desc\"]").val(dataWs.project_obj.project_desc);
  $(".project_form .add-new-item input[name=\"project_active\"]").prop('checked', utils.setCheckBoxValue(dataWs.project_obj.project_active));
  $(".project_form .add-new-item input[name=\"project_visible\"]").val(dataWs.project_obj.project_visible);
  facility_manager.loadEditForm(dataWs);
  client_manager.loadEditForm(dataWs);
 };
 project_manager.delete = function(project_id) {
  datacx.post("project/delete", {"project_id": project_id}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
    return undefined;
   } else {//success
    toastr.success(reply.message);
    //$("li[data-project-id="+ project_id +"]").remove();
    utils.redirect("project/listAll");
   }
  });
 };

 project_manager.getItem = function(project_id) {
  //get project object from cache (PHP WS)
  datacx.post("project/getItem", {"project_id": project_id}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
    $(".form_sections").hide();
    //utils.redirect("project/listAll", 3000)
   } else {//success
    $(".project_edit").show().removeClass("hide");
    toastr.success(reply.message);
    project_manager.loadEditForm(reply.sessionProject);
   }
  });
 };

 project_manager.fillFormWithRandomData = function() {
  utils.clearForm();
  var number = Math.floor((Math.random() * 1000) + 1);
  $(".project_form input[name=\"project_name\"]").val("Project " + number);
  $(".project_form .add-new-item input[name=\"project_number\"]").val("n-" + number);
  $(".project_form .add-new-item input[name=\"project_desc\"]").val("Description " + number);
  $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
  $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  $(".client_form .add-new-item input[name=\"client_company_name\"]").val("Client " + number);
  $(".client_form .add-new-item textarea[name=\"client_address\"]").val(number + " Av of There\nCity\nCountry");
  $(".client_form .add-new-item input[name=\"client_contact_phone\"]").val(Math.floor(Math.random() * 1000000000));
 };

 project_manager.updateProjects = function(action, arrayId) {
  datacx.post("project/updateItems", {"action": action, "project_ids": arrayId}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
    return undefined;
   } else {//success
    toastr.success(reply.message);
    utils.redirect("project/listAll");
   }
  });
 };
 project_manager.setCurrentProject = function(projectId) {
  datacx.post("project/setCurrentProject", {"project_id": projectId}).then(function(reply) {
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
    return undefined;
   } else {//success
    toastr.success(reply.message.replace("project", "project (ID:" + reply.dataId + ")"));
    utils.redirect("project/listAll");
   }
  });
 };
 project_manager.hideSetCurrentProject = function(showButton) {
  if (!showButton) {
   $(".btn_set_current_project").hide();
  } else {
   $(".btn_set_current_project").show();
  }

 };

 project_manager.ifProjectExists = function(projectName, decision) {
  datacx.post("project/ifProjectExists", {project_name: projectName}).then(function(reply) {
   //alert(reply.record_count);
   decision(reply.record_count);
  });
 };


}(window.project_manager = window.project_manager || {}));
