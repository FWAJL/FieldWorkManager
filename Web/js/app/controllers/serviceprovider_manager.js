/**
 * IMPORTANT NOTICE (29-12-14):
 *   LOOK AT analyte_manager for the new implementation
 *   to make AJAX calls to the web services. It is more
 *   efficient and allows to write a lot less code.
 *
 * jQuery listeners for the task actions
 */


//************************************************//
var lastMessageTime = "";
var blockRefresh = false;
$(document).ready(function(){

  Dropzone.autoDiscover = false;
  $("input[name=\"itemCategory\"]").val('discussion_id');
  $("#document-upload").hide();
  serviceprovider_manager.getThread();
  $("#btn_send_message").on('click',function() {
    var msg = $("textarea[name=\"task_comm_message\"]").val();
    if(msg.trim() != '') {
      blockRefresh = true;
      serviceprovider_manager.sendMessage(msg.trim());
    }
  });

});

/***********
 * task_manager namespace
 * Responsible to manage tasks.
 */
(function (serviceprovider_manager) {

  serviceprovider_manager.sendMessage = function(msg) {
    datacx.post('activetask/sendMessage',{discussion_content_message:msg}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        lastMessageTime = reply.data.discussion_content_time;
        $("#task-comm-chatbox").prepend(serviceprovider_manager.formatChatMessage(reply.data.user_name,reply.data.discussion_content_message,reply.data.discussion_content_time)+"<br/>");
        $("textarea[name=\"task_comm_message\"]").val('');
      }
      blockRefresh = false;
    });
  };

  serviceprovider_manager.getThread = function() {
    datacx.post('activetask/getDiscussionThread',{}).then(function(reply){
      if(reply === null || reply.result === 0) {
        toastr.error(reply.message);
      } else {
        toastr.success(reply.message);
        $("input[name=\"itemId\"]").val(reply.discussion.discussion_id);
        if(reply.user_type == 'technician_id') {
          $("#file-attach").hide();
        }
        var dropzone = new Dropzone("#document-upload");
        dropzone.on("success", function(event,res) {
          if(res.result == 0) {
            toastr.error(res.message);
            dropzone.removeAllFiles();
          } else {
            toastr.success(res.message);
            $("#document-upload").hide();
            dropzone.removeAllFiles();
            $("textarea[name=\"task_comm_message\"]").val($("textarea[name=\"task_comm_message\"]").val()+"\n"+res.filepath);
          }
        });
        if(reply.thread !== undefined) {
          lastMessageTime = reply.thread[0].discussion_content_time;
          $.each(reply.thread, function(index, value) {
            $("#task-comm-chatbox").append(serviceprovider_manager.formatChatMessage(value.user_name,value.discussion_content_message,value.discussion_content_time)+"<br/>");
          });
        }
        $("#refresh-chat").on('click',function(){
          blockRefresh = true;
          serviceprovider_manager.refreshThread();
        });
        setInterval(function(){ if(blockRefresh!==true) { blockRefresh = true; serviceprovider_manager.refreshThread();} }, config.chatRefresh);
        $("#btn_attach_file").on('click',function(e){
            $("#document-upload").show();
        });
      }
    });
  };

  serviceprovider_manager.formatChatMessage = function(name,message,time) {
    var messageFormatted = '<strong>'+name+'</strong>: '+message+' <small>@'+time+'</small>';
    return messageFormatted;
  };

  serviceprovider_manager.loadPhoto = function(id) {
    datacx.post("file/loadOne", {"id": id}).then(function(reply){
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
        return undefined;
      } else {//success
        toastr.success(reply.message);
        console.log(reply.filepath.length);
        if(reply.filepath.length>0){
          $("textarea[name=\"task_comm_message\"]").val($("textarea[name=\"task_comm_message\"]").val()+"\n"+reply.filepath);
        }
      }
      $('.pselector-modal').modal('hide');
    });
  };

  serviceprovider_manager.refreshThread = function() {
    datacx.post('activetask/getDiscussionThread',{time:lastMessageTime}).then(function(reply){
      if(reply === null || reply.result === 0) {

      } else {
        if(reply.thread !== undefined) {
          lastMessageTime = reply.thread[0].discussion_content_time;
          var messages = '';
          $.each(reply.thread, function(index, value) {
            messages += serviceprovider_manager.formatChatMessage(value.user_name,value.discussion_content_message,value.discussion_content_time)+"<br/>";
          });
          $("#task-comm-chatbox").prepend(messages);
        }
      }
      blockRefresh = false;
    });
  };
}(window.serviceprovider_manager = window.serviceprovider_manager || {}));