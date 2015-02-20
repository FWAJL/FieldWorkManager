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
            }
        }
    };
    if ($("#map").length) {
        setTimeout(function() {
            map = new GMaps({
                div: '#map',
                lat: 0,
                lng: 0,
                zoom: 15
            });
            load(params);
        }, 1000);
    }
});
