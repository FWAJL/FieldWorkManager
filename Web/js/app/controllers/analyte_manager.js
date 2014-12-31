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

 var ajaxParams = {
  "ajaxUrl": "",
  "redirectUrl": "analyte/listAll",
  "action": "",
  "arrayOfValues": []
 };

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