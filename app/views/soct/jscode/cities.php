<script>

    HomeControl = can.Control(
    {
        init: function()
        {
            this.initialLoad = 1;
            //this.Search();
        },
        //Events
        '.view-more click': function(element)
        {
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
        '#usedCity change': function(element)
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
            var filter = {};

            if (this.initialLoad) {
                if(this.position) {
                    filter.longitude = this.position.longitude;
                    filter.latitude = this.position.latitude;
                }

                bindCallback = function(entities) {
                    self.ProcessRecommendations(entities);
                };

                $('.view-more').button('loading');
                var SearchObject = {};
                SearchObject.limit = 48;
                SearchObject.order = 'rand';
                if(type == 'used')
                {
                    UsedVehicle.findAll(SearchObject, bindCallback);
                }
                else if(type == 'new')
                {
                    VehicleStyle.findAll(SearchObject, bindCallback);
                }
            }
            else
            {
                this.BindRecommendations(this.remaining_entities);
            }
        },
        'ProcessRecommendations': function(entities)
        {
            for(var i=0; i < entities.stats.returned; i++)
            {
                if(type == 'used')
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
                else if(type == 'new')
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
                $('#container').html(can.view('template_recommendation',
                {
                    entities: my_entities
                }));
            }
            else
            {
                $('#container').append(can.view('template_recommendation',
                {
                    entities: my_entities
                }));
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
        }
    });

    home_control = new HomeControl($('body'));
</script>
