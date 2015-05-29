/* JS FILE FOR MOBILE PAGES */
var locationName, blockRefresh;
var lastMessageTime = "";
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
  if($("#task-comm-chatbox").length){
    mobile_manager.getThread();
    $("#btn_send_message").on('click',function() {
      var msg = $("textarea[name=\"task_comm_message\"]").val();
      if(msg.trim() != '') {
        blockRefresh = true;
        mobile_manager.sendMessage(msg.trim());
      }
    });

    $('#categorized-list-left li.select_item').click(function(){
      var id = $(this).data('activetask-id');

      mobile_manager.updateTaskTechnicians('service_id', id);
    });
    $('#group-list-left li.select_item').click(function(){
      var id = $(this).data('activetask-id');

      mobile_manager.updateTaskTechnicians('technician_id', id);
    });
    $('#pm-list li.select_item').click(function(){
      var id = $(this).data('activetask-id');

      mobile_manager.updateTaskTechnicians('pm_id', id);
    });
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
  mobile_manager.updateTaskTechnicians = function(selection_type, id) {
    datacx.post("activetask/startCommWith", {"selection_type": selection_type, "id": id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        location.reload();
      }
    });
  };
  mobile_manager.getThread = function() {
    datacx.post('activetask/getDiscussionThread',{}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);


        if(reply.thread !== undefined) {
          lastMessageTime = reply.thread[0].discussion_content_time;
          $.each(reply.thread, function(index, value) {
            $("#task-comm-chatbox").append(mobile_manager.formatChatMessage(value.user_name,value.discussion_content_message,value.discussion_content_time)+"<br/>");
          });
        }
        $("#refresh-chat").on('click',function(){
          blockRefresh = true;
          mobile_manager.refreshThread();
        });
        setInterval(function(){ if(blockRefresh!==true) { blockRefresh = true; mobile_manager.refreshThread();} }, config.chatRefresh);
      }
    });
  };

  mobile_manager.refreshThread = function() {
    datacx.post('activetask/getDiscussionThread',{time:lastMessageTime}).then(function(reply){
      if(reply === null || reply.result === 0) {

      } else {
        if(reply.thread !== undefined) {
          lastMessageTime = reply.thread[0].discussion_content_time;
          var messages = '';
          $.each(reply.thread, function(index, value) {
            messages += mobile_manager.formatChatMessage(value.user_name,value.discussion_content_message,value.discussion_content_time)+"<br/>";
          });
          $("#task-comm-chatbox").prepend(messages);
        }
      }
      blockRefresh = false;
    });
  };

  mobile_manager.formatChatMessage = function(name,message,time) {
    var messageFormatted = '<strong>'+name+'</strong>: '+utils.hyperlinkUrls(message)+' <small>@'+time+'</small>';
    return messageFormatted;
  };

  mobile_manager.sendMessage = function(msg) {
    datacx.post('activetask/sendMessage',{discussion_content_message:msg}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        lastMessageTime = reply.data.discussion_content_time;
        $("#task-comm-chatbox").prepend(mobile_manager.formatChatMessage(reply.data.user_name,reply.data.discussion_content_message,reply.data.discussion_content_time)+"<br/>");
        $("textarea[name=\"task_comm_message\"]").val('');
      }
      blockRefresh = false;
    });
  };

}(window.mobile_manager = window.mobile_manager || {}));