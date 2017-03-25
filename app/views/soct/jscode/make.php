<script>
$(document).ready(function() {
  var hash = window.location.hash;
  $('#tabs').find('div:visible:first a').tab('show').addClass('btn-black').removeClass('btn-white');
  hash && $('#tabs a[href="' + hash + '"]').tab('show').addClass('btn-black').removeClass('btn-white');
  hash && $('.merchant-nav .model-menu a[href="' + hash + '"]').parent().addClass('active');
  //$('.merchant-nav a[href="' + hash + '"]').addClass('btn-black');
  if (hash != "") {
    $('#tabs a[href!="' + hash + '"]').removeClass('btn-black').addClass('btn-white');
    $('.merchant-nav .model-menu a[href!="' + hash + '"]').parent().removeClass('active');
  }

  var $container = $('#new-cars');
  $container.imagesLoaded( function() {
    $container.masonry();
  });

  $('.model-menu a').click(function (e) {
    window.location.hash = this.hash;
    $('.mobile-menu .model-menu li').removeClass('active');
    var $container = $('#new-cars');
    $container.imagesLoaded( function() {
      $container.masonry();
    });
    var $container = $('#used-cars');
    $container.imagesLoaded( function() {
      $container.masonry();
    });
  });

  $('#tabs a.btn').click(function (e) {
        $('#tabs a.btn.btn-black').removeClass('btn-black').addClass('btn-white');
        $(this).addClass('btn-black').removeClass('btn-white');
        var container = $('#new-cars');
        container.imagesLoaded( function() {
            var msnry = new Masonry( document.querySelector('#new-cars'), 
            {
                itemSelector: '.item'
            });
        });

        var container = $('#used-cars');
        container.imagesLoaded( function() {
            var msnry = new Masonry( document.querySelector('#used-cars'), 
            {
                itemSelector: '.item'
            });
        });
  });

  $('.mobile-menu .model-menu a').click(function (e) {
    $('.nav.model-menu li').removeClass('active');
  });
});

HomeControl = can.Control(
    {
        init: function()
        {
            $('#collapseMakes').collapse('show');
            $('.panel-title a[href="#collapseMakes"]').removeClass('collapsed');
            this.NewSearch();
            this.UsedSearch();
        },
        //Events
        '.view-more-new click': function(element)
        {
            currentNewPage++;
            element.button('loading');
            this.NewSearch();
        },
        '.view-more-used click': function(element)
        {
            currentUsedPage++;
            element.button('loading');
            this.UsedSearch();
        },
        //Methods
        'NewSearch': function()
        {
            var self = this;
            var SearchObject = {};
            SearchObject.page = currentNewPage;
            SearchObject.limit = 12;
            SearchObject.make = make_id;
            VehicleStyle.findAll(SearchObject, function(vehicles)
            {
                self.BindNewCars(vehicles);
                self.BindNewPagination(vehicles);
            });
        },
        'UsedSearch': function()
        {
            var self = this;
            var SearchObject = {};
            SearchObject.page = currentUsedPage;
            SearchObject.limit = 12;
            SearchObject.make = make_id;
            UsedVehicle.findAll(SearchObject, function(vehicles)
            {
                self.BindUsedCars(vehicles);
                self.BindUsedPagination(vehicles);
            });
        },
        'BindNewCars': function(vehicles)
        {
            for(var i=0; i < vehicles.stats.returned; i++)
            {
                if(vehicles[i].display_image.length)
                    vehicles[i].display_image = vehicles[i].display_image[0];
                else
                {
                    // TODO: Add placeholder car image.
                    var image = vehicles[i].assets.length ? vehicles[i].assets[0] : ''
                    vehicles[i].display_image = image;
                }
            }
            if(currentNewPage == 0)
            {
                $('#new-cars').html(can.view('template_new_car', 
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#new-cars').append(can.view('template_new_car', 
                {
                    vehicles: vehicles
                }));
            }
            var container = $('#new-cars');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#new-cars'), 
                {
                    itemSelector: '.item'
                });
            });
            $('#new-cars .item').removeClass('invisible');
        },
        'BindUsedCars': function(vehicles)
        {
            for(var i=0; i < vehicles.stats.returned; i++)
            {
                var images = vehicles[i].image_urls.split('|');
                if(images.length)
                    vehicles[i].display_image = images[0];
                else
                {
                    // TODO: Add placeholder car image.
                    vehicles[i].display_image = '';
                }
            }
            if(currentUsedPage == 0)
            {
                $('#used-cars').html(can.view('template_grid_vehicle', 
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#used-cars').append(can.view('template_grid_vehicle', 
                {
                    vehicles: vehicles
                }));
            }
            var container = $('#used-cars');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#used-cars'), 
                {
                    itemSelector: '.item'
                });
            });
            $('#used-cars .item').removeClass('invisible');
        },
        'BindNewPagination': function(vehicles)
        {
            $('.ajax-loader').hide();
            if(vehicles.stats.returned < vehicles.stats.take || (vehicles.stats.take * currentNewPage) >= vehicles.stats.total)
            {
                $('.view-more-new').hide();
                $('.view-more-new').button('reset');
            }
            else
            {
                $('.view-more-new').show();    
                $('.view-more-new').button('reset');
            }
            if(vehicles.stats.total == 0)
            {
                $('.no-results.new').removeClass('hidden');
            }
        },
        'BindUsedPagination': function(vehicles)
        {
            if(vehicles.stats.returned < vehicles.stats.take || (vehicles.stats.take * currentUsedPage) >= vehicles.stats.total)
            {
                $('.view-more-used').hide();
                $('.view-more-used').button('reset');
            }
            else
            {
                $('.view-more-used').show();    
                $('.view-more-used').button('reset');
            }
            if(vehicles.stats.total == 0)
            {
                $('.no-results.used').removeClass('hidden');
            }
        },
      });

    home_control = new HomeControl($('body'));

</script>