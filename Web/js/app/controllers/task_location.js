/**
 * jQuery listeners for the task actions
 */
$(document).ready(function() {
  $(".btn-warning").hide();
  //************************************************//
  // Selection of task locations
  var task_location_ids = "";
  $("#group-list-left, #group-list-right").selectable({
    stop: function() {
      var tmpSelection = "";
      $(".ui-selected", this).each(function() {
        tmpSelection += $(this).attr("data-tasklocation-id") + ",";
      });
      tmpSelection = utils.removeLastChar(tmpSelection);
      if (tmpSelection.length > 0) {
        task_location_ids = tmpSelection;
        //Show the button to appropriate button
        $(".from-" + $(this).attr("id")).show();
      } else {
        task_location_ids = [];
        $(".from-" + $(this).attr("id")).hide();
      }
    }
  });
  $(".from-group-list-right").click(function() {
    task_manager.updateTaskLocations("add", task_location_ids);
  });
  $(".from-group-list-left").click(function() {
    task_manager.updateTaskLocations("remove", task_location_ids);
  });
  //************************************************//

  
  //Setup contextual menu for left hand list
  $.contextMenu({
    selector: '#group-list-left>li',
    callback: function(key, options) {
      if (key === "edit") {
        console.log('Logic to edit goes here');
      } 
    },
    items: {
      "edit": {name: "Edit"}
    }
  });

  $.contextMenu({
    selector: '#group-list-right>li',
    callback: function(key, options) {
      if (key === "edit") {
        console.log('Logic to edit goes here');
      } 
    },
    items: {
      "edit": {name: "Edit"}
    }
  });  
  //Context menu ends here


});
/***********
 * task_manager namespace 
 * Responsible to manage tasks.
 */
(function(task_manager) {
  task_manager.updateTaskLocations = function(action, arrayId) {
    datacx.post("task/location/updateItems", {"action": action, "location_ids": arrayId}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
        return undefined;
      } else {//success
        //toastr.success(reply.message);
        utils.redirect("task/locations");
      }
    });
  };

}(window.task_manager = window.task_manager || {}));
