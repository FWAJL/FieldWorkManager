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
      map = new GMaps({
        div: '#map',
        lat: 0,
        lng: 0,
        zoom: 15,
        tilt: 0
      });
      load(params);
    }, 1000);
  }
  $("#document-upload input[name=\"itemCategory\"]").val('location_id');
});
