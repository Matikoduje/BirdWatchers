$(document).ready(() => {

    var getDataColorMap = () => {
        $.getJSON(assetsBaseDir + "voivodeship.geojson", (hoodData) => {
            $.each(hoodData.features, (index, value) => {
                value['count'] = 0;
                $.each(countsObservations, (index2, value2) => {
                    if (value.properties.cartodb_id === value2.id) {
                        value['count'] = value2.count;
                    }
                });
            });
            geoJsonLayer = L.geoJson(hoodData, {
                style: (feature) => {
                    var fillColor,
                        density = feature.count;
                    if (density > 11) fillColor = "#006837";
                    else if (density > 9) fillColor = "#31a354";
                    else if (density > 6) fillColor = "#78c679";
                    else if (density > 3) fillColor = "#c2e699";
                    else if (density > 0) fillColor = "#ffffcc";
                    else fillColor = "#999";
                    return {color: "#999", weight: 1, fillColor: fillColor, fillOpacity: .6};
                }
            }).addTo(mymap);
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
    }).setView([50.15, 19.00], 13);

    // do tej warstwy będą dodawane markery
    var observedMarkers = new L.LayerGroup();
    // warstwa odpowiedzialna za wizualną prezentację markerów
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

    const birdIcon = L.icon({
        iconUrl: assetsImgDir + 'bird_marker.png',
        iconSize: [50,40]
    });

    var countsObservations;

    var buttonMap1 = $('.leaflet-control-layers-base label:nth-child(1) input:radio');
    var buttonMap2 = $('.leaflet-control-layers-base label:nth-child(2) input:radio');

    $.get("/api/observation", () => {
    }).done((response) => {
        $.each(response.observations, (index, value) => {
            var marker = L.marker([value.latitude, value.longitude], {icon: birdIcon});
            marker.bindPopup('Gatunek: ' + value.species + '<br>Data obserwacji: ' + value.dateO);
            marker.on('mouseover', () => {
                this.openPopup();
            });
            marker.on('mouseout', () => {
                this.closePopup();
            });
            marker.on('click', () => {
                window.location.href = "/observation/" + value.id;
            });
            marker.addTo(observedMarkers);
        });
        markerCluster.addLayer(observedMarkers);
        countsObservations = response.counts;
    });

    buttonMap1.on('change', () => {
        if ($(this).is(':checked')) {
            geoJsonLayer.clearLayers();
        }
    });

    buttonMap2.on('change', () => {
        if ($(this).is(':checked')) {
            getDataColorMap();
        };
    });

    $('#userSearch').on('click', () => {
        if ($(this).prop('checked', true)) {
            $('#userVisible').removeClass('invisible');
        }
    });

    $('#allUsers').on('click', () => {
        if ($(this).prop('checked', true)) {
            $('#userVisible').addClass('invisible');
        }
    });

    $('#btnSearch').on('click', () => {
        var requestSearch;
        var speciesId = $('#sel1').val();
        var timeAmount = $('#sel2').val();
        var loginUser;

        if ($('#userSearch').is(':checked')) {
            loginUser = $('#userLogin').val();
        } else {
            loginUser = 'all';
        }

        $.get("/api/searchUser",{
            login: loginUser,
            species: speciesId,
            time: timeAmount
        }, () => {
        }).done((response) => {
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

                $.each(response.observations, (index, value) => {
                    var marker = L.marker([value.latitude, value.longitude], {icon: birdIcon});
                    marker.bindPopup('Gatunek: ' + value.species + '<br>Data obserwacji: ' + value.dateO);
                    marker.on('mouseover', () => {
                        this.openPopup();
                    });
                    marker.on('mouseout', () => {
                        this.closePopup();
                    });
                    marker.on('click', () => {
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
