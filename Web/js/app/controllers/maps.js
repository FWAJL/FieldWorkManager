var map;
function load() {
  var array = new Array();
  for (var i in objects) {
    array.push({
      "lat": objects[i][0],
      "lng": objects[i][1]
    });
  }

  map.addMarkers(array);

  setTimeout(function () {
    map.fitZoom(array);
  }, 500);
}
$(document).ready(function () {
  if ($("#map").length) {
    setTimeout(function () {
      map = new GMaps({
        div: '#map',
        lat: 0,
        lng: 0
      });
      load();
    }, 1000);
  }
});