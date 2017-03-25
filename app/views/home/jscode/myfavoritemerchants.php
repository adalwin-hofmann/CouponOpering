<script>

    HomeControl = can.Control(
    {
        init: function()
        {
            
            this.Search();
            var self = this;
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
        '.btn-remove-favorite click': function(element)
        {
            var self = this;
            var myDelete = new DeleteFavorite({user_id: user_id, type: 'location', object_id: element.data('location_id')});
            myDelete.save(function(fav)
            {
                page = 0;
                self.Search();
            });
        },
        '.searchbar-bottom input keyup': function( element, event ) 
        {
            if (event.which==13) {
                var query = $(".searchbar-bottom input").val();
                var searchTypeValue = $('.search-type button').data("value");
                window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue;
            }
        },
        '.searchbar-bottom button.search click': function( element, event ) 
        {
            var query = $(".searchbar-bottom input").val();
            var searchTypeValue = $('.search-type button').data("value");
            window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue;
        },
        '.search-type li a click': function(element)
        {
            var searchType = element.text();
            $('.search-type button').html(searchType+' <span class="caret"></span>');
            $('.search-type button').data("value",element.data("value"));
        },
        //Methods
        'Search': function()
        {
            var self = this;
            var FavoriteObject = new Object();
            FavoriteObject.limit = 12;
            FavoriteObject.page = page;
            FavoriteObject.user_id = user_id;
            FavoriteObject.type = 'location';
            UserFavorite.findAll(FavoriteObject, function(locations)
            {
                self.BindFavorites(locations);
            });
        },
        'BindFavorites': function(locations)
        {
            if(page == 0)
            {
                $('#favoritesArea').html(can.view('template_merchants',
                {
                    locations : locations
                }));
            }
            else
            {
                $('#favoritesArea').append(can.view('template_merchants',
                {
                    locations : locations
                }));
            }
            if(locations.stats.returned < locations.stats.take || (locations.stats.total == (locations.stats.page * locations.stats.take + locations.stats.take)))
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

    search_control = new HomeControl($('body'));

</script>
