/**
 * jQuery listeners for the task_field_analyte_manager actions
 */
$(document).ready(function() {

  var ajaxParams = {
    "ajaxUrl": "",
    "redirectUrl": "task/fieldAnalytes",
    "action": "",
    "arrayOfValues": [],
    "itemId": 0,
    "isFieldType": true,
    "isCommon": true
  };
  
  // Selection in the dual lists
  var selectionParamsFieldAnalytes = {
    "listLeftId": "field-analyte-list",
    "listRightId": "project-field-analyte-list",
    "dataAttrLeft": "data-fieldanalyte-id",
    "dataAttrRight": "data-fieldanalyte-id"
  };
  utils.dualListSelection(selectionParamsFieldAnalytes);
  
  $(".from-project-field-analyte-list").click(function() {
 
    ajaxParams.ajaxUrl = "task/fieldAnalytes/updateItems";
    ajaxParams.action = "add";
    
    ajaxParams.arrayOfValues =
           utils.getValuesFromList(
              selectionParamsFieldAnalytes.listRightId,
              selectionParamsFieldAnalytes.dataAttrRight, true
		);
    
    datacx.updateItems(ajaxParams);
  });
  
  $(".from-field-analyte-list").click(function() {
    ajaxParams.action = "remove";
    ajaxParams.ajaxUrl = "task/fieldAnalytes/updateItems";
    
    ajaxParams.arrayOfValues =
           utils.getValuesFromList(
              	selectionParamsFieldAnalytes.listLeftId,
              	selectionParamsFieldAnalytes.dataAttrRight, true
		);
    
    datacx.updateItems(ajaxParams);
  });
});