/**
 * utils groups the common function to handle the DOM, get values and load data.
 */
$(document).ready(function() {
  $("#datepicker").datepicker();
  $("#datepicker").datepicker("option", "dateFormat", "mm-dd-yy");
  //toolip
  $("li[has-tool-tip]").tooltip({placement: $("li[has-tool-tip]").attr("placement")});
  //file upload
  //$("#document-upload input[name=\"itemCategory\"]").val(utils.getDataFromUploadFile("^.*(_id)$", true));
  //$("#document-upload input[name=\"itemId\"]").val(utils.getDataFromUploadFile("^.*(_id)$", false));
  //Auto focus, prompt box first input
  $('.prompt-modal').on('shown.bs.modal', function () {
    $('#text_input').focus();
  })

  //Tooltip
  $('[id^="tooltipmsg_"]').each(function() {
    var hidd_parts = $(this).attr('id').split('_');
    if($(this).attr('delayshow') !== undefined) {
      $('#' + hidd_parts[1]).tooltip({placement: $(this).attr('placement'), title: $(this).val(), delay:{show:$(this).attr('delayshow'), hide:$(this).attr('delayhide')}});
    }
    else {
      $('#' + hidd_parts[1]).tooltip({placement: $(this).attr('placement'), title: $(this).val()});
    }
  });

});
/**
 * JavaScript Module to do JavaScript actions common to several views
 */
(function(utils) {
  /**
   *
   * @param {string} form_class : Css Class of the form to scan
   * @param {array} inputs_required : list of string describing the attribute names of the inputs required
   * @returns {Object} user_inputs : object built with dynamic properties dependent of form scanned
   */
  utils.retrieveInputs = function(form_class, inputs_required) {
    var user_inputs = {};
    $("." + form_class + " input, ." + form_class + " textarea, ." + form_class + " select").each(function(i, data) {
      if (utils.checkLiElement($(this), inputs_required)) {
        if($(this).prop("type") === "select-one"){
          user_inputs[$(this).attr("name")] = $(this).val();
        } else if ($(this).attr("type") === "text") {
          user_inputs[$(this).attr("name")] = $(this).val().replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        } else {//checkbox
          user_inputs[$(this).attr("name")] = ($(this).val() === "1") || ($(this).val() === "true") || $(this).prop("checked");
        }
      } else {
        toastr.error("The field " + $(this).attr("name") + " is empty. Please fill out all fields.");
        user_inputs.required_field_missing = true;
      }
    });
    return user_inputs;
  };
  /**
   * check a LI element if:
   *  - is required
   *  - and if so, has a value
   *
   * @param {jQuery object} element
   * @param {array} inputs_required
   * @returns {Boolean}
   */
  utils.checkLiElement = function(element, inputs_required) {
    var result = false;
    for (var i = 0; i <= inputs_required.length; i++) {
      if (element.attr("name") === inputs_required[i]) {
        result = element.val() !== "" ? true : false;
        if (!result) {
          result = element.find(":selected").text() !== "" ? true : false;
        }
        if (!result)
          break;
      } else {
        result = true;
      }
    }
    return result;
  };

  /**
   * Check if a string is null or empty
   *
   * @param {String} value
   * @returns {Boolean}
   */
  utils.isNullOrEmpty = function(value) {
    return value === "" || value === null || value === undefined ? true : false;
  };

  /**
   * Redirect to a given page
   *
   * @param {string} page : the relative url to redirect to
   * @param {integer} timeout : the time to wait before the redirection happens. By default, it is 0.
   */
  utils.redirect = function(page, timeout) {
    timeout = timeout || 0;
    setTimeout(function() {
      document.location.replace(config.rootFolder + page);
    }, timeout);
  };
  /**
   * Load a given page
   *
   * @param {string} page : the relative url to load
   * @param {integer} timeout : the time to wait before the load happens. By default, it is 0.
   */
  utils.loadUrl = function(page, timeout) {
    timeout = timeout || 0;
    setTimeout(function() {
      window.open(config.rootFolder + page, "_SELF");
    }, timeout);
  };
  /**
   * Clear a form inputs in the current dom (input text, input textarea, checkbox)
   *
   */
  utils.clearForm = function() {
    $(":checked, :text, textarea").each(function(i, data) {
      $(this).val("");
    });
  };

  /**
   * Retrieve the value associated to a query string key
   *
   * @param {string} variable : query string key
   * @returns {string|Boolean} : the query string value or FALSE if not found
   */
  utils.getQueryVariable = function(variable) {
    var query = window.location.search.substring(1);//remove "?"
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if (pair[0] === variable) {
        return pair[1];
      }
    }
    return(false);
  };
  /**
   * Retrieve the value associated to a url parth
   *
   * @param {string} variable : path part key
   * @returns {Boolean} : the result of lookup
   */
  utils.getPathPart = function(part, depth) {
    var path = window.location.pathname;
    var parts = path.split("/");
    for (var i = 0; i < parts.length; i++) {
      if (parts[i] === part) {
        return true;
      }
    }
    return(false);
  };
  /**
   * Remove the last character in a string
   *
   * @param {string} targetStr
   * @returns {@exp;targetStr@call;substring}
   */
  utils.removeLastChar = function(targetStr) {
    return targetStr.substring(0, targetStr.length - 1);
  };
  utils.load = function(filpath) {
    var xml = new JKL.ParseXML(filepath);
    var nodes = xml.parse();
    return nodes;
  };

  utils.makeArray = function(targetClass) {
    var inputs = utils.retrieveInputs(targetClass, []);
    var output = {};
    inputs = inputs.location_names.split("\n");
    for (var i = 0; i < inputs.length; i++) {
      output["value" + i] = utils.trim(inputs[i]);
    }
    return output;
  };

  utils.trim = function(str) {
    return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
  };

  utils.setCheckBoxValue = function(value) {
    if (value === "true" || value === true || value === "1") {
      return true;
    } else {
      return false;
    }
  };
  utils.toDate = function(value) {
    return moment(value).isValid() ? moment(value, "YYYY-MM-DD")._d : moment(value).isValid();
  };
  utils.dualListSelection = function(params) {
    var selector = utils.buildDualListSelector(params);
    if (selector === "") {
      print("<!-- No list set as selectable -->");
    } else {
      $(selector).selectable({
        stop: function() {
          var tmpSelection = "";
          $(".ui-selected", this).each(function() {
            if ($(this).attr(params.dataAttrLeft) !== undefined) {
              tmpSelection += $(this).attr(params.dataAttrLeft) + ",";
            } else {
              tmpSelection += $(this).attr(params.dataAttrRight) + ",";
            }
          });
          tmpSelection = utils.removeLastChar(tmpSelection);
          if (tmpSelection.length > 0) {
            $(".from-" + $(this).attr("id")).show();
          } else {
            $(".from-" + $(this).attr("id")).hide();
          }
        }
      });
    }
  };
  utils.buildDualListSelector = function(params) {
    var selector = params.listLeftId !== "" ? "#" + params.listLeftId : "";
    selector += (selector !== "") ?
      (params.listRightId !== "" ? ", #" + params.listRightId : "")
      :
      (params.listRightId !== "" ? "#" + params.listRightId : "");
    return selector;
  };

  utils.getValuesFromList = function(sourceList, attrToRead, readAttr, delimiter) {
    if (typeof (readAttr) === 'undefined')
      readAttr = false;
    if (typeof (delimiter) === 'undefined')
      delimiter = ",";

    var values = "";
    $("#" + sourceList + " .ui-selected").each(function(i, obj) {
      if ($(this).attr(attrToRead) !== undefined) {
        values +=
          readAttr ?
            $(this).attr(attrToRead) + delimiter :
            $(this).text() + delimiter;
      }
    });
    values = utils.removeLastChar(values);
    return values;
  };

  utils.getValuesFromListGroupedByObject = function(sourceList, attrToRead, objAttrToRead, readAttr, delimiter) {
    if (typeof (readAttr) === 'undefined')
      readAttr = false;
    if (typeof (delimiter) === 'undefined')
      delimiter = ",";
    if(typeof (objAttrToRead) === 'undefined')
      objAttrToRead = false;

    var values = {};
    $("#" + sourceList + " .ui-selected").each(function(i, obj) {
      if ($(this).attr(attrToRead) !== undefined) {
        if($(this).attr(objAttrToRead) !== undefined){
          objectAttr = $(this).attr(objAttrToRead);
          if(typeof (values[objectAttr]) === 'undefined'){
            values[objectAttr] = "";
          }
          values[objectAttr] +=
            readAttr ?
              $(this).attr(attrToRead) + delimiter :
              $(this).text() + delimiter;

        }
      }
    });
    $.each(values,function(key,val){
      values[key] = utils.removeLastChar(val);
    });
    //values = utils.removeLastChar(values);
    return values;
  };

  utils.getValuesFromTextArea = function(params) {
    var data = {
      "names": $("textarea[name=\"" + params.attrNameValues + "\"]").val(),
      "active": params.hasCheckBoxActive ?
        $("input[name=\"" + params.attrNameCheckBox + "\"]").prop("checked") : "undefined"
    };
    return data;
  };
  utils.loadItem = function(params) {
    utils.redirect(params.targetUrl + parseInt(params.element.attr(params.attrName)));
  };
  utils.containsStr = function(string, pattern) {
    var reg = new RegExp(pattern);
    return reg.test(string);
  };

  /**
   * Retrieve the value associated to a query string key
   *
   * @param {string} variable : query string key
   * @param {Boolean} useRegex : find value using a Regex
   * @returns {string|Boolean} : the query string value or FALSE if not found
   */
  utils.getDataFromUploadFile = function(variable, findCategory) {
    var patt = new RegExp(variable);
    var query = window.location.search.substring(1);//remove "?"
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split("=");
      if (findCategory && patt.test(pair[0])) {
        return pair[0];
      } else if (patt.test(pair[0])) {
        return pair[1];
      }
    }
    return(false);
  };
  utils.showConfirmBox = function(msg, decision) {
    bootbox.confirm({
      buttons: {
        confirm: {
          label: 'Ok',
          className: 'btn btn-default confirmbuttons'
        },
        cancel: {
          label: 'Cancel',
          className: 'btn btn-primary confirmbuttons'
        }
      },
      message: msg,
      callback: decision
    });
  };
  utils.stringifyJson = function(data) {
    if (!JSON || !JSON.stringify)
      throw new Error("Cannot find JSON.stringify(). Some browsers (e.g., IE < 8) don't support it natively, but you can overcome this by adding a script reference to json2.js, downloadable from http://www.json.org/json2.js");
    return JSON.stringify(data);
  };

  utils.showAlert = function(msg, doWhenOk) {
    bootbox.alert({
      message: msg,
      callback: doWhenOk
    });
    $('.btn-primary').addClass('confirmbuttons');
  };

  utils.showPromptBox = function(operation, callback, useThisIdForMsg, callbackOnCancel) {
    if(operation == 'addNullCheck'){
      $('#prompt_title').html($('#promptmsg-addNullCheck').val());
    }
    else if(useThisIdForMsg !== undefined && useThisIdForMsg !== "") {
      $('#prompt_title').html($('#' + useThisIdForMsg).val());
    }

    $('.prompt-modal').modal('show');
    //Events
    $('#prompt_ok').on('click', function(){
      callback();
    });
    if(callbackOnCancel !== undefined)
    {
      $('.prompt-modal').on('hidden.bs.modal', function (e) {
        callbackOnCancel();
      })
    }
  };

  utils.togglePromptBox = function(){
    $('.prompt-modal').toggle();
  };
  
  utils.dissmissModal = function(){
  	$('.prompt-modal').modal('hide');
  };

  utils.showSelectEntityPrompt = function(clbkOk, clbkCancel){
    if($('.pselector-modal').length !== 0)
    {
      //$('#prompt_title').html($('#promptmsg-checkCurrentProject').val());
      $('#prompt_title').html($('[id^="promptmsg-checkCurrent"]').val());

      //disable context menu
      $(".select_item").removeClass("select_item");
      $('.pselector-modal').modal('show');
    }

    //Events
    $('#prompt_ok').on('click', function(){
      clbkOk();
    });
    $('.pselector-modal').on('hidden.bs.modal', function (e) {
      clbkCancel();
    })
  }

  utils.showMapLegends = function() {
    if($('.maplegend-modal').length !== 0)
    {
      $('.maplegend-modal').modal('show');
    }

    //Events
    $('#mlalert_ok').on('click', function(){
      $('.maplegend-modal').modal('hide');
    });
  }

  utils.showInfoWindow = function(id, callback, callbackOnCancel) {
    $(id).modal('show');
    //Events
    $('.modal-update').off('click');
    $('.modal-update').on('click', function(){
      callback();
    });
    if(callbackOnCancel !== undefined)
    {
      $('.prompt-modal').on('hidden.bs.modal', function (e) {
        callbackOnCancel();
      })
    }
  };
  utils.showPromptBoxById = function(id, operation, callback, useThisIdForMsg, callbackOnCancel) {
    if(operation == 'addNullCheck'){
      if($('#prompt_title').html() == '') {
        $('#prompt_title').html($('#promptmsg-addNullCheck').val());
      }

    }
    else if(useThisIdForMsg !== undefined && useThisIdForMsg !== "") {
      if($('#prompt_title').html() == '') {
        $('#prompt_title').html($('#' + useThisIdForMsg).val());
      }
    }

    $('#'+id).modal('show');
    //Events
    $('#'+id+" .modal-update").on('click', function(){
      callback();
    });
    if(callbackOnCancel !== undefined)
    {
      $('#'+id).on('hidden.bs.modal', function (e) {
        callbackOnCancel();
      })
    }
  };
  utils.mergeStringsExclusive = function(target, source, delimiter) {
    delimiter = delimiter || "\n";
    if (!utils.endsWith(target, delimiter) && !utils.isNullOrEmpty(target)) {
      target += delimiter;
    }
    source.split(delimiter).forEach(function(e, i, array) {
      if (target.indexOf(e) === -1) {
        target += e + delimiter;
        return;
      } else {
        toastr.warning(e + "is already present in the values typed or added.");
      }
    });
    return target;
  };
  utils.endsWith = function(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
  };
  utils.checkLatLng = function(lat,lng) {
    validLat = /^(-?[1-8]?\d(?:\.\d{1,18})?|90(?:\.0{1,18})?)$/.test(lat);
    validLng = /^(-?(?:1[0-7]|[1-9])?\d(?:\.\d{1,18})?|180(?:\.0{1,18})?)$/.test(lng);
    if(validLat && validLng) {
      return true;
    } else {
      return false;
    }
  };
  utils.createRemoveFileElement = function(dataId) {
    var removeFileElement = '<button type="button" class="remove-image close" data-id="'+dataId+'" aria-hidden="true">&times;</button>';
    return removeFileElement;
  };
  utils.createImageLightboxElement = function(path, lightboxGroup, title) {
    var imageLightboxElement = '<a href="'+path+'" data-lightbox="'+lightboxGroup+'" data-title="'+title+'"><img class="img-responsive" src="'+path+'" /></a>';
    return imageLightboxElement;
  };


}(window.utils = window.utils || {}));
