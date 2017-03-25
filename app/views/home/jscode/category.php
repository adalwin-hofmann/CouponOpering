<script>
    if(initialReturn > 0)
    {
        var map = L.map('map',
        {
            center: [geoip_latitude(), geoip_longitude()],
            zoom: 10,
            scrollWheelZoom: false
        });  
    }

//steal('/js/myapp.js').then(function(){
    CategoryEntity = can.Model({
        findAll: 'GET /api/v2/entity/get-by-category'
    },{});
    Subcategory = can.Model(
    {
        findAll: 'GET /api/category/get-by-parent-slug?slug={slug}'
    },{});
    HomeControl = can.Control(
    {
        init: function()
        {
            initialLoad = 1;
            if(initialReturn > 0)
            {
                with_radius = true;
                markers = [];

                // add an OpenStreetMap tile layer
                L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                    attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
                }).addTo(map);
            }

            this.Search();
        },
        //Events
        '.sorting li a click': function(element)
        {
            var sortText = element.text();
            $('.sorting button').html(sortText+' <span class="caret"></span>');
        },
        '.view-more click': function(element)
        {
            page++;
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
            SuggestionObject.category_id = $('#suggest_category_id').val();
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
                if($(this).attr('id') != 'suggest_category')
                {
                    $(this).val('');
                } 
            });
        },
        '#parent_category_select change': function(element)
        {
            var self = this;
            var parent_category = element.val();
            if(element.val() == 'all')
            {
                $('#subcategory_select').html('<option value="all">Subcategory</option>');
                $('#subcategory_select').prop('disabled', true);
                $('#subcategory_select').addClass('disabled');
            }
            else
            {
                Subcategory.findAll({slug: element.val()}, function(categories)
                {
                    $('#subcategory_select').prop('disabled', false);
                    $('#subcategory_select').removeClass('disabled');
                    self.BindSubcategories(categories);
                });
            }
        },
        '.category-select-btn click': function(element)
        {
            var parent_category = $('#parent_category_select').val();
            var subcategory = $('#subcategory_select').val();
            if (subcategory == 'all')
            {
                var url = abs_base+'/'+type+'s/'+geoip_region().toLowerCase()+'/'+geoip_city().replace(' ', '-').toLowerCase()+'/'+parent_category;
            } else {
                var url = abs_base+'/'+type+'s/'+geoip_region().toLowerCase()+'/'+geoip_city().replace(' ', '-').toLowerCase()+'/'+parent_category+'/'+subcategory;
            }
            window.location = url;
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = {
                limit: 12,
                page: page,
                category_id: category_id,
                type: type,
                latitude: geoip_latitude(),
                longitude: geoip_longitude(),
                state: geoip_region(),
                city: geoip_city()
            };

            if(user_type == 'User') {
                EntityObject.user_id = user_id
            }

            if(type == 'contest') {
                EntityObject.radius = 80000; //About 50 miles
            }

            if(initialReturn > 0)
            {
                CategoryEntity.findAll(EntityObject, function(entities) {
                    self.BindEntities(entities);
                });
            }
            //this.BindBannerOffer();
        },
        'BindEntities': function(entities)
        {
            /*if(entities.stats.total == 0)
            {
                $('.ajax-loader').hide();
                $('.default-no-results').show();
                $('.view-more').hide();
                $('.category-footer').hide();
                $('.js-masonry').hide();
                
                return;
            }
            if(page == 0)
            {
                $('#container').html(can.view('template_entity',
                {
                    entities: entities
                }));
            }
            else
            {
                $('#container').append(can.view('template_entity',
                {
                    entities: entities
                }));
            }*/
            initalLoad = 0;
            //var container = $('#container');
            // initialize Masonry after all images have loaded  
            /*container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });*/
            // Hides item-info on contests on all pages except contest category page.
            $(document).ready(function() {
                $('#container .item.contest .item-info').removeClass('hidden');
            });
            if(entities.stats.returned < entities.stats.take || (entities.stats.total == (entities.stats.page * entities.stats.take + entities.stats.take)))
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();            }

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
            $('.view-more').button('reset');
            $('[data-toggle="tooltip"]').tooltip();
        },
        'BindBannerOffer' : function(data)
        {
        	$('.banner-offer').html(can.view('template_banner_offer',
            {
                bannerOffer : [
                { "id":"5" , "entitiable_type":"Offer" , "is_dailydeal":"0" , "name":"$1.00 OFF", "merchant_name": "Subway", "path": "http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/logos/12978518a78d60df47.jpg", "image":"http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/sliders/12978518a7b4b479a5.jpg", "link":"", "offer_count":"2" }
                ]
            }));
        },
        'BindSubcategories' : function(categories)
        {
            $('#subcategory_select').html('<option value="all">All</option>');
            $('#subcategory_select').append(can.view('template_select_subcategory',
            {
                categories: categories
            }));
        }
    });

    SidebarControl = can.Control(
    {
        init: function()
        {
            this.SearchBanners();
        },
        'SearchBanners': function()
        {
            BannerEntity.findAll({
                latitude: geoip_latitude,
                longitude: geoip_longitude,
                type: category_type
            }, function(banners){
                if(banners.length)
                {
                    var myImpression = new BannerEntityImpression({banner_entity_id: banners[0].banner_entity_id});
                    myImpression.save();
                    $('#banner').html(can.view('template_merchant_banner',{
                        banner: banners[0]
                    }));
                    $('#banner').show();
                }
            });
        }
    });

    home_control = new HomeControl($('body'));
    sidebar_control = new SidebarControl($('#banner'));
//});
</script>
