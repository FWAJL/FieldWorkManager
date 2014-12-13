/**
 * jQuery listeners for the service actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  //************************************************//
  // Selection of service technicians
  var project_service_ids = "";
  $("#group-list-left, #group-list-right").selectable({
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
  $(".from-group-list-right").click(function() {
    service_manager.updateProjectServices("add", project_service_ids);
  });
  $(".from-group-list-left").click(function() {
    service_manager.updateProjectServices("remove", project_service_ids);
  });
  //************************************************//
});
/***********
 * service_manager namespace 
 * Responsible to manage tasks.
 */
(function(service_manager) {
  service_manager.updateProjectServices = function(action, arrayId) {
    datacx.post("service/updateItems", {"action": action, "service_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("service/ListAll");
      }
    });
  };

}(window.service_manager = window.service_manager || {}));
