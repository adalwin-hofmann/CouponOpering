<script>

CategoryEntity = can.Model({
    findAll: 'GET /api/v2/entity/get-by-category'
},{});

FeaturedFranchises = can.Model(
{
    findAll: 'GET /api/v2/franchise/get-featured-dealers'
},{});

HomeControl = can.Control(
    {
        init: function()
        {
            //this.Search();
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
            var SearchObject = {};
            SearchObject.page = currentPage;
            SearchObject.limit = 25;
            FeaturedFranchises.findAll(SearchObject, function(featured)
            {
                self.BindResults(featured);
            });
        },
        'BindResults': function(featured)
        {
            if(featured.stats.returned == 0)
            {
                $('.ajax-loader').hide();
                $('.no-results').removeClass('hidden');
                $('.view-more').hide();
                //$('.category-footer').hide();
                //$('.js-masonry').hide();
                
                return;
            }
            for(var i=0; i<featured.length; i++)
            {
                for(var j=0; j<featured[i].merchant.eager_assets.length; j++)
                {
                    if(j==0)
                        featured[i].display_image = featured[i].merchant.eager_assets[0];
                    if(featured[i].merchant.eager_assets[j].name == 'logo1')
                        featured[i].display_image = featured[i].merchant.eager_assets[j];
                }
            }
            if(currentPage == 0)
            {
                $('#listView').html(can.view('template_featured_dealers',
                {
                    featured: featured
                }));
            }
            else
            {
                $('#listView').append(can.view('template_featured_dealers',
                {
                    featured: featured
                }));
            }
            $('#listView .item.contest .item-info').removeClass('hidden');
            if(featured.stats.returned < featured.stats.take || (featured.stats.take * currentPage) >= featured.stats.total)
            {
                $('.view-more').hide();
            }
            else
            {
                $('.view-more').show();
            }
            $('.view-more').button('reset');
        }
      });

    home_control = new HomeControl($('body'));

</script>