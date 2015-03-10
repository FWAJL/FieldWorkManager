var map;
var boundaryShape;
var boundaryPath;
var rulerShape;
var rulerActive = shapeActive = addActive = existingActive = panActive = false;
var selectedMarker = 0;
var markers = new Array();
var drawingManager = drawingManagerRuler = "";
var activeIcon = inactiveIcon = selectedMarkerIcon = "default";
var facilityId;
var projectId;
var highlightCircle;
var polygonSettings = {
  "fillColor": "#FF0000",
  "fillOpacity": .3,
  "strokeWeight": 3
};

/*
 * On marker drag function
 */
var markerDrag = function(e){
  var post_data = {};
  post_data.location_lat = e.latLng.lat();
  post_data.location_long = e.latLng.lng();
  post_data.location_id = this.id;
  map_manager.edit(post_data, "location", "mapEdit", function(){});
  google.maps.event.addListener(this, 'mouseover', function () { highlightMarker(e, this) });
  highlightMarker(e, this)
}

var markerDragFacility = function(e) {
  google.maps.event.addListener(this, 'mouseover', function () { highlightMarker(e, this) });
  highlightMarker(e, this);
}

var markerDragStart = function(e){
  unhighlightMarker(e);
  google.maps.event.clearListeners(this, 'mouseover');
}


/*
 * Shape on click function
 */
var boundaryClick = function(e) {
  if(addActive === true) {
    addMarkerClick(e);
  } else {
    openProjectInfo(e,facilityId)
  }
}
/*
 * Add marker on the map and call edit or load msg prompt
 */
var addMarkerClick = function(e) {
  if (addActive === true && selectedMarker !== 0) {

    var post_data = {};
    post_data.location_lat = e.latLng.lat();
    post_data.location_long = e.latLng.lng();
    post_data.location_id = selectedMarker;
    map_manager.edit(post_data, "location", "mapEdit");

    newMarker = map.addMarker({
      draggable: true,
      lat: e.latLng.lat(),
      lng: e.latLng.lng(),
      dragend: markerDrag,
      dragstart: markerDragStart,
      zIndex: 500,
      optimized: false,
      clickable: true,
      click: function(e) {openLocationInfo(e,selectedMarker)}
    });
    newMarker.id = selectedMarker;
    if (selectedMarkerIcon !== "") {
      newMarker.setIcon(selectedMarkerIcon);
    }

    $("#marker-" + selectedMarker).find(".map-info-icon img").attr("src", selectedMarkerIcon);
    $("#marker-" + selectedMarker).removeClass('map-info-marker');
    $("#marker-" + selectedMarker).removeClass("map-info-marker-clickable");
    $("#marker-" + selectedMarker).removeClass("map-info-marker-selected");

    $("#map-info-add").trigger('click');

  } else if (addActive === true) {
    //var infoPrompt = new google.maps.InfoWindow({content: "<form class='form-horizontal'><div class='form-group'><div class='col-sm-10'><input class='form-control' type='text' id='new-marker-name' /></div></div><div class='form-group'><div class='col-sm-12'><textarea class='form-control' rows='3'></textarea></div></div><div class='form-group'><div class='col-sm-5'><button type='button' class='btn btn-primary'>Save</button></div><div class='col-sm-5'><button type='button' class='btn btn-default'>Cancel</button></div></div></form>"});
    newMarker = map.addMarker({
      draggable: true,
      lat: e.latLng.lat(),
      lng: e.latLng.lng(),
      dragend: markerDrag,
      dragstart: markerDragStart,
      zIndex: 500,
      optimized: false
    });
    if (activeIcon !== "") {
      newMarker.setIcon(activeIcon);
    }
    saveMarkerData(newMarker);

  }
}
/*
 * Add new marker, call msg prompt
 */
var saveMarkerData = function (marker){
  //Adding task through promptbox from anywhere
  $("#prompt_ok").off('click');
  $('#text_input').val("");
  var post_data = {};
  utils.showPromptBoxById("location-prompt","addNullCheckAddPrompt", function(){
    if($('#text_input').val() !== '')
    {
      //Check unique
      map_manager.ifLocationExists($('#text_input').val(), function(record_count) {
        if(record_count == 0)
        {
          //Ok to add
          $("#prompt_ok").off('click');
          $(".prompt-modal").off('hidden.bs.modal');
          post_data["location"] = {};
          post_data["location"]["location_name"] = $('#text_input').val();
          post_data["location"]["location_active"] = 1;
          post_data["location"]["location_lat"] = marker.getPosition().lat();
          post_data["location"]["location_long"] = marker.getPosition().lng();
          map_manager.add(post_data, "location", "add", marker, addLocationToMapInfo);
          $('.prompt-modal').modal('hide');
        }
        else
        {
          //Show alert, that location is already taken, choose new
          utils.togglePromptBox();
          utils.showAlert($('#confirmmsg-addUniqueCheck').val(), function(){
            utils.togglePromptBox();
          });
        }
      });
    }
    else
    {
      $('#text_input').focus();
    }
  }, "promptmsg-addNullCheckAddPrompt", function(){
    map.removeMarker(marker);
  });
}

/*
 * Add new marker to the map-info sidebar list
 */
var addLocationToMapInfo = function(location, marker){
  marker.id = location.location_id;
  google.maps.event.addListener(marker,'mouseover',function(e) { highlightMarker(e,marker.id);});
  google.maps.event.addListener(marker,'click', function(e) {openLocationInfo(marker,marker.id);});
  $("#map-info").append(
    "<div id='marker-" + location.location_id
      + "' data-id='" + location.location_id
      + "' data-active='" + location.location_active
      + "' class='row  map-info-row "
      + "'><div class='map-info-icon col-md-2'><span class='map-info-icon-image'><img src='" + activeIcon
      + "' /></span></div><div class='map-info-name col-md-9'>" + location.location_name
      + "</div></div>");
}

/*
 * Open project info window
 */
var setProjectZoomEvent = function(e) {
  $("#project-info-modal-zoom").off('click');
  $("#project-info-modal-zoom").on('click',function(ev){
    ev.preventDefault();
    if(typeof(boundaryShape) === "object"){
      map.fitLatLngBounds(boundaryPath);
    } else {
      map.setCenter(e.position.lat(), e.position.lng());
      map.zoomIn(7);
    }
    $("#project-info-modal").modal("hide");
  });
}

var setBoundaryEditEvent = function(e, projectId) {
  $("#project-info-modal-edit-boundary").off('click');
  $("#project-info-modal-edit-boundary").on('click',function(ev){
    ev.preventDefault();
    if(typeof(boundaryShape) === "object" || typeof(facilityId) !== "undefined"){
      if(!shapeActive) {
        $("#map-info-shape").trigger('click');
      }
    } else {
      datacx.post('project/setCurrentProject',{project_id: projectId}).then(function(reply){
        if(reply.dataId == projectId) {
          toastr.success(reply.message);
          utils.redirect('map/currentProject?active=shape',200);
        } else {
          toastr.error(reply.message);
        }

      });

    }
    $("#project-info-modal").modal("hide");
  });
}

var setLoadCoordinatesFromMarkerEvent = function(id) {
  $("#project-info-modal-update-coordinates").on('click',function(ev){
    ev.preventDefault();
    var marker;
    $.each(markers, function(key,mrk){
      if(mrk.id = id) {
        marker = mrk;
        return;
      }
    });
    //var marker = markers.find(function(mkr) {return id == mkr.id ? mkr : false;});
    $("#project-info-modal-latitude").val(Number(marker.position.lat()).toFixed(6));
    $("#project-info-modal-longitude").val(Number(marker.position.lng()).toFixed(6));
  });
}


var openProjectInfo = function(e,id) {
  datacx.post('facility/getItem',{'facility_id':id}).then(function(reply){
    if(reply.data.length > 0) {
      toastr.success(reply.message);
      item = reply.data[0];
      $("#project-info-modal-project_name").val(item.project.project_name);
      $("#project-window-title-project_name").html(item.project.project_name);
      $("#project-info-modal-facility_name").val(item.facility.facility_name);
      $("#project-window-title-facility_name").html(item.facility.facility_name);
      $("#project-info-modal-latitude").val(item.facility.facility_lat);
      $("#project-info-modal-longitude").val(item.facility.facility_long);
      setProjectZoomEvent(e);
      setBoundaryEditEvent(e, item.project.project_id);
      setLoadCoordinatesFromMarkerEvent(id);
      utils.showInfoWindow('#project-info-modal',function(){
        if(!utils.checkLatLng($("#project-info-modal-latitude").val(),$("#project-info-modal-longitude").val())) {
          utils.togglePromptBox();
          utils.showAlert($('#confirmmsg-checkCoordinates').val(), function(){
            utils.togglePromptBox();
          });
        } else if($("#project-info-modal-project_name").val() !== '' && $("#project-info-modal-facility_name").val() !== '') {
          post_data = {};
          post_data.project = {};
          post_data.facility = {};
          post_data.project.project_id = item.project.project_id;
          post_data.project.project_name = $("#project-info-modal-project_name").val();
          post_data.facility.facility_id = id;
          post_data.facility.facility_name = $("#project-info-modal-facility_name").val();
          post_data.facility.facility_lat = $("#project-info-modal-latitude").val();
          post_data.facility.facility_long = $("#project-info-modal-longitude").val();
          map_manager.edit({params: utils.stringifyJson(post_data)},'project','mapEdit',function(r){
            location.reload();
          });
        } else {
          $('#project-info-modal-project_name').focus();
        }
      },function(){});

    } else {
      toastr.error(reply.message);
    }

  });
}

var setLocationZoomEvent = function(e) {
  $("#location-info-modal-zoom").off('click');
  $("#location-info-modal-zoom").on('click',function(ev){
    ev.preventDefault();
    map.setCenter(e.position.lat(), e.position.lng());
    map.zoomIn(7);
    $("#location-info-modal").modal("hide");
  });
}

var setViewPhotosEvent = function(photosCount) {
  $("#location-info-modal-photos").off('click');
  $("#location-info-modal-photos").on('click',function(ev){
    ev.preventDefault();
    if(photosCount == 0){
      utils.showAlert($('#confirmmsg-noPhotos').val(), function(){});
    } else {
      $(".lightbox-content a").first().trigger("click");
    }
  });
}

var openLocationInfo = function(e,id) {
    Dropzone.forElement("#document-upload").removeAllFiles();
    datacx.post('location/getItem',{location_id: id}).then(function(reply){
      toastr.success(reply.message);
      var category = $("#document-upload input[name=\"itemCategory\"]").val();
      datacx.post('load',{itemId: id, itemCategory: category}).then(function(replyPhotos){
        item = reply.location;
        $("#location-info-modal-location_name").val(item.location_name);
        $("#location-window-title-location_name").html(item.location_name);
        $("#location-info-modal-location_desc").val(item.location_desc);
        $("#location-info-modal-location_lat").val(item.location_lat);
        $("#location-info-modal-location_long").val(item.location_long);
        $("#document-upload input[name=\"itemId\"]").val(id);
        $(".lightbox-content").html("");
        $.each(replyPhotos.fileResults, function(key,photo){
          $(".lightbox-content").append('<a href="'+photo.webPath+'" data-lightbox="modal-images"></a>');
        });
        setViewPhotosEvent(replyPhotos.fileResults.length);
        setLocationZoomEvent(e);
        utils.showInfoWindow('#location-info-modal',function(){
          if(!utils.checkLatLng($("#location-info-modal-location_lat").val(),$("#location-info-modal-location_long").val())) {
            utils.togglePromptBox();
            utils.showAlert($('#confirmmsg-checkCoordinates').val(), function(){
              utils.togglePromptBox();
            });
          } else if($("#location-info-modal-location_name").val() !== '') {
            post_data = {};
            post_data.location_id = id;
            post_data.location_name = $("#location-info-modal-location_name").val();
            post_data.location_desc = $("#location-info-modal-location_desc").val();
            post_data.location_lat = $("#location-info-modal-location_lat").val();
            post_data.location_long = $("#location-info-modal-location_long").val();
            map_manager.edit(post_data,'location','mapEdit',function(r){
              location.reload();
            });
          } else {
            $('#location-info-modal-location_name').focus();
          }
        },function(){});
      });
    });
}

var setTaskLocationZoomEvent = function(e) {
  $("#task-location-info-modal-zoom").off('click');
  $("#task-location-info-modal-zoom").on('click',function(ev){
    ev.preventDefault();
    map.setCenter(e.position.lat(), e.position.lng());
    map.zoomIn(7);
    $("#task-location-info-modal").modal("hide");
  });
}

var setAddRemoveFromTaskEvent = function(e,id,action) {
  $("#task-location-info-modal-"+action).off('click');
  $("#task-location-info-modal-"+action).on('click',function(ev){
    ev.preventDefault();
    map_manager.edit({location_ids : id, action: action},'task/location', 'updateItems',function(r){
      location.reload();
    });
  });
}

var openTaskLocationInfo = function(e,id,action) {
  datacx.post('location/getItem',{location_id: id}).then(function(reply){
    toastr.success(reply.message);
    item = reply.location;
    $("#task-location-info-modal-location_name").val(item.location_name);
    $("#task-location-window-title-task_location_name").html(item.location_name);
    setTaskLocationZoomEvent(e);
    $(".task-location-info-modal-action").hide()
    $("#task-location-info-modal-"+action).show();
    setAddRemoveFromTaskEvent(e,id,action);
    utils.showInfoWindow('#task-location-info-modal',function(){
      if($("#task-location-info-modal-location_name").val() !== '') {
        post_data = {};
        post_data.location_id = id;
        post_data.location_name = $("#task-location-info-modal-location_name").val();
        map_manager.edit(post_data,'location','mapEdit',function(r){
          location.reload();
        });
      } else {
        $('#location-info-modal-location_name').focus();
      }
    },function(){});
  });
}

var highlightMarker = function(e, marker) {
  if (!marker.dragging) {
    unhighlightMarker(e);
    $("#marker-"+ marker.id).addClass('map-info-marker-highlighted');
    highlightCircle = new google.maps.Marker({
      position: e.latLng || new google.maps.LatLng(marker.position.lat(), marker.position.lng()),
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        fillOpacity: 0,
        fillColor: '#ff0000',
        strokeOpacity: 1.0,
        strokeColor: '#FFFFFF',
        strokeWeight: 3.0,
        scale: 25,
        anchor: new google.maps.Point(0,0.66)
      },
      optimized: false,
      zIndex: -50,
      map: map.map
    });
    google.maps.event.addListener(highlightCircle,'mouseout',unhighlightMarker);
  }
}

var unhighlightMarker = function(e) {
  $(".map-info-row").removeClass('map-info-marker-highlighted');
  if(typeof highlightCircle === 'object'){
    highlightCircle.setMap(null);
  }
}

function load(params) {
  datacx.post(
    params.dataUrl,
    {"properties": utils.stringifyJson(params.properties)}
  ).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success

        //set current facility and project ids if they are set
        if(typeof(reply.facility_id) !== 'undefined') {
          facilityId = reply.facility_id;
        }
        if(typeof(reply.project_id) !== 'undefined') {
          projectId = reply.project_id;
        }

        if(reply.controls.markers !== true){
          $("#map-info-add").hide();
        }
        if(reply.controls.shapes !== true){
          $("#map-info-shape").hide();
        }
        if(reply.controls.ruler !== true){
          $("#map-info-ruler").hide();
        }

        //set boundary
        if(reply.controls.shapes === true && typeof reply.boundary !== 'undefined' && reply.boundary.length>0 && reply.boundary!=""  ){
          //get boundary from response and decode it from string to path
          boundaryPath = google.maps.geometry.encoding.decodePath(reply.boundary);
          boundaryShape = new google.maps.Polygon({
            paths: boundaryPath,
            strokeWeight: polygonSettings.strokeWeight,
            fillColor: polygonSettings.fillColor,
            fillOpacity: polygonSettings.fillOpacity,
            clickable: true
          });
          google.maps.event.addListener(boundaryShape,'click',boundaryClick);
          google.maps.event.addListener(boundaryShape.getPath(), 'insert_at', function(e) {
            saveBoundary(boundaryShape);
          });
          google.maps.event.addListener(boundaryShape.getPath(), 'set_at', function(e) {
            saveBoundary(boundaryShape);
          });
          boundaryShape.setMap(map.map);
        }

        //Center Map
        map.setCenter(
          reply.defaultPosition.lat,
          reply.defaultPosition.lng);
        var markerIcon = "";
        var markerClass = "";
        if (typeof reply.activeIcon !== 'undefined' && typeof reply.inactiveIcon !== 'undefined') {
          activeIcon = reply.activeIcon;
          inactiveIcon = reply.inactiveIcon;
        }



        //Build markers list
        $.each(reply.items, function(index, item) {
          markerIcon = "";
          markerClass = "";

          if (item.noLatLng === true) {
            markerIcon = reply.noLatLngIcon;
            markerClass = "map-info-marker";
          } else {
            item.marker.id = item.id;
            if(reply.type === 'facility') {
              item.marker.draggable = true;
              item.marker.dragstart = markerDragStart;
              item.marker.dragend = markerDragFacility;
            }
            if(reply.controls.markers === true){
              item.marker.draggable = true;
              item.marker.dragend = markerDrag;
              item.marker.dragstart = markerDragStart;
            }
            if(reply.type === 'facility') {
              item.marker.clickable = true;
              item.marker.click = function(e){openProjectInfo(e,item.marker.id)};
            } else if (reply.type == 'location') {
              item.marker.clickable = true;
              item.marker.click = function(e){openLocationInfo(e,item.marker.id)};
            } else if (reply.type == 'task') {
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
            item.marker.mouseover = function(e) { highlightMarker(e,item.marker);};
            //item.marker.mouseout = unhighlightMarker;
            markers.push(item.marker);
            markerIcon = item.marker.icon;
          }
          $("#map-info").append(
            "<div id='marker-" + item.id
              + "' data-id='" + item.id
              + "' data-active='" + item.active
              + "' class='row map-info-row " + markerClass
              + "'><div class='map-info-icon col-md-2'><span class='map-info-icon-image'><img src='" + markerIcon
              + "' /></span></div><div class='map-info-name col-md-9'>" + item.name
              + "</div></div>");
        });
        markers = map.addMarkers(markers);
        $(document).on('mouseover', '.map-info-row', function () {
          var el= $(this);
          var marker;
          $.each(markers, function(key,mrk){
            if(mrk.id = el.data('id')) {
              marker = mrk;
              return;
            }
          });
          if (typeof marker !== "undefined") {
            highlightMarker(false, marker);
          } else {
            $("#marker-"+el.data('id')).addClass('map-info-marker-highlighted');
          }
        });
        $(document).on('mouseout', '.map-info-row', function () {
          unhighlightMarker(false);
        });


        setTimeout(function() {
          if(typeof(boundaryShape) === "object"){
            map.fitLatLngBounds(boundaryPath);
          } else {
            if (markers.length > 1) {
              map.fitZoom();
            } else if (markers.length === 1)
            {
              map.setCenter(markers[0].position.lat(), markers[0].position.lng());
            }
          }
        }, 500);

        // called on existing/add controls
        if(reply.controls.markers === true){
          GMaps.on('click', map.map, addMarkerClick);
        }

        /*
         * computeMeasurements function called on shape creation and updates
         */

        function computeMeasurements(overlay, type) {
          infoContainer = $("#map-ruler");
          if (type === google.maps.drawing.OverlayType.POLYLINE) {
            path = overlay.getPath();
            pathSize = path.getLength();
            distance = google.maps.geometry.spherical.computeLength(path);
            infoContainer.html("Distance: " + (distance / 1000).toFixed(2) + " km " + (distance * 0.62137).toFixed(2) + " miles");
          } else {
            path = overlay.getPath();
            pathSize = path.getLength();
            infoPosition = path.getAt(pathSize - 1);
            area = google.maps.geometry.spherical.computeArea(path);
            infoContainer.html("Area: " + (area / 1000000).toFixed(2) + " sq kms " + (area / 2589988.11).toFixed(2) + " sq miles");
          }

        };
        /*
         * save boundary shape called on point edit or new shape
         */
        function saveBoundary(shape){
          var post_data = {};
          post_data.boundary = google.maps.geometry.encoding.encodePath(shape.getPath());
          map_manager.editBoundary(post_data, "facility", "mapEdit");
        }

        $("#map-info-ruler").click(function() {
          //toggle all other controls and make sure to toggle current control last
          toggleAdd(false);
          togglePan(false);
          toggleShape(false);
          toggleRuler(!rulerActive);
        });

        $("#map-info-shape").on('click', function() {
          //toggle all other controls and make sure to toggle current control last
          toggleAdd(false);
          togglePan(false);
          toggleRuler(false);
          toggleShape(!shapeActive);
        });

        $("#map-info-pan").click(function(e) {
          //toggle all other controls and make sure to toggle current control last
          toggleAdd(false);
          toggleShape(false);
          toggleRuler(false);
          togglePan(!panActive);

        });

        $("#map-info-add").click(function(e) {
          //toggle all other controls and make sure to toggle current control last
          togglePan(false);
          toggleShape(false);
          toggleRuler(false);
          toggleAdd(!addActive);
        });

        $(".map-info-marker").on('click',function(e) {
          if($(this).hasClass("map-info-marker")){
            if (addActive === true) {
              existingActive = true;
              if (selectedMarker !== $(this).data("id")) {
                $(".map-info-marker").removeClass("map-info-marker-selected");
                $(this).addClass("map-info-marker-selected");
                selectedMarker = $(this).data("id");
                if ($(this).data('active') === 1) {
                  selectedMarkerIcon = activeIcon;
                } else {
                  selectedMarkerIcon = inactiveIcon;
                }

              } else {
                $(".map-info-marker").removeClass("map-info-marker-selected");
                selectedMarker = 0;
                existingActive = false;
              }

            }
          }
        });

        $(document).on('click','.map-info-row',function(e){
          if(!$(this).hasClass("map-info-marker")) {
            var markerId = $(this).data("id");
            var marker;
            $.each(markers, function(key,mrk){
              if(mrk.id = markerId) {
                marker = mrk;
                return;
              }
            });
            //var marker = markers.find(function(mkr) {return markerId == mkr.id ? mkr : false;});
            if(reply.type == 'location'){
              openLocationInfo(marker,$(this).data("id"));
            } else if (reply.type == 'facility') {
              openProjectInfo(marker,$(this).data("id"));
            } else if (reply.type == 'task') {
              var action = "";
              if(marker.task == true) {
                action = 'remove';
              } else {
                action = 'add';
              }
              openTaskLocationInfo(marker,$(this).data("id"),action);
            }

          }
        });

        /*
         * Toggles for map controls
         */

        function toggleAdd(toggle) {
          if(reply.controls.markers === true){
            if (toggle === false) {
              addActive = false;
              $("#map-info-add").removeClass("map-info-control-active");
              map.setOptions({draggableCursor: 'grab'});
              $(".map-info-marker").removeClass("map-info-marker-clickable");
            } else {
              $("#map-info-add").addClass("map-info-control-active");
              addActive = true;
              map.setOptions({draggableCursor: 'pointer'});
              $(".map-info-marker").addClass("map-info-marker-clickable");
            }
          }
        }
        ;

        function togglePan(toggle) {
          if (toggle === false) {
            panActive = false;
            $("#map-info-pan").removeClass("map-info-control-active");
            $(".map-info-marker").removeClass("map-info-marker-selected");
            map.setOptions({draggableCursor: 'grab'});
            selectedMarker = 0;
          } else {
            panActive = true;
            $("#map-info-pan").addClass("map-info-control-active");
            map.setOptions({draggableCursor: 'grab'});
          }
        }
        ;

        function toggleShape(toggle) {
          if(reply.controls.shapes === true){
            if (toggle === false) {
              $("#map-info-shape").removeClass("map-info-control-active");
              if (typeof(drawingManager) === "object") {
                drawingManager.setDrawingMode(null);
                drawingManager.setMap(null);
                if(typeof(boundaryShape) === "object"){
                  boundaryShape.setEditable(false);
                }
              }

              shapeActive = false;
            }
            else {
              shapeActive = true;
              if(typeof(boundaryShape) === "object"){
                boundaryShape.setEditable(true);
              }
              drawingManager = new google.maps.drawing.DrawingManager({
                drawingControl: true,
                drawingControlOptions: {
                  position: google.maps.ControlPosition.TOP_CENTER,
                  drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON
                  ]
                },
                polygonOptions: {
                  fillColor: polygonSettings.fillColor,
                  fillOpacity: polygonSettings.fillOpacity,
                  strokeWeight: polygonSettings.strokeWeight,
                  clickable: false,
                  editable: true,
                  zIndex: 1
                }
              });
              drawingManager.setMap(map.map);
              google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
                if (typeof(boundaryShape) === "object") {
                  boundaryShape.setMap(null);
                }
                if (e.type !== google.maps.drawing.OverlayType.MARKER) {
                  // Switch back to non-drawing mode after drawing a shape.
                  drawingManager.setDrawingMode(null);

                  var newShape = e.overlay;
                  newShape.type = e.type;
                  boundaryShape = newShape;
                  saveBoundary(boundaryShape);
                  google.maps.event.addListener(boundaryShape.getPath(), 'insert_at', function(e) {
                    saveBoundary(boundaryShape);
                  });
                  google.maps.event.addListener(boundaryShape.getPath(), 'set_at', function(e) {
                    saveBoundary(boundaryShape);
                  });
                }
              });
              $("#map-info-shape").addClass("map-info-control-active");
            }
          }
        };

        function toggleRuler(toggle) {
          if(reply.controls.ruler === true){
            if (toggle === false) {
              $("#map-ruler").hide();
              $("#map-ruler").html("");
              $("#map-info-ruler").removeClass("map-info-control-active");
              if (typeof(drawingManagerRuler) === "object") {
                drawingManagerRuler.setDrawingMode(null);
                map.removeOverlays();
                if (typeof(rulerShape) === "object") {
                  rulerShape.setMap(null);
                }
                drawingManagerRuler.setMap(null);
              }
              rulerActive = false;
            }
            else {
              drawingManagerRuler = new google.maps.drawing.DrawingManager({
                drawingControl: true,
                drawingControlOptions: {
                  position: google.maps.ControlPosition.TOP_CENTER,
                  drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON,
                    google.maps.drawing.OverlayType.POLYLINE
                  ]
                },
                polygonOptions: {
                  fillColor: polygonSettings.fillColor,
                  fillOpacity: polygonSettings.fillOpacity,
                  strokeWeight: polygonSettings.strokeWeight,
                  clickable: false,
                  editable: true,
                  zIndex: 1
                }
              });
              drawingManagerRuler.setMap(map.map);
              google.maps.event.addListener(drawingManagerRuler, 'overlaycomplete', function(e) {
                if (e.type !== google.maps.drawing.OverlayType.MARKER) {
                  drawingManagerRuler.setDrawingMode(null);
                  if (typeof(rulerShape) === 'object') {
                    rulerShape.setMap(null);
                  }
                  var newShape = e.overlay;
                  newShape.type = e.type;
                  rulerShape = newShape;
                  computeMeasurements(newShape, newShape.type);
                  google.maps.event.addListener(newShape.getPath(), 'insert_at', function(e) {
                    computeMeasurements(newShape, newShape.type);
                  });
                  google.maps.event.addListener(newShape.getPath(), 'set_at', function(e) {
                    computeMeasurements(newShape, newShape.type);
                  });

                }
              });
              rulerActive = true;
              $("#map-info-ruler").addClass("map-info-control-active");
              $("#map-ruler").show();
            }
          }
        }

        $(".control-active").trigger("click");

        //toggle active control
        /*if(reply.activeControl === "markers"){
         toggleAdd(!addActive);
         togglePan(false);
         toggleShape(false);
         toggleRuler(false);
         } else if (reply.activeControl === "shapes") {
         toggleAdd(false);
         togglePan(false);
         toggleShape(!shapeActive);
         toggleRuler(false);
         } else if (reply.activeControl === "ruler") {
         toggleAdd(false);
         togglePan(false);
         toggleShape(false);
         toggleRuler(!rulerActive);
         } else {
         toggleAdd(false);
         togglePan(!panActive);
         toggleShape(false);
         toggleRuler(false);
         }*/

      }
    });
}

/***********
 * map_manager namespace
 * Responsible to manage project, tasks and locations
 */
(function (map_manager) {
  map_manager.ifLocationExists = function(locationName, decision) {
    datacx.post("location/ifLocationExists", {location_name: locationName}).then(function(reply) {
      decision(reply.record_count);
    })
  };

  map_manager.add = function (data, controller, action, marker, callback) {
    datacx.post(controller + "/" + action, data["location"]).then(function (reply) {//call location/add
      if (reply === null || reply.dataId === undefined || reply.dataId === null || parseInt(reply.dataId) === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        if(callback !== undefined){
          callback(reply.dataIn[0], marker);
        }

      }
    });
  };

  map_manager.edit = function(data, controller, action, callback) {
    datacx.post(controller + "/" + action, data).then(function(reply) {//call edit
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
        if(callback !== undefined){
          callback(reply);
        }
      }
    });
  };

  map_manager.editBoundary = function(facility, controller, action) {
    datacx.post(controller + "/" + action, facility).then(function(reply) {//call location/edit
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
      }
    });
  };


}(window.map_manager = window.map_manager || {}));
