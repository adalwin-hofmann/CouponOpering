<script>
VehicleStyleRelated = can.Model({
    findAll: 'GET /api/v2/vehicle-style/search-related',
},{});

HomeControl = can.Control(
    {
        init: function()
        {
            var self = this;
            $('#collapseMakes').collapse('show');
            $('.panel-title a[href="#collapseMakes"]').removeClass('collapsed');
            $('#collapseFilter').collapse('show');
            $('.panel-title a[href="#collapseFilter"]').removeClass('collapsed');
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
            var type = $('.radioCarType:checked').val();
            var body = $('#filterBodyType').val();
            var SearchObject = {};
            SearchObject.page = currentPage;
            SearchObject.limit = 12;
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
            if(body != 'all')
                SearchObject.body = body;
            SearchObject.order = 'popularity';
            if(type == 'used')
            {
                if(distance != 'high')
                    SearchObject.dist = distance;
                UsedVehicle.findAll(SearchObject, function(vehicles)
                {
                    $('.btn-auto-filter').button('reset');
                    self.BindResults(vehicles);
                    self.BindPagination(vehicles);
                });
            }
            else
            {
                VehicleStyle.findAll(SearchObject, function(vehicles)
                {
                    $('.btn-auto-filter').button('reset');
                    self.BindResults(vehicles);
                    self.BindPagination(vehicles);
                });
            }
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
            VehicleStyleRelated.findAll(SearchObject, function(vehicles)
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
        'BindResults': function(entities)
        {
            for(var i=0; i < entities.stats.returned; i++)
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
            /*if(currentPage == 0)
            {
                $('#container').html(can.view('template_new_car', 
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#container').append(can.view('template_new_car', 
                {
                    vehicles: vehicles
                }));
            }*/
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

            if(currentPage == 0)
            {
                this.initialLoad = false;
                template_recommendation_list
                $('.offer-results-list').html(can.view('template_list_new_car',
                {
                    vehicles: my_entities
                }));
            }
            else
            {
                $('#container').append(can.view('template_new_car', 
                {
                    vehicles: my_entities
                }));
                $('.offer-results-list').append(can.view('template_list_new_car',
                {
                    vehicles: my_entities
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
                $('.no-results').removeClass('hidden');
            }
        },
        'BindRelated': function(vehicles)
        {
            for(var i=0; i < vehicles.stats.returned; i++)
            {
                if(vehicles[i].display_image.length)
                    vehicles[i].display_image = vehicles[i].display_image[0];
                else
                {
                    // TODO: Add placeholder car image.
                    var image = vehicles[i].assets.length ? vehicles[i].assets[0] : '';
                    vehicles[i].display_image = image;
                }
            }
            $('#containerRelated').html(can.view('template_new_car', 
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