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
            var self = this;
            this.position = '';
            this.remaining_entities = '';
            this.initialLoad = true;

            markers = [];

            // add an OpenStreetMap tile layer
            L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
            }).addTo(map);

            this.Search();
            this.SearchBanners();

            if((typeof fireFirstTimeCompanyModal != 'undefined') && fireFirstTimeCompanyModal == 1)
            {
                $('#firstTimeCompanyModal').modal('show');
            }

        },
        //Events
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
        'SearchBanners': function()
        {
            BannerEntity.findAll({
                latitude: geoip_latitude,
                longitude: geoip_longitude,
                type: 'homepage'
            }, function(banners){
                if(banners.length)
                {
                    var myImpression = new BannerEntityImpression({banner_entity_id: banners[0].banner_entity_id});
                    myImpression.save();
                    $('#banner').html(can.view('template_homepage_banner',{
                        banner: banners[0]
                    }));
                    $('#banner').show();
                }
            });
        },
        'Search': function() {
            var self = this;
            var filter = {};

            if (this.initialLoad) {
                if(this.position) {
                    filter.longitude = this.position.longitude;
                    filter.latitude = this.position.latitude;
                }

                bindCallback = function(entities) {
                    self.BindRecommendations(entities);
                };

                if(user_type == 'User') {
                    filter.user_id = user_id;
                    filter.order = 'score';
                    UserRecommendation.findAll(filter, bindCallback);
                } else {
                    filter.nonmember_id = user_id;
                    filter.order = 'score';
                    filter.limit = 12;
                    NonmemberRecommendation.findAll(filter, bindCallback);
                }
            } else {
                this.BindRecommendations(this.remaining_entities);
            }
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
            recommended_entities = new UserRecommendation.List(more_entities);
            if(this.initialLoad == 1)
            {
                /*$('#container').html(can.view('template_entity',
                {
                    entities: my_entities
                }));*/
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
            var container = $('#container');
            // initialize Masonry after all images have loaded
            /*container.imagesLoaded( function() {
                $('.ajax-loader').hide();
                var msnry = new Masonry( document.querySelector('#container'),
                {
                    itemSelector: '.item'
                });
            });*/

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
        'BindMoreEntities': function(entities)
        {
            this.remaining_entities = entities;
            $('#container').append(can.view('template_entity',
            {
                entities: entities
            }));
            var container = document.querySelector('#container');
            var msnry = new Masonry( container,
            {
                itemSelector: '.item'
            });
            if(more_entities.data.length < 8)
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
