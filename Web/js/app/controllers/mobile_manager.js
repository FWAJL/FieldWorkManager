/* JS FILE FOR MOBILE PAGES */
var locationName, blockRefresh;
var lastMessageTime = "";
var location_id = ''; 
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

  
  $(document).on("click", ".map-info-row", function() {
    location_id = $(this).attr('data-id');
  });

  $(document).on("click", "#task-location-info-modal-collect-data", function(e) {
    //Fetch forms through ajax
    datacx.post("task/getLocationSpecificForms", {'loc_id': location_id}).then(function(reply) {
      var li_str = '';
      if(reply.location_form_data.location_data.task_location_status == '2') {
        //Task complete, show alert
        if($('#confirmmsg-checkIfLocationComplete').length > 0) {
          utils.showAlert($('#confirmmsg-checkIfLocationComplete').val(), null);  
        }
      } else {
        if(reply.location_form_data.form_data.length > 0) {

          //Show prompt
          $('.prompt-modal').modal('hide');
          $('#prompt_ok').hide();

          //Render data
          $('#active-list').addClass('ui-selectable');
          for(i in reply.location_form_data.form_data) {
            li_str += '<li data-object="task_location" data-document-id="' + reply.location_form_data.form_data[i].document_id + 
                  '" class="ui-widget-content ui-selectee">' + reply.location_form_data.form_data[i].document_title + '</li>';
          }

          mobile_manager.showTaskLocationFormSelectorPrompt(
            null, 
            function(){
              $('.prompt-modal').modal('show');
            }
          );

          $('#active-list').html('');
          $('#active-list').append(li_str);
          $('ol#active-list li').addClass('select_item'); 
          
        } else {
          //show alert, no form
          if($('#confirmmsg-checkNoTaskLocationForms').length > 0) {
            utils.showAlert($('#confirmmsg-checkNoTaskLocationForms').val(), null);  
          }
        }
      }

    });

    $(document).on("click", "ol#active-list li", function(e) {
      $('ol#active-list li').removeClass('ui-selected'); 
      $(this).addClass('ui-selected');

      datacx.post("form/getPdfFileFor", {"form_id": parseInt($(this).attr("data-document-id")), "form_type": "task_location"}).then(function(reply) {
                
        if (reply === null || reply.result === 0) {//has an error
          toastr.error(reply.message);
          
        } else {//success
          toastr.success(reply.message);
          $.fancybox({ 
            href: reply.form_path,
            type: 'iframe', 
            openEffect : 'none', 
            closeEffect : 'none', 
            iframe : { preload: false } 
          });
        }
        
      });
    });

    
  });

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

  mobile_manager.showTaskLocationFormSelectorPrompt = function(clbkOk, clbkCancel){
    if($('.tlf-selector-modal').length !== 0)
    {
      //$('#prompt_title').html($('#promptmsg-checkCurrentProject').val());
      $('#tlf_prompt_title').html($('[id^="promptmsg-checkCurrent"]').val());

      //disable context menu
      $(".select_item").removeClass("select_item");
      $('.tlf-selector-modal').modal('show');
    }

    //Events
    $('#prompt_ok').on('click', function(){
      clbkOk();
    });
    $('.tlf-selector-modal').on('hidden.bs.modal', function (e) {
      clbkCancel();
    })
  }

}(window.mobile_manager = window.mobile_manager || {}));