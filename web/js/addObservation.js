$(document).ready(() => {

    var getCurrentDate = () => {
        let year = $('#observation_observationDate_year');
        let month = $('#observation_observationDate_month');
        let day = $('#observation_observationDate_day');

        year.val(new Date().getFullYear());
        month.val(new Date().getMonth() + 1);
        day.val(new Date().getDate());
    };

    var getLatitudeLongitude = (latlng) => {
        let array;
        let lat;
        let lng;
        array = latlng.split(',');
        lat = array[0];
        lng = array[1];
        lng = lng.slice(0, -1);
        $('#observation_latitude').val(lat.substring(7, 15));
        $('#observation_longitude').val(lng.substring(1, 10));
    };

    var getGeodata = (result, e) => {
        let country = result.properties.country;
        let locality = result.properties.locality;
        let name = result.properties.name;
        let state = result.properties.region;
        let observationState = $('#observation_state option');

        if (typeof(e.latlng) === 'undefined') {
            e.latlng = e.center;
        }

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
            $("#observation_state > option").each(() => {
                if (this.text === 'Łódzkie' && state === 'Lódzkie') {
                    $("#observation_state option[value=" + this.value + "]").prop('selected', true);
                } else if (this.text === state) {
                    $("#observation_state option[value=" + this.value + "]").prop('selected', true);
                }
            });
        }
    };

    getCurrentDate();

    var mymap = L.map('mapId').setView([50.15, 19.00], 13),
        geocoder = L.Control.Geocoder.mapzen('search-DopSHJw'),
        control = L.Control.geocoder({
            geocoder: geocoder,
            defaultMarkGeocode: false,
            placeholder: 'Wyszukaj...'
        }).addTo(mymap);

    var marker;

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);

    control.on('markgeocode', (e) => {

        if (typeof(marker) === 'undefined') {
            marker = new L.marker(e.geocode.center);
            marker.addTo(mymap);
        }
        else {
            marker.setLatLng(e.geocode.center);
        }

        mymap.panTo(new L.LatLng(e.geocode.center.lat, e.geocode.center.lng));
        getGeodata(e.geocode, e.geocode);
    });

    mymap.on('click', (e) => {
        if (typeof(marker) === 'undefined') {
            marker = new L.Marker(e.latlng);
            marker.addTo(mymap);
        }
        else {
            marker.setLatLng(e.latlng);
        }
        geocoder.reverse(e.latlng, mymap.options.crs.scale(mymap.getZoom()), (results) => {
            let r = results[0];
            getGeodata(r, e);
        })
    });

});