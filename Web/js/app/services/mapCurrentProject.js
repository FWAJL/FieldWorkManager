var map;
function load(params) {
    datacx.post(
        params.dataUrl,
        {"properties": JSON.stringify(params.properties)}
    ).then(function(reply) {//call AJAX method to call Project/Add WebService
            if (reply === null || reply.result === 0) {//has an error
                toastr.error(reply.message);
            } else {//success
                map.setCenter(
                    reply.defaultPosition.lat,
                    reply.defaultPosition.lng);
                map.addMarkers(reply.items);

                setTimeout(function() {
                    if (reply.items.length > 1) {
                        map.fitZoom(reply.items);
                    } else if(reply.items.length == 1){
                        map.setCenter(reply.items[0].lat, reply.items[0].lng);
                    }
                }, 500);
            }
        });
}
$(document).ready(function() {
    var params = {
        "dataUrl": "map/listCurrentProject",
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
                zoom: 14
            });
            load(params);
        }, 1000);
    }
});
