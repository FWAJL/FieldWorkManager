$(document).ready(function() {
  $(".btn-warning").hide();
  //************************************************//
  // Selection of task technicians
  var task_service_ids = "";
  $("#group-list-left, #group-list-right").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-taskservice-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        task_service_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        task_service_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-group-list-right").click(function() {
    task_manager.updateTaskServices("add", task_service_ids);
  });
  $(".from-group-list-left").click(function() {
    task_manager.updateTaskServices("remove", task_service_ids);
  });
  //************************************************//
});

/***********
 * task_manager namespace 
 * Responsible to manage tasks.
 */
(function(task_manager) {
  task_manager.updateTaskServices = function(action, arrayId) {
    datacx.post("task/service/updateItems", {"action": action, "service_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/services");
      }
    });
  };

}(window.task_manager = window.task_manager || {}));