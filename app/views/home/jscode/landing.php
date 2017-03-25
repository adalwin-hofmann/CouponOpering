<script>
//steal('/js/myapp.js').then(function(){
    CategoryEntity = can.Model({
        findAll: 'GET /api/v2/entity/get-by-category'
    },{});
    HomeControl = can.Control(
    {
        init: function()
        {
            if (type == 'contest')
            {
                $('.sidebar').parent().addClass('full-width');
            }
            //this.Search();

            if (typeof entity_id != 'undefined')
            {
                if (entitable_type == 'Offer')
                {
                    if(user_type == 'User')
                    {
                        var myImpression = new UserImpression({user_id: user_id, entity_id: entity_id})
                    }
                    else
                    {
                        var myImpression = new NonmemberImpression({nonmember_id: user_id, entity_id: entity_id})
                    }
                    myImpression.save(function(impression)
                    {
                        /*if(entity_is_dailydeal == 0)
                        {
                            $('#couponModal').modal('show');
                        }
                        else
                        {
                            $('#saveTodayModal').modal('show');   
                        }*/
                        master_control.BindCoupon(impression);
                    });
                }
                else if (entitable_type == 'Contest')
                {
                    if(user_type == 'User')
                    {
                        var myImpression = new UserImpression({user_id: user_id, entity_id: entity_id})
                    }
                    else
                    {
                        var myImpression = new NonmemberImpression({nonmember_id: user_id, entity_id: entity_id})
                    }
                    myImpression.save(function(impression)
                    {
                        $('#contestModal').modal('show');
                        master_control.BindContest(impression);
                    });
                }
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
        //Methods
        'Search': function()
        {
            var self = this;
            var EntityObject = {
                limit: 12,
                page: page,
                category_id: 120,
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

            CategoryEntity.findAll(EntityObject, function(entities) {
                self.BindEntities(entities);
            });
            //this.BindBannerOffer();
        },
        'BindEntities': function(entities)
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
            if(page == 0)
            {
                $('#soct_deals').html(can.view('template_soct_deals',
                {
                    entities: entities
                }));
            }
            else
            {
                $('#soct_deals').append(can.view('template_soct_deals',
                {
                    entities: entities
                }));
            }
            initalLoad = 0;
            var container = $('#container');
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
                $('.view-more').button('reset');
            }
            else
            {
                $('.view-more').show();    
                $('.view-more').button('reset');
            }
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

    home_control = new HomeControl($('body'));
//});
</script>
