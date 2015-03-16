$(document).ready(function() {
  $(".btn-warning").hide();
  
  var ajaxParams = {
    "ajaxUrl": "task/service/updateItems",
    "redirectUrl": "task/services",
    "action": "",
    "arrayOfValues": "",
    "itemId": ""
  };
  
  //************************************************//
  // Selection of task technicians
  var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-taskservice-id",
    "dataAttrRight": "data-taskservice-id"
  };
  utils.dualListSelection(selectionParams);

  $(".from-categorized-list-right").click(function() {
    ajaxParams.action = "add";
    ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParams.listRightId, selectionParams.dataAttrRight, true);
    datacx.updateItems(ajaxParams);
  });
  $(".from-categorized-list-left").click(function() {
    ajaxParams.action = "remove";
    ajaxParams.arrayOfValues = utils.getValuesFromList(selectionParams.listLeftId, selectionParams.dataAttrLeft, true);
    datacx.updateItems(ajaxParams);
  });

  //************************************************//
});