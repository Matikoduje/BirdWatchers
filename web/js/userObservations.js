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

    var birdIcon = L.icon({
        iconUrl: assetsImgDir + 'bird_marker.png',
        iconSize: [50,40]
    });

    $requestGet = $.ajax({
        url: "/api/myObservations",
        type: "get",
        dataType: "json"
    });

    $requestGet.done(function (response) {
        var observationList = $('#userObservations');

        $.each(response.observations, function (index, value) {
            var marker = L.marker([value.latitude, value.longitude], {icon: birdIcon}).addTo(mymap);
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

            var li = $('<li class="list-group-item list-group-item-success" data-lat=' + value.latitude + ' data-lon=' + value.longitude + ' data-id=' + value.id + '>' + value.species + ', ' + value.location + ', ' + value.dateO + '</li>');
            observationList.append(li);
        });

    });

    $('body').on('click', '.list-group-item', function () {

        var contentLi = '<p id="contentP"><button type="button" class="btn btn-xs btn-info" id="editBtn">Edytuj</button><button class="btn btn-xs btn-danger" id="deleteBtn" type="button">Usuń</button> </p>';

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('#contentP').remove();
        } else {
            $('.list-group-item').removeClass('active');
            $('#contentP').remove();
            $(this).addClass('active');
            mymap.panTo(new L.LatLng($(this).data('lat'), $(this).data('lon')));
        }
        if ($(this).hasClass('active')) {
            $(this).append(contentLi);
        }

    });

    $('body').on('click', '#deleteBtn', function () {

        var $requestDel;
        var id = $(this).parent().parent().data("id");

        $requestDel = $.ajax({
            url: "/api/observation/" + id,
            type: "delete",
            dataType: "json"
        });

        $requestDel.done(function () {
            window.location.href = "/userObservations";
        });
    });

    $('body').on('click', '#editBtn', function () {
        var id = $(this).parent().parent().data("id");
        window.location.href = "/observation/edit/" + id;
    });
});
