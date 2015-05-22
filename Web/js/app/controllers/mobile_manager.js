/* JS FILE FOR MOBILE PAGES */
var locationName;
$(document).ready(function(){
  $(".task_list #active-list li").on('click',function(e){
    mobile_manager.set($(this));
  });

  if($("#task-notes").length) {
    if($("#current-location-name").length) {
      locationName = $("#current-location-name").val()+': ';
      $("#task_notes_message").val(locationName);
    }
    mobile_manager.getNotes();
  }
});
/***********
 * mobile_manager namespace
 * Responsible to manage tasks.
 */
(function(mobile_manager) {
  mobile_manager.set = function(element) {
  utils.redirect("mobile/listTasks?task_id=" + parseInt(element.attr("data-task-id")));
};
  mobile_manager.getNotes = function() {
    datacx.post("activetask/getNotes", {'onlyuser':true}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        var messages = '';
        $.each(reply.notes, function(index, value) {
          messages += mobile_manager.formatChatMessage(reply.users[index],value.task_note_value,value.task_note_time)+"<br/>";
        });
        $("#task-notes").prepend(messages);
        $("#btn_savenotes").on('click',function(e){
          e.preventDefault();
          mobile_manager.sendNote($("#task_notes_message").val());
        });
      }
    });
  };
  mobile_manager.formatChatMessage = function(name,message,time) {
    var messageFormatted = '<strong>'+name+'</strong>: '+message+' <small>@'+time+'</small>';
    return messageFormatted;
  };
  mobile_manager.sendNote = function(msg) {
    datacx.post('activetask/postNote',{note:msg}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        $("#task-notes").prepend(mobile_manager.formatChatMessage(reply.user,reply.data.task_note_value,reply.data.task_note_time)+"<br/>");
        $("#task_notes_message").val(locationName);
      }
    });
  };

}(window.mobile_manager = window.mobile_manager || {}));