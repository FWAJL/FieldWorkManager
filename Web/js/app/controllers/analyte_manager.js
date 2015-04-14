/**
 * jQuery listeners for the analyte_manager actions
 */
$(document).ready(function() {

  var prompt_box_msg;
  var ajaxParams = {
    "ajaxUrl": "",
    "redirectUrl": "analyte/listAll",
    "action": "",
    "arrayOfValues": [],
    "itemId": 0,
//    "isFieldType": true,
    "isCommon": true
  };

  /* Context menu */
  $.contextMenu({
    selector: '.select_item',
    callback: function(key, options) {
      if (key === "edit") {

        if (prompt_box_msg == null || prompt_box_msg == '') {
          prompt_box_msg = $('#promptmsg-edit').val();
        }
        $('#promptmsg-edit').val(prompt_box_msg.replace('{0}', options.$trigger.html()));
        $('#text_input').val(options.$trigger.html());
        utils.showPromptBox("edit", function() {

//          var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
//
          var actionPath = "field_analyte/edit" 
//          : "lab_analyte/edit"
          ;
//          if (isFieldAnalyte) {
            getAnalyteItem("field", parseInt(options.$trigger.attr("data-fieldanalyte-id")), function(data) {
              var post_data = {};
              post_data["field_analyte"] = data.field_analyte;
              post_data["field_analyte"]["field_analyte_name_unit"] = $('#text_input').val();

              datacx.post(actionPath, post_data["field_analyte"]).then(function(reply) {//call AJAX method to call Project/Add WebService
                if (reply === null || reply.result === 0) {//has an error
                  toastr.error(reply.message);
                } else {//success
                  toastr.success(reply.message.replace("field_analyte", "field_analyte (ID:" + reply.dataId + ")"));
                  utils.redirect("analyte/listAll");
                }
              });
            });

//          } 
        }, 'promptmsg-edit');
      } else if (key === "delete") {
		var isFieldAnalyte = $(".active").attr("data-form-id") === "field_analyte_info";
		var msg = 
//                        (isFieldAnalyte) ? 
                $('#confirmmsg-deleteField').val() 
//                : $('#confirmmsg-deleteLab').val()
                ;
		if (typeof msg !== typeof undefined && msg !== false) {
		  utils.showConfirmBox(msg, function(result) {
			if (result)
			{
			  delAnalyte(ajaxParams, options);
			}
		  });
		}
		else
		{
		  delAnalyte(ajaxParams, options);
		}
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });//The context menu

  //get item for field/lab analyte
  getAnalyteItem = function(analyteId, executeWithData) {
    var actionPath = "field_analyte/getItem";
//    if ((analyteType === "field"))
//    {
      datacx.post(actionPath, {field_analyte_id: analyteId}).then(function(reply) {
        if (reply === null || reply.result === 0) {//has an error
          toastr.error(reply.message);
        } else {//success
          //return reply;
          executeWithData(reply);
        }
      });
//    }
//    else
//    {
//      datacx.post(actionPath, {lab_analyte_id: analyteId}).then(function(reply) {
//        if (reply === null || reply.result === 0) {//has an error
//          toastr.error(reply.message);
//        } else {//success
//          //return reply;
//          executeWithData(reply);
//        }
//      });
//    }

  };
  
  //delete analyte, contextual menu
  delAnalyte = function(ajaxParams, options) {
//	var isFieldAnalyte = ajaxParams.isFieldType = $(".active").attr("data-form-id") === "field_analyte_info";
	ajaxParams.itemId =
//			isFieldAnalyte ?
			parseInt(options.$trigger.attr("data-fieldanalyte-id")) 
//                        : parseInt(options.$trigger.attr("data-labanalyte-id"))
                ;
	ajaxParams.ajaxUrl = "field_analyte/delete";
	datacx.delete(ajaxParams);
  }

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

  $(".from-field-analyte-list").click(function() {
    var isFieldAnalyte = ajaxParams.isFieldType = $(".form_sections").attr("data-form-id") === "field_analyte_info";
    ajaxParams.ajaxUrl = "field_analyte/updateItems";
    ajaxParams.action = "add";
//    if (isFieldAnalyte) {
      ajaxParams.arrayOfValues =
              utils.getValuesFromList(
              selectionParamsFieldAnalytes.listLeftId,
              selectionParamsFieldAnalytes.dataAttrLeft, true);
//    } else {
//      ajaxParams.arrayOfValues =
//              utils.getValuesFromList(
//              selectionParamsLabAnalytes.listLeftId,
//              selectionParamsLabAnalytes.dataAttrLeft, true);
//    }
    datacx.updateItems(ajaxParams);
  });
  $(".from-project-field-analyte-list").click(function() {
    var isFieldAnalyte = ajaxParams.isFieldType = $(".form_sections").attr("data-form-id") === "field_analyte_info";
    ajaxParams.action = "remove";
    ajaxParams.ajaxUrl = "field_analyte/updateItems";
//    if (isFieldAnalyte) {
      ajaxParams.arrayOfValues =
              utils.getValuesFromList(
              selectionParamsFieldAnalytes.listRightId,
              selectionParamsFieldAnalytes.dataAttrRight, true);
//    } else {
//      ajaxParams.arrayOfValues =
//              utils.getValuesFromList(
//              selectionParamsLabAnalytes.listRightId,
//              selectionParamsLabAnalytes.dataAttrRight, true);
//    }
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
      analytesTyped = $("textarea[name=\"" + params.la_attr + "\"]").val();
      analytesSelected = utils.getValuesFromList(selectionParams.labListId, selectionParams.dataAttrLA, false, params.delimiter);
      $("textarea[name=\"" + params.la_attr + "\"]").val(utils.mergeStringsExclusive(analytesTyped, analytesSelected, params.delimiter));
      $('.btn-add-analyte').attr("name", params.la_attr);
    }
  });
});