$(document).ready(function() {
    var params = {
        "dataUrl": "map/listAll",
        "properties": {
            "facility_obj": {
                "objectLatPropName": "facility_lat",
                "objectLngPropName": "facility_long"
            },
            "project_obj": {
                "objectActivePropName": "project_active"
            }
        }
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