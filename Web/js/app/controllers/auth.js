$(document).ready(function() {
  validator.requiredInput();    
    $("#btn_login").click(function() {
    auth.retrieveCredentials();
  });
});
/***********
 * auth namespace 
 * Responsible to authenticate a user.
 */
(function(auth) {
  auth.retrieveCredentials = function() {
    var email = $("input[name=email]").val();
    var pwd = $("input[name=password]").val(); 
    var valid = validator.checkAndClean();
    if (valid) {
        toastr.success("<p>Logging in! :)</p><p><u>Email:</u> "+email+" ; </p><p><u>Password:</u> ****;</p>");//TODO: use resource manager
    } else {
        toastr.error("Try again...");//TODO: use resource manager
    }
  };
}(window.auth = window.auth || {}));