/**
 * jQuery listeners for the analyte_manager actions
 */
$(document).ready(function () {

//Autocomplete variables
  var timer = null;
  var autocompleteTimeout = 700;
  var query = '';
  var autocompleteArrayTo = [];
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
  //Auto complete on lab_analyte_name
  $("textarea[name='field_analyte_name_unit']").keyup(function (e) {
// do nothing if it's an arrow key
    var code = (e.keyCode || e.which);
    if (code == 27 || code == 37 || code == 38 || code == 39 || code == 40 || code == 13
            || code == 9 || code == 8 || e.shiftKey) {
      return;
    } else if (e.shiftKey && e.keyCode == 9) {
      return;
    }

    clearTimeout(timer);
    //else proceed to lookup
    timer = setTimeout(function () {
      getMasterLabAnalytesAutoComplete($("textarea[name='field_analyte_name_unit']"))
    }, autocompleteTimeout);
  });
  //auto complete fecth and render for service category
  getMasterLabAnalytesAutoComplete = function (txtBoxObject) {

    if (query != $(txtBoxObject).val()) {
      query = $(txtBoxObject).val();
      var words = query.split("\n");
      for (var i = words.length; i != 0; i--) {
        if (words[i - 1] !== '') {
          query = words[i - 1];
          break;
        }
      }

      var offsetAC = words.length * 20;
      datacx.post("lab_analyte/getMasterLabAnalytesAutoComplete", {"search": query}).then(function (reply) {

        autocompleteArrayTo = [];
        if (reply === null || reply.result === 0) {//has an error
          //Do nothing
        } else {//success
          //Time for some autocomplete
          $.each(reply.matches, function (index, selectedObject) {
            autocompleteArrayTo.push(selectedObject);
          });
          $("textarea[name='field_analyte_name_unit']").autocomplete({
            source: autocompleteArrayTo,
            focus: function (event, ui) {
              event.preventDefault();
            },
            select: function (event, ui) {
              event.preventDefault();
              //console.log(ui.item.value);
              var temp = $("textarea[name='field_analyte_name_unit']").val().split("\n");
              var str = '';
              if (temp.length <= 1) {
                $("textarea[name='field_analyte_name_unit']").val(ui.item.value);
              } else {
                temp[temp.length - 1] = ui.item.value;
                for (var i = 0; i < temp.length; i++) {
                  str += temp[i] + "\n";
                }
                $("textarea[name='field_analyte_name_unit']").val(str);
              }
            },
          });
          $("textarea[name='field_analyte_name_unit']").autocomplete("search", query);
          $('#ui-id-1').css({
            top: (150 + offsetAC)
          });
        }
      });
    }
  };
  /* Context menu */
  $.contextMenu({
    selector: '.select_item',
    callback: function (key, options) {
      if (key === "edit") {

        if (prompt_box_msg == null || prompt_box_msg == '') {
          prompt_box_msg = $('#promptmsg-edit').val();
        }
        $('#promptmsg-edit').val(prompt_box_msg.replace('{0}', options.$trigger.html()));
        $('#text_input').val(options.$trigger.html());
        utils.showPromptBox("edit", function () {
          //Make a unique check
          datacx.post('field_analyte/isfieldAnalyteExisting', {analyte_id: options.$trigger.attr("data-fieldanalyte-id"), analyte_name: $('#text_input').val()}).then(function (reply) {
            if (reply === null || reply.result === 0) {//has an error
              //toastr.success(reply.message);

              var actionPath = "field_analyte/edit";
              getAnalyteItem(parseInt(options.$trigger.attr("data-fieldanalyte-id")), function (data) {
                var post_data = {};
                post_data["field_analyte"] = data.field_analyte;
                post_data["field_analyte"]["field_analyte_name_unit"] = $('#text_input').val();
                datacx.post(actionPath, post_data["field_analyte"]).then(function (reply) {//call AJAX method to call Project/Add WebService
                  if (reply === null || reply.result === 0) {//has an error
                    //toastr.error(reply.message);
                  } else {//success
                    //toastr.success(reply.message.replace("field_analyte", "field_analyte (ID:" + reply.dataId + ")"));
                    utils.redirect("analyte/listAll");
                  }
                });
              });
            } else {//success
              //toastr.error(reply.message);
              //Show alert
              utils.togglePromptBox();
              utils.showAlert($('#confirmmsg-faExists').val(), function () {
                utils.togglePromptBox();
              });
            }
          });
        }, 'promptmsg-edit');
      } else if (key === "delete") {
        var isFieldAnalyte = $(".active").attr("data-form-id") === "field_analyte_info";
        var msg =
//                        (isFieldAnalyte) ? 
                $('#confirmmsg-deleteField').val()
//                : $('#confirmmsg-deleteLab').val()
                ;
        if (typeof msg !== typeof undefined && msg !== false) {
          utils.showConfirmBox(msg, function (result) {
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
  }); //The context menu

  //For Admin UI `labanalyte/uploadCommons`
  $.contextMenu({
    selector: '.admin-ui > #common-lab-analyte-list > .ui-widget-content',
    callback: function (key, options) {
      var lab_analyte_id = options.$trigger.attr("data-common_lab_analyte-id");
      if (key === "edit") {

        if (prompt_box_msg == null || prompt_box_msg == '') {
          prompt_box_msg = $('#promptmsg-edit').val();
        }

        $('#promptmsg-edit').val(prompt_box_msg.replace('{0}', options.$trigger.html()));
        $('#text_input').val(options.$trigger.html());
        utils.showPromptBox("edit", function () {
          //check if existing
          datacx.post('lab_analyte/isCommonAnalyteExisting', {analyte_id: lab_analyte_id, analyte_name: $('#text_input').val(), analyte_type: 'lab'}).then(function (reply) {//call AJAX method to call Project/Add WebService
            if (reply === null || reply.result === 0) {//has an error
              //Edit the common lab analyte
              datacx.post('lab_analyte/editCommonAnalyte', {analyte_id: lab_analyte_id, analyte_name: $('#text_input').val(), analyte_type: 'lab'}).then(function (reply) {
                if (reply === null || reply.result === 0) {//has an error
                  //toastr.error(reply.message);
                } else {//success
                  //toastr.success(reply.message);
                  utils.redirect("labanalyte/uploadCommons", 700);
                }
              });
            } else {//success
              //console.log('Analyte exists, show alert box');
              utils.togglePromptBox();
              utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function () {
                utils.togglePromptBox();
              });
            }
          });
        }, 'promptmsg-edit');
      } else if (key === "delete") {

        var msg = $('#confirmmsg-deleteCommonLab').val()
        if (typeof msg !== typeof undefined && msg !== false) {
          utils.showConfirmBox(msg, function (result) {
            if (result)
            {
              datacx.post('lab_analyte/deleteCommon', {analyte_id: lab_analyte_id}).then(function (reply) {//call AJAX method to call Project/Add WebService
                if (reply === null || reply.result === 0) {//has an error
                  //toastr.error(reply.message);
                } else {//success
                  //toastr.success(reply.message);
                  utils.redirect("labanalyte/uploadCommons", 700);
                }
              });
            }
          });
        }
        else
        {
          //delAnalyte(ajaxParams, options);
        }
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });
  //For Admin UI `analyte/uploadCommons`
  $.contextMenu({
    selector: '.admin-ui > #common-field-analyte-list > .ui-widget-content',
    callback: function (key, options) {
      var field_analyte_id = options.$trigger.attr("data-common_field_analyte-id");
      if (key === "edit") {

        //===========================================
        if (prompt_box_msg == null || prompt_box_msg == '') {
          prompt_box_msg = $('#promptmsg-edit').val();
        }

        $('#promptmsg-edit').val(prompt_box_msg.replace('{0}', options.$trigger.html()));
        $('#text_input').val(options.$trigger.html());
        utils.showPromptBox("edit", function () {
          //check if existing
          datacx.post('lab_analyte/isCommonAnalyteExisting', {analyte_id: field_analyte_id, analyte_name: $('#text_input').val(), analyte_type: 'field'}).then(function (reply) {//call AJAX method to call Project/Add WebService
            if (reply === null || reply.result === 0) {//has an error
              //Edit the common lab analyte
              datacx.post('lab_analyte/editCommonAnalyte', {analyte_id: field_analyte_id, analyte_name: $('#text_input').val(), analyte_type: 'field'}).then(function (reply) {
                if (reply === null || reply.result === 0) {//has an error
                  //toastr.error(reply.message);
                } else {//success
                  //toastr.success(reply.message);
                  utils.redirect("analyte/uploadCommons", 700);
                }
              });
            } else {//success
              //console.log('Analyte exists, show alert box');
              utils.togglePromptBox();
              utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function () {
                utils.togglePromptBox();
              });
            }
          });
        }, 'promptmsg-edit');
      } else if (key === "delete") {
        var msg = $('#confirmmsg-deleteCommonLab').val()
        if (typeof msg !== typeof undefined && msg !== false) {
          utils.showConfirmBox(msg, function (result) {
            if (result)
            {
              datacx.post('field_analyte/deleteCommon', {analyte_id: field_analyte_id}).then(function (reply) {//call AJAX method to call Project/Add WebService
                if (reply === null || reply.result === 0) {//has an error
                  //toastr.error(reply.message);
                } else {//success
                  //toastr.success(reply.message);
                  utils.redirect("analyte/uploadCommons", 700);
                }
              });
            }
          });
        }
        else
        {
          //delAnalyte(ajaxParams, options);
        }
      }
    },
    items: {
      "edit": {name: "Edit"},
      "delete": {name: "Delete"}
    }
  });
  //get item for field/lab analyte
  getAnalyteItem = function (analyteId, executeWithData) {
    var actionPath = "field_analyte/getItem";
//    if ((analyteType === "field"))
//    {
    datacx.post(actionPath, {field_analyte_id: analyteId}).then(function (reply) {
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
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
//          //toastr.error(reply.message);
//        } else {//success
//          //return reply;
//          executeWithData(reply);
//        }
//      });
//    }

  };
  //delete analyte, contextual menu
  delAnalyte = function (ajaxParams, options) {
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

  $(".from-field-analyte-list").click(function () {
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
  $(".from-project-field-analyte-list").click(function () {
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
  $("textarea").focus(function () {
    $('.btn-add-analyte').attr("name", $(this).attr("name"));
  });
  $("textarea").focusout(function () {
    if ($(this).val() === "")
      $('.btn-add-analyte').removeAttr($(this).attr("name"));
  });

  $(".btn-add-analyte").click(function () {
    //use url to distinguish the type of analyte...
    ajaxParams.isFieldType = utils.containsStr(window.location.pathname, "^(.*analyte/uploadList.*)$");
    ajaxParams.isCommon = utils.containsStr($(this).attr("name"), "^(common.*)$");
    var getValuesParams = {
      "attrNameValues": $(this).attr("name"),
      "attrNameCheckBox": "",
      "hasCheckBoxActive": false
    };
    if (ajaxParams.isCommon) {
      ajaxParams.ajaxUrl = ajaxParams.isFieldType ? "field_analyte/addCommon" : "lab_analyte/addCommon";
      //ajaxParams.redirectUrl = "";
      ajaxParams.redirectUrl = ajaxParams.isFieldType ? "analyte/uploadCommons" : "labanalyte/uploadCommons";
    } else {
      ajaxParams.ajaxUrl = ajaxParams.isFieldType ? "field_analyte/add" : "lab_analyte/add";
    }
    ajaxParams.arrayOfValues = utils.getValuesFromTextArea(getValuesParams);
    if (ajaxParams.arrayOfValues.names !== undefined && ajaxParams.arrayOfValues.names !== "") {
      var origin = utils.getQueryVariable("origin");
      var originid = utils.getQueryVariable("originid");
      if (origin !== false && originid !== false) {
        ajaxParams.arrayOfValues['origin'] = origin;
        ajaxParams.arrayOfValues['originid'] = originid;
        ajaxParams.redirectUrl = "task/fieldAnalytes";
      }

      datacx.add(ajaxParams);
    } else {
//toastr.error("Please type some analyte names or select at least one from the right list.");
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
  $("button[data-analyte-type]").click(function () {
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