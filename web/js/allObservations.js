$(document).ready(function () {

    function getDataColorMap() {
        $.getJSON(assetsBaseDir + "voivodeship.geojson", function (hoodData) {
            $.each(hoodData.features, function (index, value) {
                value['count'] = 0;
                $.each(countsObservations, function (index2, value2) {
                    if (value.properties.cartodb_id === value2.id) {
                        value['count'] = value2.count;
                    }
                });
            });
            geoJsonLayer = L.geoJson(hoodData, {
                style: function (feature) {
                    var fillColor,
                        density = feature.count;
                    if (density > 4) fillColor = "#006837";
                    else if (density > 3) fillColor = "#31a354";
                    else if (density > 2) fillColor = "#78c679";
                    else if (density > 1) fillColor = "#c2e699";
                    else if (density > 0) fillColor = "#ffffcc";
                    else fillColor = "#999";  // no data
                    return {color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6};
                }
            }).addTo(mymap);
        });
    }
    var mapBoxMap = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    });

    var cartoDBMap = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
        subdomains: 'abcd',
        maxZoom: 19
    });

    var mymap = L.map('mapId', {
        layers: [mapBoxMap]
    })
        .setView([50.15, 19.00], 13);

    // do tej warstwy będą dodawane markery
    var observedMarkers = new L.LayerGroup();

    var markerCluster = new L.markerClusterGroup();

    // zmienna do której będą zapisywane warstwy dodawane z geoJson
    var geoJsonLayer;

    // domyślne ustawienie wyświetlania punktów przy załadowaniu mapy
    markerCluster.addTo(mymap);

    var baseLayers = {
        "Mapa obserwacji": mapBoxMap,
        "Mapa deseniowa": cartoDBMap
    };

    var overlays = {
        "Obserwacje": markerCluster
    };

    L.control.layers(baseLayers, overlays).addTo(mymap);

    var countsObservations;

    var buttonMap1 = $('.leaflet-control-layers-base label:nth-child(1) input:radio');
    var buttonMap2 = $('.leaflet-control-layers-base label:nth-child(2) input:radio');

    buttonMap1.on('change', function () {
        if ($(this).is(':checked')) {
            geoJsonLayer.clearLayers();
        }
    });

    buttonMap2.on('change', function () {
        if ($(this).is(':checked')) {
            getDataColorMap();
        };
    });

    var requestObservation;

    requestObservation = $.ajax({
        url: "/api/observation",
        type: "get",
        dataType: "json"
    });

    requestObservation.done(function (response) {
        $.each(response.observations, function (index, value) {
            var marker = L.marker([value.latitude, value.longitude]);
            marker.bindPopup('Gatunek: ' + value.species + '<br>Data obserwacji: ' + value.dateO);
            marker.on('mouseover', function (e) {
                this.openPopup();
            });
            marker.on('mouseout', function (e) {
                this.closePopup();
            });
            marker.on('click', function (e) {
                window.location.href = "/observation/" + value.id;
            });
            marker.addTo(observedMarkers);
        });

        markerCluster.addLayer(observedMarkers);

        countsObservations = response.counts;
    });

    $('#userSearch').on('click', function (e) {
        if ($(this).prop('checked', true)) {
            $('#userVisible').removeClass('invisible');
        }
    });

    $('#allUsers').on('click', function (e) {
        if ($(this).prop('checked', true)) {
            $('#userVisible').addClass('invisible');
        }
    });

    $('#btnSearch').on('click', function () {
        var requestSearch;
        var speciesId = $('#sel1').val();
        var timeAmount = $('#sel2').val();
        var loginUser;

        if ($('#userSearch').is(':checked')) {
            loginUser = $('#userLogin').val();
        } else {
            loginUser = 'all';
        }

        requestSearch = $.ajax({
            url: "/api/searchUser",
            type: "get",
            data: {login: loginUser, species: speciesId, time: timeAmount},
            dataType: "json"
        });

        requestSearch.done(function (response) {
            if (response.message === 'badUser') {
                if ($('#infoUser').hasClass('invisible')) {
                    $('#infoUser').removeClass('invisible');
                }
            } else {
                if (!$('#infoUser').hasClass('invisible')) {
                    $('#infoUser').addClass('invisible');
                }
                // czyścimy warstwę z uprzednio załadowanych markerów zanim dodamy następne
                markerCluster.clearLayers();
                observedMarkers.clearLayers();

                $.each(response.observations, function (index, value) {
                    var marker = L.marker([value.latitude, value.longitude]);
                    marker.bindPopup('Gatunek: ' + value.species + '<br>Data obserwacji: ' + value.dateO);
                    marker.on('mouseover', function (e) {
                        this.openPopup();
                    });
                    marker.on('mouseout', function (e) {
                        this.closePopup();
                    });
                    marker.on('click', function (e) {
                        window.location.href = "/observation/" + value.id;
                    });
                    marker.addTo(observedMarkers);
                });
                markerCluster.addLayer(observedMarkers);
                countsObservations = response.counts;
                if (buttonMap2.is(':checked')) {
                    geoJsonLayer.clearLayers();
                    getDataColorMap();
                }
            }
        });
    });
});
