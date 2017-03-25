<script>
    var map = L.map('map',
    {
        center: [geoip_latitude(), geoip_longitude()],
        zoom: 10,
        scrollWheelZoom: false
    });

    HomeControl = can.Control(
    {
        init: function()
        {
            $('#collapseFilter').collapse('show');
            $('.panel-title a[href="#collapseFilter"]').removeClass('collapsed');
            $('#exploreCollapse').collapse('show');
            $('.panel-title a[href="#exploreCollapse"]').removeClass('collapsed');
            this.initialLoad = 1;

            this.markers = [];
            this.min_lat = 360;
            this.min_lng = 360;
            this.max_lat = 0;
            this.max_lng = -360;

            // add an OpenStreetMap tile layer
            L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
            }).addTo(map);

            this.Search();
            this.GetFeatured();
            var cookies = document.cookie;
            if (cookies.indexOf("searchVehiclesMobile") > -1) {
                $(document).ready(function() {
                    $('.search-vehicles-mobile .panel-title a').addClass('collapsed');
                    $('.search-vehicles-mobile .panel-collapse').removeClass('in');
                });
            }
        },
        //Events
        '.view-more click': function(element)
        {
            $('.view-more').button('loading');
            this.Search();
        },
        '.search-vehicles-mobile .panel-title a click': function(element)
        {
            var cookies = document.cookie;
            if (cookies.indexOf("searchVehiclesMobile") == -1) {
                var d = new Date();
                d.setTime(d.getTime()+(365*24*60*60*1000));
                var expires = "expires="+d.toGMTString();
                document.cookie = "searchVehiclesMobile" + "=" + 1 + "; " + expires;
            } else if (element.hasClass('collapsed')) {
                document.cookie = "searchVehiclesMobile=; expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var filter = {};

            if (this.initialLoad) {
                if(this.position) {
                    filter.longitude = this.position.longitude;
                    filter.latitude = this.position.latitude;
                }

                bindCallback = function(entities) {
                    self.ProcessRecommendations(entities);
                };

                if(user_type == 'User') {
                    filter.user_id = user_id;
                    filter.type = 'soct';
                    filter.order = 'dist';
                    UserRecommendation.findAll(filter, bindCallback);
                } else {
                    filter.nonmember_id = user_id;
                    filter.type = 'soct';
                    filter.order = 'dist';
                    NonmemberRecommendation.findAll(filter, bindCallback);
                }
            } else {
                this.BindRecommendations(this.remaining_entities);
            }
        },
        'GetFeatured': function()
        {
            var self = this;
            FeaturedFranchise.findAll({}, function(featured)
            {
                self.BindFeaturedFranchise(featured);
            });
        },
        'ProcessRecommendations': function(entities)
        {
            for(var i=0; i < entities.stats.returned; i++)
            {
                if(entities[i].object_type == 'UsedVehicle')
                {
                    var images = entities[i].image_urls.replace('|',',').split(',');
                    if(images.length)
                        entities[i]._data.display_image = images[0];
                    else
                    {
                        // TODO: Add placeholder car image.
                        entities[i]._data.display_image = '';
                    }
                }
                else if(entities[i].object_type == 'VehicleStyle')
                {
                    if(entities[i].display_image.length)
                        entities[i]._data.display_image = entities[i].display_image[0];
                    else
                    {
                        // TODO: Add placeholder car image.
                        var image = entities[i].assets.length ? entities[i].assets[0] : ''
                        entities[i]._data.display_image = image;
                    }
                }
            }
            this.BindRecommendations(entities);
        },
        'BindRecommendations': function(entities)
        {
            if(entities.stats.total == 0)
            {
                $('.ajax-loader').hide();
                $('.default-no-results').show();
                $('.view-more').hide();
                $('.category-footer').hide();
                $('.js-masonry').hide();

                return;
            }
            var my_entities = []
            for(var i=0; i < (entities.length < 12 ? entities.length : 12); i++)
            {
                my_entities.push(new UserRecommendation(entities[i]._data));
            }
            my_entities = new UserRecommendation.List(my_entities);
            var more_entities = []
            for(var i=(entities.length < 12 ? entities.length : 12); i < entities.length; i++)
            {
                more_entities.push(new UserRecommendation(entities[i]._data));
            }
            this.remaining_entities = new UserRecommendation.List(more_entities);
            if(this.initialLoad)
            {
                this.initialLoad = false;
                template_recommendation_list
                $('.offer-results-list').html(can.view('template_recommendation_list',
                {
                    entities: my_entities
                }));
                $('.offer-results-map').html(can.view('template_recommendation_map',
                {
                    entities: my_entities
                }));
                master_control.BindMap(my_entities);
                /*$('#container').html(can.view('template_recommendation',
                {
                    entities: my_entities
                }));*/
            }
            else
            {
                $('#container').append(can.view('template_recommendation',
                {
                    entities: my_entities
                }));
                $('.offer-results-list').append(can.view('template_entity_list',
                {
                    entities: my_entities
                }));
                $('.offer-results-map').append(can.view('template_entity_map',
                {
                    entities: my_entities
                }));
                master_control.BindMap(my_entities);
            }
            var container = $('#container');
            // initialize Masonry after all images have loaded
            container.imagesLoaded( function() {
                $('.ajax-loader').hide();
                var msnry = new Masonry( document.querySelector('#container'),
                {
                    itemSelector: '.item'
                });
            });

            if(my_entities.length < 12)
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
                $('.view-more').button('reset');
            }
            $('[data-toggle="tooltip"]').tooltip();
        },
        'BindFeaturedFranchise': function(featured)
        {
            if(featured.stats.returned == 0)
            {
                $('.banner-offer').hide();
                return;
            }
            for(var i=0; i<featured.length; i++)
            {
                for(var j=0; j<featured[i].merchant.eager_assets.length; j++)
                {
                    if(j==0)
                        featured[i].display_image = featured[i].merchant.eager_assets[0];
                    if(featured[i].merchant.eager_assets[j].name == 'logo1')
                        featured[i].display_image = featured[i].merchant.eager_assets[j];
                }
            }
            $('.banner-offer').html(can.view('template_featured_dealer',
            {
                featured: featured
            }));
        }
    });

    home_control = new HomeControl($('body'));
</script>
