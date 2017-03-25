<script src="/js/leaflet-src.js"></script>
<script>
    var detroitMap = L.map('map', {
        center: [42.548718, -83.161152],
        zoom: 14,
        scrollWheelZoom: false
    });

    L.marker([42.548718, -83.161152]).addTo(detroitMap);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
    }).addTo(detroitMap);

    var chicagoMap = L.map('mapChicago', {
        center: [42.063774, -88.041517],
        zoom: 14,
        scrollWheelZoom: false
    });

    L.marker([42.063774, -88.041517]).addTo(chicagoMap);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
    }).addTo(chicagoMap);

    var minnesotaMap = L.map('mapMinnesota', {
        center: [44.964869, -93.3472029],
        zoom: 14,
        scrollWheelZoom: false
    });

    L.marker([44.964869, -93.3472029]).addTo(minnesotaMap);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
    }).addTo(minnesotaMap);

     var grandrapidsMap = L.map('mapGrandrapids', {
        center: [42.878548, -85.685563],
        zoom: 14,
        scrollWheelZoom: false
    });

    L.marker([42.878548, -85.685563]).addTo(grandrapidsMap);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
    }).addTo(grandrapidsMap);

    $('a.detroit-tab').click(function (e) {
        setTimeout(function() {
            detroitMap.invalidateSize();
        }, 500);
     });

    $('a.chicago-tab').click(function () {
        setTimeout(function() {
            chicagoMap.invalidateSize();
        }, 500);
    });

    $('a.twincities-tab').click(function () {
        setTimeout(function() {
            minnesotaMap.invalidateSize();
        }, 500);
    });

    $('a.grandrapids-tab').click(function () {
        setTimeout(function() {
            grandrapidsMap.invalidateSize();
        }, 500);
    });
</script>
<!--<script src="/js/leaflet-src.js"></script>
<script>
    var map = L.map('map').setView([42.548718, -83.161152], 15);
    var marker= L.marker([42.548718, -83.161152]).addTo(map);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenSt14CAABB98FreetMap</a> contributors'
    }).addTo(map);

	var mapChicago = L.map('mapChicago').setView([42.0427801, -88.0397674], 15);
    var marker= L.marker([42.0427801, -88.0397674]).addTo(mapChicago);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenSt14CAABB98FreetMap</a> contributors'
    }).addTo(mapChicago);    
    $('body').on('shown','#chicago',function(){
    	L.Util.requestAnimFrame(mapChicago.invalidateSize,mapChicago,!1,mapChicago._container);
    })


    var mapMinnesota = L.map('mapMinnesota').setView([44.964869, -93.3472029], 15);
    var marker= L.marker([44.964869, -93.3472029]).addTo(mapMinnesota);
    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenSt14CAABB98FreetMap</a> contributors'
    }).addTo(mapMinnesota);
    $('#twincities').click(function() {
        mapMinnesota.invalidateSize(false);
		console.log('test');
     });

</script>-->
