$(document).ready(function() {
  if (typeof(validator) != 'undefined') {
    validator.requiredInput();//validate the inputs
    $("#btn_login").click(function() {//on button click...
      var post_data = auth.retrieveCredentials();//get the credentials typed in
      if (post_data['result'] === "success")//if the data is valid...
        auth.login(post_data);//continue to login the user
    });
    if (utils.getQueryVariable("enc") === "0") {
      $("#btn_login").trigger('click');
    }
  }
});
/***********
 * auth namespace 
 * Responsible to authenticate a user.
 */
(function(auth) {
 auth.retrieveCredentials = function() {
  var email = $("input[name=email]").val();
  var username = $("input[name=username]").val();
  var pwd = $("input[name=password]").val();
  var valid = validator.checkAndClean();//if input are clean...
  if (valid) {
   //success is used to call login method
   //email, username and pwd are the user data
   //encrypt_pwd is used to encrypt the password in the DB
   var enc = utils.getQueryVariable("enc");
   if (utils.getQueryVariable("enc") === "0") {
     return {"result": "success", "email": email, "username": username, "pwd": pwd};//return array with data
   } else {
     return {"result": "success", "email": email, "username": username, "pwd": pwd, "encrypt_pwd": 1};//return array with data
   }
  } else {
   toastr.error("Try again...");//TODO: use resource manager
  }
 };
 auth.login = function(credentials) {
  datacx.post("auth", credentials).then(function(reply) {//call AJAX method to call Login WebService
   if (reply === null || reply.result === 0) {//has an error
    toastr.error(reply.message);
   } else {//success
    toastr.success(reply.message);
    //Now redirect to project page
         //Admin   
          if(reply.role == '1') {
        utils.redirect("user/listAll");
     } 
     // Project Manager
          else if (reply.role == '2') {
         utils.redirect("project");
     } 
     // Field Technician
          else if (reply.role == '3') {
       utils.redirect("mobile/listTasks");
     } 
     // Visitor
          else if (reply.role == '4') {
       utils.redirect("logout");
     } 
     //  Client
          else if (reply.role == '5') {
       utils.redirect("logout");
     } 
     //Service Provider
     else if (reply.role == '6') {
       utils.redirect("serviceprovider/discussion");
     } 
     //  TBD
          else if (reply.role == '7') {
       utils.redirect("logout");
     }
   }
  });
 };
}(window.auth = window.auth || {}));