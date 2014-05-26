/***********
 * validator namespace 
 * Responsible to validate inputs given by a user.
 */
(function(validator) {
  //Properties
  validator.valid = false;
  
  //Check that a input element has a value or not and send back result
  validator.checkInput = function(element) {
    return (element.val() === "" && element.attr("name") !== "remember_me") ? false : true;  
  };
  validator.requiredInput = function() {
    $("input").each(function(i, data) {
      $(this).focusout(function() {
          var valid = validator.checkInput($(this));
          if (!valid) {
              $(this).addClass("required");
          } else {
              $(this).removeClass("required");
          }
        return !valid ? 
          validator.emptyInputToast($(this).attr("name")) : true;
      });
    });
  };
  validator.checkAndClean = function () {
      var invalidInputs = [];
      var valid = true;
      //Check if empty
      $("input").each(function(i, data) {
          var valid = validator.checkInput($(this));
          if (!valid) {
              invalidInputs.push($(this).attr("name"));
          } 
      });
      if (invalidInputs.length > 0) {
          valid = false;
          validator.invalidData(invalidInputs);
      }
      //TODO: Clean inputs for XSS
      
      return valid;
      
  };
  validator.emptyInputToast = function (attr_name) {
    toastr.error("Please enter your " + attr_name + "!");//TODO: use resource manager
    return false;
  };
  validator.invalidData = function (invalidInputs) {
    $.each(invalidInputs, function(index, value) {
       toastr.error("Please enter your " + value + "!");//TODO: use resource manager
    });
    return false;
  };
}(window.validator = window.validator || {}));