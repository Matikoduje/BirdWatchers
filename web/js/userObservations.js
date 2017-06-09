$(document).ready(() => {

    var mymap = L.map('mapId').setView([50.15, 19.00], 13);
    var body = $('body');

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);

    const birdIcon = L.icon({
        iconUrl: assetsImgDir + 'bird_marker.png',
        iconSize: [50,40]
    });

    $.get("/api/myObservations", () => {
    }).done((response) => {
        var observationList = $('#userObservations');

        $.each(response.observations, (index, value) => {
            var marker = L.marker([value.latitude, value.longitude], {icon: birdIcon}).addTo(mymap);
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

            var li = $('<li class="list-group-item list-group-item-default" data-lat=' + value.latitude + ' data-lon=' + value.longitude + ' data-id=' + value.id + '>' + value.species + ', ' + value.location + ', ' + value.dateO + '</li>');
            observationList.append(li);
        });
    });

    body.on('click', '.list-group-item', function (e) {

        var contentLi = '<p id="contentP"><br><button type="button" class="btn btn-xs btn-default" id="editBtn">Edytuj</button> <button class="btn btn-xs btn-danger" id="deleteBtn" type="button">Usuń</button> </p>';

        if ($(this).hasClass('list-group-item-success')) {
            $(this).removeClass('list-group-item-success');
            $(this).addClass('list-group-item-default');
            $('#contentP').remove();
        } else {
            $('.list-group-item').removeClass('list-group-item-success');
            $('.list-group-item').addClass('list-group-item-default');
            $('#contentP').remove();
            $(this).addClass('list-group-item-success');
            mymap.panTo(new L.LatLng($(this).data('lat'), $(this).data('lon')));
        }
        if ($(this).hasClass('list-group-item-success')) {
            $(this).append(contentLi);
        }

    });

    body.on('click', '#deleteBtn', function (e) {
        var id = $(this).parent().parent().data("id");

        var $requestDel;
        $requestDel = $.ajax({
            url: "/api/observation/" + id,
            type: "delete",
            dataType: "json"
        });
        $requestDel.done(() => {
            window.location.href = "/userObservations";
        });
    });

    body.on('click', '#editBtn', function (e) {
        var id = $(this).parent().parent().data("id");
        window.location.href = "/observation/edit/" + id;
    });
});
