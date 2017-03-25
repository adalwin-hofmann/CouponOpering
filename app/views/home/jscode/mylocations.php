<script>
    HomeControl = can.Control(
    {
        init: function()
        {
            initalLoad = 1;

            var self = this;
            this.SearchLocations();
        },
        //Events
        '.save-location-preferences click': function(element, click)
        {
            //Do stuff
        },
        '.remove-saved-location click': function(element)
        {
            var myDelete = new DeleteSavedLocation({user_id: user_id, location_id: element.data('location_id')});
            myDelete.save(function(locations)
            {
                if(locations.data.length != 0)
                {
                    $('.saved-location-results').html(can.view('template_saved', 
                    {
                        locations: locations.data
                    }));
                }
                else
                {
                    $('.saved-location-results').html('');
                }
            });
        },
        '.add-location input keyup': function(element, event)
        {
            var self = this;
            locationQuery = element.val();
            if ((locationQuery.length > 2) && (event.which!=13)) {
                // Needs to be Updated
                can.ajax({
                    url: '/api/zipcode/get-by-query?q='+encodeURIComponent(locationQuery),
                    dataType: 'json',
                    success: function(data) {
                        self.BindAddLocation(data);
                    }
                });
            } else {
                $('.add-location-dropdown').hide();
            }
            if (event.which==13) {
                var loc_element = $('.add-location-dropdown li').first();
                var myLocation = new SearchLocation({user_id: user_id, latitude: loc_element.data('latitude'), longitude: loc_element.data('longitude'), city: loc_element.data('city'), state: loc_element.data('state')});
                myLocation.save(function(locations)
                {
                    $('.add-location-dropdown').css('display', '');
                    $('.add-location input').val('');
                    if(locations.data.length != 0)
                    {
                        $('.saved-location-area').html(can.view('template_saved_location',
                        {
                            locations: locations.data
                        }));
                        $('.saved-location-results').html(can.view('template_saved', 
                        {
                            locations: locations.data
                        }));
                    }
                    else
                    {
                        $('.saved-location-area').html('<li class="dropdown-disclaimer">You have no saved locations.</li>');
                        $('.saved-location-results').html('');
                    }
            });
            }
        },
        '.add-city click': function(element)
        {
            var myLocation = new SearchLocation({user_id: user_id, latitude: element.data('latitude'), longitude: element.data('longitude'), city: element.data('city'), state: element.data('state')});
            myLocation.save(function(locations)
            {
                $('.add-location-dropdown').css('display', '');
                $('.add-location input').val('');
                if(locations.data.length != 0)
                {
                    $('.saved-location-area').html(can.view('template_saved_location',
                    {
                        locations: locations.data
                    }));
                    $('.saved-location-results').html(can.view('template_saved', 
                    {
                        locations: locations.data
                    }));
                }
                else
                {
                    $('.saved-location-area').html('<li class="dropdown-disclaimer">You have no saved locations.</li>');
                    $('.saved-location-results').html('');
                }
            });
        },
        //Methods
        'SearchLocations': function()
        {
            var self = this;
            SavedLocation.findAll({user_id: user_id}, function(locations)
            {
                if(locations.length != 0)
                {
                    $('.saved-location-results').html(can.view('template_saved', 
                    {
                        locations: locations
                    }));
                }
                else
                {
                    $('.saved-location-results').html('');
                }
            });
        },
        'BindAddLocation' : function(searchLocations)
        {
            $('.add-location-dropdown').html(can.view('template_add_location',
            {
                searchLocations : searchLocations
            }));
            if (searchLocations.stats.returned != 0)
            {
                $('.add-location-dropdown').show();
            } else {
                $('.add-location-dropdown').hide();
            }
        }
    });

    home_control = new HomeControl($('body'));

</script>