/**
 * jQuery listeners for the analyte_manager actions
 */
$(document).ready(function() {

  var ajaxParams = {
    "ajaxUrl": "",
    "redirectUrl": "analyte/listAll",
    "action": "",
    "arrayOfValues": [],
    "itemId": 0,
    "isFieldType": true
  };

  /* Context menu */
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {
        toastr.info("To be implemented.");
      } else if (key === "delete") {
        var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
        ajaxParams.itemId = parseInt(options.$trigger.attr("data-fieldanalyte-id"));
        ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/delete" : "lab_analyte/delete";
        datacx.delete(ajaxParams);
      }
    },
    items: {
      "edit": {name: "View Info"},
      "delete": {name: "Delete"}
    }
  });//The context menu
  
  // Selection in the dual lists
  var selectionParamsFieldAnalytes = {
    "listLeftId": "field-analyte-list",
    "listRightId": "project-field-analyte-list",
    "dataAttrLeft": "data-fieldanalyte-id",
    "dataAttrRight": "data-fieldanalyte-id"
  };
  utils.dualListSelection(selectionParamsFieldAnalytes);
  var selectionParamsLabAnalytes = {
    "listLeftId": "lab-analyte-list",
    "listRightId": "project-lab-analyte-list",
    "dataAttrLeft": "data-labanalyte-id",
    "dataAttrRight": "data-labanalyte-id"
  };
  utils.dualListSelection(selectionParamsLabAnalytes);
  /* End dual selection */

  $(".from-field-analyte-list, .from-lab-analyte-list").click(function() {
    var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
    ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/updateItems" : "lab_analyte/updateItems";
    ajaxParams.action = "add";
    if (isFieldAnalyte) {
      ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParamsFieldAnalytes.listLeftId, selectionParamsFieldAnalytes.dataAttrLeft);
    } else {
      ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParamsLabAnalytes.listLeftId, selectionParamsLabAnalytes.dataAttrLeft);
    }
    datacx.updateItems(ajaxParams);
  });
  $(".from-project-field-analyte-list, .from-project-lab-analyte-list").click(function() {
    var isFieldAnalyte  = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
    ajaxParams.action = "remove";
    ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/updateItems" : "lab_analyte/updateItems";
    if (isFieldAnalyte) {
      ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParamsFieldAnalytes.listRightId, selectionParamsFieldAnalytes.dataAttrRight);
    } else {
      ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParamsLabAnalytes.listRightId, selectionParamsLabAnalytes.dataAttrRight);
    }
    datacx.updateItems(ajaxParams);
  });

  $(".analyte-names textarea").focus(function() {
    $(".analyte-names textarea").val();
    $('#btn_add_analyte').attr("name", $(this).attr("name"));
  });
  $("#btn_add_analyte").click(function() {
    var isFieldAnalyte = $(this).attr("name") === "field_analyte_names";
    var getValuesParams = {
        "attrNameValues": $(this).attr("name"),
      "attrNameCheckBox": "",
      "hasCheckBoxActive": false
    };
    ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/add" : "lab_analyte/add";
    ajaxParams.arrayOfValues = utils.getValuesFromTextArea(getValuesParams);
    datacx.add(ajaxParams);
  });
});