$(document).ready(function () {
    function getPath() {
        var path = window.location.pathname.split('/');
        path = path[2];
        return path;
    };

    var $requestGet;

    $requestGet = $.ajax({
        url: "/api/observation/" + getPath(),
        type: "get",
        dataType: "json"
    });

    $requestGet.done(function (response) {
        var mymap = L.map('mapId').setView([response.latitude, response.longitude], 13);

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(mymap);

        var marker = L.marker([response.latitude, response.longitude]).addTo(mymap);
        $('#observationUsername').html(response.userName);
        $('#observationCreateDate').html(response.dateCreate);
        $('#observationDate').html(response.dateO);
        $('#observationSpecies').html('<a href=/species/' + response.speciesId + '>' + response.species + '</a>');
        $('#observationLocation').html(response.location);
        $('#observationState').html(response.state);
        $('#observationDescription').html(response.description);
        var imagesId = $('#imagesId');
        var count;
        count = response.images.length;
        for (var i = 0; i < count; i++) {
            if (count === 1) {
                imagesId.append('<img style="max-height: 100%; max-width: 100%" src=/uploads/images' + response.images[i] + ' />');
            } else if (count === 2) {
                imagesId.append('<div class="col-lg-6"><img style="max-height: 100%; max-width: 100%" src=/uploads/images' + response.images[i] + ' /></div>');
            } else {
                imagesId.append('<div class="col-lg-4"><img style="max-height: 100%; max-width: 100%" src=/uploads/images' + response.images[i] + ' /></div>');
            }
        }

    });


});
