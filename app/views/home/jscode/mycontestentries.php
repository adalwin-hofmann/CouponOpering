<script>

    Contest = can.Model({
        findAll: 'GET /api/contest/get-by-user'
    },{});
    OpenContest = can.Model({
        findAll: 'GET /api/v2/contest/get-nearby-open'
    },{});
    Winners = can.Model({
        findAll: 'GET /api/v2/contest/get-all-winners'
    },{});
    HomeControl = can.Control(
    {
        init: function()
        {
            initalLoad = 1;
            this.Search();
            this.SearchWinners();

            var $container = $('#containerEntered');
            $container.imagesLoaded( function() {
                $container.masonry();
            });

            var $container = $('#containerExpired');
            $container.imagesLoaded( function() {
                $container.masonry();
            });

            var self = this;
        },
        //Events
        '.view-more click': function(element)
        {
            $('.view-more').button('loading');
            this.Search();
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = {
                user_id: user_id,
                lat: geoip_latitude(),
                lng: geoip_longitude(),
                page: 0,
                limit: 12
            };

            OpenContest.findAll(EntityObject, function(entities) {
                self.BindEntities(entities);
            });
        },
        'SearchWinners': function()
        {
            var self = this;
            var EntityObject = {
                userSpecific: true,
                latitude: geoip_latitude(),
                longitude: geoip_longitude(),
                state: geoip_region(),
                city: geoip_city()
            };

            Winners.findAll(EntityObject, function(entities) {
                self.BindWinners(entities);
            });
        },
        'BindEntities': function(entities)
        {
            $('#containerOpen').html(can.view('template_entity',
            {
                entities: entities
            }));
            initalLoad = 0;
            var container = $('#containerOpen');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#containerOpen'), 
                {
                    itemSelector: '.item'
                });
            });
            // Hides item-info on contests on all pages except contest category page.
            $(document).ready(function() {
                $('.item.contest .item-info').removeClass('hidden');
            });
            
        },
        'BindWinners': function(entities)
        {
             if(page == 0)
            {
                $('#containerExpired').html(can.view('template_entity',
                {
                    entities: entities
                }));
            }
            else
            {
                $('#containerExpired').append(can.view('template_entity',
                {
                    entities: entities
                }));
            }
            initalLoad = 0;
            var container = $('#containerExpired');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#containerExpired'), 
                {
                    itemSelector: '.item'
                });
            });
            // Hides item-info on contests on all pages except contest category page.
            $(document).ready(function() {
                $('#containerExpired .item.contest .item-info').removeClass('hidden');
                $('#containerExpired .item.contest').addClass('disabled');
                $('#containerExpired .item.contest').removeClass('btn-get-contest');
                $('#containerExpired .btn-get-contest').hide();
                $('#containerExpired .item.contest a').css('cursor', 'default');
            });
            
        }
    });

    home_control = new HomeControl($('body'));

</script>
