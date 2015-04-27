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
      }
      else if (key === "set")
      {
        project_manager.setCurrentProject(parseInt(options.$trigger.attr("data-project-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "set": {name: "Select (as current Project)"}
    }
  });//Manages the context menu

  //Auto open prompt when on selecProject view
  utils.showSelectEntityPrompt(
	function() {
	  if ($(".ui-selected").html() !== undefined)
	  {
	    project_manager.setCurrentProject(parseInt($(".ui-selected").attr("data-project-id")));
	  }
	  else
	  {
	    $("#active-list").focus();
	  }
	},
	function() {
	  utils.redirect("project/listAll");
	}
  );
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
    post_data["client"] = utils.retrieveInputs("client_form", ["client_contact_email"]);

    var msgNullCheck = $('#confirmmsg-addNullCheck').val();
    var msgUniqueCheck = $('#confirmmsg-addUniqueCheck').val();
    if (typeof msgNullCheck !== typeof undefined && msgNullCheck !== false &&
            typeof msgUniqueCheck !== typeof undefined && msgUniqueCheck !== false) {
      if (post_data["project"].project_name !== undefined &&
              post_data["facility"].facility_address !== undefined &&
              post_data["facility"].facility_address !== "" &&
              post_data["client"].client_contact_email !== undefined &&
              post_data["client"].client_contact_email !== "")
      {
        project_manager.ifProjectExists(post_data["project"]['project_name'], function(record_count) {
          if (record_count > 0 || post_data["project"].project_name === undefined)
          {
            utils.showAlert(msgUniqueCheck.replace("{0}", post_data["project"].project_name));
          }
          else
          {
            if (post_data["project"].project_name !== undefined &&
                    post_data["facility"].facility_name !== undefined && 
                    post_data["facility"].facility_address !== undefined &&
                    post_data["client"].client_contact_email !== undefined) {
              geocoder = new google.maps.Geocoder();
              geocoderAddress = post_data.facility.facility_address.replace(/(\r\n|\n|\r)/gm,",");
              geocoder.geocode({'address': geocoderAddress}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                  post_data.facility.facility_lat = results[0].geometry.location.lat();
                  post_data.facility.facility_long = results[0].geometry.location.lng();
                  $("#facility_info input[name='facility_lat']").val(post_data.facility.facility_lat);
                  $("#facility_info input[name='facility_long']").val(post_data.facility.facility_long);
                  project_manager.add(post_data, "project", "add");
                } else {
                  utils.showPromptBoxById("address-modal",'',function(){
                    if($("#address-city").val() != '' && $("#address-state") != '') {
                      geocoderAddress = $("#address-city").val()+','+$("#address-state").val();
                      geocoder.geocode({'address': geocoderAddress}, function(resultsFallback, statusFallback){
                        if(statusFallback == google.maps.GeocoderStatus.OK){
                          post_data.facility.facility_lat = resultsFallback[0].geometry.location.lat();
                          post_data.facility.facility_long = resultsFallback[0].geometry.location.lng();
                          $("#facility_info input[name='facility_lat']").val(post_data.facility.facility_lat);
                          $("#facility_info input[name='facility_long']").val(post_data.facility.facility_long);
                          project_manager.add(post_data, "project", "add");
                        } else {
                          confirmMsg = $("#confirmmsg-addAddressCheck").val();
                          utils.showAlert(confirmMsg, function() {
                            utils.togglePromptBox();
                            project_manager.add(post_data, "project", "add");
                          });
                        }
                      });
                    } else {
                      $("#address-city").focus();
                    }
                  },'',function(){});
                }
              });
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
              post_data["facility"].facility_name !== undefined && 
              post_data["facility"].facility_address !== undefined &&
              post_data["client"].client_contact_email !== undefined) {
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

  $('#btn_copy_project').click(function() {
    //alert(options.$trigger.html());
    if (project_manager.prompt_box_msg == null || project_manager.prompt_box_msg == '') {
      project_manager.prompt_box_msg = $('#promptmsg-addNullCheck').val();
    }

    $('#promptmsg-addNullCheck').val(project_manager.prompt_box_msg.replace('{0}', $('input[name=project_name]').val()));
    utils.showPromptBoxById("copy-prompt","addNullCheck", function() {
      if ($('#text_input').val() !== '')
      {
        //Check unique		
        project_manager.ifProjectExists($('#text_input').val(), function(record_count) {
          if (record_count == 0)
          {
            project_manager.getItemforCopy(parseInt(utils.getQueryVariable("project_id")), function(reply) {
              var post_data = {};
              post_data["project"] = reply.sessionProject.project_obj;
              post_data["facility"] = reply.sessionProject.facility_obj;
              post_data["client"] = reply.sessionProject.client_obj;

              //Remove some attributes
              delete(post_data["project"]['project_id']);
              delete(post_data["facility"]['project_id']);
              delete(post_data["facility"]['facility_id']);
              delete(post_data["client"]['project_id']);
              delete(post_data["client"]['client_id']);
              //Set some new attributes
              post_data["project"]['project_name'] = $('#text_input').val();
              console.log(post_data);
              //add
              project_manager.add(post_data, "project", "add", true);

            });
          }
          else
          {
            utils.togglePromptBox();
            var confirmMsg = $('#confirmmsg-addUniqueCheck').val().replace('{0}', $('#text_input').val());
            utils.showAlert(confirmMsg, function() {
              utils.togglePromptBox();
            });
          }
        });

      }
      else
      {
        $('#text_input').focus();
      }

    });
  });//Copy a project

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

  //To keep the original msg from the hidden intact
  project_manager.prompt_box_msg;

  project_manager.add = function(data, controller, action, isCopying) {
    isCopying = isCopying || false;
    datacx.post(controller + "/" + action, data["project"]).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message.replace("project", "project (ID:" + reply.dataId + ")"));
        var facility_data = [];
        var client_data = [];
        if (!isCopying) {
           facility_data = utils.retrieveInputs("facility_form", ["facility_name", "facility_address"]);
           client_data = utils.retrieveInputs("client_form", ["client_contact_email"]);
        } else {
          facility_data = data["facility"];
          client_data = data["client"];
        }

        if (facility_data.facility_name !== undefined && facility_data.facility_address !== undefined) {
          facility_data["project_id"] = reply.dataId;
          facility_manager.send("facility/" + action, facility_data);
        }
        client_data["project_id"] = reply.dataId;
        client_manager.send("client/" + action, client_data, isCopying);
        if (!isCopying) {
          project_manager.fillFormWithRandomData();
         }
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
    //breadcrumb
    $('h3').html('Edit ' + dataWs.project_obj.project_name);
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

  project_manager.getItemforCopy = function(project_id, executeCopy) {
    //get project object from cache (PHP WS)
    datacx.post("project/getItem", {"project_id": project_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        //return reply;
        executeCopy(reply);
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
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val("USA");
    $(".client_form .add-new-item input[name=\"client_company_name\"]").val("Client " + number);
    $(".client_form .add-new-item textarea[name=\"client_address\"]").val(number + " Somewhere");
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

        if ($("#redirectOnSuccess").val() !== undefined) {
          utils.redirect($("#redirectOnSuccess").val());
        }
        else {
          utils.redirect("project/listAll");
        }
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
      decision(reply.record_count);
    });
  };


}(window.project_manager = window.project_manager || {}));
