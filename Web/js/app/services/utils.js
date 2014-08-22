/**
 * JavaScript Module to do JavaScript actions common to several views
 */
(function(utils) {
  /**
   * 
   * @param {string} form_class : Css Class of the form to scan
   * @param {array} inputs_required 
   * @returns {Object} user_inputs : object built with dynamic properties dependent of form scanned
   */
  utils.retrieveInputs = function(form_class, inputs_required) {
    var user_inputs = {};
    $("." + form_class + " input, ." + form_class + " textarea").each(function(i, data) {
      if (utils.checkLiElement($(this), inputs_required)) {
        if ($(this).attr("type") === "text") {
          user_inputs[$(this).attr("name")] = $(this).val();
        } else {//checkbox
          user_inputs[$(this).attr("name")] = $(this).is(":checked");
        }
      } else {
        toastr.error("The field " + $(this).attr("name") + " is empty. Please fill out all fields.");
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
        if (!result) break;
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
    return value !== null || value !== "" ? false : true; 
  };
  
  /**
   * Redirect to a given page
   * 
   * @param {type} page
   */
  utils.redirect = function(page) {
    document.location.replace(service_config.rootFolder + page);
  };
  
}(window.utils = window.utils || {}));