/**
 * IMPORTANT NOTICE (29-12-14): 
 *   LOOK AT analyte_manager for the new implementation 
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *   
 * jQuery listeners for the task actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  //************************************************//
  // Selection of task technicians
  var task_technician_ids = "";
  $("#group-list-left, #group-list-right").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-tasktechnician-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        task_technician_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        task_technician_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-group-list-right").click(function() {
    task_manager.updateTaskTechnicians("add", task_technician_ids);
  });
  $(".from-group-list-left").click(function() {
    task_manager.updateTaskTechnicians("remove", task_technician_ids);
  });
  //************************************************//
});
/***********
 * task_manager namespace 
 * Responsible to manage tasks.
 */
(function(task_manager) {
  task_manager.updateTaskTechnicians = function(action, arrayId) {
    datacx.post("task/technician/updateItems", {"action": action, "technician_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        utils.redirect("task/technicians");
      }
    });
  };

}(window.task_manager = window.task_manager || {}));
