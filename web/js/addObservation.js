$(document).ready(function () {
    function getLatitudeLongitude(latlng) {
        console.log(latlng);
        var array;
        var lat;
        var lng;
        array = latlng.split(',');
        lat = array[0];
        lng = array[1];
        lng = lng.slice(0, -1);
        $('#observation_latitude').val(lat.substring(7, 15));
        $('#observation_longitude').val(lng.substring(1, 10));
    };

    function getGeodata(result,e) {
        var country = result.properties.country;
        var locality = result.properties.locality;
        var name = result.properties.name;
        var state = result.properties.region;
        var observationState = $('#observation_state option');

        if (country !== 'Poland') {
            var popup = L.popup().setLatLng(e.latlng).setContent('Proszę o wskazanie miejsca znajdującego się w Polsce').openOn(mymap);
            $('#observation_latitude').val('');
            $('#observation_longitude').val('');
            $('#observation_location').val('');
        } else {
            getLatitudeLongitude(e.latlng.toString());
            if (locality) {
                $('#observation_location').val(locality);
            } else {
                $('#observation_location').val(name);
            }
            $("#observation_state > option").each(function() {
                if (this.text === 'Łódzkie' && state === 'Lódzkie') {
                    $("#observation_state option[value=" + this.value + "]").prop('selected', true);
                } else if (this.text === state) {
                    $("#observation_state option[value=" + this.value + "]").prop('selected', true);
                }
            });
        }
    }

    function getCurrentDate() {
        var year = $('#observation_observationDate_year');
        var month = $('#observation_observationDate_month');
        var day = $('#observation_observationDate_day');

        year.val(new Date().getFullYear());
        month.val(new Date().getMonth() + 1);
        day.val(new Date().getDate());
    }

    var mymap = L.map('mapId').setView([50.15, 19.00], 13),
        geocoder = L.Control.Geocoder.mapzen('search-DopSHJw'),
        control = L.Control.geocoder({
            geocoder: geocoder
        }).addTo(mymap),
        marker;

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);

    mymap.on('click', function (e) {
        geocoder.reverse(e.latlng, mymap.options.crs.scale(mymap.getZoom()), function(results) {
            var r = results[0];
            getGeodata(r,e);
        })
    });

    getCurrentDate();
});