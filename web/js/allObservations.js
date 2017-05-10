$(document).ready(function () {

    var mymap = L.map('mapId').setView([50.15, 19.00], 13);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);


    var $requestGet;

    $requestGet = $.ajax({
        url: "/api/observation",
        type: "get",
        dataType: "json"
    });

    $requestGet.done(function (response) {

        $.each(response.observations, function (index, value) {
            var marker = L.marker([value.latitude, value.longitude]).addTo(mymap);
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
        });
    });

});
