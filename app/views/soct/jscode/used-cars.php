<script>
UsedVehicleRelated = can.Model({
    findAll: 'GET /api/v2/vehicle-entity/search-related',
},{});

$(document).ready(function() {
    /*$('.view-change a').click(function(e)
    {
        $('.view-change a').removeClass('btn-green');
        $('.view-change a').addClass('btn-white');
        $(this).addClass('btn-green');
        $(this).removeClass('btn-white');
        var container = $('#container');
        container.imagesLoaded( function() {
            var msnry = new Masonry( document.querySelector('#container'), 
            {
                itemSelector: '.item'
            });
        });
    });*/
});
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
            var self = this;
            $('.radioCarType').prop('checked', false);
            $('.radioCarType[value=used]').prop('checked', true);
            $('#collapseFilter').collapse('show');
            $('.panel-title a[href="#collapseFilter"]').removeClass('collapsed');
            $('#collapseMakes').collapse('show');
            $('.panel-title a[href="#collapseMakes"]').removeClass('collapsed');

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
            if (vehicleCount == 0)
            {
                this.SearchOther();
            }
        },
        //Events
        '.view-more click': function(element)
        {
            currentPage++;
            element.button('loading');
            this.Search();
        },
        //Methods
        'Search': function()
        {
            var self = this;
            $('.btn-auto-filter').button('loading');
            
            var min = $('#filterPriceMin').val();
            var max = $('#filterPriceMax').val();
            var distance = $('#filterDistance').val();
            var SearchObject = {};
            SearchObject.page = currentPage;
            SearchObject.limit = 12;
            SearchObject.state = geoip_region();
            if(year != 'all')
                SearchObject.year = year;
            if(make != 'all')
                SearchObject.make = make;
            if(model != 'all')
                SearchObject.model = model;
            if(min != 'low')
                SearchObject.min = min;
            if(max != 'high')
                SearchObject.max = max;
            if(distance != 'high')
                SearchObject.dist = distance;
            VehicleEntity.findAll(SearchObject, function(vehicles)
            {
                $('.btn-auto-filter').button('reset');
                self.BindResults(vehicles);
                self.BindPagination(vehicles);
            });
        },
        'SearchOther': function()
        {
            var self = this;
            var SearchObject = {};
            SearchObject.limit = 3;
            if(year != 'all')
                SearchObject.year = year;
            if(make != 'all')
                SearchObject.make = make;
            if(model != 'all')
                SearchObject.model = model;
            UsedVehicleRelated.findAll(SearchObject, function(vehicles)
            {
                //$('.btn-auto-filter').button('reset');
                self.BindRelated(vehicles);
                //self.BindPagination(vehicles);
            });
        },
        'GetFeatured': function()
        {
            var self = this;
            FeaturedFranchise.findAll({}, function(featured)
            {
                self.BindFeaturedFranchise(featured);
            });
        },
        'BindResults': function(vehicles)
        {
            var my_entities = []
            for(var i=0; i < (vehicles.length < 12 ? vehicles.length : 12); i++)
            {
                my_entities.push(new UserRecommendation(vehicles[i]._data));
            }
            my_entities = new UserRecommendation.List(my_entities);
            var more_entities = []
            for(var i=(vehicles.length < 12 ? vehicles.length : 12); i < vehicles.length; i++)
            {
                more_entities.push(new UserRecommendation(vehicles[i]._data));
            }
            this.remaining_entities = new UserRecommendation.List(more_entities);

            for(var i=0; i < vehicles.stats.returned; i++)
            {

                var images = vehicles[i].image_urls.replace('|',',').split(',');
                if(images.length)
                    vehicles[i].display_image = images[0];
                else
                {
                    // TODO: Add placeholder car image.
                    vehicles[i].display_image = '';
                }
            }
            if(currentPage == 0)
            {
                /*$('#listView').html(can.view('template_list_vehicle', 
                {
                    vehicles: vehicles
                }));
                $('#container').html(can.view('template_grid_vehicle', 
                {
                    vehicles: vehicles
                }));*/
                $('.offer-results-map').html(can.view('template_map_vehicle',
                {
                    vehicles: my_entities
                }));
                master_control.BindMap(my_entities);
            }
            else
            {
                $('.offer-results-list').append(can.view('template_list_vehicle', 
                {
                    vehicles: vehicles
                }));
                $('#container').append(can.view('template_grid_vehicle', 
                {
                    vehicles: vehicles
                }));
                 $('.offer-results-map').append(can.view('template_map_vehicle',
                {
                    vehicles: my_entities
                }));
                master_control.BindMap(my_entities);
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
            $('[data-toggle="tooltip"]').tooltip();
        },
        'BindPagination': function(vehicles)
        {
            $('.ajax-loader').hide();
            if(vehicles.stats.returned < vehicles.stats.take || (vehicles.stats.take * currentPage) >= vehicles.stats.total)
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
            }
            $('.view-more').button('reset');
            if(vehicles.stats.total == 0)
            {
                this.SearchOther();
                $('.view-change').hide();
                $('#listView').hide();
                $('#mapView').hide();
                $('.no-results').removeClass('hidden');
            }
        },
        'BindRelated': function(vehicles)
        {
            if(vehicles.stats.total == 0)
            {
                $('.related-text').hide();
            }
            for(var i=0; i < vehicles.stats.returned; i++)
            {

                var images = vehicles[i].image_urls.replace('|',',').split(',');
                if(images.length)
                    vehicles[i].display_image = images[0];
                else
                {
                    // TODO: Add placeholder car image.
                    vehicles[i].display_image = '';
                }
            }
            $('#containerRelated').html(can.view('template_grid_vehicle', 
            {
                vehicles: vehicles
            }));
            
            var container = $('#containerRelated');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#containerRelated'), 
                {
                    itemSelector: '.item'
                });
                $('#containerRelated .item').removeClass('invisible');
            });
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