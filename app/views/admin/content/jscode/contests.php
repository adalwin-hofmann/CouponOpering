<script>
Contest = can.Model({
  findOne: 'GET /api/contest/find?id={contest_id}',
  findAll: 'GET /api/contest/get-by-name?name={name}',
  create:  'POST /update-contest'
},{});

Franchise = can.Model({
    findOne: 'GET /find-franchise',
    findAll: 'GET /api/franchise/get-by-name?name={name}'
},{});

Zipcode = can.Model(
{
    findOne: 'GET /api/zipcode/get-nearest',
    findAll: 'GET /api/zipcode/get-by-query'
},{});

Coupon = can.Model({
    findOne: 'GET /api/v2/offer/find?id={offer_id}',
    findAll: 'GET /api/v2/offer/get-by-query',
    create: 'POST /wizard-update-coupon/{offer_id}'
},{});

GalleryImage = can.Model({
    findOne: 'GET /gallery/{image_id}',
    findAll: 'GET /gallery'
},{});

AwardDate = can.Model({
    findOne: 'GET /api/v2/contest-award-date/find?id={award_date_id}',
    findAll: 'GET /api/v2/contest-award-date/get-by-contest?contest_id={contest_id}',
    create: 'GET /api/v2/contest-award-date/update'
},{});

NewAwardDate = can.Model({
    findOne: 'GET /api/v2/contest-award-date/find?id={award_date_id}',
    findAll: 'GET /api/v2/contest-award-date/get-by-contest?contest_id={contest_id}',
    create: 'GET /api/v2/contest-award-date/create'
},{});

DeleteAwardDate = can.Model({
    create: 'GET /api/v2/contest-award-date/delete?award_date_id={award_date_id}'
},{});

CopyAwardDate = can.Model({
    create: 'GET /api/v2/contest-award-date/copy?award_date_id={award_date_id}'
},{});

Subcategory = can.Model({
    findAll: 'GET /api/category/get-by-parent-id?category_id={category_id}'
},{});

Location = can.Model({
    findAll: 'GET /api/v2/location/get-by-franchise'
},{});

Entity = can.Model({
    findAll: 'GET /api/v2/entity/get-by-entitiable?entitiable_id={entitiable_id}&entitiable_type={entitiable_type}'
},{});

ContestLocation = can.Model({
    findOne: 'GET /api/v2/contest-location/delete',
    findAll: 'GET /api/v2/contest-location/get-for-contest',
    create: 'GET /api/v2/contest-location/create'
},{});

$( "#contestStart" ).datepicker();
$( "#contestEnd" ).datepicker();
$( "#starts_at" ).datepicker();
$( "#expires_at" ).datepicker();
$('#prize_expiration_date').datepicker();
$('#award_at').datepicker();

ContestControl = can.Control({
    init: function()
    {
        this.Search();
        CKEDITOR.replace('contestDescription', {height:"155"});
        CKEDITOR.replace('contestRules', {height:"155"});
        CKEDITOR.replace('description', {height:"155"});
        formdata = false;  
          
        if (window.FormData) {  
            formdata = new FormData(); 
        }
    },
    // Events
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
            OfferObject.max_prints = $('#max_prints').val();
            OfferObject.code = $('#code').val();
            OfferObject.savings = $('#savings').val();
            OfferObject.savetoday = $('#coupon_type').val() == 'savetoday' ? 1 : 0;
            OfferObject.regularprice = $('#regularprice').val();
            OfferObject.specialprice = $('#specialprice').val();
            OfferObject.print_override = $('#print_override').val();
            OfferObject.franchise_id = $('#contestFranchise').data('franchise_id');
            OfferObject.requires_member = $('#member_print').val();
            OfferObject.is_mobile_only = $('#is_mobile_only').val();
            OfferObject.secondary_type = $('#secondary_type').val();
            OfferObject.short_name_line1 = $('#short_name_line1').val();
            OfferObject.short_name_line2 = $('#short_name_line2').val();
            OfferObject.is_location_specific = 0;

            var myCoupon = new Coupon(OfferObject);
            myCoupon.save(function(json)
            {
                element.button('reset');
                self.SetBlank();
                $('.coupon-messages').hide();
                $('#contestCoupon').data('coupon_id', json.id);
                $('#contestCoupon').val(json.name+' - '+json.merchant.display);
                $('#followUpModal').modal('hide');
                $('#btnCouponEdit').attr('href', '/coupon?viewing='+json.franchise_id);
                $('#btnCouponEdit').show();
            });
        }
    },
    'SetBlank': function()
    {
        $('#titleArea :input').val('');
        $('#detailsArea :input').val('');
        $('#quantity :input').val('');
        $('#regularprice').val('');
        $('#specialprice').val('');
        $('#contestFranchise').val('');
        $('#contestFranchise').data('franchise_id', 0);
        CKEDITOR.instances.description.setData('');
    },
    'Validate': function()
    {
        $('.coupon-messages').hide();
        $('#followUpModal .form-group').removeClass('error');
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
    '#is_national change': function(element)
    {
        if(element.val() == 0 && $('#contestFranchise').val() == '')
        {
            $('#locationRow').show();
        }
        else
        {
            $('#locationRow').hide();
            $('#contestLocation').data('latitude', 0);
            $('#contestLocation').data('longitude', 0);
            $('#contestLocation').val('');
            $('#contestRadius').val(0);
        }
    },
    '#contestType change': function(element)
    {
        if(element.val() == 'generic')
        {
            $('#divNumbers').hide();
            $('#divNumbers :input').val('');
            $("#contestNumberType").val('num');
        }
        else
        {
            $('#divNumbers').show();
        }
    },
    '#is_location_independent change': function(element)
    {
        if(element.prop('checked'))
        {
            $('#independentLocationsControls').show();
            $('#independentZipcode').val('');
            $('#independentRadius').val('');
            if($('#moreLocations > i').hasClass('icon-minus'))
            {
                $('#moreLocations > i').toggleClass('icon-plus icon-minus');
                $('#contestLocations').prepend('<option value="0">All</option>');
                $('#contestLocations').prop('multiple', false);
            }
            $('#contestLocations').val(0);
            if(selectedContest != 0)
            {
                $('#independentLocationsAlert').hide();
                $('.btn-add-zipcode').prop('disabled', false);
            }
        }
        else
            $('#independentLocationsControls').hide();
    },
    '.btn-add-zipcode click': function()
    {
        if($('#independentZipcode').val() != '' && $('#independentRadius').val() != '')
        {
            var myLocation = new ContestLocation({
                contest_id: selectedContest,
                zipcode: $('#independentZipcode').val(),
                service_radius: $('#independentRadius').val()*1607
            });
            myLocation.save(function(locations)
            {
                ContestLocation.findAll({contest_id: selectedContest}, function(locations)
                {
                    $('#independentLocationsArea').html(can.view('template_independent_location',{
                        locations: locations
                    }));
                    $('#independentLocationsMessages').hide();
                    $('#independentLocationsMessages').css('color', 'red');
                    $('#independentLocationsMessages').html('Please Save The Contest');
                    $('#independentLocationsMessages').fadeIn(500);
                });
                $('#independentZipcode').val('')
                $('#independentRadius').val('')
            });
        }
    },
    '.btn-remove-zipcode click': function(element)
    {
        ContestLocation.findOne({contest_id: selectedContest, zipcode: element.data('zipcode')}, function(location)
        {
            element.parent().parent().parent().parent().remove();
            $('#independentLocationsMessages').hide();
            $('#independentLocationsMessages').css('color', 'red');
            $('#independentLocationsMessages').html('Please Save The Contest');
            $('#independentLocationsMessages').fadeIn(500);
        });
    },
    '#contestFranchise change': function(element)
    {
        if(element.val() == '')
        {
            $('#franchiseLocationsRow').hide();
            $('#independentLocationsRow').hide();
            $('#contestLocations').html('<option value="0">All</option>');
            element.data('franchise_id', 0);
            $('#nationalRow').show();
            if($('#is_national').val() == 0)
                $('#locationRow').show();
            $('#btnCouponCreate').addClass('disabled');
            $('#is_location_independent').prop('checked', false);
        }
        else
        {
            Location.findAll({franchise_id: element.data('franchise_id')}, function(locations)
            {
                $('#contestLocations').html('<option value="0">All</option>');
                $('#contestLocations').append(can.view('template_location',
                {
                    locations: locations
                }));
                $('#franchiseLocationsRow').show();
                $('#independentLocationsRow').show();
            });
            $('#nationalRow').hide();
            $('#locationRow').hide();
            $('#is_national').val(0);
            $('#contestLocation').data('latitude', 0);
            $('#contestLocation').data('longitude', 0);
            $('#contestLocation').val('');
            $('#btnCouponCreate').removeClass('disabled');
        }
    },
    '#contestFranchise typeahead:selected': function(element, event, dataset)
    {
        element.data('franchise_id', dataset.id);
    },
    '#contestLocation typeahead:selected': function(element, event, dataset)
    {
        element.data('latitude', dataset.latitude);
        element.data('longitude', dataset.longitude);
    },
    '#contestCoupon typeahead:selected': function(element, event, dataset)
    {
        element.data('coupon_id', dataset.id);
    },
    '#addContest click': function()
    {
        var self = this;
        selectedContest = 0;
        this.SaveNew();
    },
    'a.pagingButton click': function(element, options)
    {
        currentPage = element.data('page')
        this.Search();
    },
    '#btnClose click': function()
    {
        $('#editBox').fadeOut(400, function()
        {
            $('#resultsGrid').fadeIn(400);
        });
    },
    '#moreLocations click': function(element)
    {
        $('#moreLocations > i').toggleClass('icon-plus icon-minus');
        if($('#moreLocations > i').hasClass('icon-minus'))
        {
            $('#contestLocations').prop('multiple', true);
            $('#contestLocations').attr('rows', 5);
            $('#contestLocations option[value="0"]').remove();
        }
        else
        {
            $('#contestLocations').prepend('<option value="0">All</option>');
            $('#contestLocations').prop('multiple', false);
            $('#contestLocations').val(0);
        }
    },
    '#btnSave click': function()
    {
        var self = this;
        var ContestObject = new Object;
        ContestObject.name = $('#contestName').val();
        ContestObject.slug = ContestObject.name.replace(/[^0-9a-z]+/g, '_');
        ContestObject.type = $('#contestType').val();
        ContestObject.is_active = $('#contestStatus').val();
        ContestObject.is_demo = $('#contestDemo').val();
        ContestObject.franchise_id = $('#contestFranchise').data('franchise_id');
        var location_specific = 0;
        if($('#contestLocations').val() == 0)
        {
            location_specific = 0;
        }
        else
        {
            var locations = [];
            location_specific = 1;
            $('#contestLocations :selected').each(function(i)
            {
                locations[i] = $(this)[0].value;
            });
            ContestObject.locations = locations;
        }
        ContestObject.is_location_specific = location_specific;
        ContestObject.is_national = $('#is_national').val();
        if($('#contestStart').val() != '')
        {
            ContestObject.starts_at = this.GetTimeStamp($('#contestStart').val());
        }
        if($('#contestEnd').val() != '')
        {
            ContestObject.expires_at = this.GetTimeStamp($('#contestEnd').val());
        }
        if(CKEDITOR.instances.contestDescription.getData() != '')
        {
            ContestObject.contest_description = CKEDITOR.instances.contestDescription.getData();
        }
        if($('#contestDisplayName').val() != '')
        {
            ContestObject.display_name = $('#contestDisplayName').val();
        }
        if($('#contestWufooLink').val() != '')
        {
            ContestObject.wufoo_link = $('#contestWufooLink').val();
        }
        if($('#contestType').val() != 'generic')
        {
            ContestObject.winning_number_min = $("#contestNumberMin").val() == '' ? 0 : $("#contestNumberMin").val();
            ContestObject.winning_number_max = $("#contestNumberMax").val() == '' ? 0 : $("#contestNumberMax").val();
            ContestObject.winning_number_length = $("#contestNumberLength").val() == '' ? 0 : $("#contestNumberLength").val();
            ContestObject.winning_number_type = $("#contestNumberType").val();
        }
        ContestObject.contest_rules = CKEDITOR.instances.contestRules.getData();
        ContestObject.is_featured = $('#is_featured_contest').val();
        ContestObject.latitude = $('#contestLocation').data('latitude');
        ContestObject.longitude = $('#contestLocation').data('longitude');
        ContestObject.radius = $('#contestRadius').val();
        ContestObject.path = $("#contest_listing").val();
        ContestObject.banner = $("#contest_contestBanner").val();
        ContestObject.landing = $("#contest_landing").val();
        ContestObject.follow_up_text = $('#contestFollowText').val();
        ContestObject.is_automated = $('#contestAutomated').prop('checked') ? 1 : 0;
        ContestObject.total_inventory = $('#totalInventory').val();
        ContestObject.current_inventory = (selectedContest != 0) ? $('#remainingInventory').val() : $('#totalInventory').val();
        ContestObject.is_location_independent = $('#is_location_independent').prop('checked') ? 1 : 0;
        if($('#contestCoupon').data('coupon_id') != 0)
        {
            ContestObject.follow_up_id = $('#contestCoupon').data('coupon_id');
            ContestObject.follow_up_type = 'SOE\\DB\\Offer';
        }
        else
        {
            ContestObject.follow_up_id = 0;
            ContestObject.follow_up_type = '';
        }
        if(selectedContest != 0)
            ContestObject.contest_id = selectedContest;
        var myContest = new Contest(ContestObject);
        
        myContest.save(function(json)
        {
            $('#messages').html("Changes Saved!");
            $('#messages').css('color', 'green');
            $('#messages').hide();
            $('#messages').fadeIn(400, function()
            {
                $('#messages').fadeOut(5000); 
            });
            selectedContest = json.id;
            $('.btn-add-zipcode').prop('disabled', false);
            $('#independentLocationsAlert').hide();
            $('#btn-add-date').removeClass('disabled');
            $('#dateMessage').hide();
            $('#independentLocationsMessages').hide();
            $('#independentLocationsMessages').html('');
        });
    },
    '#btnCouponClear click': function()
    {
        $('#contestCoupon').data('coupon_id', 0);
        $('#contestCoupon').val('');
        $('#btnCouponEdit').hide();
    },
    'button.edit click': function(element, options)
    {
        var self = this;
        selectedContest = element.data('contest_id');
        this.Edit();
    },
    '#nameSearch keydown': function(element, event)
    {
        if(event.which != 13)
        {
            clearTimeout(typingTimer);
        }
    },
    '#nameSearch keyup': function(element, event)
    {
        var self = this; 
        if(event.which!=13)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(contest_control.TriggerSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingTimer);
            current_page = 0;
            self.Search();
        }
    },
    '[id^=btnAdd_] click': function(element)
    {
        var pieces = element.attr('id').split('_');
        $('#'+pieces[1]+'_LinkDiv').fadeOut(400, function()
        {
            $('#'+pieces[1]+'_InputDiv').fadeIn(400);
        });
    },
    '[id$=_InputCancel] click': function(element)
    {
        var pieces = element.attr('id').split('_');
        $('#'+pieces[0]+'_InputDiv').fadeOut(400, function()
        {
            $('#'+pieces[0]+'_LinkDiv').fadeIn(400);
        });
    },
    '[id$=_Save] click': function(element)
    {
        var pieces = element.attr('id').split('_');
        var self = this;
        var img, reader, file;  
        var element = $('#'+pieces[0]+'_Input');

        if (formdata) 
        { 
            file = element[0].files[0]; 
            if (!!file.type.match(/image.*/)) 
            {  
                /*if ( window.FileReader ) {  
                    reader = new FileReader();  
                    reader.onloadend = function (e) {   
                        self.ShowUploadedItem(thumb, e.target.result);  
                    };  
                    reader.readAsDataURL(file);  
                }*/  
                formdata.append('name', file);  
                formdata.append('object_id', selectedContest);
                formdata.append('type', pieces[0]);
            }     
         
            $.ajax({  
                url: "/contest-image-upload",  
                type: "POST",  
                data: formdata,
                processData: false,  
                contentType: false,  
                success: function (res) {  
                    if(res.error == '1')
                    {
                        $('#'+pieces[0]+'_messages').html('There was an error.');
                        $('#'+pieces[0]+'_messages').css('color', 'red');
                        $('#'+pieces[0]+'_messages').hide();
                        $('#'+pieces[0]+'_messages').fadeIn(400, function()
                        {
                            $('#'+pieces[0]+'_messages').fadeOut(5000);       
                        });
                    }
                    else
                    {
                        $('#contest_'+pieces[0]).val(res.path);
                        $('#'+pieces[0]+'_messages').html('Image uploaded successfully.');
                        $('#'+pieces[0]+'_messages').css('color', 'green');
                        $('#'+pieces[0]+'_messages').hide();
                        $('#'+pieces[0]+'_messages').fadeIn(400);
                        $('#'+pieces[0]+'_InputDiv').fadeOut(400, function()
                        {
                            $('#'+pieces[0]+'_LinkDiv').fadeIn(400);
                        });
                    }
                    formdata = new FormData();
                }  
            });  
        }
    },
    // Methods
    'TriggerSearch': function()
    {
        currentPage = 0;
        contest_control.Search();
    },
    'Search': function()
    {
        var self = this;
        var ContestObject = new Object();
        ContestObject.name = $('#nameSearch').val();
        ContestObject.page = currentPage;
        ContestObject.limit = 10;
        Contest.findAll(ContestObject, function(contests)
        {
            self.BindContests(contests);
            self.BindPagination(contests);
        });
    },
    'BindContests': function(contests)
    {
        for(var i=0; i<contests.stats.returned; i++)
        {
            contests[i].start = this.GetDate(contests[i].starts_at);
            contests[i].end = this.GetDate(contests[i].expires_at);
        }
        $('#resultsArea').html(can.view('template_contest',
        {
            contests: contests
        }));
    },
    'GetDate': function(time)
    {
        if(typeof time === 'object')
        {
            time = time.date
        }
        if(time.indexOf('/') != -1)
            return time;

        var c = (time+'').split(/[- :]/);
        time = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
        var myDate = this.Pad(Number((time.getMonth())+1),2)+'/'+this.Pad(time.getDate(),2)+'/'+time.getFullYear();
        return myDate;
    },
    'GetTimeStamp': function(date)
    {
        var aPieces = date.split('/');
        var timestamp = aPieces[2]+'-'+this.Pad(aPieces[0], 2)+'-'+this.Pad(aPieces[1],2)+' 00:00:00';
        return timestamp;
    },
    'Pad': function(number, length) 
    {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    },
    'SaveNew': function()
    {
        var self = this;
        $('#companyDiv').show();
        $('#landingDiv').show();
        $('#listingDiv').show();
        $('#logoDiv').show();
        $('#descriptionDiv').show();
        $('#displayDiv').show();
        $('#wufooDiv').show();
        $('#locationRow').show();
        $('#nationalRow').show();
        $('#is_national').val(0);
        $('#contestLocation').val('');
        $('#contestLocation').data('latitude', '');
        $('#contestLocation').data('longitude', '');
        $('#contestFranchise').val('');
        $('#contestFranchise').data('franchise_id', 0);
        $('#franchiseLocationsRow').hide();
        $('#independentLocationsRow').hide();
        $('#is_location_independent').prop('checked', false);
        $('.btn-add-zipcode').prop('disabled', true);
        $('#independentLocationsAlert').show();
        $('#contestLocations').html('<option value="0">All</option>');
        $('#contestName').val('');
        $('#contestType').val('');
        $('#contestStatus').val('');
        $('#contestDemo').val(0);
        $('#contest_listing').val('');
        $('#contest_contestBanner').val('');
        $('#contest_companyButton').val('');
        $('#contest_landing').val('');
        $('#contest_logoButton').val('');
        CKEDITOR.instances.contestDescription.setData('');
        $('#contestLogoLink').val('');
        $('#contestDisplayName').val('');
        $('#contestWufooLink').val('');
        $('#contestStart').val('');
        $('#contestEnd').val('');
        $('#is_featured_contest').val();
        CKEDITOR.instances.contestRules.setData('');
        $('#bannerDim').html('(940px X 196px)');
        $('#companyDiv').hide();
        $('#landingDim').html('(425px X 240px)');
        $('#logoDiv').hide();
        $('#divNumbers').hide();
        $('#divNumbers :input').val('');
        $("#contestNumberType").val('num');
        $('#is_featured_contest').val('0');
        $('#contestRadius').val('');
        $('#contestFollowText').val('');
        $('#contestCoupon').val('');
        $('#contestCoupon').data('coupon_id', 0);
        $('#btnCouponEdit').hide();
        $('#awardDates').html('');
        $('#btn-add-date').addClass('disabled');
        $('#dateMessage').show();
        $('#resultsGrid').fadeOut(400, function()
        {
            $('#editBox').fadeIn(400);
        });
    },
    'Edit': function()
    {
        var self = this;
        //Show all optional areas
        $('#companyDiv').show();
        $('#landingDiv').show();
        $('#listingDiv').show();
        $('#logoDiv').show();
        $('#descriptionDiv').show();
        $('#displayDiv').show();
        $('#wufooDiv').show();
        $('#nationalRow').show();
        $('#contestFranchise').val('');
        Contest.findOne({contest_id: selectedContest}, function(json)
        {
            if(json.latitude != 0)
            {
                Zipcode.findOne({latitude: json.latitude, longitude: json.longitude}, function(zipcode)
                {
                    $('#locationRow').show();
                    $('#contestLocation').val(zipcode.city+', '+zipcode.state);
                    $('#contestLocation').data('latitude', zipcode.latitude);
                    $('#contestLocation').data('longitude', zipcode.longitude);
                    $('#btnCouponCreate').addClass('disabled');
                });
            }
            else if(json.franchise_id != 0)
            {
                $('#is_national').val(0);
                $('#nationalRow').hide();
                $('#locationRow').hide();
                $('#contestLocation').val('');
                $('#contestLocation').data('latitude', '');
                $('#contestLocation').data('longitude', '');
                $('#contestRadius').val(0);
                $('#btnCouponCreate').removeClass('disabled');
            }
            Franchise.findOne({franchise_id: json.franchise_id}, function(franchise)
            {
                if(typeof franchise !== 'undefined')
                {
                    $('#contestFranchise').val(franchise.display);
                    $('#contestFranchise').data('franchise_id', franchise.id);
                    Location.findAll({franchise_id: json.franchise_id}, function(locations)
                    {
                        $('#contestLocations').html('<option value="0">All</option>');
                        $('#contestLocations').append(can.view('template_location',
                        {
                            locations: locations
                        }));
                        $('#franchiseLocationsRow').show();
                        $('#independentLocationsRow').show();
                        if(json.is_location_specific == "1")
                        {
                            Entity.findAll({entitiable_id: selectedContest, entitiable_type: 'Contest'}, function(entities)
                            {
                                $('#contestLocations option[value="0"]').remove();
                                $('#contestLocations').prop('multiple', true);
                                $('#contestLocations').attr('rows', 5);
                                $('#moreLocations > i').attr('class', 'icon-minus');
                                $('#contestLocations option').prop('selected', false);
                                for(var i = 0; i < entities.length; i++)
                                {
                                    $('#contestLocations option[value="'+entities[i].location_id+'"]').prop('selected', true);
                                }
                            });
                        }
                        if(json.is_location_independent == "1")
                        {
                            $('#independentLocationsControls').show();
                            $('#is_location_independent').prop('checked', true);
                            $('.btn-add-zipcode').prop('disabled', false);
                            $('#independentLocationsAlert').hide();
                            ContestLocation.findAll({contest_id: selectedContest}, function(locations)
                            {
                                $('#independentLocationsArea').html(can.view('template_independent_location', {
                                    locations: locations
                                }));
                            });
                        }
                        else
                        {
                            $('#is_location_independent').prop('checked', false);
                            $('#independentLocationsControls').hide();
                        }
                    });
                }
                else
                {
                    $('#franchiseLocationsRow').hide();
                    $('#independentLocationsRow').hide();
                    $('#contestLocations').html('<option value="0">All</option>');
                }
            });
            if(json.type == 'generic')
                $('#divNumbers').hide();
            else
                $('#divNumbers').show();
            $('#is_national').val(json.is_national);
            $('#contestNumberMin').val(json.winning_number_min);
            $('#contestNumberMax').val(json.winning_number_max);
            $('#contestNumberType').val(json.winning_number_type == '' ? 'num' : json.winning_number_type);
            $('#contestNumberLength').val(json.winning_number_length);
            $('#contestRadius').val(json.radius);
            $('#contestName').val(json.name);
            $('#contestType').val(json.type);
            $('#contestStatus').val(json.is_active);
            $('#contestFranchise').data('franchise_id', json.franchise_id);
            $('#contestDemo').val(json.is_demo);
            $('#contest_listing').val(json.path);
            $('#contest_contestBanner').val(json.banner);
            $('#contest_companyButton').val(json.logo);
            $('#contest_landing').val(json.landing);
            $('#contest_logoButton').val(json.contest_logo);
            CKEDITOR.instances.contestDescription.setData(json.contest_description);
            $('#contestCustIOMember').val(json.customerio_member_attr);
            $('#contestCustIONonMember').val(json.customerio_non_member_attr);
            $('#contestLogoLink').val(json.logo_link);
            $('#contestDisplayName').val(json.display_name);
            $('#contestWufooLink').val(json.wufoo_link);
            $('#contestStart').val(self.GetDate(json.starts_at));
            $('#contestEnd').val(self.GetDate(json.expires_at));
            $('#contestTracking').val(json.tracking_code);
            $('#is_featured_contest').val(json.is_featured);
            $('#btn-add-date').removeClass('disabled');
            $('#dateMessage').hide();
            CKEDITOR.instances.contestRules.setData(json.contest_rules);
            //Hide unnecessary areas
            switch(json.type)
            {
                case 'generic':
                    $('#bannerDim').html('(940px X 196px)');
                    $('#companyDiv').hide();
                    //$('#landingDiv').hide();
                    $('#landingDim').html('(425px X 240px)');
                    $('#logoDiv').hide();
                    break;
                case 'wws':
                    $('#bannerDim').html('(700px X 100px)');
                    $('#landingDim').html('(620px X 540px)');
                    $('#descriptionDiv').hide();
                    $('#wufooDiv').hide();
                    break;
                case 'wws_email':
                    $('#bannerDim').html('(700px X 100px)');
                    $('#landingDim').html('(940px X 540px)');
                    $('#companyDiv').hide();
                    $('#logoDiv').hide();
                    $('#wufooDiv').hide();
                    break;
            }
            $('#contestAutomated').prop('checked', json.is_automated == 1);
            $('#totalInventory').val(json.total_inventory);
            $('#remainingInventory').val(json.current_inventory);
            $('#newRemainingInventory').val(json.current_inventory);
            AwardDate.findAll({contest_id: selectedContest}, function(dates)
            {
                if(dates.length != 0)
                {
                    for(var i=0; i<dates.length; i++)
                    {
                        dates[i].award_at = self.GetDate(dates[i].award_at);
                    }
                    $('#awardDates').html(can.view('template_award_date',
                    {
                        dates: dates
                    }));
                }
                else
                    $('#awardDates').html('');
            });
            $('#contestFollowText').val(json.follow_up_text);
            Coupon.findOne({offer_id: json.follow_up_id}, function(coupon)
            {
                if(coupon)
                {
                    $('#contestCoupon').val(coupon.name);
                    $('#contestCoupon').data('coupon_id', coupon.id);
                    $('#btnCouponEdit').attr('href', '/coupon?viewing='+coupon.franchise_id);
                    $('#btnCouponEdit').show();
                }
                else
                {
                    $('#contestCoupon').val('');
                    $('#contestCoupon').data('coupon_id', 0);
                    $('#btnCouponEdit').hide();    
                }
                $('#resultsGrid').fadeOut(400, function()
                {
                    $('#editBox').fadeIn(400);
                });
            });
        });
    },
    '.btn-award-date-edit click': function(element)
    {
        var self = this;
        AwardDate.findOne({award_date_id: element.data('award_date_id')}, function(date)
        {
            $('#award_at').val(date.award_at);
            $('#winners').val(date.winners);
            $('#prize_name').val(date.prize_name);
            $('#redeemable_at').val(date.redeemable_at);
            $('#prize_description').val(date.prize_description);
            $('#prize_expiration_date').val(self.GetDate(date.prize_expiration_date));
            $('#prize_authorizer').val(date.prize_authorizer);
            $('#prize_authorizer_title').val(date.prize_authorizer_title);
            $('#btn-save-date').data('award_date_id', date.id);

            $('#editBox').fadeOut(300, function()
            {
                $('#dateBox').fadeIn(300);
            });
        });
    },
    '.btn-award-date-delete click': function(element)
    {
        var self = this;
        var mydelete = new DeleteAwardDate({award_date_id: element.data('award_date_id')});
        mydelete.save(function(json)
        {
            AwardDate.findAll({contest_id: selectedContest}, function(dates)
            {
                if(dates.length != 0)
                {
                    for(var i=0; i<dates.length; i++)
                    {
                        dates[i].award_at = self.GetDate(dates[i].award_at);
                    }
                    $('#awardDates').html(can.view('template_award_date',
                    {
                        dates: dates
                    }));
                }
                else
                    $('#awardDates').html('');

                $('#dateBox').fadeOut(300, function()
                {
                    $('#editBox').fadeIn(300);
                });
            });
        });
    },
    '.btn-award-date-copy click': function(element)
    {
        var self = this;
        var mycopy = new CopyAwardDate({award_date_id: element.data('award_date_id')});
        mycopy.save(function(json)
        {
            AwardDate.findAll({contest_id: selectedContest}, function(dates)
            {
                for(var i=0; i<dates.length; i++)
                {
                    dates[i].award_at = self.GetDate(dates[i].award_at);
                }
                $('#awardDates').html(can.view('template_award_date',
                {
                    dates: dates
                }));
            });
        });
    },
    '#btn-add-date click': function(element)
    {
        $('#award_at').val();
        $('#winners').val('');
        $('#prize_name').val('');
        $('#redeemable_at').val('');
        $('#prize_description').val('');
        $('#prize_expiration_date').val('');
        $('#prize_authorizer').val('');
        $('#prize_authorizer_title').val('');
        $('#btn-save-date').data('award_date_id', 0);

        $('#editBox').fadeOut(300, function()
        {
            $('#dateBox').fadeIn(300);
        });
    },
    '#btn-close-date click': function(element)
    {
        $('#dateBox').fadeOut(300, function()
        {
            $('#editBox').fadeIn(300);
        });
    },
    '#btn-save-date click': function(element)
    {
        var self = this;
        var AwardObject = {};
        AwardObject.award_at = this.GetTimeStamp($('#award_at').val());
        AwardObject.winners = $("#winners").val();
        AwardObject.prize_name = $("#prize_name").val();
        AwardObject.redeemable_at = $("#redeemable_at").val();
        AwardObject.prize_description = $('#prize_description').val();
        AwardObject.prize_expiration_date = this.GetTimeStamp($('#prize_expiration_date').val());
        AwardObject.prize_authorizer = $("#prize_authorizer").val();
        AwardObject.prize_authorizer_title = $('#prize_authorizer_title').val();
        AwardObject.contest_id = selectedContest;
        AwardObject.has_prize = prize_name != '' ? 1 : 0;
        if(element.data('award_date_id') == 0)
            var myAwardDate = new NewAwardDate(AwardObject);
        else
        {
            AwardObject.award_date_id = element.data('award_date_id');
            var myAwardDate = new AwardDate(AwardObject);
        }
        myAwardDate.save(function(json)
        {
            AwardDate.findAll({contest_id: selectedContest}, function(dates)
            {
                if(dates.length != 0)
                {
                    for(var i=0; i<dates.length; i++)
                    {
                        dates[i].award_at = self.GetDate(dates[i].award_at);
                    }
                    $('#awardDates').html(can.view('template_award_date',
                    {
                        dates: dates
                    }));
                }
                else
                    $('#awardDates').html('');

                $('#dateBox').fadeOut(300, function()
                {
                    $('#editBox').fadeIn(300);
                });
            });
        });
    },
    'BindPagination': function(data)
    {
        var first = $('#first');
        var prev = $('#prev');
        var next = $('#next');
        var last = $('#last');

        var lastpage = Math.floor((data.stats.total / data.stats.take)) == 0 ? 0 : Math.floor((data.stats.total / data.stats.take));

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
            first.data('page', 0);
        }
        if(data.page == lastpage)
        {
            last.parent().addClass('disabled');
            next.parent().addClass('disabled');
        }
        else
        {
            last.parent().removeClass('disabled');
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
            last.data('page', lastpage);
        }
    },
    '#newRemainingInventoryPrompt click': function()
    {
        $('#newRemainingInventoryModal').modal('show');
    },
    '#btnUpdateRemainingInventory click': function()
    {
        newRemainingInventory = $('#newRemainingInventory').val();
        $('#remainingInventory').val(newRemainingInventory);
        $('#newRemainingInventoryModal').modal('hide');
    }
});

$('#contestFranchise').typeahead({
  minLength: 2,
  highlight: true,
  hint: false,
},
{  
    name: 'my-dataset',
    source: function(query, cb){
        Franchise.findAll({name: query}, function(json)
        {
            var arr = new Array();
            for(var i=0; i<json.length; i++)
            {
                arr[i] = {value: json[i].display, id: json[i].id};
            }
            cb(arr);
        });
    },
    templates: {
        suggestion: function(item){
            return '<p data-franchise_id="'+item.id+'">'+item.value+'</p>';
        }
    }
});

$('#contestLocation').typeahead({
  minLength: 2,
  highlight: true,
  hint: false,
},
{  
    name: 'my-dataset',
    source: function(query, cb){
        Zipcode.findAll({q: query}, function(json)
        {
            var arr = new Array();
            for(var i=0; i<json.length; i++)
            {
                arr[i] = {value: json[i].city+', '+json[i].state, latitude: json[i].latitude, longitude: json[i].longitude};
            }
            cb(arr);
        });
    },
    templates: {
        suggestion: function(item){
            return '<p data-latitude="'+item.latitude+'" data-longitude="'+item.longitude+'">'+item.value+'</p>';
        }
    }
});

$('#contestCoupon').typeahead({
  minLength: 2,
  highlight: true,
  hint: false,
},
{  
    name: 'my-dataset',
    source: function(query, cb){
        Coupon.findAll({query: query, limit: 5}, function(coupons)
        {
            var arr = new Array();
            for(var i=0; i<coupons.length; i++)
            {
                arr[i] = {value: coupons[i].name+' - '+coupons[i].merchant.display, id: coupons[i].id};
            }
            cb(arr);
        });
    },
    templates: {
        suggestion: function(item){
            return '<p>'+item.value+'</p>';
        }
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
        var prev = $('#imgPrev');
        var next = $('#imgNext');
        var current = $('#imgLblCurrentPage');

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
    }
});

contest_control = new ContestControl($('#main'));
modal_control = new ModalControl($('#myModal'));
upload_control = new UploadControl($('#uploadModal'));

</script>