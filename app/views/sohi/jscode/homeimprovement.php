<script>
    HomeControl = can.Control(
    {
        init: function()
        {
            if (type == 'contest' || type == 'coupon') 
            {
                $('.sidebar').parent().addClass('full-width');
            }
            this.remaining_entities = '';
            this.initialLoad = true;
            //this.Search();

            var cookies = document.cookie;
            if (cookies.indexOf("howItWorks") > -1) {
                $(document).ready(function() {
                    $('.how-it-works .panel-title a').addClass('collapsed');
                    $('.how-it-works .panel-collapse').removeClass('in');
                });
            }
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
        '.how-it-works .panel-title a click': function(element)
        {
            var d = new Date();
            d.setTime(d.getTime()+(365*24*60*60*1000));
            var expires = "expires="+d.toGMTString();
            document.cookie = "howItWorks" + "=" + 1 + "; " + expires;
        },
        //Methods
        'Search': function()
        {
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
                    filter.type = 'sohi';
                    filter.order = 'score';
                    UserRecommendation.findAll(filter, bindCallback);
                } else {
                    filter.nonmember_id = user_id;
                    filter.type = 'sohi';
                    filter.order = 'score';
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
            this.remaining_entities = new UserRecommendation.List(more_entities);
            if(this.initialLoad)
            {
                this.initialLoad = false;
                $('#container').html(can.view('template_entity',
                {
                    entities: my_entities
                }));
            }
            else
            {
                $('#container').append(can.view('template_entity',
                {
                    entities: my_entities
                }));
            }
            var container = $('#container');
            // initialize Masonry after all images have loaded
            container.imagesLoaded( function() {
                $('.ajax-loader').hide();
                var msnry = new Masonry( document.querySelector('#container'),
                {
                    itemSelector: '.item'
                });
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
        }
    });

    home_control = new HomeControl($('body'));
</script>