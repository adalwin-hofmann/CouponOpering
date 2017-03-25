<script>
Banner = can.Model({
    findAll: 'GET /api/v2/banner/get-by-franchise',
    create: 'POST /wizard-update-banner'
},{});

BannerDelete = can.Model({
    create: 'POST /wizard-delete-banner'
},{});

Package = can.Model({
    create: 'POST /wizard-banner-package'
},{});

BannerControl = can.Control({
    init: function()
    {
        this.Search();
    },
    //Events
    '#moreLocations click': function(element)
    {
        $('#moreLocations > i').toggleClass('icon-plus icon-minus');
        if($('#moreLocations > i').hasClass('icon-minus'))
        {
            $('#locations').prop('multiple', true);
            $('#locations').attr('rows', 5);
            $('#locations option[value=""]').remove();
            $('#moreLocations').tooltip('destroy');
            $('#moreLocations').tooltip({'title': 'Collapse'});
        }
        else
        {
            $('#locations').prepend('<option value="">All</option>');
            $('#locations').prop('multiple', false);
            $('#locations').val('');
            $('#moreLocations').tooltip('destroy');
            $('#moreLocations').tooltip({'title': 'Select Multiple'});
        }
    },
    '#banner_package change': function(element)
    {
        var myPackage = new Package({franchise_id: selectedFranchise, banner_package: element.val()});
        myPackage.save(function(){
            if(element.val() == '')
            {
                $('#homepageRow').hide();
                $('#allCouponsRow').hide();
                $('#subcategoryRow').hide();
                $('#keywordRow').hide();
            }
            else if(element.val() == 'basic')
            {
                $('#homepageRow').hide();
                $('#allCouponsRow').hide();
                $('#keywordRow').hide();
                $('#subcategoryRow').show();
            }
            else
            {
                $('#homepageRow').show();
                $('#allCouponsRow').show();
                $('#subcategoryRow').show();
                $('#keywordRow').show();
            }
        });
    },
    '#showInactive change': function(element)
    {
        this.Search();
    },
    '.btn-file :file change': function(element)
    {
        var type = element.data('type');
        var label = element.val().replace(/\\/g, '/').replace(/.*\//, '');
        $('#'+type+'-image').val(label);
    },
    '#homepageDoneButton click': function(element)
    {
        $('#homepage-image').val(element.html());
        element.html('');
    },
    '#all-couponsDoneButton click': function(element)
    {
        $('#all-coupons-image').val(element.html());
        element.html('');
    },
    '#keywordDoneButton click': function(element)
    {
        $('#keyword-image').val(element.html());
        element.html('');
    },
    '#subcategoryDoneButton click': function(element)
    {
        $('#subcategory-image').val(element.html());
        element.html('');
    },
    '.btn-delete click': function(element)
    {
        var self = this;
        var myDelete = new BannerDelete({banner_id: element.data('banner_id')});
        myDelete.save(function()
        {
            self.Search();
        });
    },
    '.btn-more-locations click': function(element)
    {
        element.find('i').toggleClass('icon-plus icon-minus');
        var select = element.parent().parent().find('select');
        if(element.find('i').hasClass('icon-minus'))
        {
            select.prop('multiple', true);
            select.attr('rows', 5);
            select.find('option[value="0"]').remove();
        }
        else
        {
            select.prepend('<option value="0">All Locations</option>');
            select.prop('multiple', false);
            select.val('0');
        }
    },
    '.btn-create-new click': function(element)
    {
        var self = this;
        var type = element.data('type');
        if($('#'+type+'-image').val() != '')
        {
            var bannerObject = {
                banner_id: 0,
                merchant_id: selectedMerchant,
                franchise_id: selectedFranchise,
                path: $('#'+type+'-image').val(),
                type: type,
                is_active: 1,
                is_demo: $('#'+type+'-demo').prop('checked') ? '1' : '0',
                custom_url: $('#'+type+'-url').val(),
                service_radius: $('#'+type+'-radius').val()*1607
            }

            var location_specific = '';
            if($('#'+type+'-locations').val() == '0')
            {
                location_specific = '0';
            }
            else
            {
                var locations = [];
                location_specific = '1';
                $('#'+type+'-locations :selected').each(function(i)
                {
                    locations[i] = $(this)[0].value;
                });
                bannerObject.locations = locations;
            }
            bannerObject.is_location_specific = location_specific;

            if(type == 'keyword')
                bannerObject.keywords = $('#keywords').val();
            var myBanner = new Banner(bannerObject);
            myBanner.save(function(json)
            {
                self.Search();
            });
        }

    },
    //Methods
    'Search': function(callback)
    {
        var self = this;
        Banner.findAll({franchise_id: selectedFranchise, show_inactive: ($('#showInactive').prop('checked') ? '1' : '0')}, function(banners)
        {
            self.BindBanners(banners);
        });
    },
    'BindBanners': function(banners)
    {
        $('#homepageArea').html('');
        $('#allCouponsArea').html('');
        $('#subcategoryArea').html('');
        $('#keywordSearchArea').html('');
        for(var i=0; i<banners.length; i++)
        {
            if(banners[i].banner.type == 'homepage')
            {
                $('#homepageArea').append(can.view('template_banner', {
                    banner: banners[i].banner,
                    locations: banners[i].locations
                }));
            }
            if(banners[i].banner.type == 'all-coupons')
            {
                $('#allCouponsArea').append(can.view('template_banner', {
                    banner: banners[i].banner,
                    locations: banners[i].locations
                }));
            }
            if(banners[i].banner.type == 'subcategory')
            {
                $('#subcategoryArea').append(can.view('template_banner', {
                    banner: banners[i].banner,
                    locations: banners[i].locations
                }));
            }
            if(banners[i].banner.type == 'keyword')
            {
                $('#keywordSearchArea').append(can.view('template_banner', {
                    banner: banners[i].banner,
                    locations: banners[i].locations
                }));
            }
        }
        if($('#homepageArea').html() != '')
            $('#homepage-creation').hide();
        else
            $('#homepage-creation').show();
        if($('#allCouponsArea').html() != '')
            $('#all-coupons-creation').hide();
        else
            $('#all-coupon-creation').show();
        if($('#subcategoryArea').html() != '')
            $('#subcategory-creation').hide();
        else
            $('#subcategory-creation').show();
        if($('.keyword-banner-row').length >= 2)
            $('#keyword-creation').hide();
        else
            $('#keyword-creation').show();
    }
});

banner_control = new BannerControl($('.grid-content'));

</script>