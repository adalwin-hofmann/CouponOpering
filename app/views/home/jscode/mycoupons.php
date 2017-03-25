<script>
//steal('/js/myapp.js').then(function(){
    HomeControl = can.Control(
    {
        init: function()
        {
            
            this.Search();

            var self = this;
        },
        //Events
        '.view-more click': function(element)
        {
            page++
            $('.view-more').button('loading');
            this.Search();
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = new Object();
            EntityObject.limit = 12;
            EntityObject.page = page;
            EntityObject.user_id = user_id;
            EntityObject.is_dailydeal = '0';
            UserClip.findAll(EntityObject, function(entities)
            {
                self.BindEntities(entities);
            });
        },
        'BindEntities': function(entities)
        {
            if(page == 0)
            {
                $('.ajax-loader').hide();
                $('#container').html(can.view('template_entity', 
                {
                    entities: entities
                }));
            }
            else
            {
                $('.ajax-loader').hide();
                $('#container').append(can.view('template_entity',
                {
                    entities: entities
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
            if(entities.stats.returned < entities.stats.take || (entities.stats.total == (entities.stats.page * entities.stats.take + entities.stats.take)))
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
            }
            $('.view-more').button('reset');
            if (entities.stats.total == 0)
            {
                $('.no-saved-offers').show();
                $('.no-saved-offers').parent().addClass('content-bg');
                $('.content-bg').find('.col-xs-12').removeClass('col-xs-12');
                $('.text-center').hide();
            }
        },
        'BindBanner': function(data)
        {
            var element = $('#banner');
            element.html(can.view('template_banner',
            {
                //banner: data
                banner : [
                { "id":"1" , "name":"Get 15% off your total bill", "merchant_name": "Direct Buy", "path": "http://s3.amazonaws.com/saveoneverything_assets/images/1384277475-winandsave_category.jpg", "banner_link":"/merchant" },
                ]
            }));
             $('#banner').show();
        }
    });

    home_control = new HomeControl($('body'));
//});
</script>