<script src="/js/jquery.raty.min.js"></script>
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

VehicleIncentives = can.Model({
    findAll: 'GET /api/v2/vehicle-style/incentives',
    findOne: 'GET /api/v2/vehicle-incentive/find'
},{});

DeleteFavorite = can.Model({
    create: 'GET /api/user-favorite/delete-favorite?user_id={user_id}&favoritable_type={favoritable_type}&favoritable_id={favoritable_id}'
},{});

HomeControl = can.Control(
    {
        init: function()
        {
            $('#collapseMakes').collapse('show');
            $('.panel-title a[href="#collapseMakes"]').removeClass('collapsed');
            $('#collapseFilter').collapse('show');
            $('.panel-title a[href="#collapseFilter"]').removeClass('collapsed');
            this.IncentiveSearch();
            //Fire Review Modal on Open?
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
        },
        //Events
        '.pager li click': function(element)
        {
            if(element.hasClass('disabled'))
                return;
            currentPage = element.data('page');
            this.IncentiveSearch();
        },
        '.show-incentive click': function(element)
        {
            var self = this;
            var incentive_id = element.data('incentive_id');
            var IncentiveObject = {};
            IncentiveObject.id = incentive_id;
            VehicleIncentives.findOne(IncentiveObject, function(incentive)
            {
                self.BindIncentiveModel(incentive);
            });
        },
        '#btnFavorite click': function(element)
        {
            if(user_type == 'Nonmember')
            {
                $('.eid-type').val('FavoriteVehicle');
                $('#signInModal .login-message span').html('You must be signed in to favorite a vehicle.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
            element.button('loading');
            var myFavorite = new UserFavorite({
                user_id: user_id, 
                type: 'vehicle-style', 
                object_id: element.data('style_id')});

            myFavorite.save(function(json)
            {
                element.button('reset');
                element.addClass('hidden');
                $('#btnUnFavorite').removeClass('hidden');
            });
        },
        '#btnUnFavorite click': function(element)
        {
            element.button('loading');
            var myFavorite = new DeleteFavorite({
                user_id: user_id, 
                type: 'vehicle-style', 
                object_id: element.data('style_id')});

            myFavorite.save(function(json)
            {
                element.button('reset');
                element.addClass('hidden');
                $('#btnFavorite').removeClass('hidden');
            });
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
            ReviewObject.reviewable_id = style_id;
            ReviewObject.reviewable_type = 'vehicle-style';
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
        //Methods
        'IncentiveSearch': function()
        {
            var self = this;
            var SearchObject = {};
            SearchObject.id = style_id;
            SearchObject.page = currentPage;
            SearchObject.limit = 5;
            VehicleIncentives.findAll(SearchObject, function(incentives)
            {
                self.BindIncentives(incentives);
                self.BindIncentivesPagination(incentives);
            });
        },
        'BindIncentives': function(incentives)
        {
            $('#divIncentives').html(can.view('template_incentive',
            {
                incentives: incentives
            }));
        },
        'BindIncentivesPagination': function(incentives)
        {
            if (incentives.stats.total == 0) 
            {
                $('.content-bg.margin-top-20.incentives').hide();
            }

            var lastpage = incentives.stats.total % incentives.stats.take == 0 ? (incentives.stats.total / incentives.stats.take - 1) : Math.floor(incentives.stats.total / incentives.stats.take);

            if(incentives.stats.page == 0)
            {
                $('.previous').addClass('disabled');
            }
            else
            {
                $('.previous').removeClass('disabled');
                $('.previous').data('page', incentives.stats.page - 1);
            }

            if(incentives.stats.page == lastpage)
            {
                $('.next').addClass('disabled');
            }
            else
            {
                $('.next').removeClass('disabled');
                $('.next').data('page', Number(incentives.stats.page) + 1);
            }
        },
        'BindIncentiveModel': function(incentive)
        {
            var modal = $('#IncentiveModal');
            var pieces = incentive.expires_at.split(' ');
            var date_pieces = pieces[0].split('-');
            modal.find('.coupon-expire').html(date_pieces[1]+'-'+date_pieces[2]+'-'+date_pieces[0]);
            modal.find('.coupon-title').html(incentive.name);
            modal.find('.coupon-description').html(incentive.description);
            modal.find('.restrictions').html(incentive.restrictions);
            modal.modal('show');
        }
      });

    home_control = new HomeControl($('body'));

</script>