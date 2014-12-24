/**
 * jQuery listeners for the service actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  //************************************************//
  // Selection of service technicians
  var project_service_ids = "";
  $("#categorized-list-left, #categorized-list-right").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-projectservice-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        project_service_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        project_service_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-categorized-list-right").click(function() {
    project_service.updateProjectServices("add", project_service_ids);
  });
  $(".from-categorized-list-left").click(function() {
    project_service.updateProjectServices("remove", project_service_ids);
  });
  //************************************************//
});
/***********
 * project_service namespace 
 * Responsible to manage tasks.
 */
(function(project_service) {
  project_service.updateProjectServices = function(action, arrayId) {
    datacx.post("service/updateItems", {"action": action, "service_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("service/listAll");
      }
    });
  };

}(window.project_service = window.project_service || {}));
