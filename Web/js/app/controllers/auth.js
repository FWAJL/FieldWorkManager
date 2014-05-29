$(document).ready(function() {
  validator.requiredInput();
  $("#btn_login").click(function() {
    var post_data = auth.retrieveCredentials();
    if (post_data['result'] === "success")
      auth.login(post_data);
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
      toastr.success("<p>Logging in! :)</p><p><u>Email:</u> " + email + " ; </p><p><u>Password:</u> ****;</p><br/><p>NOT IMPLEMENTED YET</p>");//TODO: use resource manager
      return {"result": "success", "email": email, "pwd": pwd};
    } else {
      toastr.error("Try again...");//TODO: use resource manager
    }
  };
  auth.login = function(credentials) {
    datacx.post("auth", credentials).then(function(reply) {
      if (reply.result === !1) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        //Now redirect to project page
        
      }
    });
  };
}(window.auth = window.auth || {}));