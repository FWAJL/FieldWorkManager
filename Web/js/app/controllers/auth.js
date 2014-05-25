$(document).ready(function() {
  $("#btn_login").click(function() {
    if (true) {
      toastr.info("Logging...");
      //Retrieve data to send 
      auth.retrieveCredentials();
    } else {
      toastr.error("Your credentials are required !");
    }
  });
});
/***********
 * auth namespace 
 * Responsible to authenticate a user.
 */
(function(auth) {
  auth.retrieveCredentials = function() {
    var email = $("input[name=email]").val();
    var pwd = $("input[name=pwd]").val(); 
    var valid = validator.requiredInput();
  };
}(window.auth = window.auth || {}));