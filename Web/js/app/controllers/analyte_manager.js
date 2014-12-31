/**
 * jQuery listeners for the analyte_manager actions
 */
$(document).ready(function() {
 $(".btn-warning").hide();
 $.contextMenu({
  selector: '.select_item',
  callback: function(key, options) {
   if (key === "edit") {
    analyte_manager.retrieveFieldAnalyte(options.$trigger);
   } else if (key === "delete") {
    analyte_manager.delete(parseInt(options.$trigger.attr("data-fieldanalyte-id")));
   }
  },
  items: {
   "edit": {name: "View Info"},
   "delete": {name: "Delete"}
  }
 });//Manages the context menu
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

 var ajaxParams = {
  "ajaxUrl": "",
  "redirectUrl": "analyte/listAll",
  "action": "",
  "arrayOfValues": []
 };

 $(".from-field-analyte-list, .from-lab-analyte-list").click(function() {
  var isFieldAnalyte = $(".active").attr("data-form-id") === "field_analyte_info";
  ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/updateItems" : "lab_analyte/updateItems";
  ajaxParams.action = "add";
  ajaxParams.arrayOfIds = utils.getValuesFromList(selectionParams.listRightId, selectionParams.dataAttrRight);
  datacx.updateItems(ajaxParams);
 });
 $(".from-project-field-analyte-list, .project-lab-analyte-list").click(function() {
  ajaxParams.action = "remove";
  ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/updateItems" : "lab_analyte/updateItems";
  ajaxParams.arrayOfIds = utils.getValuesFromList(selectionParams.listLeftId, selectionParams.dataAttrLeft);
  datacx.updateItems(ajaxParams);
 });

 $("#btn_add_analyte").click(function() {
  var isFieldAnalyte = $(".active").attr("data-form-id") === "field_analyte_info";
  var getValuesParams = {
   "attrNameValues": isFieldAnalyte ? "field_analyte_name_unit" : "lab_analyte_name",
   "attrNameCheckBox": "",
   "hasCheckBoxActive": false
  };
  ajaxParams.ajaxUrl = isFieldAnalyte ? "field_analyte/add" : "lab_analyte/add";
  ajaxParams.arrayOfValues = utils.getValuesFromTextArea(getValuesParams);
  datacx.add(ajaxParams);
 });
});