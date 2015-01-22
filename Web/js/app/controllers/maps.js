var map;
function load() {
  var array = new Array();
  datacx.post("map/listAll", {}).then(function(reply) {//call AJAX method to call Project/Add WebService
    if (reply === null || reply.result === 0) {//has an error
      toastr.error(reply.message);
    } else {//success
      map.addMarkers(reply.items);

      setTimeout(function() {
        map.fitZoom(reply.items);
      }, 500);
    }
  });
}
$(document).ready(function() {
  if ($("#map").length) {
    setTimeout(function() {
      map = new GMaps({
        div: '#map',
        lat: 0,
        lng: 0
      });
      load();
    }, 1000);
  }
});