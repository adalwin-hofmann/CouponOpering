<script>
    var map = L.map('map',
    {
        center: [geoip_latitude(), geoip_longitude()],
        zoom: 10,
        scrollWheelZoom: false
    });


    SponsorEntity = can.Model({
        findAll: 'GET /api/v2/entity/get-sponsor'
    },{});
    SponsorControl = can.Control(
    {
        init: function()
        {
            with_radius = true;
            initialLoad = 1;
            markers = [];

            // add an OpenStreetMap tile layer
            L.tileLayer('http://otile1.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
                attribution: 'Data, imagery and map information provided by MapQuest, OpenStreetMap <http://www.openstreetmap.org/copyright> and contributors, ODbL <http://wiki.openstreetmap.org/wiki/Legal_FAQ#3a._I_would_like_to_use_OpenStreetMap_maps._How_should_I_credit_you.3F>'
            }).addTo(map);

            this.Search();
        },
        //Events
        '.view-more click': function(element)
        {
            page++;
            $('.view-more').button('loading');
            this.Search();
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = {
                limit: 12,
                page: page,
                district_slug: district_slug,
                sort: 'nearest'
            };

            SponsorEntity.findAll(EntityObject, function(entities) {
                self.BindEntities(entities);
            });
        },
        'BindEntities': function(entities)
        {
            initalLoad = 0;
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
                $('.view-more').show();
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
        }
    });

    sponsor_control = new SponsorControl($('body'));

</script>
