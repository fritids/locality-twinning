var map;
var geocoder;
var marker;
var infoWindow;

//custom map style
var roadAtlasStyles = [
    {
    "stylers": [
    { "visibility": "off" }
    ]
    },{
    "featureType": "landscape.natural",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    "featureType": "water",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 40 }
    ]
    },{
    "featureType": "poi.park",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 85 }
    ]
    },{
    "featureType": "road.highway.controlled_access",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -20 },
    { "weight": 4 }
    ]
    },{
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -80 }
    ]
    },{
    "featureType": "road.arterial",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "weight": 1.5 },
    { "lightness": -20 }
    ]
    },{
    "featureType": "road.local",
    "elementType": "geometry.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "weight": 0.5 },
    { "lightness": 10 }
    ]
    },{
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -80 }
    ]
    },{
    "featureType": "road.highway",
    "elementType": "labels.text.stroke",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    "featureType": "road.arterial",
    "elementType": "labels.text.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -65 }
    ]
    },{
    "featureType": "road.arterial",
    "elementType": "labels.text.stroke",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    "featureType": "road.local",
    "elementType": "labels.text.fill",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": -55 }
    ]
    },{
    "featureType": "road.local",
    "elementType": "labels.text.stroke",
    "stylers": [
    { "visibility": "on" },
    { "color": "#808080" },
    { "lightness": 100 }
    ]
    },{
    }
    ]
//end

function initialize() {

    if(!document.getElementById("grid-lat") && !document.getElementById("grid-lng")){
        return false;
    }

    var dft_grid_lat = 43.653524;
    var dft_grid_lng = -79.3839069;
    var zoom = parseInt(document.getElementById("hdn-grid-zoom").value);
    //if(zoom < 2){ zoom = 2 }

    if(document.getElementById("grid-lat").value!=''){
        var dft_grid_lat = document.getElementById("grid-lat").value;
    }
    if(document.getElementById("grid-lng").value!=''){
        var dft_grid_lng = document.getElementById("grid-lng").value;
    }
    var mapDiv = document.getElementById('map-canvas');

    var myOptions = {
        zoom: 15,
        center: new google.maps.LatLng(dft_grid_lat, dft_grid_lng),
        panControl: false,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_TOP,
            padding:'50px'
        },
        scaleControl: false,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false
    };

    map = new google.maps.Map(mapDiv, myOptions);

    var styledMapOptions = { name: 'Road Atlas' };
    var roadMapType = new google.maps.StyledMapType(roadAtlasStyles, styledMapOptions);
    map.mapTypes.set('roadatlas', roadMapType);
    map.setMapTypeId('roadatlas');

    geocoder = new google.maps.Geocoder();
    marker_work(dft_grid_lat, dft_grid_lng);
    document.getElementById("grid-lat").value = dft_grid_lat;
    document.getElementById("grid-lng").value = dft_grid_lng;

    google.maps.event.addListener(map, 'zoom_changed', function() {
        document.getElementById("hdn-grid-zoom").value = map.getZoom();
    });
}//end initialze

function marker_work(lat,lng){
    marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(lat, lng),
        draggable: true
    });

    infoWindow = new google.maps.InfoWindow;
    google.maps.event.addListener(map, 'click', function() {infoWindow.close();});
    google.maps.event.addListener(marker, 'click', function() {
        var latLng = marker.getPosition();
        infoWindow.setContent('<h3>Marker position is:</h3>' + address_in());
        infoWindow.open(map, marker);
    });
    google.maps.event.addListener(marker, 'dragend', function(){
        var latLng = marker.getPosition();
        infoWindow.setContent(address_in());
        document.getElementById("grid-lat").value = latLng.lat();
        document.getElementById("grid-lng").value = latLng.lng();
        infoWindow.open(map, marker);
    });
    infoWindow.open(map, marker);
}

function backendGeocode() {
    marker.setMap(null);
    var address = address_in();
    geocoder.geocode({'address': address,'partialmatch': true}, backendGeocodeResult);
}
function address_in() {
    var address = $("#grid-address").val();
    var ax = address;
    return ax;
}

function backendGeocodeResult(results, status) {
    if (status == 'OK' && results.length > 0) {
        map.fitBounds(results[0].geometry.viewport);
        var x = results[0].geometry.location;
        document.getElementById("grid-lat").value = x.lat();
        document.getElementById("grid-lng").value = x.lng();
        marker_work(x.lat(), x.lng());
    } else {
    alert("Geocode was not successful for the following reason: " + status);
    }
}

google.maps.event.addDomListener(window, 'load', initialize);

$(document).ready(function(){
    $("#grid-address").change(function(){
        backendGeocode();
    });

    $("#view-in-map").click(function(){
        backendGeocode();
    });
});

$(document).ready(function(){
    var d = new Date();
    $('#grid_expire_date').datetimepicker({
        minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate())
        //maxDate: new Date(d.getFullYear(), d.getMonth(), d.getDate()+8)
    });

    $('#post-body #titlewrap input#title').keyup(function () {
        var maxLength = 30;
        var text = $(this).val();
        var textLength = text.length;
        if (textLength > maxLength) {
            $(this).val(text.substring(0, (maxLength)));
            alert("Sorry, maximum " + maxLength + " characters are allowed");
        }
    });
});//end ready