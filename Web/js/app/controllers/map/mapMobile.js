function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}
$(document).ready(function() {
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
  if ($("#map").length) {
    setTimeout(function() {
      var options = {
        div: '#map',
        lat: 0,
        lng: 0,
        zoom: 15,
        disableDefaultUI: true,
        mapTypeControl: true,
        tilt: 0
      };
      var mapTypeId = readCookie('mapTypeId');
      console.log(mapTypeId);
      if(mapTypeId !== null){
        options.mapTypeId = readCookie('mapTypeId');
      }
      map = new GMaps(options);

      load(params);
    }, 1000);
  }
  $("#document-upload input[name=\"itemCategory\"]").val('location_id');
});
