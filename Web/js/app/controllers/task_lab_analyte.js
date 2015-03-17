/**
 * jQuery listeners for the lab_field_analyte_manager actions
 */
$(document).ready(function() {

  var ajaxParams = {
    "ajaxUrl": "",
    "redirectUrl": "task/labAnalytes",
    "action": "",
    "arrayOfValues": [],
    "itemId": 0,
    "isFieldType": false,
    "isCommon": true
  };
  
  // Selection in the dual lists
  var selectionParamsLabAnalytes = {
    "listLeftId": "lab-analyte-list",
    "listRightId": "project-lab-analyte-list",
    "dataAttrLeft": "data-labanalyte-id",
    "dataAttrRight": "data-labanalyte-id"
  };
  utils.dualListSelection(selectionParamsLabAnalytes);
  
  $(".from-project-lab-analyte-list").click(function() {
 
    ajaxParams.ajaxUrl = "task/labAnalytes/updateItems";
    ajaxParams.action = "add";
    
    ajaxParams.arrayOfValues =
           utils.getValuesFromList(
              selectionParamsLabAnalytes.listRightId,
              selectionParamsLabAnalytes.dataAttrRight, true
		);
    
    datacx.updateItems(ajaxParams);
  });
  
  $(".from-lab-analyte-list").click(function() {
    ajaxParams.action = "remove";
    ajaxParams.ajaxUrl = "task/labAnalytes/updateItems";
    
    ajaxParams.arrayOfValues =
           utils.getValuesFromList(
              	selectionParamsLabAnalytes.listLeftId,
              	selectionParamsLabAnalytes.dataAttrRight, true
		);
    
    datacx.updateItems(ajaxParams);
  });
});