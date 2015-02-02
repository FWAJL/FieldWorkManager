var map;
function load(params) {
 datacx.post(
         params.dataUrl,
         {"objectType": params.objectType,
          "objectLatPropName": params.objectLatPropName,
          "objectLngPropName": params.objectLngPropName}
 ).then(function(reply) {//call AJAX method to call Project/Add WebService
  if (reply === null || reply.result === 0) {//has an error
   toastr.error(reply.message);
  } else {//success
   map.setCenter(
             reply.defaultPosition.lat,
             reply.defaultPosition.lng);
   map.addMarkers(reply.items);

   setTimeout(function() {
    if (reply.items.length > 0) {
     map.fitZoom(reply.items);
    }
   }, 500);
  }
 });
}
$(document).ready(function() {
 var params = {
  "dataUrl": "map/listAll",
  "objectType": "facility_obj",
  "objectLatPropName": "facility_lat",
  "objectLngPropName": "facility_long"
 };
 if ($("#map").length) {
  setTimeout(function() {
   map = new GMaps({
    div: '#map',
    lat: 0,
    lng: 0,
    zoom: 4
   });
   load(params);
  }, 1000);
 }
});