<script src="/js/leaflet-src.js"></script>
<script>
    var map = L.map('map').setView([42.548718, -83.161152], 15);
    var marker = L.marker([42.548718, -83.161152]).addTo(map);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenSt14CAABB98FreetMap</a> contributors'
    }).addTo(map);
</script>