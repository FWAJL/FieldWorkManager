/**
 * jQuery listeners for the service actions
 */
$(document).ready(function() {
  var ajaxParams = {
    "ajaxUrl": "service/updateItems",
    "redirectUrl": "service/listAll",
    "action": "",
    "arrayOfValues": "",
    "itemId": ""
  };

  $(".btn-warning").hide();
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      var params = {
        "targetUrl": "service/showForm?mode=edit&service_id=",
        "element": options.$trigger,
        "attrName": "data-service-id"
      };
      if (key === "edit") {
        utils.loadItem(params);
      } else if (key === "delete") {
        ajaxParams.ajaxUrl = "service/delete";
        ajaxParams.itemId = parseInt(options.$trigger.attr("data-service-id"));
        datacx.delete(ajaxParams);
      }
    },
    items: {
      "edit": {name: "View Info"},
      "delete": {name: "Delete"}
    }
  });//Manages the context menu

  //************************************************//
  // Selection of service
  var selectionParams = {
    "listLeftId": "categorized-list-left",
    "listRightId": "categorized-list-right",
    "dataAttrLeft": "data-service-id",
    "dataAttrRight": "data-service-id"
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
  $("#btn_delete_service").click(function() {
    ajaxParams.ajaxUrl = "service/delete";
    ajaxParams.itemId = parseInt(parseInt(utils.getQueryVariable("service_id")));
    datacx.delete(ajaxParams);
  });
});