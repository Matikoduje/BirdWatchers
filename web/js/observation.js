$(document).ready(() => {
    let getPath = () => {
        let path = window.location.pathname.split('/');
        path = path[2];
        return path;
    };

    const birdIcon = L.icon({
        iconUrl: assetsImgDir + 'bird_marker.png',
        iconSize: [50,40]
    });

    $.get(`/api/observation/${getPath()}`, () => {

    }).done((response) => {
        let mymap = L.map('mapId').setView([response.latitude, response.longitude], 13);

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(mymap);

        let marker = L.marker([response.latitude, response.longitude], {icon: birdIcon}).addTo(mymap);
        $('#observationUsername').html('<a href=/showUser/' + response.userName + '>' + response.userName + '</a>');
        $('#observationCreateDate').html(response.dateCreate);
        $('#observationDate').html(response.dateO);
        $('#observationSpecies').html('<a href=/species/' + response.speciesId + '>' + response.species + '</a>');
        $('#observationLocation').html(response.location);
        $('#observationState').html(response.state);
        $('#observationDescription').html(response.description);
        let imagesId = $('#imagesId');
        let count;
        count = response.images.length;
        for (let i = 0; i < count; i++) {
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
