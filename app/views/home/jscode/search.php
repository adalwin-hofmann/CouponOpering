<script>
    var map = L.map('map',
    {
        center: [geoip_latitude(), geoip_longitude()],
        zoom: 10,
        scrollWheelZoom: false
    });

    $('.view-change-search .tab-toggle').click(function (e)
    {
        map.panTo([geoip_latitude(), geoip_longitude()]);
        //if(map.getZoom() > 14)
        setTimeout(function() {
            //map.panTo([geoip_latitude(), geoip_longitude()]);
            if(map.getZoom() < 8)
                map.setZoom(8);

            map.invalidateSize();
        }, 500);
    });

    $('.map-panel .panel-title a').click(function (e)
    {
        
        $('#map').show();
        //map.invalidateSize(false)
        setTimeout(function() {
            map.panTo([geoip_latitude(), geoip_longitude()]);
        }, 500);
    });
</script>
<script>

    SearchControl = can.Control(
    {
        init: function()
        {
            $('.searchbar input.inptSearch').focus();
            //$('.subheader-content').hide();
            //$('.active-filter-div').show();
            //$('.input-keyword').addClass('col-sm-offset-2');
            facet = '<?php echo Input::get("f",""); ?>';
            facetType = '<?php echo Input::get("ft",""); ?>';
            merchants = '';
            offers = '';
            filter_with_coupons = 'active';
            with_radius = true;
            initialLoad = 1;
            markers = [];
            $('.searchbar input').val(query);
            
            $('.sort-dropdown').find('[data-value="'+sorting_type+'"]').parent().addClass('active');
            switch(sorting_type)
            {
                case 'distance':
                    $('#searchHintDistance').hide();
                    $('#searchHintRelevance').show();
                    break;
                case 'relevance':
                    $('#searchHintDistance').show();
                    $('#searchHintRelevance').hide();
                    break;
                case 'popular':
                    $('#searchHintRelevance').show();
                    break;
                case 'az':
                    $('#searchHintRelevance').show();
                    break;
                case 'za':
                    $('#searchHintRelevance').show();
                    break;
            }
            this.SearchBanners();
            
            // add an OpenStreetMap tile layer
            L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
            }).addTo(map);

            var search_view_type_preference = master_control.getCookie('search_view_type_preference');
            if (search_view_type_preference != '')
            {
                $('.tab-toggle.btn-green').addClass('btn-green-border').removeClass('btn-green');
                $('.tab-toggle.'+search_view_type_preference).removeClass('btn-green-border').addClass('btn-green').tab('show');
                setTimeout(function() {
                    map.invalidateSize();
                    //map.panTo([geoip_latitude(), geoip_longitude()]);
                    //map.setZoom(8);
                }, 500);
            }

            this.Search();
        },
        //Events
        '.view-change-search .tab-toggle click': function(element)
        {
            $('.view-change-search .tab-toggle.btn-green').addClass('btn-green-border').removeClass('btn-green');
            element.removeClass('btn-green-border').addClass('btn-green')
            
            var search_view_type_preference = '';
            if (element.hasClass('list-view'))
            {
                search_view_type_preference = 'list-view';
            }
            else if (element.hasClass('grid-view'))
            {
                search_view_type_preference = 'grid-view';
                var container = $('#container');
                container.imagesLoaded( function() {
                    var msnry = new Masonry( document.querySelector('#container'), 
                    {
                        itemSelector: '.item'
                    });
                });
            }
            else if (element.hasClass('map-view'))
            {
                search_view_type_preference = 'map-view';
            }

            var cookies = document.cookie;
            var d = new Date();
            d.setTime(d.getTime()+(365*24*60*60*1000));
            var expires = "expires="+d.toGMTString();
            document.cookie = "search_view_type_preference" + "=" + search_view_type_preference + "; " + expires;

            //map.panTo([geoip_latitude(), geoip_longitude()]);

        },
        '#btnDistanceSort click': function(element)
        {
            var query = $(".searchbar input").val();
            var searchTypeValue = $('.search-type button').data("value");

            var search_sort_preference = 'distance';
            var cookies = document.cookie;
            var d = new Date();
            d.setTime(d.getTime()+(365*24*60*60*1000));
            var expires = "expires="+d.toGMTString();
            document.cookie = "search_sort_preference" + "=" + search_sort_preference + "; " + expires;

            window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue+"&s=distance";
        },
        '#btnRelevanceSort click': function(element)
        {
            var query = $(".searchbar input").val();
            var searchTypeValue = $('.search-type button').data("value");

            var search_sort_preference = 'relevance';
            var cookies = document.cookie;
            var d = new Date();
            d.setTime(d.getTime()+(365*24*60*60*1000));
            var expires = "expires="+d.toGMTString();
            document.cookie = "search_sort_preference" + "=" + search_sort_preference + "; " + expires;

            window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue+"&s=relevance";
        },
        '.btn-favorite-merchant click': function(element)
        {
            if(element.hasClass('disabled'))
                return;
            if(user_type == "Nonmember")
            {
                $('#signInModal .login-message span').html('You must be signed in to add to favorites.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
            var myFav = new UserFavorite({user_id: user_id, type: 'location', object_id: element.data('location_id')});
            myFav.save(function(fav)
            {
                element.effect( "highlight", {color: '#0E6C36'} );
                element.addClass('disabled');
                element.prop('disabled', true);
            });
        },
        '.sorting li a click': function(element)
        {
            var sortText = element.text();
            var query = $(".searchbar input").val();
            var searchTypeValue = $('.search-type button').data("value");

            var search_sort_preference = element.data('value');
            var cookies = document.cookie;
            var d = new Date();
            d.setTime(d.getTime()+(365*24*60*60*1000));
            var expires = "expires="+d.toGMTString();
            document.cookie = "search_sort_preference" + "=" + search_sort_preference + "; " + expires;
            
            window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue+"&s="+element.data('value');

        },
        '.active-filter li a click': function(element)
        {
            $('.active-filter li').removeClass('active');
            element.parent().addClass('active');
            var filterText = element.text();
            filter_with_coupons = element.data('value');
            initialLoad = 1;
            this.Search();
        },
        '.view-more click': function(element)
        {
            $('.view-more').button('loading');
            this.Search();
        },
        '.suggest-merchant click': function(element, event)
        {
            $('.no-merchant-results').show();
            $('.no-merchant-results h2').hide();
        },
        '#btnSuggestion click': function(element)
        {
            if($('#suggest_business').val() == '' || $('#suggest_city').val() == '' || $('#suggest_state').val() == '')
            {
                $('#suggestMessages').hide();
                $('#suggestMessages').html('Please fill out the required fields.');
                $('#suggestMessages').css('color', 'red');
                $('#suggestMessages').fadeIn(500);
                return;
            }
            var SuggestionObject = new Object();
            $('.suggest-form input').each(function(index)
            {
                if($(this).attr('id') != 'suggest_category_id')
                {
                    var aPieces = $(this).attr('id').split('_');
                    SuggestionObject[aPieces[1]] = $(this).val();
                }
            });
            SuggestionObject.category_id = $('#suggest_category_id').val();
            if(user_type == 'User')
            {
                SuggestionObject.user_id = user_id;
                var mySuggestion = new UserSuggestion(SuggestionObject);
            }
            else
            {
                SuggestionObject.nonmember_id = nonmember_id;
                var mySuggestion = new UserSuggestion(SuggestionObject);
            }
            mySuggestion.save();
            $('#suggestMessages').hide();
            $('#suggestMessages').html('Thanks for your suggestion! We\'ll get right on that!');
            $('#suggestMessages').css('color', 'green');
            $('#suggestMessages').fadeIn(500);
            $('.suggest-form input').each(function(index)
            {
                if($(this).attr('id') != 'suggest_category')
                {
                    $(this).val('');
                } 
            });
        },
        '.subcategory-facet click': function(element)
        {
            $('.subcategory-links li.active').removeClass('active');
            element.parent().addClass('active');
            facet = element.data('value');
            facetType = 'subcategory';
            initialLoad = 1;
            this.Search();
        },

        '.city-facet click': function(element)
        {
            $('.city-links li.active').removeClass('active');
            element.parent().addClass('active');
            facet = element.data('value');
            facetType = 'city';
            initialLoad = 1;
            this.Search();
        },

        '.searchbar-bottom input keyup': function( element, event ) 
        {
            if (event.which==13) {
                var query = $(".searchbar-bottom input").val();
                var searchTypeValue = $('.search-type button').data("value");
                window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue;
            }
        },
        '.searchbar-bottom button.search click': function( element, event ) 
        {
            var query = $(".searchbar-bottom input").val();
            var searchTypeValue = $('.search-type button').data("value");
            window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue;
        },
        '.search-type li a click': function(element)
        {
            var searchType = element.text();
            $('.search-type button').html(searchType);
            $('.search-type button').data("value",element.data("value"));
        },
        'a.search-filter click': function(element)
        {
            $('.filter-links li.active').removeClass('active');
            element.parent().addClass('active');
            if (element.data('value') == 'filter_with_coupons')
            {
                filter_with_coupons = true;
            } else {
                filter_with_coupons = false;
            }
            initialLoad = 1;
            this.Search();
        },
        '.map-panel .panel-title a click': function(element)
        {
            var cookies = document.cookie;
            if (cookies.indexOf("mapView") == -1) {
                var d = new Date();
                d.setTime(d.getTime()+(365*24*60*60*1000));
                var expires = "expires="+d.toGMTString();
                document.cookie = "mapView" + "=" + 1 + "; " + expires;
            } else if (element.hasClass('collapsed')) {
                document.cookie = "mapView=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
        },
        //Methods
        'SearchBanners': function()
        {
            BannerEntity.findAll({
                latitude: geoip_latitude,
                longitude: geoip_longitude,
                type: 'keyword',
                keywords: query,
                limit: 3
            }, function(banners){
                if(banners.length)
                {
                    var i = Math.floor((Math.random() * banners.length));
                    var myImpression = new BannerEntityImpression({banner_entity_id: banners[i].banner_entity_id});
                    myImpression.save();
                    $('#banner').html(can.view('template_keyword_banner',{
                        banner: banners[i]
                    }));
                    $('#banner').show();
                }
            });
        },
        'Search': function()
        {
            var self = this;
            var SearchObject = new Object();
            SearchObject.limit = 100;
            SearchObject.page = page;
            SearchObject.facet = facet;
            SearchObject.facet_type = facetType;
            SearchObject.filter_with_coupons = filter_with_coupons;
            SearchObject.t = searchType;
            SearchObject.q = query;
            SearchObject.s = sorting_type;
            SearchObject.with_radius = with_radius;

            if (initialLoad == 1)
            {
                Search.findAll(SearchObject, function(objects)
                {
                    
                    if (searchType == 'merchant' || searchType != 'offer')
                    {
                        $('.ajax-loader').hide();
                        if(objects.stats.total == 0) {
                            if(filter_with_coupons == 'active')
                            {
                                $('.active-filter button').html('Show All Merchants');
                                filter_with_coupons = 'all';
                                initialLoad = 1;
                                $('.filter-links [data-value="filter_with_coupons"]').parent().removeClass('active');
                                $('.filter-links [data-value="all_results"]').parent().addClass('active');
                                self.Search();
                                return;
                            }
                            else if(with_radius)
                            {
                                with_radius = false;
                                initialLoad = 1;
                                self.Search();
                                return;
                            }
                            $('.subcategory-panel').hide();
                            $('.city-panel').hide();
                            $('.merchant-results-holder').hide();
                            $('.no-merchant-results').show();
                        }
                        else
                        {
                            $('.subcategory-panel').show();
                            $('.city-panel').show();
                            $('.merchant-results-holder').show();
                            $('.no-merchant-results').hide();
                        }
                        self.BindLocations(objects);
                    }
                    else if(searchType == 'offer')
                    {
                        $('.ajax-loader').hide();
                        if(objects.stats.total == 0) {
                            $('.no-offer-results').show();
                        }
                        self.BindEntities(objects);
                    }
                    if(facet == '')
                    {
                        if(typeof objects.stats.facets.subcategory !== 'undefined')
                        {
                            self.BindSubcategories(objects.stats.facets.subcategory.constraints);
                        }
                        if(typeof objects.stats.facets.city !== 'undefined')
                        {
                            self.BindCities(objects.stats.facets.city.constraints);
                        }
                    }
                });
            } 
            else 
            {
                if (searchType == 'merchant' || searchType != 'offer')
                {
                    self.BindLocations(merchants);
                }
                else if(searchType == 'offer')
                {
                    self.BindEntities(offers);
                }
            }
        },
        'BindLocations': function(locations)
        {
            var my_locations = []
            for(var i=0; i < (locations.length < 12 ? locations.length : 12); i++)
            {
                my_locations.push(new Search(locations[i]._data));
            }
            my_locations = new Search.List(my_locations);
            var more_merchants = []
            for(var i=(locations.length < 12 ? locations.length : 12); i < locations.length; i++)
            {
                more_merchants.push(new Search(locations[i]._data));
            }
            merchants = new Search.List(more_merchants);
            if(initialLoad == 1)
            {
                $('.merchant-result').html(can.view('template_locations',
                {
                    locations: my_locations
                }));

                $('.merchant-result-map-results').html(can.view('template_locations_map',
                {
                    locations: my_locations
                }));

                $('.merchant-result-grid').html(can.view('template_locations_grid',
                {
                    locations: my_locations
                }));

                for(var i=0; i<markers.length; i++)
                {
                    map.removeLayer(markers[i]);
                }
                markers = [];
                var min_lat = 360;
                var min_lng = 360;
                var max_lat = 0;
                var max_lng = -360;
                for (var i=0;i<my_locations.length;i++)
                {
                    markers[i] = L.marker([my_locations[i].latitude, my_locations[i].longitude],{title:my_locations[i].id}).addTo(map);
                    markers[i].bindPopup('<div id="location'+i+'">' +
                        '<span class="h3 hblock"><a href="'+abs_base+'/coupons/'+my_locations[i].category_slug+'/'+my_locations[i].subcategory_slug+'/' + my_locations[i].merchant_slug + '/' + my_locations[i].id + '">' + my_locations[i].merchant_name + '</a></span>' +
                        '<address>' + my_locations[i].address + '<br>' +
                        (((my_locations[i].address2 != null) && (my_locations[i].address2.length > 0))?my_locations[i].address2 + '<br>':'') +
                        my_locations[i].city + ', ' + my_locations[i].state + ' ' + my_locations[i].zip + '<br>' +
                        'P: ' + my_locations[i].phone + '</address>' +
                    '</div>');
                    map.addLayer(markers[i]);
                    min_lat = Number(my_locations[i].latitude) < min_lat ? Number(my_locations[i].latitude) : min_lat;
                    min_lng = Number(my_locations[i].longitude) < min_lng ? Number(my_locations[i].longitude) : min_lng;
                    max_lat = Number(my_locations[i].latitude) > max_lat ? Number(my_locations[i].latitude) : max_lat;
                    max_lng = Number(my_locations[i].longitude) > max_lng ? Number(my_locations[i].longitude) : max_lng;
                }
                map.fitBounds([L.latLng((min_lat-0.005), (min_lng-0.005)), L.latLng((max_lat+0.005), (max_lng+0.005))]);
                if(map.getZoom() > 14)
                {
                    map.setZoom(14);
                }
            }
            else
            {
                $('.merchant-result').append(can.view('template_locations',
                {
                    locations: my_locations
                }));

                $('.merchant-result-map-results').append(can.view('template_locations_map',
                {
                    locations: my_locations
                }));

                $('.merchant-result-grid').append(can.view('template_locations_grid',
                {
                    locations: my_locations
                }));

                var min_lat = 360;
                var min_lng = 360;
                var max_lat = 0;
                var max_lng = -360;
                var initial_length = markers.length;
                for (var i=0;i<my_locations.length;i++)
                {
                    markers[i+initial_length] = L.marker([my_locations[i].latitude, my_locations[i].longitude],{title:my_locations[i].id}).addTo(map);
                    markers[i+initial_length].bindPopup('<div id="location'+i+'">' +
                        '<span class="h3 hblock"><a href="'+abs_base+'/coupons/'+my_locations[i].category_slug+'/'+my_locations[i].subcategory_slug+'/' + my_locations[i].merchant_slug + '/' + my_locations[i].id + '">' + my_locations[i].merchant_name + '</a></span>' +
                        '<address>' + my_locations[i].address + '<br>' +
                        my_locations[i].address2 + '<br>' +
                        my_locations[i].city + ', ' + my_locations[i].state + ' ' + my_locations[i].zip + '<br>' +
                        'P: ' + my_locations[i].phone + '</address>' +
                    '</div>');
                    map.addLayer(markers[i+initial_length]);
                    min_lat = Number(my_locations[i].latitude) < min_lat ? Number(my_locations[i].latitude) : min_lat;
                    min_lng = Number(my_locations[i].longitude) < min_lng ? Number(my_locations[i].longitude) : min_lng;
                    max_lat = Number(my_locations[i].latitude) > max_lat ? Number(my_locations[i].latitude) : max_lat;
                    max_lng = Number(my_locations[i].longitude) > max_lng ? Number(my_locations[i].longitude) : max_lng;
                }
                map.fitBounds([L.latLng((min_lat-0.005), (min_lng-0.005)), L.latLng((max_lat+0.005), (max_lng+0.005))]);
                if(map.getZoom() > 14)
                {
                    map.setZoom(14);
                }
            }
            initialLoad = 0;
            var container = $('#container');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            $('.btn-favorite-merchant').tooltip();
            $('.btn-has-offers').tooltip();
            
            if(my_locations.length < 12)
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
                $('.view-more').button('reset');
            }
        },
        'BindEntities': function(entities)
        {
            if(page == 0)
            {
                $('.offer-results').html(can.view('template_entity',
                {
                    entities: entities
                }));
            }
            else
            {
                $('.offer-results').append(can.view('template_entity',
                {
                    entities: entities
                }));
            }
            var container = $('#container');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
        },
        'BindSubcategories': function(subcategories)
        {
            $('.subcategory-links ul').html(can.view('template_subcategory',
            {
                subcategories: subcategories
            }));
        },
        'BindCities': function(cities)
        {
            $('.city-links ul').html(can.view('template_city',
            {
                cities: cities
            }));
        }
    });

    search_control = new SearchControl($('body'));

</script>

