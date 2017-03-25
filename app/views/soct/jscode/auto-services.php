<script>

$(document).ready(function() {
    /*$('.view-change a').click(function(e)
    {
        var container = $('#container');
        container.imagesLoaded( function() {
            var msnry = new Masonry( document.querySelector('#container'), 
            {
                itemSelector: '.item'
            });
        });
    });*/
});
var map = L.map('map',
{
    center: [geoip_latitude(), geoip_longitude()],
    zoom: 10,
    scrollWheelZoom: false
});

CategoryEntity = can.Model({
    findAll: 'GET /api/v2/entity/get-by-category'
},{});

HomeControl = can.Control(
    {
        init: function()
        {
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
            currentPage++;
            element.button('loading');
            this.Search();
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = {
                limit: 12,
                page: currentPage,
                category_id: subcategory_id,
                type: 'coupon',
                latitude: geoip_latitude(),
                longitude: geoip_longitude(),
                state: geoip_region(),
                city: geoip_city()
            };

            if(user_type == 'User') {
                EntityObject.user_id = user_id
            }
            EntityObject.radius = 80000; //About 50 miles
            
            CategoryEntity.findAll(EntityObject, function(entities) {
                $('.view-more').button('reset');
                self.BindResults(entities);
            });
        },
        'BindResults': function(entities)
        {
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
            if(entities.stats.total == 0)
            {
                $('.ajax-loader').hide();
                $('.default-no-results').show();
                $('.view-more').hide();
                $('.category-footer').hide();
                $('.js-masonry').hide();
                
                return;
            }
            if(currentPage == 0)
            {
                /*$('#container').html(can.view('template_entity',
                {
                    entities: entities
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
                    entities: entities
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
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
            $('#container .item.contest .item-info').removeClass('hidden');
            if(entities.stats.returned < entities.stats.take || (entities.stats.take * currentPage) >= entities.stats.total)
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
            }
            $('.view-more').button('reset');
            $('[data-toggle="tooltip"]').tooltip();
        }
      });

    home_control = new HomeControl($('body'));

</script>