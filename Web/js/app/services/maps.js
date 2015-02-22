var map;
var boundaryShape;
var rulerShape;
var rulerActive = shapeActive = addActive = existingActive = panActive = false;
var selectedMarker = 0;
var markers = new Array();
var drawingManager = drawingManagerRuler = "";
var activeIcon = inactiveIcon = selectedMarkerIcon = "default";

function load(params) {
    datacx.post(
        params.dataUrl,
        {"properties": utils.stringifyJson(params.properties)}
    ).then(function(reply) {//call AJAX method to call Project/Add WebService
            if (reply === null || reply.result === 0) {//has an error
                toastr.error(reply.message);
            } else {//success
                map.setCenter(
                    reply.defaultPosition.lat,
                    reply.defaultPosition.lng);
                var markerIcon = "";
                var markerClass = "";
                if(typeof reply.activeIcon != 'undefined' && typeof reply.inactiveIcon != 'undefined'){
                    activeIcon = reply.activeIcon;
                    inactiveIcon = reply.inactiveIcon;
                }
                $.each(reply.items, function(index, item){
                    markerIcon = "";
                    markerClass = "";
                    if(item.noLatLng===true){
                        markerIcon = reply.noLatLngIcon;
                        markerClass="map-info-marker";
                    } else {
                        markers.push(item.marker);
                        markerIcon = item.marker.icon;
                    }
                    $("#map-info").append("<div id='marker-"+item.id+"' data-id='"+item.id+"' data-active='"+item.active+"' class='row  map-info-row "+markerClass+"'><div class='map-info-icon col-md-2'><span class='map-info-icon-image'><img src='"+ markerIcon +"' /></span></div><div class='map-info-name col-md-9'>"+ item.name +"</div></div>");
                });
                if(markers.length==1){
                    var mrk = map.addMarker(markers[0]);
                } else {
                    markers = map.addMarkers(markers);
                }


                setTimeout(function() {
                    if (markers.length > 1) {
                        map.fitZoom(markers);
                    } else if (markers.length == 1)
                    {
                        map.setCenter(markers[0].lat, markers[0].lng);
                    }
                }, 500);
                // called on existing/add controls
                GMaps.on('click',map.map, function(e){
                    if (addActive===true && selectedMarker!=0) {
                        newMarker = map.addMarker({
                            draggable:true,
                            lat: e.latLng.lat(),
                            lng: e.latLng.lng()
                        });
                        if(selectedMarkerIcon!=""){
                            newMarker.setIcon(selectedMarkerIcon);
                        }

                        $("#marker-"+selectedMarker).find(".map-info-icon img").attr("src",selectedMarkerIcon);
                        $("#marker-"+selectedMarker).removeClass('map-info-marker');
                        $("#marker-"+selectedMarker).css("background-color","transparent");
                        $("#marker-"+selectedMarker).css("cursor","default");;
                        $("#map-info-add").trigger('click');

                    } else if(addActive===true){

                        //var infoPrompt = new google.maps.InfoWindow({content: "<form class='form-horizontal'><div class='form-group'><div class='col-sm-10'><input class='form-control' type='text' id='new-marker-name' /></div></div><div class='form-group'><div class='col-sm-12'><textarea class='form-control' rows='3'></textarea></div></div><div class='form-group'><div class='col-sm-5'><button type='button' class='btn btn-primary'>Save</button></div><div class='col-sm-5'><button type='button' class='btn btn-default'>Cancel</button></div></div></form>"});
                        newMarker = map.addMarker({
                            draggable:true,
                            lat: e.latLng.lat(),
                            lng: e.latLng.lng()
                        });
                        if(activeIcon!=""){
                            newMarker.setIcon(activeIcon);
                        }

                    }

                });
                /*
                * computeMeasurements function called on shape creation and updates
                */

                function computeMeasurements(overlay, type){
                    infoContainer = $("#map-ruler");
                    if(type == google.maps.drawing.OverlayType.POLYLINE){
                        path = overlay.getPath();
                        pathSize = path.getLength();
                        distance = google.maps.geometry.spherical.computeLength(path);
                        infoContainer.html("Distance: "+(distance/1000).toFixed(2)+" km "+(distance*0.62137).toFixed(2)+" miles");
                    } else {
                            path = overlay.getPath();
                            pathSize = path.getLength();
                            infoPosition = path.getAt(pathSize-1);
                            area = google.maps.geometry.spherical.computeArea(path);
                            infoContainer.html("Area: "+(area/1000000).toFixed(2)+" sq kms "+(area/2589988.11).toFixed(2)+" sq miles");
                        }

                };

                $("#map-info-ruler").click(function(){
                    //toggle all other controls and make sure to toggle current control last
                    toggleAdd(false);
                    togglePan(false);
                    toggleShape(false);
                    toggleRuler(!rulerActive);
                });

                $("#map-info-shape").click(function(){
                    //toggle all other controls and make sure to toggle current control last
                    toggleAdd(false);
                    togglePan(false);
                    toggleRuler(false);
                    toggleShape(!shapeActive);
                });

                $("#map-info-pan").click(function(e){
                    //toggle all other controls and make sure to toggle current control last
                    toggleAdd(false);
                    toggleShape(false);
                    toggleRuler(false);
                    togglePan(!panActive);

                });

                $("#map-info-add").click(function(e){
                    //toggle all other controls and make sure to toggle current control last
                    togglePan(false);
                    toggleShape(false);
                    toggleRuler(false);
                    toggleAdd(!addActive);
                });

                $(".map-info-marker").click(function(e){
                    if(addActive===true){
                        existingActive = true;
                        if(selectedMarker!=$(this).data("id")){
                            $(".map-info-marker").css("background-color","transparent");
                            $(this).css("background-color","#EEEEEE");
                            selectedMarker = $(this).data("id");
                            if($(this).data('active')==1){
                                selectedMarkerIcon = activeIcon;
                            } else {
                                selectedMarkerIcon = inactiveIcon;
                            }

                        } else {
                            $(".map-info-marker").css("background-color","transparent");
                            selectedMarker = 0;
                            existingActive = false;
                        }

                    }
                });

                /*
                 * Toggles for map controls
                 */

                function toggleAdd(toggle){
                    if(toggle===false){
                        addActive = false;
                        $("#map-info-add").css("background-color","transparent");
                        map.setOptions({draggableCursor:'grab'});
                        $(".map-info-marker").css("cursor","default");
                    } else {
                        $("#map-info-add").css("background-color","#EEEEEE");
                        addActive=true;
                        map.setOptions({draggableCursor:'pointer'});
                        $(".map-info-marker").css("cursor","pointer");
                    }
                };

                function togglePan(toggle){
                    if(toggle===false){
                        panActive=false;
                        $("#map-info-pan").css("background-color","transparent");
                        $(".map-info-marker").css("background-color","transparent");
                        map.setOptions({draggableCursor:'grab'});
                        selectedMarker=0;
                    } else {
                        panActive=true;
                        $("#map-info-pan").css("background-color","#EEEEEE");
                        map.setOptions({draggableCursor:'grab'});
                    }
                };

                function toggleShape(toggle){
                    if(toggle===false){
                        $("#map-info-shape").css("background-color","transparent");
                        if(typeof(drawingManager)==="object"){
                            drawingManager.setDrawingMode(null);
                            map.removeOverlays();
                            drawingManager.setMap(null);
                            if(typeof(boundaryShape)==="object"){
                                boundaryShape.setMap(null);
                            }
                        }
                        shapeActive = false;
                    }
                    else {
                        shapeActive = true;
                        drawingManager = new google.maps.drawing.DrawingManager({
                            drawingControl: true,
                            drawingControlOptions: {
                                position: google.maps.ControlPosition.TOP_CENTER,
                                drawingModes: [
                                    google.maps.drawing.OverlayType.POLYGON
                                ]
                            },
                            polygonOptions: {
                                fillColor: '#FF0000',
                                fillOpacity: .3,
                                strokeWeight: 3,
                                clickable: false,
                                editable: true,
                                zIndex: 1
                            }
                        });
                        drawingManager.setMap(map.map);
                        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
                            if(typeof(boundaryShape)==="object"){
                                boundaryShape.setMap(null);
                            }
                            if (e.type != google.maps.drawing.OverlayType.MARKER) {
                                // Switch back to non-drawing mode after drawing a shape.
                                drawingManager.setDrawingMode(null);
                                // Add an event listener that selects the newly-drawn shape when the user
                                // mouses down on it.
                                var newShape = e.overlay;
                                newShape.type = e.type;
                                boundaryShape = newShape;
                            }
                        });
                        $("#map-info-shape").css("background-color","#EEEEEE");
                    }
                };

                function toggleRuler(toggle){
                    if(toggle===false){
                        $("#map-ruler").hide();
                        $("#map-ruler").html("");
                        $("#map-info-ruler").css("background-color","transparent");
                        if(typeof(drawingManagerRuler)==="object"){
                            drawingManagerRuler.setDrawingMode(null);
                            map.removeOverlays();
                            if(typeof(rulerShape)==="object"){
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
                                fillColor: '#FF0000',
                                fillOpacity: .3,
                                strokeWeight: 3,
                                clickable: false,
                                editable: true,
                                zIndex: 1
                            }
                        });
                        drawingManagerRuler.setMap(map.map);
                        google.maps.event.addListener(drawingManagerRuler, 'overlaycomplete', function(e) {
                            if (e.type != google.maps.drawing.OverlayType.MARKER) {
                                drawingManagerRuler.setDrawingMode(null);
                                if(typeof(rulerShape)==='object'){
                                    rulerShape.setMap(null);
                                }
                                var newShape = e.overlay;
                                newShape.type = e.type;
                                rulerShape = newShape;
                                computeMeasurements(newShape, newShape.type);
                                if(newShape.type == google.maps.drawing.OverlayType.POLYLINE){
                                    google.maps.event.addListener(newShape.getPath(),'set_at',function(e){
                                        computeMeasurements(newShape,newShape.type)
                                    });
                                    google.maps.event.addListener(newShape.getPath(),'insert_at',function(e){
                                        computeMeasurements(newShape,newShape.type)
                                    });
                                } else {
                                    google.maps.event.addListener(newShape.getPath(),'insert_at',function(e){
                                        computeMeasurements(newShape,newShape.type)
                                    });
                                    google.maps.event.addListener(newShape.getPath(),'set_at',function(e){
                                        computeMeasurements(newShape,newShape.type)
                                    });
                                }

                            }
                        });
                        rulerActive = true;
                        $("#map-info-ruler").css("background-color","#EEEEEE");
                        $("#map-ruler").show();
                    }
                }
            }
        });
}
