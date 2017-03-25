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
            initialLoad = 1;

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

        },
        //Events
        '.view-more click': function(element)
        {
            $('.view-more').button('loading');
            this.Search();
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
                $(this).val('');
            });
        },
        //Methods
        'Search': function()
        {
            var self = this;
            if (initialLoad == 1)
            {
                UserRecommendation.findAll({user_id: user_id, order: 'score'}, function(entities)
                {
                    self.BindRecommendations(entities);
                    //self.BindSidebarOffers(entities);
                    if(entities.stats.returned == 0)
                    {
                        $('.ajax-loader').hide();
                        $('.default-no-results').show();
                        $('.view-more').hide();
                        $('.category-footer').hide();
                        $('.js-masonry').hide();
                        
                        return;
                    }
                });
            } else {
                self.BindRecommendations(recommended_entities);
            }

        },
        'BindRecommendations': function(entities)
        {
            if (initialLoad == 1) {
                $('.ajax-loader').hide();
                if(entities.stats.returned == 0)
                {
                    //$('#container').html(can.view('template_no_results'));
                    return;
                }
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
            recommended_entities = new UserRecommendation.List(more_entities);
            if(initialLoad == 1)
            {
                $('#container').html(can.view('template_entity',
                {
                    entities: my_entities
                }));
                $('.offer-results-list').html(can.view('template_entity_list',
                {
                    entities: my_entities
                }));
                $('.offer-results-map').html(can.view('template_entity_map',
                {
                    entities: my_entities
                }));
                master_control.BindMap(my_entities);
            }
            else
            {
                $('#container').append(can.view('template_entity',
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
        }
    });

    home_control = new HomeControl($('body'));

</script>