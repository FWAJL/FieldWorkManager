var map;
var boundaryShape;
var boundaryPath;
var rulerShape;
var rulerActive = shapeActive = addActive = existingActive = panActive = false;
var selectedMarker = 0;
var markers = new Array();
var drawingManager = drawingManagerRuler = "";
var activeIcon = inactiveIcon = selectedMarkerIcon = "default";
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
  map_manager.edit(post_data, "location", "mapEdit");
}


/*
 * Shape on click function
 */
var boundaryClick = function(e) {
  if(addActive === true) {
    addMarkerClick(e);
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
      dragend: markerDrag
    });
    newMarker.id = selectedMarker;
    if (selectedMarkerIcon !== "") {
      newMarker.setIcon(selectedMarkerIcon);
    }

    $("#marker-" + selectedMarker).find(".map-info-icon img").attr("src", selectedMarkerIcon);
    $("#marker-" + selectedMarker).removeClass('map-info-marker');
    $("#marker-" + selectedMarker).css("background-color", "transparent");
    $("#marker-" + selectedMarker).css("cursor", "default");

    $("#map-info-add").trigger('click');

  } else if (addActive === true) {
    //var infoPrompt = new google.maps.InfoWindow({content: "<form class='form-horizontal'><div class='form-group'><div class='col-sm-10'><input class='form-control' type='text' id='new-marker-name' /></div></div><div class='form-group'><div class='col-sm-12'><textarea class='form-control' rows='3'></textarea></div></div><div class='form-group'><div class='col-sm-5'><button type='button' class='btn btn-primary'>Save</button></div><div class='col-sm-5'><button type='button' class='btn btn-default'>Cancel</button></div></div></form>"});
    newMarker = map.addMarker({
      draggable: true,
      lat: e.latLng.lat(),
      lng: e.latLng.lng(),
      dragend: markerDrag
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
  if($('#promptmsg-addNullCheckAddPrompt').length !== 0)
  {
    $("#prompt_ok").off('click');
    var post_data = {};
    utils.showPromptBox("addNullCheckAddPrompt", function(){
      if($('#text_input').val() !== '')
      {
        //Check unique
        map_manager.ifLocationExists($('#text_input').val(), function(record_count) {
          if(record_count == 0)
          {
            console.log('duplicate');
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
            //Show alert, that task is already taken, choose new
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
}

/*
* Add new marker to the map-info sidebar list
*/
var addLocationToMapInfo = function(location, marker){
  marker.id = location.location_id;
  $("#map-info").append(
    "<div id='marker-" + location.location_id
      + "' data-id='" + location.location_id
      + "' data-active='" + location.location_active
      + "' class='row  map-info-row "
      + "'><div class='map-info-icon col-md-2'><span class='map-info-icon-image'><img src='" + activeIcon
      + "' /></span></div><div class='map-info-name col-md-9'>" + location.location_name
      + "</div></div>");
}

function load(params) {
  datacx.post(
    params.dataUrl,
    {"properties": utils.stringifyJson(params.properties)}
  ).then(function(reply) {//call AJAX method to call Project/Add WebService
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success

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
            if(reply.controls.markers === true){
              item.marker.draggable = true;
              item.marker.dragend = markerDrag;
            }
            markers.push(item.marker);
            markerIcon = item.marker.icon;
          }
          $("#map-info").append(
            "<div id='marker-" + item.id
              + "' data-id='" + item.id
              + "' data-active='" + item.active
              + "' class='row  map-info-row " + markerClass
              + "'><div class='map-info-icon col-md-2'><span class='map-info-icon-image'><img src='" + markerIcon
              + "' /></span></div><div class='map-info-name col-md-9'>" + item.name
              + "</div></div>");
        });
        if (markers.length === 1) {
          var mrk = map.addMarker(markers[0]);
        } else {
          markers = map.addMarkers(markers);
        }


        setTimeout(function() {
          if(typeof(boundaryShape) === "object"){
            map.fitLatLngBounds(boundaryPath);
          } else {
            if (markers.length > 1) {
              map.fitZoom(markers);
            } else if (markers.length === 1)
            {
              map.setCenter(markers[0].lat, markers[0].lng);
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

        $("#map-info-shape").click(function() {
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
                $(".map-info-marker").css("background-color", "transparent");
                $(this).css("background-color", "#EEEEEE");
                selectedMarker = $(this).data("id");
                if ($(this).data('active') === 1) {
                  selectedMarkerIcon = activeIcon;
                } else {
                  selectedMarkerIcon = inactiveIcon;
                }

              } else {
                $(".map-info-marker").css("background-color", "transparent");
                selectedMarker = 0;
                existingActive = false;
              }

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
              $("#map-info-add").css("background-color", "transparent");
              map.setOptions({draggableCursor: 'grab'});
              $(".map-info-marker").css("cursor", "default");
            } else {
              $("#map-info-add").css("background-color", "#EEEEEE");
              addActive = true;
              map.setOptions({draggableCursor: 'pointer'});
              $(".map-info-marker").css("cursor", "pointer");
            }
          }
        }
        ;

        function togglePan(toggle) {
          if (toggle === false) {
            panActive = false;
            $("#map-info-pan").css("background-color", "transparent");
            $(".map-info-marker").css("background-color", "transparent");
            map.setOptions({draggableCursor: 'grab'});
            selectedMarker = 0;
          } else {
            panActive = true;
            $("#map-info-pan").css("background-color", "#EEEEEE");
            map.setOptions({draggableCursor: 'grab'});
          }
        }
        ;

        function toggleShape(toggle) {
          if(reply.controls.shapes === true){
            if (toggle === false) {
              $("#map-info-shape").css("background-color", "transparent");
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
              $("#map-info-shape").css("background-color", "#EEEEEE");
            }
          }
        };

        function toggleRuler(toggle) {
          if(reply.controls.ruler === true){
            if (toggle === false) {
              $("#map-ruler").hide();
              $("#map-ruler").html("");
              $("#map-info-ruler").css("background-color", "transparent");
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
              $("#map-info-ruler").css("background-color", "#EEEEEE");
              $("#map-ruler").show();
            }
          }
        }

        function addLocationToMapInfo(location, marker){
          marker.id = location.location_id;
          $("#map-info").append(
            "<div id='marker-" + location.location_id
              + "' data-id='" + location.location_id
              + "' data-active='" + location.location_active
              + "' class='row  map-info-row "
              + "'><div class='map-info-icon col-md-2'><span class='map-info-icon-image'><img src='" + activeIcon
              + "' /></span></div><div class='map-info-name col-md-9'>" + location.location_name
              + "</div></div>");
        }

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

  map_manager.edit = function(location, controller, action) {
    datacx.post(controller + "/" + action, location).then(function(reply) {//call location/edit
      if (reply === null || reply.result === 0) {//has an error
        toastr.error(reply.message);
      } else {//success
        toastr.success(reply.message);
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
