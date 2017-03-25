<script>
Coupon = can.Model({
    findOne: 'GET /wizard-coupon/{offer_id}',
    findAll: 'GET /api/offer/get-by-franchise-id?franchise_id={franchise_id}',
    create: 'POST /wizard-update-coupon/{offer_id}'
},{});

Duplicate = can.Model({
    create: 'POST /duplicate/{coupon_id}'
},{});

GalleryImage = can.Model({
    findOne: 'GET /gallery/{image_id}',
    findAll: 'GET /gallery'
},{});

Subcategory = can.Model({
    findAll: 'GET /api/category/get-by-parent-id?category_id={category_id}'
},{});

Merchant = can.Model({
    findOne: 'GET /api/merchant/{merchant_id}',
    create: 'POST /backoffice/content/merchants/update_merchant/{merchant_id}'
},{});

VehicleModel = can.Model({
    findAll: 'GET /api/v2/vehicle-model/get-by-make-id'
},{});


$( "#starts_at" ).datepicker();
$( "#expires_at" ).datepicker();


CouponControl = can.Control({
    init: function()
    {
        $('#btnGallery').tooltip({'title': 'Select Image From Gallery Or Leave Blank To Use Merchant Logo'});
        $('.btn-copy').tooltip({'title': 'Duplicate This Coupon'});
        $('.btn-save').tooltip({'title': 'Save This Coupon'});
        $('.btn-add-coupon').tooltip({'title': 'Add New Coupon'});
        CKEDITOR.replace('description', {height:"155"});
        //this.SetBlank();
        this.Search();
        if(selectedMerchant == 0)
        {
            return;
        }
    },
    //Events
    '#lease_make change': function(element)
    {
        var self = this;
        if(element.val() == '0')
        {
            $("#lease_model").val('0');
            $("#lease_model").prop('disabled', true);
        }
        else
        {
            VehicleModel.findAll({make_id: element.val()}, function(models)
            {
                self.BindModels(models);
                $("#lease_model").prop('disabled', false);    
            });
        }
    },
    '.search-query keypress': function( element, event ) 
    {       
        var self = this; 
        if(element.val().length > 2)
        {
            selectedOffer = 0;
            $('.row-selectable').removeClass('row-selected');
            $('.btn-add-coupon').hide();
            $('.btn-save').html('Save New');
            this.SetBlank();
            currentPage = 0;
            self.Search();
        }
        else if(event.which==13)
        {
            selectedOffer = 0;
            $('.row-selectable').removeClass('row-selected');
            $('.btn-add-coupon').hide();
            $('.btn-save').html('Save New');
            this.SetBlank();
            currentPage = 0;
            self.Search();
        }
    },
    '#coupPaginationBottom > li > a click': function(element)
    {
        selectedOffer = 0;
        $('.row-selectable').removeClass('row-selected');
        $('.btn-add-coupon').hide();
        $('.btn-save').html('Save New');
        this.SetBlank();
        currentPage = element.data('page');
        this.Search();
    },
    '.row-select click': function(element)
    {
        var self = this;
        $('.row-selectable').removeClass('row-selected');
        $('.btn-add-coupon').show();
        $('.btn-save').html('Save');
        $('.btn-copy').show();
        element.parent().parent().parent().parent().addClass('row-selected');
        selectedOffer = element.data('offer_id');//$('#coupon_type option:contains("Save Today")').prop('selected',true);//alert( $('#coupon_type').options[2].val());
        self.SetBlank();
        Coupon.findOne({offer_id: selectedOffer}, function(data)
        {
            $('#image').val(data.path);
            var s_today = data.is_dailydeal;
            $('#name').val(data.name);


            if(data.is_location_specific == 1)
            {
                $('#locations option[value=""]').remove();
                $('#locations').prop('multiple', true);
                $('#locations').attr('rows', 5);
                $('#moreLocations > i').attr('class', 'icon-minus');
                $('#moreLocations').tooltip('destroy');
                $('#moreLocations').tooltip({'title': 'Collapse'});
                $('#locations option').prop('selected', false);
                for(var i = 0; i < data.locations.length; i++)
                {
                    $('#locations option[value="'+data.locations[i].attributes.id+'"]').prop('selected', true);
                }
            }
            else
            {
                $('#locations').prop('multiple', false);
                $('#locations').val('');
                $('#moreLocations > i').attr('class', 'icon-plus');
                $('#moreLocations').tooltip('destroy');
                $('#moreLocations').tooltip({'title': 'Show Multiple'});
            }
            $('#category_visible').val(data.category_visible);
            $('#is_featured_offer').val(data.is_featured);
            $('#print_override').val(data.print_override);
            $('#max_prints').val((data.max_prints == 0)?1:data.max_prints);
            $('#status').val(data.is_active);
            $('#is_demo').val(data.is_demo);
            $('#code').val(data.code);
            $('#savings').val(data.savings);
            $('#hide_expiration').prop('checked', data.hide_expiration == '0' ? false : true);
            $('#is_reoccurring').prop('checked', data.is_reoccurring == '0' ? false : true);
            CKEDITOR.instances.description.setData(data.description);
            $('#starts_at').val(self.GetDate(data.attr('starts_at')));
            $('#expires_at').val(self.GetDate(data.attr('expires_at')));
            $('#regularprice').val(data.regular_price);
            $('#specialprice').val(data.special_price);
            $('#member_print').val(data.requires_member);
            $('#is_mobile_only').val(data.is_mobile_only);
            $('#secondary_type').val(data.secondary_type);
            $('#short_name_line1').val(data.short_name_line1);
            $('#short_name_line2').val(data.short_name_line2);
            $('#secondary_image').val(data.secondary_image);
            if(s_today==1){
                $('#savetdy').hide();
                $('#blkfriday').show();
                $('#coupon_type').val('savetoday');
                $('#regularPrice').removeClass('offset1');
                $('#regularPrice').css('margin-left', '0px');
            }else{
                $('#blkfriday').hide();
                $('#savetdy').hide();
                $('#coupon_type').val('simple');
                $('#regularPrice').removeClass('offset1');
            }
            $('#custom_category_id').val(data.custom_category_id);
            if(data.custom_category_id != 0)
            {
                Subcategory.findAll({category_id: data.custom_category_id}, function(subcategories)
                {
                    $('#custom_subcategory_id').html(can.view('template_subcategory',
                    {
                        subcategories: subcategories
                    }));
                    $('#custom_subcategory_id').val(data.custom_subcategory_id);
                    $('#custom_subcategory_id').prop('disabled', false);
                });
            }
            else
            {
                $('#custom_subcategory_id').html('<option value="0">Same As Merchant</option>');
                $('#custom_subcategory_id').val(0);
                $('#custom_subcategory_id').prop('disabled', true);
            }

            if(($('#secondary_type').val() == 'lease') || ($('#secondary_type').val() == 'purchase'))
            {
                $('#lease_year').val(data.year);
                $('#lease_make').val(data.make_id);
                if(data.make_id != '0')
                {
                    VehicleModel.findAll({make_id: data.make_id}, function(models)
                    {
                        self.BindModels(models);
                        $('#lease_model').prop('disabled', false);
                        if(data.model_id != '0')
                            $('#lease_model').val(data.model_id);
                    });
                }
                else
                {
                    $('#lease_model').val('0');
                    $('#lease_model').prop('disabled', true);
                }
                $('.lease-info').show();
            } else {
                $('.lease-info').hide();
            }

        });
    },
    '.btn-file :file change': function(element)
    {
        var numFiles = element.get(0).files ? element.get(0).files.length : 1;
        var label = element.val().replace(/\\/g, '/').replace(/.*\//, '');
        $('#secondary_image').val(label);
    },
    '#custom_category_id change': function(element)
    {
        if(element.val() != 0)
        {
            Subcategory.findAll({category_id: element.val()}, function(subcategories)
            {
                $('#custom_subcategory_id').html(can.view('template_subcategory',
                {
                    subcategories: subcategories
                }));
                $('#custom_subcategory_id').prop('disabled', false);
            });
        }
        else
        {
            $('#custom_subcategory_id').html('<option value="0">Same As Merchant</option>');
            $('#custom_subcategory_id').val(0);
            $('#custom_subcategory_id').prop('disabled', true);
        }
    },
    '.btn-status click': function(element)
    {
        element.html('<img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif">');
        var self = this;
        var offer_id = element.data('offer_id');
        var status = element.data('status');
        var OfferObject = new Object();
        OfferObject.offer_id = offer_id;
        OfferObject.status = status ? 0 : 1;
        var myCoupon = new Coupon(OfferObject);
        myCoupon.save(function(json)
        {
            self.SetBlank();
            self.Search();
        });
    },
    '.btn-add-coupon click': function()
    {
        selectedOffer = 0;
        $('.row-selectable').removeClass('row-selected');
        $('.btn-add-coupon').hide();
        $('.btn-copy').hide();
        $('.btn-save').html('Save New');
        this.SetBlank();
    },
    '.btn-copy click': function(element)
    {
        element.button('loading');
        var self = this;
        var myDup = new Duplicate({coupon_id: selectedOffer});
        myDup.save(function(offer)
        {
            element.button('reset');
            $('.coupon-messages').hide();
            $('.coupon-messages').css('color', 'green');
            $('.coupon-messages').html('Coupon Copied!');
            $('.coupon-messages').fadeIn(400, function()
            {
                $('.coupon-messages').fadeOut(4000);     
            });
            self.Search(function()
            {
                $('#coupResultsArea').find(".row-select[data-offer_id='" + offer.id +"']").click();
            });
        });
    },
    '#startNow click': function()
    {
        var d = new Date();
        $('#starts_at').val(Number(d.getMonth())+1+'/'+d.getDate()+'/'+d.getFullYear());
    },
    '#coupon_type change':function()
    {
        if ($('#coupon_type').val() == 'blackfriday'){
             $('#savetdy').css('display','');
             $('#blkfriday').css('display','');
             $('#regularPrice').css('margin-left', '');
             $('#regularPrice').addClass('offset1');
             //$('#savetdy').css('display','none');
        }else if ($('#coupon_type').val() == 'savetoday'){
             $('#savetdy').css('display','none');
             $('#blkfriday').css('display','');
             $('#regularPrice').css('margin-left', '0px');
             $('#regularPrice').removeClass('offset1');
        }else {
            $('#blkfriday').css('display','none');
            $('#savetdy').css('display','none');
        }
    },
    '.btn-save click': function(element)
    {
        element.button('loading');
        var self = this;
        var OfferObject = this.Validate();
        if(OfferObject.errors.length > 0)
        {
            for(var i=0; i<OfferObject.errors.length; i++)
            {
                $('#'+OfferObject.errors[i]).parent().parent().addClass('error');
            }
            $('.coupon-messages').hide();
            $('.coupon-messages').css('color', 'red');
            $('.coupon-messages').html('Please fill out all required fields!');
            $('.coupon-messages').fadeIn(400);
            element.button('reset');
        }
        else
        {
            OfferObject.is_featured_offer = $('#is_featured_offer').val();
            OfferObject.path = $('#image').val()
            OfferObject.offer_id = selectedOffer;
            OfferObject.merchant_id = selectedMerchant;
            OfferObject.max_prints = (!$('#max_prints').val())?1:$('#max_prints').val();
            OfferObject.code = $('#code').val();
            OfferObject.savings = $('#savings').val();
            OfferObject.savetoday = $('#coupon_type').val() == 'savetoday' ? 1 : 0;
            OfferObject.regularprice = $('#regularprice').val();
            OfferObject.specialprice = $('#specialprice').val();
            OfferObject.print_override = $('#print_override').val();
            OfferObject.franchise_id = selectedFranchise;
            OfferObject.requires_member = $('#member_print').val();
            OfferObject.is_mobile_only = $('#is_mobile_only').val();
            OfferObject.secondary_type = $('#secondary_type').val();
            OfferObject.short_name_line1 = $('#short_name_line1').val();
            OfferObject.short_name_line2 = $('#short_name_line2').val();
            OfferObject.custom_category_id = $('#custom_category_id').val();
            OfferObject.custom_subcategory_id = $('#custom_subcategory_id').val();
            OfferObject.category_visible = $('#category_visible').val();
            OfferObject.secondary_image = $('#secondary_image').val();
            OfferObject.hide_expiration = $('#hide_expiration').prop('checked') ? 1 : 0;
            OfferObject.is_reoccurring = $('#is_reoccurring').prop('checked') ? 1 : 0;
            if(($('#secondary_type').val() == 'lease') || ($('#secondary_type').val() == 'purchase'))
            {
                OfferObject.year = $('#lease_year').val();
                OfferObject.make_id = $('#lease_make').val();
                OfferObject.model_id = $('#lease_model').val();
            }
            
            var location_specific = '';
            if($('#locations').val() == '')
            {
                location_specific = '0';
            }
            else
            {
                var locations = [];
                location_specific = '1';
                $('#locations :selected').each(function(i)
                {
                    locations[i] = $(this)[0].value;
                });
                OfferObject.locations = locations;
            }
            OfferObject.is_location_specific = location_specific;

            var myCoupon = new Coupon(OfferObject);
            myCoupon.save(function(json)
            {
                element.button('reset');
                if(selectedOffer == 0)
                {
                    self.Search();
                    self.SetBlank();
                    $('.coupon-messages').hide();
                    $('.coupon-messages').css('color', 'green');
                    $('.coupon-messages').html('New Coupon Added!');
                    $('.coupon-messages').fadeIn(400, function()
                    {
                        $('.coupon-messages').fadeOut(4000);     
                    });
                }
                else
                {
                    self.Search();
                    self.SetBlank();
                    $('.coupon-messages').hide();
                    $('.coupon-messages').css('color', 'green');
                    $('.coupon-messages').html('Coupon Saved!');
                    $('.coupon-messages').fadeIn(400, function()
                    {
                        $('.coupon-messages').fadeOut(4000);     
                    });
                }
            });
        }
    },
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
    '#showInactive change': function(element)
    {
        this.Search();
    },
    '#ieSecondaryDoneButton click': function(element)
    {
        var path = element.html();
        element.html('');
        $('#secondary_image').val(path);
    },
    '#secondary_type change': function(element)
    {
        if(($('#secondary_type').val() == 'lease') || ($('#secondary_type').val() == 'purchase'))
        {
            $('.lease-info').show();
        } else {
            $('.lease-info').hide();
        }
    },
    //Methods
    'BindModels': function(models)
    {
        $('#lease_model').html('<option value="0">Select</option>');
        $('#lease_model').append(can.view('template_model',
        {
            models: models
        }));
    },
    'Search': function(callback)
    {
        var self = this;
        var query = $('.search-query').val();
        if(selectedMerchant == 0)
        {
            return;
        }
        Coupon.findAll({filter: '%'+query+'%', franchise_id: selectedFranchise, page: currentPage, limit: 5, is_active: ($('#showInactive').prop('checked') ? '-1' : '1')}, function(coupons)
        {
            self.BindOffers(coupons);
            self.BindPagination(coupons);
            if(typeof callback !== "undefined")
            {
                callback();
            }
        });
    },
    'Validate': function()
    {
        $('.coupon-messages').hide();
        $('.form-group').removeClass('error');
        var OfferObject = new Object();
        
        OfferObject.name = $('#name').val();
        OfferObject.description = CKEDITOR.instances.description.getData();
        OfferObject.starts_at = $('#starts_at').val();
        OfferObject.expires_at = $('#expires_at').val();
        OfferObject.status = $('#status').val();
        OfferObject.is_demo = $('#is_demo').val();
        OfferObject.errors = [];

        for(var input in OfferObject)
        {
            if(OfferObject[input] == '' && input != 'errors')
            {
                OfferObject.errors.push(input);
            }
        }

        return OfferObject;
    },
    'BindOffers': function(coupons)
    {
        for(var i=0; i<coupons.length; i++)
        {
            coupons[i].starts_at = this.GetDate(coupons[i].attr('starts_at'));
            coupons[i].expires_at = this.GetDate(coupons[i].attr('expires_at'));
        }
        $('#coupResultsArea').html(can.view('template_coupon',
        {
            results: coupons
        }));
        $('.tooltip').remove();
        $('.row-selectable').tooltip();
    },
    'BindPagination': function(data)
    {
        var prev = $('#coupPrev');
        var next = $('#coupNext');
        var current = $('#coupLblCurrentPage');

        var lastpage = Math.ceil(data.stats.total / data.stats.take);

        if(data.stats.page == 0)
        {
            prev.parent().addClass('disabled');
        }
        else
        {
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
        }
        if((Number(data.stats.page)+1) == lastpage)
        {
            next.parent().addClass('disabled');
        }
        else
        {
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
        }
        current.html((Number(data.stats.page)+1)+" of "+lastpage);
    },
    'SetBlank': function()
    {
        $('#titleArea :input').val('');
        $('#detailsArea :input').val('');
        $('#quantity :input').val('');
        $('#regularprice').val('');
        $('#specialprice').val('');
        $('#secondaryTypeInput').val('secondary_image');
        CKEDITOR.instances.description.setData('');
    

        if($('#moreLocations > i').hasClass('icon-minus'))
        {
            $('#moreLocations > i').removeClass('icon-minus')
            $('#moreLocations > i').addClass('icon-plus')
            $('#locations').prepend('<option value="">All</option>');
            $('#locations').prop('multiple', false);
            $('#moreLocations').tooltip('destroy');
            $('#moreLocations').tooltip({'title': 'Select Multiple'});
        }
        $('#locations').val('');
        $('#max_prints').val(1);
    },
    'GetDate': function(time)
    {
        if(typeof time === 'object')
        {
            time = time.date
        }
        var c = time.split(/[- :]/);
        time = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
        time = Number(time.getMonth())+1+'/'+time.getDate()+'/'+time.getFullYear();

        return time;
    }
});

UploadControl = can.Control({
    init: function()
    {

    },
    // Events
    '#uploadCategory change': function(element)
    {
        if(element.val() == '0')
        {
            $('#uploadSubCategory').val('0');
            $('#uploadSubCategory').prop('disabled', true);
        }
        else
        {
            Subcategory.findAll({category_id: element.val()}, function(json)
            {
                $('#uploadSubCategory').prop('disabled', false);
                $('#uploadSubCategory').html('<option value="0">Subcategory</option>');
                $('#uploadSubCategory').append(can.view('template_subcategory',
                {
                    subcategories: json
                }));
            });
        }
    },
    '#ieDoneButton click': function(element)
    {
        var path = element.html();
        element.html('');
        $('#image').val(path);
        $('#uploadModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    },
    // Methods
    'Validate': function()
    {
        if($('#uploadCategory').val() == 0 || $('#uploadSubCategory').val() == 0)
        {
            $('#uploadMessages').html('Please select both a Category and Subcategory!');
            $('#uploadMessages').css('color', 'red');
            $('#uploadMessages').hide();
            $('#uploadMessages').fadeIn(300, function()
            {
                $('#uploadMessages').fadeOut(10000); 
            });
            return false;
        }
    }
});

ModalControl = can.Control({
    // Constructor
    init: function()
    {
        this.Search();
    },
    // Events
    '.img-polaroid click': function(element)
    {
        selectedCategory = 0;
        selectedSubcategory = 0;
        $('#image').val(element.data('path'));
        $('#myModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        $('#gallerySelected').addClass('open');
    },
    '#imgCategory change': function(element)
    {
        selectedCategory = element.val();
        selectedPage = 0;
        if(selectedCategory == '0')
        {
            $('#imgSubCategory').prop('disabled', true);
            this.Search();
        }
        else
        {
            this.Search();
            Subcategory.findAll({category_id: selectedCategory}, function(json)
            {
                $('#imgSubCategory').prop('disabled', false);
                $('#imgSubCategory').html('<option value="0">Subcategory</option>');
                $('#imgSubCategory').append(can.view('template_subcategory',
                {
                    subcategories: json
                }));
            });
        }
    },
    '#imgSubCategory change': function(element)
    {
        selectedSubcategory = element.val();
        selectedPage = 0;
        this.Search();
    },
    '#imgPaginationBottom > li > a click': function(element)
    {
        selectedPage = element.data('page');
        this.Search();
    },
    // Methods
    'Search': function(image_id)
    {
        var self = this;
        var SearchObject = new Object();
        if(selectedCategory != 0)
        {
            SearchObject.category_id = selectedCategory;
        }
        if(selectedSubcategory != 0)
        {
            SearchObject.subcategory_id = selectedSubcategory;
        }
        SearchObject.page = selectedPage;
        SearchObject.limit = 12;
        if(image_id)
        {
            SearchObject.image_id = image_id;
            GalleryImage.findOne(SearchObject, function(json)
            {

            });
        }
        else
        {
            GalleryImage.findAll(SearchObject, function(json)
            {
                self.BindImages(json);   
                self.BindPagination(json);    
            });
        }
    },
    'BindImages': function(data)
    {
        $('.tooltip').remove();
        $('#gallery').css('width', '100%');
        $('#gallery').html(can.view('template_image', 
        {
            images: data
        })).find('img.img-polaroid').tooltip();
    },
    'BindPagination': function(data)
    {
        var first = $('#imgFirst');
        var prev = $('#imgPrev');
        var next = $('#imgNext');
        var last = $('#imgLast');
        var current = $('#imgLblCurrentPage');

        var lastpage = Math.ceil(data.stats.total / data.stats.take);

        first.data('page', 0);
        last.data('page', lastpage - 1);
        if(data.stats.page == 0)
        {
            first.parent().addClass('disabled');
            prev.parent().addClass('disabled');
        }
        else
        {
            first.parent().removeClass('disabled');
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
        }
        if((Number(data.stats.page)+1) == lastpage)
        {
            last.parent().addClass('disabled');
            next.parent().addClass('disabled');
        }
        else
        {
            last.parent().removeClass('disabled');
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
        }
        current.html((Number(data.stats.page)+1)+" of "+lastpage);
    }
});

coup_control_area = new CouponControl($('.grid-content'));
modal_control = new ModalControl($('#myModal'));
upload_control = new UploadControl($('#uploadModal'));

</script>