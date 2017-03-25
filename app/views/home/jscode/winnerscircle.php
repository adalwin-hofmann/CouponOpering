<script>

    Winners = can.Model({
        findOne: 'GET /api/v2/contest/get-winners?contest_id={contest_id}',
        findAll: 'GET /api/v2/contest/get-all-winners'
    },{});
    HomeControl = can.Control(
    {
        init: function()
        {
            initalLoad = 1;
            
            this.Search();

            var self = this;
        },
        //Events
        '.view-more click': function(element)
        {
            $('.view-more').button('loading');
            this.Search();
        },
        '.show-all-winners click': function(element)
        {
            Winners.findOne({contest_id: element.data('contest_id')}, function(winners)
            {
                $('#winnersModal .modal-body').html(can.view('template_winner',
                {
                    winners: winners.data
                }));
                $('#winnersModal').modal('show');
            });
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = {
                latitude: geoip_latitude(),
                longitude: geoip_longitude(),
                state: geoip_region(),
                city: geoip_city()
            };

            Winners.findAll(EntityObject, function(entities) {
                self.BindEntities(entities);
            });
        },
        'BindEntities': function(entities)
        {
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
            }
            initalLoad = 0;
            var container = $('#container');
            // initialize Masonry after all images have loaded  
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
            // Hides item-info on contests on all pages except contest category page.
            $(document).ready(function() {
                $('.item.contest .item-info').removeClass('hidden');
                $('#container').addClass('entered');
                $('.item.contest').removeClass('btn-get-contest');
                //$('.item.contest a').css('cursor', 'default');
            });
            
        }
    });

    home_control = new HomeControl($('body'));

</script>
