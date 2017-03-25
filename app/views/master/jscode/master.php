<script>

/*********************************
 *
 * 2/14/2014 - Caleb
 * DEPRICATED!!! DO NOT USE!!!
 *
 * Code now exists in /public/js/jscode/master.js
 *
 **/


//steal('/js/myapp.js').then(function(){
    Subcategory = can.Model(
    {
        findAll: 'GET /api/category/get-by-parent-slug?slug={slug}'
    },{});

    PasswordMail = can.Model(
    {
        findOne: 'GET /email/passwordresetemail'
    },{});

    MasterControl = can.Control(
    {
    	init: function()
        {
            var self = this;
            if(typeof type !== 'undefined' && type == 'dailydeal')
            {
                this.BindFeatured('dailydeal');
            }
            if(typeof type !== 'undefined' && type == 'contest')
            {
                this.BindFeatured('contest');
            }
            this.BindFeatured('offer');
            showeid = this.getQueryVariable('showeid');
            var fireModal = this.getQueryVariable("modal");
            if (fireModal != '')
            {
                if(fireModal == 'sharemodal')
                {
                    var eid = this.getQueryVariable("eid")
                    Entity.findOne({entity_id: eid}, function(entity)
                    {
                        self.BindSharing(entity);
                    });
                }
                else
                {
                    $('#'+fireModal).modal('show')
                }
            }
            else
            {
                if(showeid)
                {
                    this.ShowEntity(showeid);
                }
            }

            $('.popover-info').popover().click(function(e) { 
                e.preventDefault(); 
                $(this).focus(); 
            });
        },
        //Events
        '#submitSweepstakes click': function(element)
        {
            var valid = true;
            var WinObject = new Object();
            $('#sweepstakesWinnerModal input').each(function(index)
            {
                if($(this).val() == '' && $(this).attr('id') != 'address2_sweepstakes' && $(this).attr('id') != 'magazinenum_sweepstakes')
                {
                    valid = false;
                }

                var aPieces = $(this).attr('id').split('_');
                var key = aPieces[0];
                WinObject[key] = $(this).val();
            });

            if($('#externalNumberInput').is(':visible') && $('#magazinenum_sweepstakes').val() == '')
            {
                valid = false;
            }

            if(!valid)
                return;
            WinObject.magazinenum = $('#magazinenum_sweepstakes').val();
            WinObject.user_id = user_id;
            WinObject.entity_id = $('#sweepstakesWinnerModal').data('entity_id');
            var mySweepstakes = new Sweepstakes(WinObject);
            mySweepstakes.save(function(application)
            {
                $('#sweepstakesWinnerModal').modal('hide');
                $('#sweepstakesWinnerThankYouModal').modal('show');
            });
        },
        '#sweepstakesSignUp click': function(element)
        {
            $('#sweepstakesModal').modal('hide');
            $('#signUpModal').modal('show');
        },
        '.searchbar input keyup': function( element, event ) 
        {
            if (event.which==13) {
                var query = $(".searchbar input").val();
                if(query==""){query = $(".inptSearch").val();}
                var searchTypeValue = $('.search-type button').data("value");
                window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue;
            }
        },
        '.searchbar button.search click': function( element, event ) 
        {
            var query = $(".searchbar input").val();
            if(query==""){query = $(".inptSearch").val();}
            var searchTypeValue = $('.search-type button').data("value");
            window.location = "/search?q="+encodeURIComponent(query)+"&t="+searchTypeValue;
        },
        '.search-type li a click': function(element)
        {
            var searchType = element.text();
            $('.search-type button').html(searchType);
            $('.search-type button').data("value",element.data("value"));
        },
        /*'.sorting li a click': function(element)
        {
            var sort = element.text();
            $('.sorting button').html(sort+' <span class="caret"></span>');
            $('.sorting button').data("value",element.data("value"));
        },*/
        '.update-location-modal input keyup': function(element, event)
        {
            var self = this;
            locationQuery = element.val();
            if ((locationQuery.length > 2) && (event.which!=13)) {
                // Needs to be Updated
                can.ajax({
                    url: '/api/zipcode/get-by-query?q='+encodeURIComponent(locationQuery),
                    dataType: 'json',
                    success: function(data) {
                        //can.$('.search-locations').html(data);
                        self.BindLocationModal(data);
                    }
                });
            } else {
                $('.update-location-modal .change-location-dropdown').hide();
            }

            if (event.which==13) {
                $('#locLat').val($('.update-location-modal .change-location-dropdown li').first().data('latitude'));
                $('#locLng').val($('.update-location-modal .change-location-dropdown li').first().data('longitude'));
                $('#locCity').val($('.update-location-modal .change-location-dropdown li').first().data('city'));
                $('#locState').val($('.update-location-modal .change-location-dropdown li').first().data('state'));
                $('#locUrl').val(window.location.pathname);
                $('#formChangeLocation').submit();
            }
        },
        '.navlocation keyup': function(element, event)
        {
            var self = this;
            locationQuery = element.val();
            if ((locationQuery.length > 2) && (event.which!=13)) {
                // Needs to be Updated
                can.ajax({
                    url: '/api/zipcode/get-by-query?q='+encodeURIComponent(locationQuery),
                    dataType: 'json',
                    success: function(data) {
                        //can.$('.search-locations').html(data);
                        self.BindSearchLocations(data);
                    }
                });
            } else {
                $('.search-locations').hide();
                $('.static-locations').show();
            }
            $('.navlocation').not(this).val(locationQuery);

            if (event.which==13) {
                $('#locLat').val($('.search-locations li').first().data('latitude'));
                $('#locLng').val($('.search-locations li').first().data('longitude'));
                $('#locCity').val($('.search-locations li').first().data('city'));
                $('#locState').val($('.search-locations li').first().data('state'));
                $('#locUrl').val(window.location.pathname);
                $('#formChangeLocation').submit();
            }
        },
        '.other-city click': function(element, event)
        {
            $('#locLat').val(element.data('latitude'));
            $('#locLng').val(element.data('longitude'));
            $('#locCity').val(element.data('city'));
            $('#locState').val(element.data('state'));
            $('#locUrl').val(window.location.pathname);
            $('#formChangeLocation').submit();
        },
        '.current-city click': function(element, event)
        {
            console.log('current-city clicked!');
            $('#locLat').val(loc.latitude);
            $('#locLng').val(loc.longitude);
            $('#locCity').val(loc.city_name);
            $('#locState').val(loc.region_name);
            $('#locUrl').val(window.location.pathname);
            $('#formChangeLocation').submit();
        },
        '.saved-city .change-city click': function(element, event)
        {
            $('#locLat').val(element.data('latitude'));
            $('#locLng').val(element.data('longitude'));
            $('#locCity').val(element.data('city'));
            $('#locState').val(element.data('state'));
            $('#locUrl').val(window.location.pathname);
            $('#formChangeLocation').submit();
        },
        '.saved-city .remove-saved-city click': function(element)
        {
            var myDelete = new DeleteSavedLocation({user_id: user_id, location_id: element.data('location_id')});
            myDelete.save(function(locations)
            {
                if(locations.data.length != 0)
                {
                    $('.saved-location-area').html(can.view('template_saved_location', 
                    {
                        locations: locations.data
                    }));
                    for (var i = 0; i < locations.length; i++) {
                        if ((locations[i]['latitude'] == geoip_latitude()) && (locations[i]['longitude'] == geoip_longitude()))
                        {
                            $('.current-city .glyphicon-heart').removeClass('saved');
                        }
                    }
                }
                else
                {
                    $('.saved-location-area').html('<li class="dropdown-disclaimer">You have no saved locations.</li>');
                }
            });
        },
        '.btn-get-coupon click': function(element)
        {
            var self = this;
            var entity_id = element.data('entity_id');
            var my_img = element.find('img');
            var source = my_img.prop('src');
            my_img.prop('src', "http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif");
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
                $('.show-eid').val(impression.entity.id);
                if(impression.offer.is_dailydeal == 0)
                {
                    $('#couponModal').modal('show');
                    $('#signInRedirect').val(window.location.href)
                }
                else
                {
                    $('#saveTodayModal').modal('show');   
                }
                my_img.prop('src', source);
                self.BindCoupon(impression);
            });
        },
        '.btn-coupon-clip click': function(element)
        {
            if(element.find('.coupon-save-text').html() == 'Saved!' || element.prop('disabled' == true))
            {
                return;
            }
            if(user_type == 'Nonmember')
            {
                $('#couponModal').modal('hide');
                $('#saveTodayModal').modal('hide');
                $('#signInModal .login-message span').html('You must be signed in to clip an offer.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
            var myClip = new UserClip({user_id: user_id, offer_id: element.data('offer_id')});
            myClip.save(function(clip)
            {
                element.effect( "highlight", {color: '#0E6C36'} );
                element.find('.coupon-save-text').html('Saved!');
                $('[data-offer_id="'+element.data('offer_id')+'"].btn-save-coupon .save-coupon-text').html('Saved!');
            });
        },
        '.btn-save-coupon click': function(element)
        {
            if(element.find('.save-coupon-text').html() == 'Saved!' || element.prop('disabled' == true))
            {
                return;
            }
            if(user_type == 'Nonmember')
            {
                $('#signInModal .login-message span').html('You must be signed in to save an offer.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
            var myClip = new UserClip({user_id: user_id, offer_id: element.data('offer_id')});
            myClip.save(function(clip)
            {
                element.effect( "highlight", {color: '#0E6C36'} );
                element.find('.save-coupon-text').html('Saved!');
            });
        },
        '.btn-get-contest click': function(element)
        {
            var self = this;
            var entity_id = element.data('entity_id');
            if(user_type == 'Nonmember')
            {
                $('#signInModal .login-message span').html('You must be signed in to enter a contest.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal();
                return;
            }
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
                if(impression.entity.secondary_type == 'external' || impression.entity.secondary_type == 'internal')
                {
                    $('#sweepstakesModal').modal('show');
                    self.BindSweepstakes(impression);
                }
                else
                {
                    $('#contestModal').modal('show');
                    self.BindContest(impression);
                }
                
            });
            
            /*if(element.find('.save-Contest-text').html() == 'Submitted!' || element.prop('disabled' == true))
            {
                return;
            }*/
            /*var myEntry = new ContestApplication({user_id: user_id, contest_id: element.data('contest_id')});
            myEntry.save(function(clip)
            {
                element.effect( "highlight", {color: '#FF1500'} );
                element.find('.save-Contest-text').html('Submitted!');
            });*/
        },
        '.btn-enter-contest click': function(element)
        {
            if ($('#contestEntryEmail').val() == '')
            {
                $('#contestEntryEmail').attr("placeholder", 'You must enter an email.');
                $('#contestEntryEmail').parent().addClass('has-error');
            }
            if ($('#contestEntryZip').val() == '')
            {
                $('#contestEntryZip').attr("placeholder", 'You must enter a zip code.');
                $('#contestEntryZip').parent().addClass('has-error');
            }
            if (!$('#contestEntryRules').is(':checked'))
            {
                $('#contestEntryRules').parent().parent().parent().addClass('has-error');
                $('#contestEntryRules').next('strong.warning').show();
            }
            if (($('#contestEntryEmail').val() != '') && ($('#contestEntryZip').val() != '') && ($('#contestEntryRules').is(':checked')))
            {
                var myEntry = new ContestApplication({user_id: user_id, contest_id: element.data('contest_id'), zip: $('#contestEntryZip').val(), email: $('#contestEntryEmail').val() });
                myEntry.save(function(clip)
                {
                    $('#contestModal').modal('hide');
                    $('#contestThanksModal').modal('show');
                });
            }
        },
        '.btn-coupon-share click': function(element)
        {
            if(user_type == 'Nonmember')
            {
                $('#couponModal').modal('hide');
                $('#saveTodayModal').modal('hide');
                $('#signInModal .login-message span').html('You must be signed in to share.');
                $('#signInModal .login-message').show();
                $('#signInModal').modal('show');
                return;
            }
            var self = this;
            Entity.findOne({entity_id: element.data('entity_id')}, function(entity)
            {
                self.BindSharing(entity);
            });
        },
        '.btn-email-share click': function(element)
        {
            element.button('loading');
            if($('.share-email-terms').prop('checked') == false)
            {
                $('.email-share-message').hide();
                $('.email-share-message').css('color', 'red');
                $('.email-share-message').html('You must agree to the terms!');
                $('.email-share-message').fadeIn(500);
                element.button('reset');
                return;
            }
            var message = $('.email-share-text').val();
            message = message == '' ? 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.' : message;
            var name = $('#shareName').val();
            var shareEmail = $('#shareEmail').val();
            var shareToEmails  = $('#shareToEmails').val();
            if(shareToEmails == '')
            {
                $('.email-share-message').hide();
                $('.email-share-message').css('color', 'red');
                $('.email-share-message').html('You must share with at least one email!');
                $('.email-share-message').fadeIn(500);
                element.button('reset');
                return;
            }
            var myShare = new UserShare({user_id: user_id, shareable_id: element.data('entity_id'), shareable_type: 'entity', type: 'email', sharer_name: name, message: message, emails: shareToEmails, from_email: shareEmail});
            myShare.save(function(share)
            {
                $('.email-share-message').hide();
                $('.email-share-message').css('color', 'green');
                $('.email-share-message').html('Thanks for sharing!')

                $('.email-share-message').fadeIn(500, function(){
                    $('.email-share-message').fadeOut(10000);
                });
                $('.facebook-share-text').val('');
                $('#shareName').val('');
                $('#shareEmail').val('');
                $('#shareToEmails').val('');
                element.button('reset');
            });
        },
        '.btn-facebook-share click': function(element)
        {
            element.button('loading');
            if($('.share-facebook-terms').prop('checked') == false)
            {
                $('.facebook-share-message').hide();
                $('.facebook-share-message').css('color', 'red');
                $('.facebook-share-message').html('You must agree to the terms!');
                $('.facebook-share-message').fadeIn(500);
                element.button('reset');
                return;
            }
            var message = $('.facebook-share-text').val();
            message = message == '' ? 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.' : message;
            
            FB.login(function(response)
            {
                Entity.findOne({entity_id: element.data('entity_id'), includes: 'entitiable'}, function(entity)
                {
                    FB.api('/me/feed', 
                        'post', 
                        {picture: entity.path,
                        link: '<?php echo URL::to("/coupons"); ?>/'+entity.category_slug+'/'+entity.subcategory_slug+'/'+entity.merchant_slug+'/'+entity.location_id+'?showeid='+entity.id,
                        message: message,
                        name: entity.name,
                        caption: entity.entitiable.description.replace(/(<([^>]+)>)/ig,"")}, 
                        function(){
                            $('.facebook-share-message').hide();
                            $('.facebook-share-message').css('color', 'green');
                            $('.facebook-share-message').html('Thanks for sharing!');
                            $('.facebook-share-message').fadeIn(500, function(){
                                $('.facebook-share-message').fadeOut(10000);
                            });
                            $('.facebook-share-text').val('');
                            var myShare = new UserShare({user_id: user_id, shareable_id: element.data('entity_id'), shareable_type: 'entity', type: 'facebook'});
                            myShare.save();
                            element.button('reset');
                        }
                    );
                });
            }, {scope: 'publish_actions'});
        },
        '.btn-coupon-redeem click': function(element)
        {
            var self = this;
            if(element.hasClass('disabled'))
            {
                return;
            }
            if(element.hasClass('external-print'))
            {
                return;
            }
            if(user_type == 'User')
            {
                var myRedeem = new UserRedeem({user_id: user_id, entity_id: element.data('entity_id')});
            }
            else
            {
                var myRedeem = new NonmemberRedeem({nonmember_id: user_id, entity_id: element.data('entity_id')});
            }
            myRedeem.save(function(redeem)
            {
                var parent = element.parent().parent().parent().parent().parent();
                parent.find('.coupon-redemption-message').html('Successfully Redeemed!');
                parent.find('.coupon-redemption-message').addClass('alert alert-success');
                parent.find('.coupon-redemption-message').parent().parent().parent().parent().parent().parent().addClass('redeemed');
                if(redeem.can_print == 0)
                {
                    element.prop('disabled', true);
                    element.addClass('disabled');
                }
            });
        },
        '.btn-default btn-block click': function(element)
        {
            if(element.find('.save-Contest-text').html() == 'Submitted!' || element.prop('disabled' == true))
            {
                return;
            }
            if(user_type == "Nonmember")
            {
                $('#signInModal .login-message span').html('You must be signed in to save a location.');
                $('#signInModal .login-message').show()
                $('#signInModal').modal();
                return;
            }
            var myEntry = new ContestApplication({user_id: user_id, contest_id: element.data('contest_id')});
            myEntry.save(function(clip)
            {
                element.effect( "highlight", {color: '#FF1500'} );
                element.find('.save-Contest-text').html('Submitted!');
            });
        },
        '.btn-coupon-like click': function(element)
        {
            var parent = element.parent().parent();
            if(element.hasClass('review-on'))
                return;
            if(user_type == 'User')
            {
                var myReview = new UserReview({user_id: user_id, reviewable_type: 'Offer', reviewable_id: element.data('offer_id'), content: '1'});
            }
            else
            {
                var myReview = new NonmemberReview({nonmember_id: user_id, reviewable_type: 'Offer', reviewable_id: element.data('offer_id'), content: '1'});
            }
            if(parent.find('.btn-coupon-dislike').hasClass('review-on'))
            {
                if(user_type == 'User')
                {
                    UserReviewDelete.findOne({user_id: user_id, reviewable_id: element.data('offer_id'), reviewable_type: 'Offer'}, function(review)
                    {
                        myReview.save(function(review)
                        {
                            var dis_current = parent.find('.dislikes-count').html();
                            parent.find('.dislikes-count').html(Number(dis_current)-1);
                            parent.find('.btn-coupon-dislike').removeClass('review-on');
                            var current = parent.find('.likes-count').html();
                            parent.find('.likes-count').html(Number(current)+1);
                            element.addClass('review-on');
                        });
                    });
                }
                else
                {
                    NonmemberReviewDelete.findOne({nonmember_id: user_id, reviewable_id: element.data('offer_id'), reviewable_type: 'Offer'}, function(review)
                    {
                        myReview.save(function(review)
                        {
                            var dis_current = parent.find('.dislikes-count').html();
                            parent.find('.dislikes-count').html(Number(dis_current)-1);
                            parent.find('.btn-coupon-dislike').removeClass('review-on');
                            var current = parent.find('.likes-count').html();
                            parent.find('.likes-count').html(Number(current)+1);
                            element.addClass('review-on');
                        });
                    });
                }
            }
            else
            {
                if(user_type == 'User')
                {
                    myReview.save(function(review)
                    {
                        var current = parent.find('.likes-count').html();
                        parent.find('.likes-count').html(Number(current)+1);
                        element.addClass('review-on');
                    });
                }
                else
                {
                    myReview.save(function(review)
                    {
                        var current = parent.find('.likes-count').html();
                        parent.find('.likes-count').html(Number(current)+1);
                        element.addClass('review-on');
                    });
                }
            }
        },
        '.btn-coupon-dislike click': function(element)
        {
            var parent = element.parent().parent();
            if(element.hasClass('review-on'))
                return;
            if(user_type == 'User')
            {
                var myReview = new UserReview({user_id: user_id, reviewable_type: 'Offer', reviewable_id: element.data('offer_id'), content: '-1'});
            }
            else
            {
                var myReview = new NonmemberReview({nonmember_id: user_id, reviewable_type: 'Offer', reviewable_id: element.data('offer_id'), content: '-1'});
            }
            if(parent.find('.btn-coupon-like').hasClass('review-on'))
            {
                if(user_type == 'User')
                {
                    UserReviewDelete.findOne({user_id: user_id, reviewable_id: element.data('offer_id'), reviewable_type: 'Offer'}, function(review)
                    {
                        myReview.save(function(review)
                        {
                            var likes_current = parent.find('.likes-count').html();
                            parent.find('.likes-count').html(Number(likes_current)-1)
                            parent.find('.btn-coupon-like').removeClass('review-on');
                            var current = parent.find('.dislikes-count').html();
                            parent.find('.dislikes-count').html(Number(current)+1);
                            element.addClass('review-on');
                        });
                    });
                }
                else
                {
                    NonmemberReviewDelete.findOne({nonmember_id: user_id, reviewable_id: element.data('offer_id'), reviewable_type: 'Offer'}, function(review)
                    {
                        myReview.save(function(review)
                        {
                            var likes_current = parent.find('.likes-count').html();
                            parent.find('.likes-count').html(Number(likes_current)-1)
                            parent.find('.btn-coupon-like').removeClass('review-on');
                            var current = parent.find('.dislikes-count').html();
                            parent.find('.dislikes-count').html(Number(current)+1);
                            element.addClass('review-on');
                        });
                    });
                }
            }
            else
            {
                if(user_type == 'User')
                {
                    myReview.save(function(review)
                    {
                        var current = parent.find('.dislikes-count').html();
                        parent.find('.dislikes-count').html(Number(current)+1);
                        element.addClass('review-on');
                    });
                }
                else
                {
                    myReview.save(function(review)
                    {
                        var current = parent.find('.dislikes-count').html();
                        parent.find('.dislikes-count').html(Number(current)+1);
                        element.addClass('review-on');
                    });
                }
            }
        },
        '.explore-expand click': function(element)
        {
            var self = this;
            var slug = element.data('parent_slug')
            Subcategory.findAll({slug: slug}, function(categories)
            {
                var my_ul = element.parent().parent();
                my_ul.html(can.view('template_explore_subcategory',
                {
                    categories: categories,
                    parent_slug: slug
                }));
                my_ul.append('<li><button class="btn-link explore-collapse" data-parent_slug="'+slug+'">See Less...</button></li>');
            });
        },
        '.explore-collapse click': function(element)
        {
            var self = this;
            var slug = element.data('parent_slug')
            var my_ul = element.parent().parent();
            my_ul.find('li').each(function(index)
            {
                if(index > 4)
                    $(this).remove();
            });
            my_ul.append('<li><button class="btn-link explore-expand" data-parent_slug="'+slug+'">See More...</button></li>');
        },
        '.btn-coupon-print click': function(element)
        {
            var self = this;
            if(element.hasClass('disabled'))
            {
                return;
            }
            if(element.hasClass('external-print'))
            {
                return;                                 
            } 
            if(user_type == 'User')
            {
                var myPrint = new UserPrint({user_id: user_id, entity_id: element.data('entity_id'), offer_rand: element.data('offer_rand')});
            }
            else
            {
                var myPrint = new NonmemberPrint({nonmember_id: user_id, entity_id: element.data('entity_id'), offer_rand: element.data('offer_rand')});   
            }
            myPrint.save(function(print)
            {
                if(print.can_print == 0)
                {
                    element.prop('disabled', true);
                    element.addClass('disabled');
                }
                var content = element.parent().parent().parent().find('.printable');
                self.PrintElement(content);
                if(trackable)
                {
                    /*mixpanel.track('Offer Print', {
                        'Environment': "<?php echo App::environment(); ?>",
                        'LocationId': element.data('location_id'),
                        'MerchantId': element.data('merchant_id'),
                        'OfferId': element.data('offer_id'),
                        'MerchantName': element.data('merchant_name')
                    });*/
                }
            });
        },
        '#forgotPasswordModal .btn-green click': function (element, event)
        {
            var self = this;
            email = $('#forgotPasswordModal #signInEmail').val();
            if (email != '') {
                var UserObject = new Object;
                UserObject['where|email'] = email;
                User.findAll(UserObject, function (user) {
                    if (user.length > 0)
                    {
                        //Success
                        $('#forgotPasswordThankYouModal .user-email').html(email);
                        $('#forgotPasswordModal').modal('hide');
                        $('#forgotPasswordThankYouModal').modal('show');

                        var PasswordObject = new Object;
                        PasswordObject.email = email;
                        PasswordObject.name = user['0'].name;
                        PasswordMail.findOne(PasswordObject, function(password) {

                        });

                    } else {
                        $('#forgotPasswordModal .no-email-alert').show();
                        $('#forgotPasswordModal .email-group').addClass('has-error');
                    }
                });
            } else {
                $('#forgotPasswordModal .no-email-alert-entered').show();
                $('#forgotPasswordModal .email-group').addClass('has-error');
            }
            
        },
        '.current-city click': function(element)
        {
            if(user_type == "Nonmember")
            {
                $('#signInModal .login-message span').html('You must be signed in to save a location.');
                $('#signInModal .login-message').show()
                $('#signInModal').modal();
                return;
            }
            
            var myLocation = new SavedLocation({user_id: user_id});
            myLocation.save(function(locations)
            {
                if(locations.data.length != 0)
                {
                    $('.saved-location-area').html(can.view('template_saved_location',
                    {
                        locations: locations.data
                    }));
                    $('.current-city .glyphicon-heart').addClass('saved');
                }
                else
                {
                    $('.saved-location-area').html('<li class="dropdown-disclaimer">You have no saved locations.</li>');
                }
            });
        },
        '#locationMenu click': function(element)
        {
            if(element.parent().hasClass('open'))
                return;
            this.SearchNearbyDropdown();
        },
        '#btnChangeLocation click': function(element)
        {
            this.SearchNearbyModal();
        },
        //Methods
        'ValidateSignup': function()
        {
            $('#signUpButton').button('loading');
            var SignupObject = new Object();
            SignupObject.first_name = $('#signUpFirstName').val();
            SignupObject.password = $('#signUpPassword').val();
            SignupObject.password_confirmation = $('#signUpPasswordConfirm').val();
            SignupObject.email = $('#signUpEmail').val();
            SignupObject.zipcode = $('#signUpLastZip').val();
            var mySignup = new ValidSignup(SignupObject);
            mySignup.save(function(response)
            {
                $('#signUpModal .form-group').removeClass('has-error');
                $('.signup-message').html('');
                if(typeof response.data.valid === 'undefined')
                {
                    if(typeof response.data.email !== 'undefined')
                    {
                        $('#signUpEmail').parent().addClass('has-error');
                        $('#email_message').html(response.data.email);
                    }
                    if(typeof response.data.first_name !== 'undefined')
                    {
                        $('#signUpFirstName').parent().addClass('has-error');
                        $('#first_name_message').html(response.data.first_name);
                    }
                    if(typeof response.data.password !== 'undefined')
                    {
                        $('#signUpPassword').parent().addClass('has-error');
                        $('#signUpPasswordConfirm').parent().addClass('has-error');
                        $('#password_message').html(response.data.password);
                    }
                    if(typeof response.data.zipcode !== 'undefined')
                    {
                        $('#signUpLastZip').parent().addClass('has-error');
                        $('#zipcode_message').html(response.data.zipcode);
                    }
                    $('#signUpButton').button('reset');
                    return false;
                }
                if($('#signUpTerms').prop('checked') == false)
                {
                    $('#terms_message').html('You must agree to the terms.');
                    $('#signUpTerms').parent().parent().parent().addClass('has-error');
                    $('#signUpButton').button('reset');
                    return false;
                }
                $('#signUpButton').button('reset');
                $('#signUpForm').attr('onsubmit', function(){return true;});
                $('#signUpForm').submit();
            });
            return false;
        },
        'SearchNearbyDropdown' : function()
        {
            var self = this;
            can.ajax({
                url: '/api/zipcode/get-nearby?limit=3',
                dataType: 'json',
                success: function(data) {
                    self.BindNearbyDropdown(data);
                }
            });
            SavedLocation.findAll({user_id: user_id}, function(locations)
            {
                if(locations.length != 0)
                {
                    $('.saved-location-area').html(can.view('template_saved_location',
                    {
                        locations: locations
                    }));
                    for (var i = 0; i < locations.length; i++) {
                        if ((locations[i]['latitude'] == geoip_latitude()) && (locations[i]['longitude'] == geoip_longitude()))
                        {
                            $('.current-city .glyphicon-heart').addClass('saved');
                        }
                    }
                }
                else
                {
                    $('.saved-location-area').html('<li class="dropdown-disclaimer">You have no saved locations.</li>');
                }
            });
        },
        'SearchNearbyModal' : function()
        {
            var self = this;
            can.ajax({
                url: '/api/zipcode/get-nearby?limit=9',
                dataType: 'json',
                success: function(data) {
                    self.BindNearbyModal(data);
                }
            });
        },
        'BindSearchLocations' : function(searchLocations)
        {
            $('.search-locations').html(can.view('template_search_location',
            {
                searchLocations : searchLocations
            }));
            if (searchLocations.stats.returned != 0)
            {
                $('.search-locations').show();
                $('.static-locations').hide();
            } else {
                $('.search-locations').hide();
                $('.static-locations').show();
            }
        },
        'BindNearbyDropdown' : function(nearbyDropdown)
        {
            $('.nearbyLocations').html(can.view('template_nearby_dropdown',
            {
                nearbyDropdown : nearbyDropdown
            }));
        },
        'BindNearbyModal' : function(nearbyModal)
        {
            $('.suggested-location-modal .row').html(can.view('template_nearby_modal',
            {
                nearbyModal : nearbyModal
            }));
        },
        'BindLocationModal' : function(searchLocations)
        {
            $('.update-location-modal .change-location-dropdown').html(can.view('template_location_modal',
            {
                searchLocations : searchLocations
            }));
            if (searchLocations.stats.returned != 0)
            {
                $('.update-location-modal .change-location-dropdown').show();
            } else {
                $('.update-location-modal .change-location-dropdown').hide();
            }
        },
        'PrintElement': function(elem) 
        {
            var printSection = $("#printSection");
            $('html').addClass("print-modal");
            $("#printSection").html($(".modal.in .printable").clone());
            window.print();
        },
        'BindFeatured': function(featured_type)
        {
            var FeaturedObject = new Object();
            FeaturedObject.user_id = user_id;
            FeaturedObject.user_type = user_type;
            FeaturedObject.latitude = geoip_latitude();
            FeaturedObject.longitude = geoip_longitude();
            FeaturedObject.state = geoip_region();
            switch(featured_type)
            {
                case 'offer':
                    FeaturedObject.type = 'offer';
                    FeaturedEntity.findAll(FeaturedObject, function(entities)
                    {
                        var j=1;
                        var banner = false;
                        for(var i=0; i<entities.length; i++)
                        {
                            if(entities[i].entitiable_type != 'filler' && banner == false)
                            {
                                if($('.banner-offer').length && type == 'coupon')
                                {
                                    $('.banner-offer').html(can.view('template_banner_offer',
                                    {
                                        bannerOffer : entities[i]
                                    }));
                                }
                                banner = true;
                            }
                            else
                            {
                                $('.featured-coupon'+(j++)).html(can.view('template_single_entity',
                                {
                                    entities : entities[i]
                                }));
                            }
                        }
                    });
                    break;
                case 'dailydeal':
                    FeaturedObject.type = 'dailydeal';
                    FeaturedEntity.findAll(FeaturedObject, function(entities)
                    {
                        var j=1;
                        var banner = false;
                        for(var i=0; i<entities.length; i++)
                        {
                            if(entities[i].entitiable_type != 'filler' && banner == false)
                            {
                                if($('.banner-offer').length && type == 'dailydeal')
                                {
                                    $('.banner-offer').html(can.view('template_banner_offer',
                                    {
                                        bannerOffer : entities[i]
                                    }));
                                }
                                banner = true;
                            }
                            else
                            {
                                $('.featured-daily-deal'+(j++)).html(can.view('template_single_entity',
                                {
                                    entities : entities[i]
                                }));
                            }
                        }
                    });
                    break;
                case 'contest':
                    FeaturedObject.type = 'contest';
                    FeaturedEntity.findAll(FeaturedObject, function(entities)
                    {
                        var j=1;
                        var banner = false;
                        /*for(var i=0; i<entities.length && j < 3; i++)
                        {
                            $('.featured-contest'+(j++)).html(can.view('template_single_entity',
                            {
                                entities : entities[i]
                            }));
                        }*/
                        for(var i=0; i<entities.length; i++)
                        {
                            if(entities[i].entitiable_type != 'filler' && banner == false)
                            {
                                if($('.banner-offer').length && type == 'contest')
                                {
                                    $('.banner-offer').html(can.view('template_banner_offer',
                                    {
                                        bannerOffer : entities[i]
                                    }));
                                }
                                banner = true;
                            }
                            else
                            {
                                $('.featured-contest'+(j++)).html(can.view('template_single_entity',
                                {
                                    entities : entities[i]
                                }));
                            }
                        }
                    });
                    break;
                }
        },
        'BindFeaturedCoupon1' : function(data)
        {
        	$('.featured-coupon1').html(can.view('template_featured_coupon1',
            {
                featuredCoupon1 : [
        		{ "id":"4" , "entitiable_type":"Ad" , "is_dailydeal":"" , "name":"Free Grocery Coupons", "merchant_name": "", "path": "http://s3.amazonaws.com/saveoneverything_assets/assets/images/save-banners/groceries_ad.jpg", "link":"/groceries" }
                ]
            }));
        },
        'BindFeaturedCoupon2' : function(data)
        {
        	$('.featured-coupon2').html(can.view('template_featured_coupon2',
            {
                featuredCoupon2 : [
                { "id":"5" , "entitiable_type":"Offer" , "is_dailydeal":"0" , "name":"$1.00 OFF", "merchant_name": "Subway", "path": "http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/logos/12978518a78d60df47.jpg", "link":"" }
                ]
            }));
        },
        'BindFeaturedDailyDeal1' : function(data)
        {
        	$('.featured-daily-deal1').html(can.view('template_featured_daily_deal1',
            {
                featuredDailyDeal1 : [
                { "id":"5" , "entitiable_type":"Offer" , "is_dailydeal":"1" , "name":"$1.00 OFF", "merchant_name": "Massage", "path": "http://s3.amazonaws.com/saveoneverything_assets/images/1373307183-Spa&Massage4_72dpi.jpg", "link":"" }
                ]
            }));
        },
        'BindFeaturedDailyDeal2' : function(data)
        {
        	$('.featured-daily-deal2').html(can.view('template_featured_daily_deal2',
            {
                featuredDailyDeal2 : [
        		{ "id":"4" , "entitiable_type":"Ad" , "is_dailydeal":"" , "name":"Free Grocery Coupons", "merchant_name": "", "path": "http://s3.amazonaws.com/saveoneverything_assets/assets/images/save-banners/groceries_ad.jpg", "link":"/groceries" }
                ]
            }));
        },
        'BindFeaturedContest1' : function(data)
        {
        	$('.featured-contest1').html(can.view('template_featured_contest1',
            {
                featuredContest1 : [
                { "id":"4" , "entitiable_type":"Contest" , "is_dailydeal":"" , "name":"Win Free Gas", "merchant_name": "", "path": "http://s3.amazonaws.com/saveoneverything_assets/images/1375363802-Gas_220x220.jpg", "link":"" },
                ]
            }));
        },
        'BindFeaturedContest2' : function(data)
        {
        	$('.featured-contest2').html(can.view('template_featured_contest2',
            {
                featuredContest2 : [
        		{ "id":"4" , "entitiable_type":"Ad" , "is_dailydeal":"" , "name":"Free Grocery Coupons", "merchant_name": "", "path": "http://s3.amazonaws.com/saveoneverything_assets/assets/images/save-banners/groceries_ad.jpg", "link":"/groceries" }
                ]
            }));
        },
        'BindCoupon': function(impression)
        {
            var pieces = impression.offer.expires_at.split(' ');
            var date_pieces = pieces[0].split('-');
            if(impression.entity.is_dailydeal == 1)
            {
                var modal = $('#saveTodayModal');
                modal.find('.coupon-expire').html(date_pieces[1]+'-'+date_pieces[2]+'-'+date_pieces[0]);
                modal.find('.daily-merchant-about').html(impression.entity.merchant_about_truncated);
                modal.find('.daily-merchant-more').attr('href', '/coupons/'+impression.entity.category_slug+'/'+impression.entity.subcategory_slug+'/'+impression.entity.merchant_slug+'/'+impression.entity.location_id+'#about');
            }
            else
            {
                var modal = $('#couponModal');
                modal.find('.coupon-expire').html(date_pieces[1]+'-'+date_pieces[2]+'-'+date_pieces[0]);
            }
            
            modal.find('.btn-view-locations').attr('href', '/directions/'+impression.entity.merchant_slug+'/'+impression.entity.merchant_id);
            modal.find('.btn-view-business').attr('href', '/coupons/'+impression.entity.category_slug+'/'+impression.entity.subcategory_slug+'/'+impression.entity.merchant_slug+'/'+impression.entity.location_id);
            modal.find('.location-title').html(impression.entity.merchant_name)
            modal.find('.coupon-code').html(impression.code);
            modal.find('.coupon-path').attr('src', impression.entity.path);
            modal.find('.coupon-secondary-image').attr('src', impression.offer.secondary_image);
            if(impression.offer.secondary_image == '')
                modal.find('.coupon-secondary-image').hide();
            else
                modal.find('.coupon-secondary-image').show();
            modal.find('.coupon-title').html(impression.offer.name);
            modal.find('.coupon-description').html(impression.offer.description);
            if(impression.is_clipped)
            {
                modal.find('.coupon-save-text').html('Saved!');
                modal.find('.btn-coupon-clip').addClass('disabled')

            }
            else
            {
                modal.find('.coupon-save-text').html('Save It');
            }
            if(impression.my_review == 1)
            {
                modal.find('.btn-coupon-like').addClass('review-on');
            }
            else if(impression.my_review == -1)
            {
                modal.find('.btn-coupon-dislike').addClass('review-on');
            }
            else
            {
                modal.find('.btn-coupon-like').removeClass('review-on');
                modal.find('.btn-coupon-dislike').removeClass('review-on');
            }
            modal.find('.btn-coupon-clip').data('offer_id', impression.offer.id);
            modal.find('.btn-coupon-share').data('entity_id', impression.entity.id);
            modal.find('.btn-coupon-dislike').data('offer_id', impression.offer.id);
            modal.find('.dislikes-count').html(impression.down_count);
            modal.find('.likes-count').html(impression.up_count);
            modal.find('.btn-coupon-like').data('offer_id', impression.offer.id);
            modal.find('.coupon-code').html(impression.offer_rand);

            modal.find('.btn-coupon-print').data('offer_id', impression.offer.id);
            modal.find('.btn-coupon-print').data('entity_id', impression.entity.id);
            modal.find('.btn-coupon-print').data('location_id', impression.entity.location_id);
            modal.find('.btn-coupon-print').data('merchant_id', impression.entity.merchant_id);
            modal.find('.btn-coupon-print').data('merchant_name', impression.entity.merchant_name);
            modal.find('.btn-coupon-print').data('offer_rand', impression.offer_rand);
            if(impression.entity.print_override != '' || impression.entity.url != '')
            {
                modal.find('.btn-coupon-print').addClass('external-print');
                modal.find('.btn-coupon-print').click(function()
                {
                    window.open('/print-external/'+impression.entity.id, '_newtab');
                    return;
                });
            }
            else
            {
                modal.find('.btn-coupon-print').removeClass('external-print');
                modal.find('.btn-coupon-print').click(function(){});
            }
            if(impression.can_print == 0)
            {
                modal.find('.btn-coupon-print').prop('disabled', true);
                modal.find('.btn-coupon-print').addClass('disabled');
            }
            else
            {
                modal.find('.btn-coupon-print').prop('disabled', false);
                modal.find('.btn-coupon-print').removeClass('disabled');
            }

            modal.removeClass('already-redeemed');
            modal.removeClass('redeemed');
            modal.find('.coupon-redemption-message').html('');
            modal.find('.btn-coupon-redeem').data('offer_id', impression.offer.id);
            modal.find('.btn-coupon-redeem').data('entity_id', impression.entity.id);
            modal.find('.btn-coupon-redeem').data('location_id', impression.entity.location_id);
            modal.find('.btn-coupon-redeem').data('merchant_id', impression.entity.merchant_id);
            modal.find('.btn-coupon-redeem').data('merchant_name', impression.entity.merchant_name);
            modal.find('.btn-coupon-redeem').data('offer_rand', impression.offer_rand);
            if(impression.entity.print_override != '' || impression.entity.url != '')
            {
                modal.find('.btn-coupon-redeem').addClass('external-print');
                modal.find('.btn-coupon-redeem').click(function()
                {
                    window.open('/print-external/'+impression.entity.id, '_newtab');
                    return;
                });
            }
            else
            {
                modal.find('.btn-coupon-redeem').removeClass('external-print');
                modal.find('.btn-coupon-redeem').click(function(){});
            }
            if(impression.can_print == 0)
            {
                modal.find('.coupon-redemption-message').html('Already Redeemed!');
                modal.find('.coupon-redemption-message').addClass('alert alert-danger');
                modal.addClass('already-redeemed');
                modal.find('.btn-coupon-redeem').prop('disabled', true);
                modal.find('.btn-coupon-redeem').addClass('disabled');
            }
            else
            {
                modal.find('.coupon-redemption-message').html('');
                modal.find('.coupon-redemption-message').removeClass('alert alert-danger');
                modal.removeClass('already-redeemed');
                modal.find('.btn-coupon-redeem').prop('disabled', false);
                modal.find('.btn-coupon-redeem').removeClass('disabled');
            }

            if(trackable)
            {
                /*mixpanel.track('Offer View', {
                    'Environment': "<?php echo App::environment(); ?>",
                    'LocationId': impression.entity.location_id,
                    'MerchantId': impression.entity.merchant_id,
                    'OfferId': impression.offer.id,
                    'MerchantName': impression.entity.merchant_name
                });*/
            }

        },
        'BindSharing': function(entity)
        {
            if (typeof sharing_off != 'undefined') {
                $('#shareComingSoonModal').modal('show');
            } else {
                var modal = $('#shareModal');
                modal.find('.share-image').prop('src', entity.path);
                modal.find('.share-name').html(entity.name);
                modal.find('.btn-email-share').data('entity_id', entity.id);
                modal.find('.btn-facebook-share').data('entity_id', entity.id);
                modal.find('.btn-twitter-share').data('entity_id', entity.id);
                //modal.find('#shareEmail').val('');
                modal.find('#shareToEmails').val('');
                //modal.find('#shareName').val('');
                modal.find('.email-share-message').val('');
                modal.find('.facebook-share-message').val('');
                modal.find('.twitter-share-message').val('');
                modal.find('.share-email-terms').prop('checked', false);
                modal.find('.share-facebook-terms').prop('checked', false);
                modal.find('.share-twitter-terms').prop('checked', false);
                modal.modal('show');
            }
        },
        'BindContest': function(impression)
        {
            var modal = $('#contestModal');
            modal.find('strong.warning').hide();
            if(impression.is_entered == 1)
            {
                $('.btn-enter-contest').addClass('disabled').text('Entry Submitted');
            } else {
                $('.btn-enter-contest').removeClass('disabled').text('Enter Contest');
            }
            modal.find('.has-error').removeClass('has-error');
            modal.find('.btn-enter-contest').data('contest_id', impression.contest.id);
            modal.find('.contest-title').html(impression.contest.display_name);
            modal.find('.contest-description').html(impression.contest.contest_description);
            modal.find('.contest-banner').attr('src', impression.contest.banner);
            $('#contestRulesModal .contest-rules').html(impression.contest.contest_rules);
        },
        'BindSweepstakes': function(impression)
        {
            $('#sweepstakesWinnerModal input').each(function(index)
            {
                $(this).val('');
            });
            $('#sweepstakesModalLabel').html(impression.contest.display_name);
            $('#sweepstakesWinnerModalLabel').html(impression.contest.display_name);
            $('#sweepstakesWinnerThankYouModalLabel').html(impression.contest.display_name);
            $('#sweepstakesModalDescExternal').html(impression.contest.contest_description);
            $('#sweepstakesModalDescInternal').html(impression.contest.contest_description);
            $('#sweepstakesModalBanner').attr('src', impression.contest.banner);
            $('#sweepstakesWinnerModal').data('entity_id', impression.entity.id);
            var aNums = impression.randomnum.split('');
            for(var i=0; i<aNums.length; i++)
            {
                $('.winning-numbers.num_'+i).html(aNums[i]);
            }
            if(impression.entity.secondary_type == 'external')
            {
                $('.external-body').show();
                $('.internal-body').hide();
                $('#externalNumberInput').show();
            }
            else
            {
                $('.external-body').hide();
                $('.internal-body').show();
                $('#externalNumberInput').hide();
            }
        },
        'getQueryVariable':function (variable)
        {
               var query = window.location.search.substring(1);
               var vars = query.split("&");
               for (var i=0;i<vars.length;i++) {
                       var pair = vars[i].split("=");
                       if(pair[0] == variable){return pair[1];}
               }
               return(false);
        },
        'ShowEntity': function(eid)
        {
            var self = this;
            if(user_type == 'User')
            {
                var myImpression = new UserImpression({user_id: user_id, entity_id: eid})
            }
            else
            {
                var myImpression = new NonmemberImpression({nonmember_id: user_id, entity_id: eid})
            }
            myImpression.save(function(impression)
            {
                $('.show-eid').val(impression.entity.id);
                if(impression.entity.entitiable_type == 'Offer')
                {
                    if(impression.offer.is_dailydeal == 0)
                    {
                        $('#couponModal').modal('show');
                    }
                    else
                    {
                        $('#saveTodayModal').modal('show');   
                    }
                    self.BindCoupon(impression);
                }
                else
                {
                    if(user_type == 'Nonmember')
                    {
                        $('#signInModal .login-message span').html('You must be signed in to enter a contest.');
                        $('#signInModal .login-message').show();
                        $('#signInModal').modal();
                        return;
                    }
                    if(impression.entity.secondary_type == 'external' || impression.entity.secondary_type == 'internal')
                    {
                        $('#sweepstakesModal').modal('show');
                        self.BindSweepstakes(impression);
                    }
                    else
                    {
                        $('#contestModal').modal('show');
                        self.BindContest(impression);
                    }
                }
            });
        }
    });
    master_control = new MasterControl($('body'));
    if (newUser == 1 && document.URL.indexOf('printable') == -1)
    {
        $(document).ready(function() {
            $('#firstTimeUserModal').modal('show');
        });
    }

    /*window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo Config::get("integrations.facebook.app_id") ?>',
          status     : true,
          xfbml      : true
        });
      };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));*/
    
//});
</script>
