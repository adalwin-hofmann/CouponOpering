<script src="/js/owl.carousel.min.js"></script>
<script src="/js/jquery.raty.min.js"></script>
<script src="/js/jquery-scrollto/lib/jquery-scrollto.js"></script>
<script>
$(document).ready(function() {

  var sync1 = $("#sync1");
  var sync2 = $("#sync2");

  sync1.owlCarousel({
    singleItem: true,
    slideSpeed: 1000,
    navigation: false,
    pagination: false,
    afterAction: syncPosition,
    responsiveRefreshRate : 200,
  });

  sync2.owlCarousel({
    items : 4,
    itemsDesktop: [1199,5],
    itemsDesktopSmall: [979,5],
    itemsTablet: [768,5],
    itemsMobile: [479,3],
    pagination: false,
    responsiveRefreshRate : 100,
    navigation: true,
    navigationText : ['<span class="glyphicon glyphicon-chevron-left"></span>','<span class="glyphicon glyphicon-chevron-right"></span>'],
    afterInit : function(el){
      el.find(".owl-item").eq(0).addClass("synced");
    }
  });

  function syncPosition(el){
    var current = this.currentItem;
    $("#sync2")
      .find(".owl-item")
      .removeClass("synced")
      .eq(current)
      .addClass("synced")
    if($("#sync2").data("owlCarousel") !== undefined){
      center(current)
    }

  }

  $("#sync2").on("click", ".owl-item", function(e){
    e.preventDefault();
    var number = $(this).data("owlItem");
    sync1.trigger("owl.goTo",number);
  });

  function center(number){
    var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

    var num = number;
    var found = false;
    for(var i in sync2visible){
      if(num === sync2visible[i]){
        var found = true;
      }
    }

    if(found===false){
      if(num>sync2visible[sync2visible.length-1]){
        sync2.trigger("owl.goTo", num - sync2visible.length+2)
      }else{
        if(num - 1 === -1){
          num = 0;
        }
        sync2.trigger("owl.goTo", num);
      }
    } else if(num === sync2visible[sync2visible.length-1]){
      sync2.trigger("owl.goTo", sync2visible[1])
    } else if(num === sync2visible[0]){
      sync2.trigger("owl.goTo", num-1)
    }
  }

});
</script>

<script>
    LocationEntity = can.Model({
        findAll: 'GET /api/v2/location/get-entities?location_id={location_id}'
    },{});

    LocationReview = can.Model({
        findAll: 'GET /api/location/get-reviews?location_id={location_id}'
    },{});

    UsedVehicleMerchant = can.Model({
        findAll: 'GET /api/v2/vehicle-entity/get-by-merchant'
    },{});

    CategoryEntity = can.Model({
        findAll: 'GET /api/v2/entity/get-by-category'
    },{});

    HomeControl = can.Control(
    {
        init: function()
        {
            if(typeof ga !== 'undefined')
            {
                ga('send', 'event', 'user', 'location-view', encodeURIComponent(merchant_name)+':'+location_id, view_event_value);
            }
            initialLoad = 1;
            searchPage = 0;
            searchItems = 0;
            category_type = 'subcategory';

            hash = window.location.hash;
            hash && $('.merchant-nav a[href="' + hash + '"]').tab('show').addClass('active');
            if (hash != "") {
                //$('.merchant-nav .hidden-xs a[href!="' + hash + '"]').removeClass('btn-black').addClass('btn-white');
                $('.merchant-nav .merchant-menu a[href!="' + hash + '"]').removeClass('active');
            }
            
            //this.Search();
            //this.SearchReviews();
            var is_reviewed = 0;
            var writereview = master_control.getQueryVariable("writereview");
            if (writereview != '')
            {
                if(user_type == 'Nonmember')
                {
                    $('.eid-type').val('Review');
                    $('#signInModal .login-message span').html('You must be signed in to write a review.');
                    $('#signInModal .login-message').show();
                    $('#signInModal').modal();
                    return;
                }
                else if (is_reviewed == 1)
                {
                    $('#reviewErrorModal').modal();
                }
                else
                {
                    $('#star').raty({
                        score: function() {
                            return $(this).attr('data-score');
                        },
                        path: '/img',
                        half     : true,
                        size     : 30,
                        starHalf : 'star-half.png',
                        starOff  : 'star-off.png',
                        starOn   : 'star-on.png',
                        number   : 5
                    });
                    $('#reviewModal').modal();
                }
            }
            if (is_dealer == '1')
            {
                $('.radioCarType').prop('checked', false);
                $('.radioCarType[value=used]').prop('checked', true);
                $('#collapseFilter').collapse('show');
                $('.panel-title a[href="#collapseFilter"]').removeClass('collapsed');
                if ((hash == '#newCars') || (hash == '#usedCars') || (hash == '#autoServices'))
                {
                    $('.merchant-nav a[href="#offers"]').tab('show').addClass('active');
                    $('.dealer-menu a.btn.btn-green').removeClass('btn-green').addClass('btn-white');
                    $('.dealer-menu a.btn[href="' + hash +'"]').tab('show').removeClass('btn-white').addClass('btn-green');

                    if (new_car_leads == 0 && hash == '#newCars')
                    {
                        $('.dealer-menu a.btn[href="#usedCars"]').tab('show').removeClass('btn-white').addClass('btn-green');
                    }
                    if (used_car_leads == 0 && hash == '#usedCars')
                    {
                        $('.dealer-menu a.btn[href="#autoServices"]').tab('show').removeClass('btn-white').addClass('btn-green');
                    }
                    
                }
                if (make_ids != '')
                {
                    //this.SearchNew();
                } else {
                    $('.dealer-menu a.btn[href="#newCars"]').hide();
                }
            }

            var container = $('#new-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#new-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#used-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#used-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#container');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#containerRelated');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#containerRelated'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
        },
        //Events
        '#autoServicesTab .view-more click': function(element)
        {
            element.button('loading');
            searchPage++;
            initialLoad = 0;
            this.Search();
        },
        '#usedCarsTab .view-more click': function(element)
        {
            element.button('loading');
            usedPage++;
            this.SearchUsed();
        },
        '#newCarsTab .view-more click': function(element)
        {
            element.button('loading');
            newPage++;
            this.SearchNew();
        },
        '.btn-downloads click': function(event,element)
        {
            $('.mobile-menu .merchant-menu li').removeClass('active');
            
            $('.merchant-menu a').removeClass('active');
            $('.merchant-menu.hidden-xs a[href="#about"]').addClass('active');
            $('.mobile-menu .merchant-menu a[href="#about"]').parent().addClass('active');
            $('.footer').ScrollTo();

        },
        '.btn-favorite-merchant click': function(element)
        {
            if(element.hasClass('disabled'))
                return;
            if(user_type == "Nonmember")
            {
                $('.eid-type').val('Add to Favorites');
                $('#signInModal .login-message span').html('You must be signed in to add to favorites.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
            var myFav = new UserFavorite({user_id: user_id, type: 'location', object_id: location_id});
            myFav.save(function(fav)
            {
                element.effect( "highlight", {color: '#0E6C36'} );
                element.addClass('disabled');
                element.prop('disabled', true);
                element.find('.fav-text').html('My <br class="visible-lg">Favorite!');
            });
        },
        '.btn-get-save-today click': function(element)
        {
            var self = this;
            var offer_id = element.data('offer_id');
            if(user_type == 'User')
            {
                var myPrint = new UserPrint({user_id: user_id, offer_id: offer_id})
                myPrint.save(function(print)
                {
                    self.BindSaveToday(print);
                });
            }
            else
            {
                var myPrint = new NonmemberPrint({nonmember_id: user_id, offer_id: offer_id})
                myPrint.save(function(print)
                {
                    self.BindSaveToday(print);
                });
            }
        },
        '.btn-open-review click': function(element)
        {
            if(user_type == 'Nonmember')
            {
                $('.eid-type').val('Review');
                $('#signInModal .login-message span').html('You must be signed in to write a review.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
            else
            {
                $('#star').raty({
                    score: function() {
                        return $(this).attr('data-score');
                    },
                    path: '/img',
                    half     : true,
                    size     : 30,
                    starHalf : 'star-half.png',
                    starOff  : 'star-off.png',
                    starOn   : 'star-on.png',
                    number   : 5
                });
                $('#reviewModal').modal();
            }
        },
        '.btn-review-submit click': function(element)
        {
            var self = this;
            var review_text = $('#reviewText').val();
            var rating = $("#star").raty('score');
            $('.btn-review-submit').prop('disabled', true);
            if(user_type == 'Nonmember' || review_text == '')
                return;
            if($('#rules').prop('checked') == false)
            {
                $('#reviewMessages').hide();
                $('#reviewMessages').css('color', 'red');
                $('#reviewMessages').html('You must agree to the terms of use!');
                $('#reviewMessages').fadeIn(500, function()
                {
                    $('#reviewMessages').fadeOut(10000);
                });
                return;
            }
            var ReviewObject = new Object();
            ReviewObject.user_id = user_id;
            ReviewObject.reviewable_id = location_id;
            ReviewObject.reviewable_type = 'Location';
            ReviewObject.content = review_text;
            ReviewObject.rating = rating;
            var is_reviewed =1;
            $('.no-reviews').hide()
            var myReview = new UserReview(ReviewObject);
            myReview.save(function(review)
            {
                window.location.reload(false);
            });
        },
        '.btn-delete-review click': function(element)
        {
            var review_id = element.data('review_id');
            var myDelete = new ReviewDelete({review_id: review_id});
            myDelete.save(function(review)
            {
                element.parent().parent().remove();
                $('.open-review-area').show();
                var count = $('#reviewCount').html()
                $('#reviewCount').html(Number(count)-1);
                window.location.reload(false);
            });
        },
        '#sync2 .about-item click': function(element)
        {
            imgText = element.children('.img-text').clone();
            $('.merchant-img-text').html(imgText);
        },
        '.vote-up click': function(element)
        {
            var VoteObject = new Object();
            VoteObject.vote = 1;
            VoteObject.review_id = element.data('review_id');
            if(user_type == 'User')
            {
                VoteObject.user_id = user_id
                var myVote = new UserReviewVote(VoteObject);
            }
            else
            {
                VoteObject.nonmember_id = user_id
                var myVote = new NonmemberReviewVote(VoteObject);
            }
            myVote.save(function(vote)
            {
                element.addClass('active');
                element.parent().find('.vote-down').removeClass('active');
                $(".review[data-review_id='"+element.data('review_id')+"']").find('.total-ups').html(vote.upvotes);
                $(".review[data-review_id='"+element.data('review_id')+"']").find('.total-votes').html(vote.votes);
            });
        },
        '.vote-down click': function(element)
        {
            var VoteObject = new Object();
            VoteObject.vote = -1;
            VoteObject.review_id = element.data('review_id');
            if(user_type == 'User')
            {
                VoteObject.user_id = user_id
                var myVote = new UserReviewVote(VoteObject);
            }
            else
            {
                VoteObject.nonmember_id = user_id
                var myVote = new NonmemberReviewVote(VoteObject);
            }
            myVote.save(function(vote)
            {
                element.addClass('active');
                element.parent().find('.vote-up').removeClass('active');
                $(".review[data-review_id='"+element.data('review_id')+"']").find('.total-ups').html(vote.upvotes);
                $(".review[data-review_id='"+element.data('review_id')+"']").find('.total-votes').html(vote.votes);
            });
        },
        '.merchant-menu a click': function(element, event)
        {
            event.preventDefault();
            var hash = element.attr('href');
            location.hash = hash;
            //$(hash+'Tab').tab('show');

            //$('.mobile-menu .merchant-menu li').removeClass('active');
            
            $('.merchant-menu a').removeClass('active');
            $('.merchant-menu a[href='+hash+']').addClass('active');
            //$('.mobile-menu .merchant-menu a[href='+hash+']').parent().addClass('active');
            
            var container = $('#new-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#new-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#used-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#used-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#container');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#containerRelated');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#containerRelated'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
        },
        '.dealer-menu a click': function(element, event)
        {
            event.preventDefault();
            var hash = element.attr('href');
            location.hash = hash;

            $('.dealer-menu a').removeClass('btn-green').addClass('btn-white');
            $(element).removeClass('btn-white').addClass('btn-green');

            var container = $('#new-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#new-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#used-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#used-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#container');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });

            var container = $('#containerRelated');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#containerRelated'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
        },
        '.js-masonry .item a click': function(element, event)
        {
            var self = this;
            event.preventDefault();
            var item = element.parents('.item');
            var entity_id = element.parents('.item').find('.btn-get-coupon').data('entity_id');
            var my_img = element.parents('.item').find('.btn-get-coupon').find('img');
            var source = my_img.prop('src');
            my_img.prop('src', "http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif");
            if(user_type == 'User')
            {
                var myImpression = new UserImpression2({user_id: user_id, object_id: entity_id, type: 'entity'})
            }
            else
            {
                var myImpression = new NonmemberImpression2({nonmember_id: user_id, object_id: entity_id, type: 'entity'})
            }
            myImpression.save(function(impression)
            {
                $('.show-eid').val(impression.entity.id);
                /*if(impression.offer.is_dailydeal == 0)
                {
                    $('#couponModal').modal('show');
                    $('#signInRedirect').val(window.location.href)
                }
                else
                {
                    $('#saveTodayModal').modal('show');
                }*/
                my_img.prop('src', source);
                master_control.BindCoupon(impression);
            });
        },
        //Methods
        'Search': function()
        {
            var self = this;

            var EntityObject = new Object();
            EntityObject.limit = 12;
            EntityObject.page = searchPage;
            EntityObject.location_id = location_id;
            if(user_type == 'User')
                EntityObject.user_id = user_id;
            LocationEntity.findAll(EntityObject, function(entities)
            {
                self.BindEntities(entities);

            });
        },
        'SearchUsed': function()
        {
            var self = this;
            var SearchObject = {
                page: usedPage,
                merchant_id: merchant_id,
                limit: 12
            }
            UsedVehicleMerchant.findAll(SearchObject, function(vehicles)
            {
                self.BindUsed(vehicles);
            });
        },
        'SearchNew': function()
        {
            var self = this;
            var SearchObject = {
                page: newPage,
                make: make_ids,
                limit: 12
            }
            VehicleStyle.findAll(SearchObject, function(vehicles)
            {
                self.BindNew(vehicles);
            });
        },
        'SearchExpired': function()
        {
            var self = this;

            var RelatedObject = {
                limit: 3,
                page: 0,
                category_id: subcategory_id,
                type: 'coupon',
                latitude: geoip_latitude(),
                longitude: geoip_longitude(),
                state: geoip_region(),
                city: geoip_city(),
            };

            CategoryEntity.findAll(RelatedObject, function(entities) {
                self.BindRelated(entities);
            });

            var EntityObject = new Object();
            EntityObject.limit = 3;
            EntityObject.page = searchPage;
            EntityObject.location_id = location_id;
            EntityObject.expired = false;
            if(user_type == 'User')
                EntityObject.user_id = user_id;
            LocationEntity.findAll(EntityObject, function(entities)
            {
                self.BindExpired(entities);
            });
        },
        SearchMoreRelated: function()
        {
            var self = this;
            var RelatedObject = {
                limit: 3,
                page: 0,
                category_id: category_id,
                type: 'coupon',
                latitude: geoip_latitude(),
                longitude: geoip_longitude(),
                state: geoip_region(),
                city: geoip_city(),
            };

            CategoryEntity.findAll(RelatedObject, function(entities) {
                self.BindRelated(entities);
            });
        },
        'no-reviews catch': function()
        {
            if (is_reviewed==0){
                $('.no-reviews').show();
            }
            if (is_reviewed==1){
                $('.no-reviews').hide();
            }
        },
        'SearchReviews': function()
        {
            var self = this;
            var ReviewObject = new Object();
            ReviewObject.limit = 12;
            ReviewObject.page = 0;
            ReviewObject.location_id = location_id;
            LocationReview.findAll(ReviewObject, function(reviews)
            {
                for(var i=0; i<reviews.length; i++)
                {
                    var pieces = reviews[i].created_at.split(' ');
                    var date_pieces = pieces[0].split('-');
                    reviews[i].created_at = date_pieces[1]+'/'+date_pieces[2]+'/'+date_pieces[0];
                }
                self.BindReviews(reviews);
            });
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
        },
        'BindEntities': function(entities)
        {
            if (entities.stats.total == 1) {
                $('div.offer-count').html('<span class="offer-count">1</span><br>offer');
            }
            $('span.offer-count').html(entities.stats.total);
            if(initialLoad == 1)
            {
                $('.ajax-loader').hide();
                $('#container').html(can.view('template_entity_v2',
                {
                    entities : entities
                }));
            }
            else
            {
                $('.ajax-loader').hide();
                $('#container').append(can.view('template_entity_v2',
                {
                    entities: entities
                }));
            }
            if(entities.stats.total == 0)
            {
                $('.ajax-loader').hide();
                if ((special_merchant != 'soct') && (hash == '')) {
                    $('.merchant-nav .hidden-xs a[href="#offers"]').removeClass('btn-black').addClass('btn-white');
                    $('.merchant-nav .merchant-menu a[href="#offers"]').parent().removeClass('active');
                    $('.merchant-nav .hidden-xs a[href="#about"]').tab('show').addClass('btn-black').removeClass('btn-white');
                    $('.merchant-nav .merchant-menu a[href="#about"]').parent().addClass('active');
                }
                $('#container').addClass('expired');
                this.SearchExpired();
            }
            searchItems = searchItems + entities.stats.returned;
            
            if(entities.stats.total == searchItems)
            {
                $('#autoServicesTab .view-more').hide();
            }
            else
            {
                $('#autoServicesTab .view-more').show();
                $('#autoServicesTab .view-more').button('reset');
            }
            initialLoad = 0;
            var container = $('#container');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
            if (entities.stats.total == 0)
            {
                $('.no-offers').show();
            }
        },
        'BindExpired': function(entities)
        {
            $('.ajax-loader').hide();
            $('#container').html(can.view('template_entity',
            {
                entities : entities
            }));
            initialLoad = 0;
            var container = $('#container');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
        },
        'BindRelated': function(entities)
        {
            if((entities.stats.total < 3) && (category_type == 'subcategory'))
            {
                category_type = 'category';
                this.SearchMoreRelated();
                return;
            }
            $('#containerRelated').html(can.view('template_entity',
            {
                entities : entities
            }));
            initialLoad = 0;
            var container = $('#containerRelated');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#container'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
            });
        },
        'BindReviews': function(reviews)
        {
            $('#reviews').html(can.view('template_review',
            {
                reviews: reviews
            }));
        },
        'BindSaveToday': function(print)
        {
            $('#saveTodayModal .location-title').html(print.entity.merchant_name)
            $('#saveTodayModal .coupon-code').html(print.code);
            $('#saveTodayModal .logo').attr('src', print.entity.path);
            $('#saveTodayModal .coupon-title').html(print.offer.name);
            $('#saveTodayModal .coupon-description').html(print.offer.description);
            var pieces = print.offer.expires_at.split(' ');
            var date_pieces = pieces[0].split('-');
            $('#saveTodayModal .coupon-expire').html(date_pieces[1]+'-'+date_pieces[2]+'-'+date_pieces[0]);
            $('#saveTodayModal .btn-coupon-dislike').data('offer_id', print.offer.id);
            $('#saveTodayModal .btn-coupon-like').data('offer_id', print.offer.id);
            $('#saveTodayModal .btn-coupon-print').data('offer_id', print.offer.id);
            $('#saveTodayModal .btn-coupon-clip').data('offer_id', print.offer.id);
            $('#saveTodayModal .btn-coupon-share').data('offer_id', print.offer.id);
        },
        'BindUsed': function(vehicles)
        {
            if ((make_ids == '') && (vehicles.stats.returned == 0))
            {
                $('.dealer-menu').hide();
                $('.dealer-menu a.btn[href="#autoServices"]').tab('show');
                return;
            }
            if (vehicles.stats.returned == 0)
            {
                $('.dealer-menu a.btn[href="#usedCars"]').hide();
            }
            for(var i=0; i < vehicles.stats.returned; i++)
            {
                var images = vehicles[i].image_urls.split('|');
                if(images.length)
                    vehicles[i].display_image = images[0];
                else
                {
                    // TODO: Add placeholder car image.
                    vehicles[i].display_image = '';
                }
            }

            if(usedPage == 0)
            {
                $('#used-cars').html(can.view('template_grid_vehicle',
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#used-cars').append(can.view('template_grid_vehicle',
                {
                    vehicles: vehicles
                }));
            }
            var container = $('#used-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#used-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
                if(vehicles.stats.returned < vehicles.stats.take || (vehicles.stats.take * usedPage) >= vehicles.stats.total)
                {
                    $('#usedCarsTab .view-more').hide();
                    $('#usedCarsTab .view-more').button('reset');
                }
                else
                {
                    $('#usedCarsTab .view-more').show();    
                    $('#usedCarsTab .view-more').button('reset');
                }
            });
        },
        'BindNew': function(vehicles)
        {
            for(var i=0; i < vehicles.stats.returned; i++)
            {
                if(vehicles[i].display_image.length)
                    vehicles[i].display_image = vehicles[i].display_image[0];
                else
                {
                    // TODO: Add placeholder car image.
                    var image = vehicles[i].assets.length ? vehicles[i].assets[0] : ''
                    vehicles[i].display_image = image;
                }
            }
            if(newPage == 0)
            {
                $('#new-cars').html(can.view('template_new_car', 
                {
                    vehicles: vehicles
                }));
            }
            else
            {
                $('#new-cars').append(can.view('template_new_car', 
                {
                    vehicles: vehicles
                }));
            }

            var container = $('#new-cars');
            container.imagesLoaded( function() {
                var msnry = new Masonry( document.querySelector('#new-cars'), 
                {
                    itemSelector: '.item'
                });
                $('#container .item').removeClass('invisible');
                if(vehicles.stats.returned < vehicles.stats.take || (vehicles.stats.take * newPage) >= vehicles.stats.total)
                {
                    $('#newCarsTab .view-more').hide();
                    $('#newCarsTab .view-more').button('reset');
                }
                else
                {
                    $('#newCarsTab .view-more').show();    
                    $('#newCarsTab .view-more').button('reset');
                }
            });
        }
    });

    home_control = new HomeControl($('body'));
</script>

