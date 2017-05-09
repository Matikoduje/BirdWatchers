$(document).ready(function () {
    function getLatitudeLongitude(latlng) {
        var array;
        var lat;
        var lng;
        array = latlng.split(',');
        lat = array[0];
        lng = array[1];
        $('#observation_latitude').val(lat.substring(7, 14));
        $('#observation_longitude').val(lng.substring(1, 8));
    };

    var mymap = L.map('mapId').setView([50.15, 19.00], 13);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);

    mymap.on('click', function (e) {
        getLatitudeLongitude(e.latlng.toString());
    });

    $('#observation_save').click(function (e) {
        var $form = $('form');
        var values = {};
        $.each($form.serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });
        var $requestPost;

        var dataFromForm = new FormData();
        dataFromForm.append("observation[location]", document.getElementById("observation_location").value);
        dataFromForm.append("observation[species]", document.getElementById("observation_species").value);
        dataFromForm.append("observation[state]", document.getElementById("observation_state").value);
        dataFromForm.append("observation[latitude]", document.getElementById("observation_latitude").value);
        dataFromForm.append("observation[longitude]", document.getElementById("observation_longitude").value);
        dataFromForm.append("observation[description]", document.getElementById("observation_description").value);
        dataFromForm.append("image", $('input[type=file]')[0].files[0]);

        $requestPost = $.ajax({
            type: 'POST',
            url: '/api/observation',
            data: dataFromForm,
            contentType:false,
            processData:false
        });
        $requestPost.done(function (response) {
            console.log(response.message);
        });

    });


});
