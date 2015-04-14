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

  //Field Matrix
  $('#toggle_all').click(function(){
    if($(this).is(":checked")){
      //Check all
      $('.matrix-checkbox').prop('checked', true);
    }
    else {
      $('.matrix-checkbox').prop('checked', false);
    }
  });

  $('#btn_save_fieldmatrix').click(function(){
    //get task coc object
    datacx.post("task/saveFieldMatrix", {'field_matrix': $('.matrix-checkbox').serialize()}).then(function(reply) {
      console.log(reply);
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        
        toastr.success(reply.message);
        
      }
    });
  });

});