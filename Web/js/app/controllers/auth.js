$(document).ready(function() {
    $("#btn_login").click(function() {
        toastr.info("Logging...");
        //Retrieve data to send 
        auth.retrieveUserInputs();
    });
});
/***********
 * auth namespace 
 * Responsible to authenticate a user.
 */
(function(auth) {
    auth.retrieveUserInputs = function () {
      var items = $("section").filter("[name]");  
    };
}(window.auth = window.auth || {}));