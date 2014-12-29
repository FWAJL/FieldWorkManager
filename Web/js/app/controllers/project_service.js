/**
 * jQuery listeners for the service actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  //************************************************//
  // Selection of service technicians
  var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-service-id",
    "dataAttrRight": "data-service-id"
  };
  utils.dualListSelection(selectionParams)

  var ajaxParams = {
    "ajaxUrl": "service/updateItems",
    "redirectUrl": "service/listAll",
    "action": "",
    "arrayOfIds": ""
  };
  $(".from-categorized-list-right").click(function() {
    ajaxParams.action = "add";
    ajaxParams.arrayOfIds = utils.getValuesFromList(selectionParams.listRightId, selectionParams.dataAttrRight);
    datacx.updateItems(ajaxParams);
  });
  $(".from-categorized-list-left").click(function() {
    ajaxParams.action = "remove";
    ajaxParams.arrayOfIds = utils.getValuesFromList(selectionParams.listLeftId, selectionParams.dataAttrLeft);
    datacx.updateItems(ajaxParams);
  });
});