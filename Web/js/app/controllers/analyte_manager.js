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
  "isFieldType": true,
  "isCommon": true
 };

 /* Context menu */
 $.contextMenu({
  selector: '.select_item',
  callback: function(key, options) {
   if (key === "edit") {
    toastr.info("To be implemented.");
   } else if (key === "delete") {
    var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
    ajaxParams.itemId =
            isFieldAnalyte ?
            parseInt(options.$trigger.attr("data-fieldanalyte-id")) :
            parseInt(options.$trigger.attr("data-labanalyte-id"));
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
 var selectionParamsCommonAnalytes = {
  "listLeftId": "common-field-analyte-list",
  "listRightId": "common-lab-analyte-list",
  "dataAttrLeft": "data-common_field_analyte-id",
  "dataAttrRight": "data-common_lab_analyte-id"
 };
 utils.dualListSelection(selectionParamsCommonAnalytes);

 /* End dual selection */

 $(".from-field-analyte-list, .from-lab-analyte-list").click(function() {
  var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
  ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/updateItems" : "lab_analyte/updateItems";
  ajaxParams.action = "add";
  if (isFieldAnalyte) {
   ajaxParams.arrayOfValues =
           utils.getValuesFromList(
                   selectionParamsFieldAnalytes.listLeftId,
                   selectionParamsFieldAnalytes.dataAttrLeft, true);
  } else {
   ajaxParams.arrayOfValues =
           utils.getValuesFromList(
                   selectionParamsLabAnalytes.listLeftId,
                   selectionParamsLabAnalytes.dataAttrLeft, true);
  }
  datacx.updateItems(ajaxParams);
 });
 $(".from-project-field-analyte-list, .from-project-lab-analyte-list").click(function() {
  var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
  ajaxParams.action = "remove";
  ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/updateItems" : "lab_analyte/updateItems";
  if (isFieldAnalyte) {
   ajaxParams.arrayOfValues =
           utils.getValuesFromList(
                   selectionParamsFieldAnalytes.listRightId,
                   selectionParamsFieldAnalytes.dataAttrRight, true);
  } else {
   ajaxParams.arrayOfValues =
           utils.getValuesFromList(
                   selectionParamsLabAnalytes.listRightId,
                   selectionParamsLabAnalytes.dataAttrRight, true);
  }
  datacx.updateItems(ajaxParams);
 });

 $("textarea").focus(function() {
  $('.btn-add-analyte').attr("name", $(this).attr("name"));
 });
 $("textarea").focusout(function() {
  if ($(this).val() === "")
   $('.btn-add-analyte').removeAttr($(this).attr("name"));
 });
 $()

 $(".btn-add-analyte").click(function() {
  ajaxParams.isFieldType = utils.containsStr($(this).attr("name"), "^(.*field.*)$");
  ajaxParams.isCommon = utils.containsStr($(this).attr("name"), "^(common.*)$");

  var getValuesParams = {
   "attrNameValues": $(this).attr("name"),
   "attrNameCheckBox": "",
   "hasCheckBoxActive": false
  };
  if (ajaxParams.isCommon) {
   ajaxParams.ajaxUrl = ajaxParams.isFieldType ? "field_analyte/addCommon" : "lab_analyte/addCommon";
   ajaxParams.redirectUrl = "";
  } else {
   ajaxParams.ajaxUrl = ajaxParams.isFieldType ? "field_analyte/add" : "lab_analyte/add";
  }
  ajaxParams.arrayOfValues = utils.getValuesFromTextArea(getValuesParams);
  if (ajaxParams.arrayOfValues.names !== undefined && ajaxParams.arrayOfValues.names !== "") {
   datacx.add(ajaxParams);
  } else {
   toastr.error("Please type some analyte names or select at least one from the right list.");
  }
 });
 //************************************************//
 // Selection of service technicians
 var selectionParams = {
  "fieldListId": "common-field-analyte-list",
  "labListId": "common-lab-analyte-list",
  "dataAttrFA": "data-common_field_analyte-id",
  "dataAttrLA": "data-common_lab_analyte-id"
 };

 $("button[data-analyte-type]").click(function() {
  var analytesSelected = [];
  var analytesTyped = [];
  var params = {
   "fa_attr": "field_analyte_name_unit",
   "la_attr": "lab_analyte_name",
   "delimiter": "\n"
  };
  if ($(this).attr("data-analyte-type") === "CFA") {
   analytesTyped = $("textarea[name=\"" + params.fa_attr + "\"]").val();
   analytesSelected = utils.getValuesFromList(selectionParams.fieldListId, selectionParams.dataAttrFA, false, params.delimiter);
   $("textarea[name=\"" + params.fa_attr + "\"]").val(utils.mergeStringsExclusive(analytesTyped, analytesSelected, params.delimiter));
   $('.btn-add-analyte').attr("name", params.fa_attr);
  }
  else {
   analytesTyped = $("textarea[name=\"" + params.la_attr + "\"]").val().split("\n");
   analytesSelected = utils.getValuesFromList(selectionParams.labListId, selectionParams.dataAttrLA, false, params.delimiter);
   $("textarea[name=\"" + params.la_attr + "\"]").val(analytesTyped + analytesSelected, params.delimiter);
   $('.btn-add-analyte').attr("name", params.la_attr);
  }
 });
});