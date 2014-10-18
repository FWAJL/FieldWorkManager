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
    $("." + form_class + " input, ." + form_class + " textarea").each(function(i, data) {
//            alert(form_class + " - " + inputs_required );
      if (utils.checkLiElement($(this), inputs_required)) {
        if ($(this).attr("type") === "text") {
          user_inputs[$(this).attr("name")] = $(this).val().replace(/^\s\s*/, '').replace(/\s\s*$/, '');
        } else {//checkbox
          user_inputs[$(this).attr("name")] = ($(this).val() === "1") || ($(this).val() === "true");
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
    return value !== null || value !== "" ? false : true;
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
  utils.getPathPart = function(part) {
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
  }
}(window.utils = window.utils || {}));