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

  if($("#mobile-location-list").length){
    var optionsPosition = {
      enableHighAccuracy: true,
      timeout: 50000,
      maximumAge: 0
    };
    $("#document-upload input[name=\"itemCategory\"]").val('location_id')
    var params = {
      "dataUrl": "map/listCurrentProjectTasks",
      "properties": {
        "location_obj": {
          "objectLatPropName": "location_lat",
          "objectLngPropName": "location_long",
          "objectActivePropName": "location_active",
          "objectNamePropName": "location_name",
          "objectIdPropName": "location_id"
        },
        "defaultLocation": {
          "object": "facility_obj",
          "objectLatPropName": "facility_lat",
          "objectLngPropName": "facility_long"
        },
        activeTask: true
      }
    };
    var setViewPhotosEvent = function(photosCount,documentId) {
      $("#location-info-modal-photos").off('click');
      if(photosCount == 0){
        $("#location-info-modal-photos").hide();
      } else {
        $("#location-info-modal-photos-count").html('('+photosCount+')');
        $("#location-info-modal-photos").on('click',function(ev){
          ev.preventDefault();
          if(photosCount == 0){
            //utils.showAlert($('#confirmmsg-noPhotos').val(), function(){});
          } else {
            $(".lightbox-content a").first().trigger("click");
          }
        });
        $("#location-info-modal-photos").show();
      }
      if(photosCount != 0){
        $("#lightbox").on("click",".remove-file-link",function(){
          var documentId = $(this).data('id');
          mobile_manager.removePhoto(documentId);
        });
      }

    }

    var openTaskLocationInfo = function(e,id,action) {
      $("#task-location-id-selected").val(id);
      selectedMarker = id;
      $("#document-upload input[name=\"title\"]").val("");
      if(e !== undefined) {
        $("#task-location-info-modal-zoom").show();
        $("#location-info-modal-place").hide();
        $("#location-info-modal-directions").show();
      } else {
        $("#task-location-info-modal-zoom").hide();
        $("#location-info-modal-place").show();
        $("#location-info-modal-directions").hide();
      }
      $("#task-location-info-modal-zoom").hide();
      $("#location-info-modal-directions").hide();
      $("#location-info-modal-place").hide();
      $("#task-location-info-walk-drive").hide();
      if(action === 'add') {
        $("#task-location-info-modal-collect-data").hide();
      } else {
        $("#task-location-info-modal-collect-data").show();
      }
      Dropzone.forElement("#document-upload").removeAllFiles();
      datacx.post('location/getItem',{location_id: id}).then(function(reply){
        //toastr.success(reply.message);
        var category = $("#document-upload input[name=\"itemCategory\"]").val();
        datacx.post('file/load',{itemId: id, itemCategory: category}).then(function(replyPhotos){
          item = reply.location;
          $("#document-upload input[name=\"itemId\"]").val(id);
          $("#task-location-info-modal-location_name").val(item.location_name);
          $("#task-location-info-modal-location_desc").val(item.location_desc);
          $("#task-location-info-modal-location_lat").val(item.location_lat);
          $("#task-location-info-modal-location_long").val(item.location_long);
          $("#task-location-window-title-task_location_name").html(item.location_name);
          $(".lightbox-content").html("");
          var documentId;
          $.each(replyPhotos.fileResults, function(key,photo){
            $(".lightbox-content").append('<a href="'+photo.filePath+'" data-title="'+photo.document_title+'<a data-id=\''+photo.document_id+'\' class=\'remove-file-link\' href=\'#\'>'+$("#remove-file-title").val()+'</a>" data-lightbox="modal-images"></a>');
            documentId = photo.document_id;
          });
          setViewPhotosEvent(replyPhotos.fileResults.length,documentId);
          $(".task-location-info-modal-action").hide();
          if(activeTask!==true) {
            $("#task-location-info-modal-"+action).show();
            setAddRemoveFromTaskEvent(e,id,action);
          }
          utils.showInfoWindow('#task-location-info-modal',function(){
            if($("#task-location-info-modal-location_name").val() !== '') {
              post_data = {};
              post_data.location_id = id;
              post_data.location_name = $("#task-location-info-modal-location_name").val();
              post_data.location_desc = $("#task-location-info-modal-location_desc").val();
              post_data.location_lat = $("#task-location-info-modal-location_lat").val();
              post_data.location_long = $("#task-location-info-modal-location_long").val();
              mobile_manager.editLocation(post_data,'location','mapEdit',function(r){
                location.reload();
              });
            } else {
              $('#location-info-modal-location_name').focus();
            }
          },function(){});
        });
      });
    }
    var openAddNewTaskLocation = function() {
      var imagesOfNewLocation = [];

      utils.showInfoWindow('#task-location-info-modal',
        function(){
          //check to see if unique
          datacx.post('location/ifLocationExists', {location_name: $("#task-location-info-modal-location_name").val()}).then(function(reply) {
            if(reply.record_count > 0) {
              $('#task-location-info-modal').modal('hide');
              utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function(){
                $('#task-location-info-modal').modal('show');
                hideModifyTaskLocationFields();
              });
            } else {
              //Callback on OK
              if($("#task-location-info-modal-location_name").val() !== '') {
                post_data = {};
                //values
                post_data.location_name   = $("#task-location-info-modal-location_name").val();
                post_data.location_desc   = $("#task-location-info-modal-location_desc").val();
                post_data.location_lat    = $("#task-location-info-modal-location_lat").val();
                post_data.location_long   = $("#task-location-info-modal-location_long").val();
                post_data.images          = JSON.stringify(imagesOfNewLocation);
                if(navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(function(position) {
                    post_data.location_lat = position.coords.latitude;
                    post_data.location_long = position.coords.longitude;
                    //Call save
                    mobile_manager.editLocation(post_data,'location', 'addLocMob', function(r){
                      //console.log(r);
                      //resetTaskLocationDialogForEdit();
                      utils.redirect("mobile/map");
                    });
                  },function(err){},optionsPosition);
                }
              } else {
                $('#location-info-modal-location_name').focus();
              }
            }
          });

        },function(){
          //Callback on Cancel
          resetTaskLocationDialogForEdit();
        }
      );

      Dropzone.forElement("#document-upload").removeAllFiles();

      hideModifyTaskLocationFields();


      Dropzone.forElement("#document-upload").on("success", function(e, response) {
        //Keep pushing to the JS array
        imagesOfNewLocation.push(response.document.document_value);
      });
    };
    var hideModifyTaskLocationFields = function () {
      //hide/modify irrelevant fields
      $('#task-location-info-modal-location_name').removeAttr('disabled');
      $('.modal-update').html('Add');
      $('#task-location-info-modal-zoom').parent().hide();
      $('#task-location-info-modal-collect-data').parent().hide();
      $('#location-info-modal-directions').parent().hide();
      $('#location-info-modal-photos').parent().hide();
      $("#task-location-info-modal-location_lat").parent().hide();
      $("#task-location-info-modal-location_long").parent().hide();
      $("#location-info-modal-mark").parent().hide();
      //clear text
      $('#task-location-info-modal-location_name').val('');
      $('#task-location-info-modal-location_desc').val('');
    };
    var resetTaskLocationDialogForEdit = function () {
      $('.modal-update').html('Update');
      $('#task-location-info-modal-zoom').parent().show();
      $('#task-location-info-modal-collect-data').parent().show();
      $('#location-info-modal-directions').parent().show();
      $('#location-info-modal-photos').parent().show();
      $("#task-location-info-modal-location_lat").parent().show();
      $("#task-location-info-modal-location_long").parent().show();
      $("#location-info-modal-mark").parent().show();
    };

    $("#location-info-modal-mark").on('click',function(e){
      e.preventDefault();
      if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          $("#task-location-info-modal-location_lat").val(position.coords.latitude);
          $("#task-location-info-modal-location_long").val(position.coords.longitude);
        },function(err){},optionsPosition);
      }
    });
    var markers = new Array();
    var selectedMarker = 0;
    function loadList(params) {
      datacx.post(
        params.dataUrl,
        {"properties": utils.stringifyJson(params.properties)}
      ).then(function(reply) {//call AJAX method to call Project/Add WebService
          if (reply === null || reply.result === 0) {//has an error
            //toastr.error(reply.message);
          } else {
            mapType = reply.type;
            activeTask = reply.activeTask;
            //set current facility and project ids if they are set
            if(typeof(reply.facility_id) !== 'undefined') {
              facilityId = reply.facility_id;
            }
            if(typeof(reply.project_id) !== 'undefined') {
              projectId = reply.project_id;
              setCurrentProjectFlag = false;
            } else {
              setCurrentProjectFlag = true;
            }

            var taskHeading=false;
            var taskOtherHeading=false;
          }
          //Build markers list
          $.each(reply.items, function(index, item) {
            markerIcon = "";
            markerClass = "";

            if (item.noLatLng === true) {
              markerIcon = reply.noLatLngIcon;
              markerClass = "location-list-marker";
            } else {
              item.marker.id = item.id;
              if (reply.type == 'task') {
                item.marker.clickable = true;
                item.marker.task = item.task;
                if(item.task) {
                  item.marker.click = function(e){openTaskLocationInfo(e,item.marker.id,'remove')};
                } else {
                  item.marker.click = function(e){openTaskLocationInfo(e,item.marker.id,'add')};
                }
              }
              item.marker.zIndex = 500;
              item.marker.optimized = false;
//            item.marker.mouseover = function(e) { highlightMarker(e,item.marker);};
              //item.marker.mouseout = unhighlightMarker;
              markers.push(item.marker);
              markerIcon = item.marker.icon;
            }
            if(item.task && !taskHeading && reply.type === 'task') {
              taskHeading = true;
              $("#location-list").append("<div class='row'><h4>"+$("#tasks-heading").val()+"</h4></div>");
              $("#location-list").append("<div class='row'><input type='button' value='Add New Location' class='at at-status btn btn-default' id='btn_addnewloc'></div>")
            }
            if(!taskOtherHeading && !item.task && reply.type === 'task') {
              taskOtherHeading = true;
              $("#location-list").append("<div class='row'><h4>"+$("#other-locations-heading").val()+"</h4></div>");
            }
            var showMarker = true;
            if(reply.type === 'task' && item.noLatLng === true && item.task !==true) {
              var showMarker = false;
            }
            if(showMarker){
              $("#location-list").append(
                "<div id='marker-" + item.id
                  + "' data-id='" + item.id
                  + "' data-active='" + item.active
                  + "' class='row location-list-row " + markerClass
                  + "'><div class='location-list-icon col-md-2'><span class='location-list-icon-image'><img src='" + markerIcon
                  + "' /></span></div><div class='location-list-name col-md-9'>" + item.name
                  + "</div></div>");
            }

          });

          $(document).on('click','.location-list-row',function(e){
            //if(!$(this).hasClass("map-info-marker")) {
            var markerId = $(this).data("id");
            var marker;
            $.each(markers, function(key,mrk){
              if(mrk.id == markerId) {
                return marker = mrk;
              }
            });
            //var marker = markers.find(function(mkr) {return markerId == mkr.id ? mkr : false;});
            if (reply.type == 'task') {
              selectedMarker = markerId;
              var action = "";
              if(marker !== undefined) {
                if(marker.task == true) {
                  action = 'remove';
                } else {
                  action = 'add';
                }
              }
              openTaskLocationInfo(marker,$(this).data("id"),action);
            }

            //}
          });
          //add new task location
          $(document).on('click','#btn_addnewloc',function(e){
            openAddNewTaskLocation();
          });
        });
    }
    loadList(params);

  }

  
  $(document).on("click", ".location-list-row", function() {
    $("#task-location-id-selected").val($(this).attr('data-id'));
  });

  $(document).on("click", "#task-location-info-modal-collect-data", function(e) {
    //Fetch forms through ajax
    datacx.post("task/getLocationSpecificForms", {'loc_id': $("#task-location-id-selected").val()}).then(function(reply) {
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
          //toastr.error(reply.message);
          
        } else {//success
          //toastr.success(reply.message);
          $.fancybox({ 
            href: reply.form_path,
            type: 'iframe',
            width: '100%',
            height: '100%',
            autoSize: false, 
            openEffect: 'none', 
            closeEffect: 'none', 
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
        //toastr.error(reply.message);
        return undefined;
      } else {//success
        //toastr.success(reply.message);
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
        //toastr.error(reply.message);
      } else {
        //toastr.success(reply.message);
        $("#task-notes").prepend(mobile_manager.formatChatMessage(reply.user,reply.data.task_note_value,reply.data.task_note_time)+"<br/>");
        $("#task_notes_message").val(locationName);
      }
    });
  };
  mobile_manager.updateTaskTechnicians = function(selection_type, id) {
    datacx.post("activetask/startCommWith", {"selection_type": selection_type, "id": id}).then(function(reply) {
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
        return undefined;
      } else {//success
        //toastr.success(reply.message);
        location.reload();
      }
    });
  };
  mobile_manager.getThread = function() {
    datacx.post('activetask/getDiscussionThread',{}).then(function(reply){
      if(reply === null || reply.result === 0) {
        //toastr.error(reply.message);
      } else {
        //toastr.success(reply.message);


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
        //toastr.error(reply.message);
      } else {
        //toastr.success(reply.message);
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
  };

  mobile_manager.editLocation = function(data, controller, action, callback) {
    datacx.post(controller + "/" + action, data).then(function(reply) {//call edit
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
      } else {//success
        //toastr.success(reply.message);
        if(callback !== undefined){
          callback(reply);
        }
      }
    });
  };
  mobile_manager.removePhoto = function(document_id) {
    datacx.post("file/remove", {"document_id": document_id, "itemCategory": 'location_id'}).then(function(reply){
      if (reply === null || reply.result === 0) {//has an error
        //toastr.error(reply.message);
        return undefined;
      } else {//success
        //toastr.success(reply.message);
        location.reload();
      }
    });
  }

}(window.mobile_manager = window.mobile_manager || {}));