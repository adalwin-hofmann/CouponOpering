<script src="/js/leaflet-src.js"></script>
<script>
    var map = L.map('map', {
        center: [geoip_latitude(), geoip_longitude()],
        zoom: 10,
        scrollWheelZoom: false
    });
</script>
<script>
    DistanceLocation = can.Model({
        findAll: 'GET /api/merchant/get-locations-by-distance'
    },{});

    FindLocationControl = can.Control(
    {
        init: function()
        {
            markers = [];
            page = 0;
            $('.search-zipcode').data('latitude', geoip_latitude());
            $('.search-zipcode').data('longitude', geoip_longitude());
            // add an OpenStreetMap tile layer
            L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
            }).addTo(map);
            this.locations = locationsJson;
            this.BindLocations();
            //this.Search();
        },
        //Events
        /*'.pagination a click' : function(element,event)
        {
            page = element.data('page');
            this.Search();
            for(i=0;i<markers.length;i++) {
                map.removeLayer(markers[i]);
            }
        },*/
        'a.marker click' : function(element,event)
        {
            var self = this;
            map.panTo(new L.LatLng(element.data('latitude'), element.data('longitude')));
        },
        '.search-zipcode input keyup': function(element,event)
        {
            if (event.which==13) {
                locationQuery = element.val();
                // Needs to be Updated
                can.ajax({
                    url: '/api/zipcode/get-by-query?q='+encodeURIComponent(locationQuery),
                    dataType: 'json',
                    success: function(data) {
                        newZip = data['data'][0];
                        latitude = newZip['latitude'];
                        longitude = newZip['longitude'];
                        city_name = newZip['city'];
                        region_name = newZip['state'];

                        $('.search-zipcode').data('latitude', latitude);
                        $('.search-zipcode').data('longitude', longitude);
                        page = 0;
                        find_location_control.Search();
                    }
                });
            }
        },
        '.search-zipcode button click': function(element,event)
        {
            locationQuery = $('.search-zipcode input').val();
            // Needs to be Updated
            can.ajax({
                url: '/api/zipcode/get-by-query?q='+encodeURIComponent(locationQuery),
                dataType: 'json',
                success: function(data) {
                    newZip = data['data'][0];
                    latitude = newZip['latitude'];
                    longitude = newZip['longitude'];
                    city_name = newZip['city'];
                    region_name = newZip['state'];

                    $('.search-zipcode').data('latitude', latitude);
                    $('.search-zipcode').data('longitude', longitude);
                    page = 0;
                    find_location_control.Search();
                }
            });
        },
        //Methods
        /*'Search': function()
        {
            var self = this;

            var LocationObject = new Object;
            LocationObject.page = page;
            LocationObject.limit = 5;
            LocationObject.merchant_id = merchant_id;
            LocationObject.latitude = $('.search-zipcode').data('latitude');
            LocationObject.longitude = $('.search-zipcode').data('longitude');
            DistanceLocation.findAll(LocationObject, function (locations) {
                self.BindLocations(locations);
            });
        },*/
        'BindLocations': function()
        {
            var locations = this.locations;
            for(var i=0; i<markers.length; i++)
            {
                map.removeLayer(markers[i]);
            }
            markers = [];
            var min_lat = 360;
            var min_lng = 360;
            var max_lat = 0;
            var max_lng = -360;
            for (var i=0;i<locations.length;i++)
            {
                markers[i] = L.marker([locations[i].latitude, locations[i].longitude],{title:locations[i].id}).addTo(map);
                markers[i].bindPopup('<div id="location'+i+'">' +
                    '<span class="h3 hblock"><a href="'+abs_base+'/coupons/'+ category_slug +'/'+ subcategory_slug +'/' + merchant_slug + '/' + locations[i].id + '">' + locations[i].merchant_name + '</a></span>' +
                    '<address>' + locations[i].address + '<br>' +
                    locations[i].address2 + '<br>' +
                    locations[i].city + ', ' + locations[i].state + ' ' + locations[i].zip + '<br>' +
                    'P: ' + locations[i].phone + '</address>' +
                '</div>');
                map.addLayer(markers[i]);
                min_lat = Number(locations[i].latitude) < min_lat ? Number(locations[i].latitude) : min_lat;
                min_lng = Number(locations[i].longitude) < min_lng ? Number(locations[i].longitude) : min_lng;
                max_lat = Number(locations[i].latitude) > max_lat ? Number(locations[i].latitude) : max_lat;
                max_lng = Number(locations[i].longitude) > max_lng ? Number(locations[i].longitude) : max_lng;
            }
            map.fitBounds([L.latLng((min_lat-0.005), (min_lng-0.005)), L.latLng((max_lat+0.005), (max_lng+0.005))]);
            if(map.getZoom() > 14)
            {
                map.setZoom(14);
            }

            /*$('.location-list').html(can.view('template_locations',
            {
                locations : locations
            }));*/
        }/*,
        'BindSidebarOffers': function(entities)
        {
            var my_entities = [];
            for(var i=0; i < entities.length && i < 3; i++)
            {
                my_entities.push(new UserRecommendation(entities[i]._data));
            }
            var my_list = new UserRecommendation.List(my_entities);
            $('#sidebarOffers').html(can.view('template_sidebar_offer',
            {
                entities: my_list
            }));
        }*/
    });

    find_location_control = new FindLocationControl($('body'));
</script>