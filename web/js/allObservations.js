$(document).ready(function () {

    var getDataColorMap = () => {

        $.getJSON(assetsBaseDir + "voivodeship.geojson", (hoodData) => {

            var allCounts = 0;

            var colors = ["#ffffcc", "#c2e699", "#78c679", "#31a354", "#006837"];

            $.each(hoodData.features, (index, value) => {
                value['count'] = 0;
                $.each(countsObservations, (index2, value2) => {
                    if (value.properties.cartodb_id === value2.id) {
                        value['count'] = value2.count;
                        allCounts += parseInt(value2.count);
                    }
                });
            });

            geoJsonLayer = L.geoJson(hoodData, {
                style: (feature) => {
                    var fillColor;
                    var density = feature.count;
                    if (density > (0.51 * allCounts)) fillColor = "#006837";
                    else if (density > (0.25 * allCounts)) fillColor = "#31a354";
                    else if (density > (0.1 * allCounts)) fillColor = "#78c679";
                    else if (density > (0.05 * allCounts)) fillColor = "#c2e699";
                    else if (density > 0) fillColor = "#ffffcc";
                    else fillColor = "#999";  // no data
                    return {color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6};
                },
                onEachFeature: (feature, layer) => {
                    layer.on({
                        mouseover: (e) => {
                            var stateLayer = e.target;

                            stateLayer.setStyle({
                                weight: 2,
                                color: '#a30028',
                                dashArray: '',
                                fillOpacity: 0.7
                            });

                            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                                stateLayer.bringToFront();
                            }
                            info.update(feature);
                        },
                        mouseout: (e) => {
                            geoJsonLayer.resetStyle(e.target);
                            info.update(0);
                        },
                        click: (e) => {
                            mymap.fitBounds(e.target.getBounds());
                        }
                    });
                }
            }).addTo(mymap);

            legend.onAdd = () => {
                var div = L.DomUtil.create('div', 'information legend'),
                    grades = [0, parseInt(0.05 * allCounts), parseInt(0.1 * allCounts), parseInt(0.25 * allCounts), parseInt(0.51 * allCounts)],
                    labels = [];

                for (let i = 0; i < grades.length; i++) {
                    // div.innerHTML += '<i style="background:' + colors[i] + '"></i> ';
                    if (grades[i] !== grades[i + 1]) {
                        div.innerHTML += '<i style="background:' + colors[i] + '"></i> ' + parseInt(grades[i] + 1) + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
                    }

                }
                return div;
            };

            legend.addTo(mymap);

            info.onAdd = () => {
                var div = L.DomUtil.create('div', 'information info-obs');
                div.innerHTML = '<h4>Ilość Obserwacji:</h4>';
                return div;
            };

            info.update = (feature) => {
                if (feature === 0) {
                    $(".info-obs")[0].innerHTML = '<h4>Ilość Obserwacji:</h4>';
                } else {
                    $(".info-obs")[0].innerHTML = '<h4>Ilość Obserwacji:' + feature.count + '</h4>';
                }
            };

            info.addTo(mymap);
        });
    };

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

    // warstwa odpowiedzialna za wizualną prezentację markerów
    var markerCluster = new L.markerClusterGroup();

    // zmienna do której będą zapisywane warstwy dodawane z geoJson
    var geoJsonLayer;

    // domyślne ustawienie wyświetlania punktów przy załadowaniu mapy
    markerCluster.addTo(mymap);

    // warstwa legendy
    var legend = L.control({position: 'bottomleft'});

    // warstwa informacyjna
    var info = L.control({position: 'bottomright'});

    var baseLayers = {
        "Mapa obserwacji": mapBoxMap,
        "Mapa deseniowa": cartoDBMap
    };

    var overlays = {
        "Obserwacje": markerCluster
    };

    L.control.layers(baseLayers, overlays).addTo(mymap);

    const birdIcon = L.icon({
        iconUrl: assetsImgDir + 'bird_marker.png',
        iconSize: [50, 40]
    });

    var countsObservations;

    var buttonMap1 = $('.leaflet-control-layers-base label:nth-child(1) input:radio');
    var buttonMap2 = $('.leaflet-control-layers-base label:nth-child(2) input:radio');

    buttonMap1.on('change', function () {
        if ($(this).is(':checked')) {
            mymap.removeControl(legend);
            mymap.removeControl(info);
            geoJsonLayer.clearLayers();
        }
    });

    buttonMap2.on('change', function () {
        if ($(this).is(':checked')) {
            getDataColorMap();
        }
        ;
    });

    var requestObservation;

    requestObservation = $.ajax({
        url: "/api/observation",
        type: "get",
        dataType: "json"
    });

    requestObservation.done((response) => {
        $.each(response.observations, function (index, value) {
            var marker = L.marker([value.latitude, value.longitude], {icon: birdIcon});
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

        requestSearch.done((response) => {
            if (response.message === 'badUser') {
                if ($('#infoUser').hasClass('invisible')) {
                    $('#infoUser').removeClass('invisible');
                }
            } else {
                if (!$('#infoUser').hasClass('invisible')) {
                    $('#infoUser').addClass('invisible');
                }
                // czyścimy warstwę z uprzednio załadowanych markerów oraz usuwamy legendę zanim dodamy następne
                markerCluster.clearLayers();
                observedMarkers.clearLayers();

                $.each(response.observations, (index, value) => {
                    var marker = L.marker([value.latitude, value.longitude], {icon: birdIcon});
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