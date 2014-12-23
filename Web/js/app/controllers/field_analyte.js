/**
 * jQuery listeners for the field_analyte actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        field_analyte.retrieveFieldAnalyte(options.$trigger);
      } else if (key === "delete") {
        field_analyte.delete(parseInt(options.$trigger.attr("data-fieldanalyte-id")));
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of field_analytes
  var field_analyte_ids = "";
  $("#active-list, #inactive-list").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-field_analyte-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        field_analyte_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        field_analyte_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-inactive-list").click(function() {
    field_analyte.updateFieldAnalytes("active", field_analyte_ids);
  });
  $(".from-active-list").click(function() {
    field_analyte.updateFieldAnalytes("inactive", field_analyte_ids);
  });
  //************************************************//


  $("#btn-add-field_analyte-names").click(function() {
    var data = { "names": $("textarea[name=\"field_analyte_names\"]").val() };
    field_analyte.add(data, "field_analyte", "add");
  });//Add many field_analytes

  $("#btn-add-field_analyte-manual").click(function() {
    utils.redirect("field_analyte/showForm?mode=add&test=true");
  });//Button click "add a field_analyte"

  $("#btn_add_field_analyte").click(function() {
    var post_data = {};
    post_data = utils.retrieveInputs("field_analyte_form", ["field_analyte_name"]);
    if (post_data.field_analyte_name !== undefined) {
      field_analyte.add(post_data, "field_analyte", "add", true);
    }
  });//Add a field_analyte

  $("#btn_edit_field_analyte").click(function() {
    var post_data = utils.retrieveInputs("field_analyte_form", ["field_analyte_name"]);
    if (post_data.field_analyte_name !== undefined) {
      field_analyte.edit(post_data, "field_analyte", "edit");
    }
  });//Edit a field_analyte

  $("#btn_delete_field_analyte").click(function() {
    field_analyte.delete(parseInt(utils.getQueryVariable("field_analyte_id")));
  });//Delete a field_analyte

  if (utils.getQueryVariable("mode") === "edit") {
    $(".form_sections").fadeIn('2000').addClass("show").removeClass("hide");
    $(".welcome").fadeOut('2000').removeClass("show").addClass("hide");
    $(".field_analyte_add").hide();
    field_analyte.getItem(utils.getQueryVariable("field_analyte_id"));
  }//Load field_analyte

  if (utils.getQueryVariable("mode") === "add" && utils.getQueryVariable("test") === "true") {
    field_analyte.fillFormWithRandomData();
  }

  var alreadyHovered = false;
  $(".select_item").hover(function() {
    if (!alreadyHovered)
      toastr.info("Right-click to edit!");
    alreadyHovered = true;
  });//Show a field_analyte tip

  $("#field_analyte_list_all").click(function() {
    utils.clearForm();
    $(".right-aside section").fadeOut('2000').removeClass("active").removeClass("show");
    $(".field_analyte_list").fadeIn('2000').removeClass("hide");
    field_analyte.getList();
  });//Show "List All" panel

});
/***********
 * field_analyte namespace 
 * Responsible to manage field_analytes.
 */
(function(field_analyte) {
  field_analyte.add = function(data, controller, action, isSingle) {
//    var data = isSingle ? userData : {"names": userData};
    datacx.post(controller + "/" + action, data).then(function(reply) {//call AJAX method to call FieldAnalyte/Add WebService
      if (reply === null || reply.dataId === undefined || reply.dataId === null || parseInt(reply.dataId) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("field_analyte/listAll", 1000);
      }
    });
  };
  field_analyte.edit = function(field_analyte, controller, action) {
    datacx.post(controller + "/" + action, field_analyte).then(function(reply) {//call AJAX method to call FieldAnalyte/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        utils.redirect("field_analyte/listAll", 1000);
      }
    });
  };
  field_analyte.getList = function() {
    datacx.post("field_analyte/getlist", null).then(function(reply) {//call AJAX method to call FieldAnalyte/GetList WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Build the table
        field_analyte.buildOutputList(reply.lists.field_analytes);
        //Now show the table
      }
    });
  };
  field_analyte.buildOutputList = function(field_analytes) {
    var active_field_analytes = "";
    var inactive_field_analytes = "";
    for (i = 0; i < field_analytes.length; i++) {
      if (parseInt(field_analytes[i].active) !== 0) {
        active_field_analytes += "<option value=\"" + field_analytes[i].field_analyte_name + "\">" + field_analytes[i].field_analyte_name + "</option>";
      } else {
        inactive_field_analytes += "<option value=\"" + field_analytes[i].field_analyte_name + "\">" + field_analytes[i].field_analyte_name + "</option>";
      }
    }
    inactive_field_analytes = utils.isNullOrEmpty(inactive_field_analytes) ?
            "<option value=\"\">{empty}</option>" : inactive_field_analytes;
    active_field_analytes = utils.isNullOrEmpty(active_field_analytes) ?
            "<option value=\"\">{empty}</option>" : active_field_analytes;
    $("#field_analyte-data-active, #field_analyte-data-inactive").show();
    $("#field_analyte-data-active").html(active_field_analytes);
    $("#field_analyte-data-inactive").html(inactive_field_analytes);
  };
  field_analyte.retrieveFieldAnalyte = function(element) {
    utils.redirect("field_analyte/showForm?mode=edit&field_analyte_id=" + parseInt(element.attr("data-fieldanalyte-id")));
  };
  field_analyte.loadEditForm = function(dataWs) {
    utils.clearForm();
    $("input[name=\"field_analyte_id\"]").val(parseInt(dataWs.field_analyte.field_analyte_id));
    $("input[name=\"field_analyte_name_unit\"]").val(dataWs.field_analyte.field_analyte_name_unit);
  };
  field_analyte.delete = function(field_analyte_id) {
    datacx.post("field_analyte/delete", {"field_analyte_id": field_analyte_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        //$("li[data-field_analyte-id="+ field_analyte_id +"]").remove();
        utils.redirect("field_analyte/listAll");
      }
    });
  };

  field_analyte.getItem = function(field_analyte_id) {
    //get field_analyte object from cache (PHP WS)
    datacx.post("field_analyte/getItem", {"field_analyte_id": field_analyte_id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        $(".form_sections").hide();
        utils.redirect("field_analyte/listAll", 3000)
      } else {//success
        $(".field_analyte_edit").show().removeClass("hide");
        toastr.success(reply.message);
        field_analyte.loadEditForm(reply);
      }
    });
  };

  field_analyte.fillFormWithRandomData = function() {
    utils.clearForm();
    var number = Math.floor((Math.random() * 100) + 1);
    $(".field_analyte_form input[name=\"field_analyte_name\"]").val("FieldAnalyte " + number);
    $("input[name=\"field_analyte_num\"]").val("n-" + number);
    $("input[name=\"field_analyte_desc\"]").val("Description " + number);
    $(".facility_form .add-new-item input[name=\"facility_name\"]").val("Facility " + number);
    $(".facility_form .add-new-item textarea[name=\"facility_address\"]").val(number + " St of Somewhere\nCity\nCountry");
  };

  field_analyte.updateFieldAnalytes = function(action, arrayId) {
    datacx.post("field_analyte/updateItems", {"action": action, "field_analyte_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("field_analyte/listAll");
      }
    });
  };

}(window.field_analyte = window.field_analyte || {}));
