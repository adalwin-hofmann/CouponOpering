<script>
VehicleStyle = can.Model({
    findAll: 'GET /api/v2/vehicle-style/search'
},{});

VehicleModel = can.Model({
    findAll: 'GET /api/v2/vehicle-model/get-by-make'
},{});

FavoriteCar = can.Model({
    findAll: 'GET /api/v2/user/favorites?user_id={user_id}'
},{});

HomeControl = can.Control(
    {
        init: function()
        {
            var self = this;
            $('#collapseMakes').collapse('show');
            $('.panel-title a[href="#collapseMakes"]').removeClass('collapsed');
            this.Search();
        },
        //Events
        '.view-more click': function(element)
        {
            currentPage++;
            element.button('loading');
            this.Search();
        },
        '.btn-used-unsave click': function(element)
        {
            $('#usedCarModal').modal('hide');
            $('.btn-view-used-car[data-id="'+element.data('vehicle_id')+'"]').parent().parent().remove();
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var SearchObject = {};
            SearchObject.user_id = user_id;
            SearchObject.type = 'vehicle-style,vehicle-entity';
            SearchObject.page = currentPage;
            SearchObject.limit = 12;
            FavoriteCar.findAll(SearchObject, function(vehicles)
            {
                self.BindResults(vehicles);
                self.BindPagination(vehicles);
            });
        },
        'BindResults': function(vehicles)
        {
            if (vehicles.stats.total == 0)
            {
                $('#container').hide();
                $('.no-saved-offers').show();
                $('.no-saved-offers').parent().addClass('content-bg');
                $('.content-bg').find('.col-xs-12').removeClass('col-xs-12');
                return;
            }
            for(var i=0; i < vehicles.stats.returned; i++)
            {
                if(vehicles[i].favoritable_type == 'VehicleStyle')
                {
                    if(vehicles[i].favoritable.display_image.length)
                        vehicles[i].favoritable.display_image = vehicles[i].favoritable.display_image[0];
                    else
                    {
                        // TODO: Add placeholder car image.
                        var image = vehicles[i].favoritable.assets.length ? vehicles[i].favoritable.assets[0] : ''
                        vehicles[i].favoritable.display_image = image;
                    }
                }
                else
                {
                    var images = vehicles[i].favoritable.image_urls.split('|');
                    if(images.length)
                        vehicles[i].favoritable.display_image = images[0];
                    else
                    {
                        // TODO: Add placeholder car image.
                        vehicles[i].favoritable.display_image = '';
                    }
                }
            }
            if(currentPage == 0)
            {
                $('#container').html(can.view('template_car', 
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#container').append(can.view('template_car', 
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
            if(vehicles.stats.returned < vehicles.stats.take || (vehicles.stats.take * currentPage) >= vehicles.stats.total)
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
            }
            $('.view-more').button('reset');
        }
      });

    home_control = new HomeControl($('body'));

</script>