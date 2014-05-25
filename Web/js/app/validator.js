/***********
 * validator namespace 
 * Responsible to validate inputs given by a user.
 */
(function(validator) {
  //Properties
  validator.valid = false;
  
  //Check that a input element has a value or not and send back result 
  validator.requiredInput = function() {
    $("input").each(function(i, data) {
      $(this).focusout(function() {
        return $(this).val() === "" && $(this).attr("name") !== "remember_me" ? 
          validator.emptyInputToast($(this).attr("name")) : true;
      });
    });
  }
  validator.emptyInputToast = function (attr_name) {
    //TODO: use resource manager
    toastr.error("Please enter your " + attr_name + "!");
    return false;
  };
}(window.validator = window.validator || {}));