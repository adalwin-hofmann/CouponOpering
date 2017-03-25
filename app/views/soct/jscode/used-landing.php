<script>
UsedVehicleRelated = can.Model({
    findAll: 'GET /api/v2/used-vehicle/search-related',
},{});

$(document).ready(function() {
    $('.view-change a').click(function(e)
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
    });
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
            //this.Search();
            this.GetFeatured();
        },
        //Events
        '.view-more click': function(element)
        {
            currentPage++;
            element.button('loading');
            this.Search();
        },
        '#usedState change': function(element)
        {
            var self = this;
            if(element.val() != 'all')
            {
                window.location = element.val();
            }
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
            UsedVehicle.findAll(SearchObject, function(vehicles)
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
                $('#listView').html(can.view('template_list_vehicle', 
                {
                    vehicles: vehicles
                }));
                $('#container').html(can.view('template_grid_vehicle', 
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#listView').append(can.view('template_list_vehicle', 
                {
                    vehicles: vehicles
                }));
                $('#container').append(can.view('template_grid_vehicle', 
                {
                    vehicles: vehicles
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
                $('.no-results').removeClass('hidden');
            }
        },
        'BindRelated': function(vehicles)
        {
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