<style>
    .map-lat-lng {
        width:100%;
    }

    .map-lat-lng #map-content {
        width:100%;
        height:300px;
    }
</style>

<script>
    try {
        var unidadeProdutiva = JSON.parse('{!!json_encode($unidadeProdutiva)!!}');

        if (unidadeProdutiva){
            var map = L.map('map-content').setView([unidadeProdutiva.lat, unidadeProdutiva.lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([unidadeProdutiva.lat, unidadeProdutiva.lng], { title: unidadeProdutiva.nome, draggable:false }).addTo(map);
        }
    } catch(e) {

    }
</script>

