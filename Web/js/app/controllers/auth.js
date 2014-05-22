$(document).ready(function() {
  if (validator.requiredInput()) {
    $("#btn_login").click(function() {
      toastr.info("Logging...");
      //Retrieve data to send 
      auth.retrieveCredentials();
    });
  }
});
/***********
 * auth namespace 
 * Responsible to authenticate a user.
 */
(function(auth) {
  auth.retrieveCredentials = function() {
    var email = $("input[name=email]").val();
    var pwd = $("input[name=pwd]").val();

  };
}(window.auth = window.auth || {}));