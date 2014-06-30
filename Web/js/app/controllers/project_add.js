$(document).ready(function() {
//  validator.requiredInput();//validate the inputs
  $("#btn_add_project").click(function() {
    var post_data = project_add.retrieveInputs();
    toastr.success("name: " + post_data.project_name + "; number: " + post_data.project_num + "; desc: " + post_data.project_desc + "; active: " + post_data.project_active_flag + " ; visible: " + post_data.project_visible_flag);
//    if (post_data['result'] === "success")
//      project_add.send(post_data);
  });
});
/***********
 * auth namespace 
 * Responsible to authenticate a user.
 */
(function(project_add) {
  project_add.retrieveInputs = function() {
    var user_inputs = [];
    $(".project_form input").each(function(i, data) {
      if (project_add.checkLiElement($(this))) {
        if ($(this).attr("type") === "text") {
          user_inputs[$(this).attr("name")] = $(this).val();
        } else {//checkbox
          user_inputs[$(this).attr("name")] = $(this).is(":checked");
        }
      } else {
        toastr.error("The field " + $(this).attr("name") + " is empty. Please fill out all fileds.")
      }
    });
    return user_inputs;
//    var email = $("input[name=email]").val();
//    var username = $("input[name=username]").val();
//    var pwd = $("input[name=password]").val();
//    var valid = validator.checkAndClean();//if input are clean...
//    if (valid) {
//      //success is used to call login method
//      //email, username and pwd are the user data
//      //encrypt_pwd is used to encrypt the password in the DB
//      return {"result": "success", "email": email, "username": username, "pwd": pwd, "encrypt_pwd": 1};//return array with data
//    } else {
//      toastr.error("Try again...");//TODO: use resource manager
//    }
  };
  project_add.send = function(credentials) {
    datacx.post("auth", credentials).then(function(reply) {//call AJAX method to call Login WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        //Now redirect to project page
        window.location.replace("project");
      }
    });
  };
  project_add.checkLiElement = function(element) {
    return element.val() !== "" ? true : false;
  };
}(window.project_add = window.project_add || {}));