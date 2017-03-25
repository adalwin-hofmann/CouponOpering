<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=AIzaSyCJOT4rvL2wYvcx18Iu2LFOOEC2MN_DeFA" type="text/javascript"></script>
<script>
MerchantLocation = can.Model({
    findOne: 'GET /wizard-location/{location_id}',
    findAll: 'GET /api/location/get-by-franchise-id?franchise_id={franchise_id}',
    create: 'POST /wizard-update-location/{location_id}'
},{});

MerchantLocationDelete = can.Model({
    create: 'POST /wizard-delete-location/{location_id}'
},{});

Logo = can.Model({
    findOne: 'GET /api/v2/asset/get-location-logo?location_id={location_id}'
},{});

Banner = can.Model({
    findOne: 'GET /api/v2/asset/get-location-banner?location_id={location_id}'
},{});

LocationControl = can.Control({
    init: function()
    {
        CKEDITOR.replace('subheader', {height:"400"});
        $('#btnBulkApply').tooltip({'title': 'Apply Bulk Hours To Selected Days', 'placement': 'top'});
        $('.hours-clear').tooltip({'title': 'Clear This Day\'s Hours', 'placement': 'right'});
        $('#btnAdd').tooltip({'title': 'Add New Location', 'placement': 'top'});
        $('#btnDelete').tooltip({'title': 'Delete This Location', 'placement': 'top'});
        $('#btnCopy').tooltip({'title': 'Copy This Location', 'placement': 'top'})
        $('#btnSave').tooltip({'title': 'Save This Location', 'placement': 'top'});
        this.SetBlank();
        this.Search();
    },
    //Events
    '.search-query keypress': function( element, event ) 
    {       
        var self = this; 
        if(element.val().length > 2)
        {
            selectedLocation = 0;
            $('.row-selectable').removeClass('row-selected');
            $('#btnAdd').hide();
            $('#btnDelete').hide();
            $('#btnCopy').hide();
            $('#btnSave').html('Save New');
            this.SetBlank();
            currentPage = 0;
            self.Search();
        }
        else if(event.which==13)
        {
            selectedLocation = 0;
            $('.row-selectable').removeClass('row-selected');
            $('#btnAdd').hide();
            $('#btnDelete').hide();
            $('#btnCopy').hide();
            $('#btnSave').html('Save New');
            this.SetBlank();
            currentPage = 0;
            self.Search();
        }
    },
    '.pagination > ul > li > a click': function(element)
    {
        selectedLocation = 0;
        $('.row-selectable').removeClass('row-selected');
        $('#btnAdd').hide();
        $('#btnDelete').hide();
        $('#btnCopy').hide();
        $('#btnSave').html('Save New');
        this.SetBlank();
        currentPage = element.data('page');
        this.Search();
    },
    '.row-selectable click': function(element)
    {
        var self = this;
        $('.row-selectable').removeClass('row-selected');
        $('#btnAdd').show();
        $('#btnDelete').show();
        $('#btnCopy').show();
        $('#btnSave').html('Save');
        element.addClass('row-selected');
        selectedLocation = element.data('location_id');
        MerchantLocation.findOne({location_id: selectedLocation}, function(location)
        {
            self.SetBlank();
            $('#redirect_number').val(location.redirect_number);
            $('#redirect_text').val(location.redirect_text);
            $('#name').val(location.name);
            $('#display_name').val(location.display_name);
            $('#is_address_hidden').prop('checked', location.is_address_hidden == 1 ? true : false);
            $('#custom_address_text').val(location.custom_address_text);
            $('#address').val(location.address);
            $('#address2').val(location.address2);
            $('#city').val(location.city);
            $('#state').val(location.state);
            $('#zipcode').val(location.zip);
            $('#phone').val(location.phone);
            $('#website').val(location.website);
            $('#twitter').val(location.twitter);
            $('#facebook').val(location.facebook);
            $('#custom_website').val(location.custom_website);
            $('#custom_website_text').val(location.custom_website_text);
            $('#status').val(location.is_active);
            $('#company_id').val(location.company_id);
            $('#is_deleted').val(location.deleted_at == null ? 0 : 1);
            $('#is_logo_specific').prop('checked', location.is_logo_specific == 1 ? true : false);
            $('#is_banner_specific').prop('checked', location.is_banner_specific == 1 ? true : false);
            $('#is_24_hours').prop('checked', location.is_24_hours == 1 ? true : false);
            $("#logoLocationId").val(location.id);
            $("#bannerLocationId").val(location.id);
            if(location.is_logo_specific == 1)
            {
                $('#logoRow').show();
                Logo.findOne({location_id: location.id}, function(json)
                {
                    $('#logoImg').attr('src', json.path);
                });
            }
            else
            {
                $('#logoRow').hide();
                $('#logoImg').attr('src', 'http://placehold.it/500X300');
            }
            if(location.is_banner_specific == 1)
            {
                $('#bannerRow').show();
                Banner.findOne({location_id: location.id}, function(json)
                {
                    $('#bannerImg').attr('src', json.path);
                });
            }
            else
            {
                $('#bannerRow').hide();
                $('#bannerImg').attr('src', 'http://placehold.it/988X250');
            }
            CKEDITOR.instances.subheader.setData(location.subheader);
            for(var i=0; i< location.hours_array.objects.length; i++)
            {
                var day = location.hours_array.objects[i].attributes.weekday;
                $('#'+day+'_start').val(location.hours_array.objects[i].attributes.start_time);
                $('#'+day+'_start_ampm').val(location.hours_array.objects[i].attributes.start_ampm);
                $('#'+day+'_end').val(location.hours_array.objects[i].attributes.end_time);
                $('#'+day+'_end_ampm').val(location.hours_array.objects[i].attributes.end_ampm);
            }
        });
    },
    '#is_logo_specific change': function(element)
    {
        if(element.prop('checked'))
            $('#logoRow').show();
        else
            $('#logoRow').hide();
    },
    '#is_banner_specific change': function(element)
    {
        if(element.prop('checked'))
            $('#bannerRow').show();
        else
            $('#bannerRow').hide();
    },
    '#ieDoneButton click': function()
    {
        Logo.findOne({location_id: selectedLocation}, function(json)
        {
            if(typeof json.path !== 'undefined')
                $('#logoImg').attr('src', json.path);
        });
        Banner.findOne({location_id: selectedLocation}, function(json)
        {
            if(typeof json.path !== 'undefined')
                $('#bannerImg').attr('src', json.path);
        });
    },
    '#btnAdd click': function()
    {
        selectedLocation = 0;
        $('.row-selectable').removeClass('row-selected');
        $('#btnAdd').hide();
        $('#btnSave').html('Save New');
        this.SetBlank();
    },
    '#btnCopy click': function()
    {
        selectedLocation = 0;
        $('.row-selectable').removeClass('row-selected');
        $('#btnAdd').hide();
        $('#btnDelete').hide();
        $('#btnSave').html('Save New');
    },
    '#btnSave click': function()
    {
        var self = this;
        var LocationObject = this.Validate();
        if(LocationObject.errors.length > 0)
        {
            for(var i=0; i<LocationObject.errors.length; i++)
            {
                $('#'+LocationObject.errors[i]).parent().parent().addClass('error');
            }
            $('#messages').hide();
            $('#messages').css('color', 'red');
            $('#messages').html('Please fill out all required fields!');
            $('#messages').fadeIn(400);
        }
        else
        {
            var response = this.ValidateHours();
            if(response.errors.length > 0)
            {
                for(var i=0; i<response.errors.length; i++)
                {
                    $('#'+response.errors[i]+"_start").parent().parent().addClass('error');
                }
                $('#messages').hide();
                $('#messages').css('color', 'red');
                $('#messages').html('Both a start time and an end time with AM/PM are required!');
                $('#messages').fadeIn(400);
            }
            else
            {
                LocationObject.redirect_number = $('#redirect_number').val();
                LocationObject.redirect_text = $('#redirect_text').val();
                LocationObject.display_name = $('#display_name').val();
                LocationObject.is_address_hidden = $('#is_address_hidden').prop('checked') ? 1 : 0;
                LocationObject.custom_address_text = $('#custom_address_text').val();
                LocationObject.location_id = selectedLocation;
                LocationObject.merchant_id = selectedMerchant;
                LocationObject.fax = $('#fax').val();
                LocationObject.website = $('#website').val();
                LocationObject.facebook = $('#facebook').val();
                LocationObject.twitter = $('#twitter').val();
                LocationObject.hours = response.hours_string;
                LocationObject.address2 = $('#address2').val();
                LocationObject.hours_object = response.hours;
                LocationObject.franchise_id = selectedFranchise;
                LocationObject.is_deleted = $('#is_deleted').val();
                LocationObject.custom_website = $('#custom_website').val();
                LocationObject.custom_website_text = $('#custom_website_text').val();
                LocationObject.is_logo_specific = $('#is_logo_specific').prop('checked') ? 1 : 0;
                LocationObject.is_banner_specific = $('#is_banner_specific').prop('checked') ? 1 : 0;
                LocationObject.is_24_hours = $('#is_24_hours').prop('checked') ? 1 : 0;
                LocationObject.subheader = CKEDITOR.instances.subheader.getData();
                var geocoder = new GClientGeocoder();
                var address = LocationObject.address+' '+LocationObject.city+', '+LocationObject.state;
                geocoder.getLatLng(address, function(point)
                {
                    LocationObject.latitude = point.lat();
                    LocationObject.longitude = point.lng();
                    var myMerchantLocation = new MerchantLocation(LocationObject);
                    myMerchantLocation.save(function(json)
                    {
                        if(selectedLocation == 0)
                        {
                            self.Search();
                            self.SetBlank();
                            $('#messages').hide();
                            $('#messages').css('color', 'green');
                            $('#messages').html('New Location Added!');
                            $('#messages').fadeIn(400, function()
                            {
                                $('#messages').fadeOut(4000);     
                            });
                        }
                        else
                        {
                            self.Search();
                            $('#messages').hide();
                            $('#messages').css('color', 'green');
                            $('#messages').html('Location Saved!');
                            $('#messages').fadeIn(400, function()
                            {
                                $('#messages').fadeOut(4000);     
                            });
                        }
                    });
                });

                
            }
        }
    },
    '#btnDelete click': function()
    {
        $('#deleteModal').modal('show');
    },
    '#btnDeleteConfirm click': function()
    {
        var self = this;
        var LocationObject = new Object();
        LocationObject.location_id = selectedLocation;
        var myMerchantLocationDelete = new MerchantLocationDelete(LocationObject);
        myMerchantLocationDelete.save(function(json)
        {
            self.Search();
            self.SetBlank();
            $('#deleteModal').modal('hide');
        });
    },
    'a.hours-clear click': function(element)
    {
        var day = element.data('day');
        $('#'+day+'_start').val('');
        $('#'+day+'_start_ampm').val('');
        $('#'+day+'_end').val('');
        $('#'+day+'_end_ampm').val('');
    },
    '#allCheck change': function(element, event)
    {
        console.log(element);
        if(element.prop('checked'))
        {
            $('[id$=_bulkCheck]').prop('checked',1);
        } else {
            $('[id$=_bulkCheck]').prop('checked',0);
        }
    },
    '#btnBulkApply click': function()
    {
        var start = $('#Bulk_start').val();
        var start_ampm = $('#Bulk_start_ampm').val();
        var end = $('#Bulk_end').val();
        var end_ampm = $('#Bulk_end_ampm').val();
        $('[id$=_bulkCheck]:checked').each(function(){
            var aPieces = $(this).attr('id').split('_');
            $('#'+aPieces[0]+'_start').val(start).effect('highlight', {color: "#66FF94", duration: 1500});
            $('#'+aPieces[0]+'_start_ampm').val(start_ampm).effect('highlight', {color: "#66FF94", duration: 1500});
            $('#'+aPieces[0]+'_end').val(end).effect('highlight', {color: "#66FF94", duration: 1500});
            $('#'+aPieces[0]+'_end_ampm').val(end_ampm).effect('highlight', {color: "#66FF94", duration: 1500});
            $(this).prop('checked', false);
        });
        $('#Bulk_start').val('');
        $('#Bulk_start_ampm').val('');
        $('#Bulk_end').val('');
        $('#Bulk_end_ampm').val('');
    },
    '#showInactive change': function(element)
    {
        this.Search();
    },
    '#showDeleted change': function(element)
    {
        this.Search();
    },
    '.dynamic click': function(element)
    {
        var text = element.data('text');
        if(text == '')
        {
            text = '{}'
        }
        this.InsertText(text);
    },
    '#selLocation change': function(element)
    {
        if(element.val() == 0)
        {
            CKEDITOR.instances.locAbout.setData(CKEDITOR.instances.about.getData());
            $('#locTitle').val($('#page_title').val());
            $('#locKeywords').val($('#keywords').val());
            $('#locDescription').val($('#meta_description').val());
            return;
        }
        Location.findOne({location_id: element.val()}, function(json)
        {
            console.log(json);
            CKEDITOR.instances.locAbout.setData(json.about);
            $('#locTitle').val(json.page_title);
            $('#locKeywords').val(json.keywords);
            $('#locDescription').val(json.meta_description);
        });
    },
    '#btnLocSave click': function(element)
    {
        if($('#selLocation').val() == 0)
            return;
        var AboutObject = {};
        AboutObject.franchise_id = selectedFranchise;
        AboutObject.about = CKEDITOR.instances.locAbout.getData();
        AboutObject.page_title = $('#locTitle').val();
        AboutObject.keywords = $('#locKeywords').val();
        AboutObject.meta_description = $('#locDescription').val();
        AboutObject.location_id = $('#selLocation').val();
        var myAbout = new About(AboutObject);
        myAbout.save(function(json)
        {
            $("#locMessages").html('Location Saved!');
            $("#locMessages").fadeIn(500, function()
            {
                $("#locMessages").fadeOut(10000);   
            })
        });
    },
    //Methods
    'InsertText': function(text) 
    {
        var editor = CKEDITOR.instances.subheader;
        var orig_selection = editor.getSelection().getRanges()[0];
        var orig_container = orig_selection.startContainer;
        editor.insertText(text);
        var selection = editor.getSelection().getRanges()[0];
        var container = selection.startContainer;
        var offset = text == '{}' ? 1 : text.length;

        if(container.type != CKEDITOR.NODE_TEXT && orig_container.type != CKEDITOR.NODE_TEXT)
        {
            selection.setStart(container.getLast().getPrevious(), offset);
            selection.setEnd(container.getLast().getPrevious(), offset);
            selection.select();
        }
        else
        {
            if(orig_selection.startOffset == 0)
            {
                selection.setStart(orig_container.getPrevious(), offset);
                selection.setEnd(orig_container.getPrevious(), offset);
                selection.select();
            }
            else
            {
                selection.setStart(orig_container.getNext(), offset);
                selection.setEnd(orig_container.getNext(), offset);
                selection.select();
            }
        }
    },
    'Search': function()
    {
        var self = this;
        var query = $('.search-query').val();
        MerchantLocation.findAll({filter: '%'+query+'%', franchise_id: selectedFranchise, page: currentPage, limit: 5, is_active: ($('#showInactive').prop('checked') ? '-1' : '1'), is_deleted: ($('#showDeleted').prop('checked') ? '-1' : '1')}, function(json){
            self.BindLocations(json);
            self.BindPagination(json);
        });
    },
    'Validate': function()
    {
        $('#messages').hide();
        $('.form-group').removeClass('error');
        var LocationObject = new Object();
        
        LocationObject.name = $('#name').val();
        LocationObject.address = $('#address').val();
        LocationObject.city = $('#city').val();
        LocationObject.state = $('#state').val();
        LocationObject.zipcode = $('#zipcode').val();
        LocationObject.phone = $('#phone').val();
        LocationObject.status = $('#status').val();
        LocationObject.company_id = $('#company_id').val();
        LocationObject.errors = [];

        for(var input in LocationObject)
        {
            if(LocationObject[input] == '' && input != 'errors')
            {
                LocationObject.errors.push(input);
            }
        }

        return LocationObject;
    },
    'ValidateHours': function()
    {
        $('#messages').hide();
        $('.form-group').removeClass('error');
        var hours_string = '';
        var hours = [];
        var weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var errors = [];
        for(var i=0; i<weekdays.length; i++)
        {
            if($('#'+weekdays[i]+'_start').val() != '' && $('#'+weekdays[i]+'_end').val() != '')
            {
                if($('#'+weekdays[i]+'_start_ampm').val() != '' && $('#'+weekdays[i]+'_end_ampm').val() != '')
                {
                    var HourObject = new Object();
                    HourObject.day = weekdays[i];
                    HourObject.start = $('#'+weekdays[i]+'_start').val();
                    HourObject.start_ampm = $('#'+weekdays[i]+'_start_ampm').val();
                    HourObject.end = $('#'+weekdays[i]+'_end').val();
                    HourObject.end_ampm = $('#'+weekdays[i]+'_end_ampm').val();
                    hours.push(HourObject);
                    hours_string += weekdays[i]+": "+$('#'+weekdays[i]+'_start').val()+$('#'+weekdays[i]+'_start_ampm').val()+" - "+$('#'+weekdays[i]+'_end').val()+$('#'+weekdays[i]+'_end_ampm').val()+"<br/>";
                }
                else
                {
                    //Need both start and end ampm filled out
                    errors.push(weekdays[i]);
                }
            }
            else if(($('#'+weekdays[i]+'_start').val() == '' || $('#'+weekdays[i]+'_end').val() == '') && ($('#'+weekdays[i]+'_start').val() != $('#'+weekdays[i]+'_end').val()))
            {
                //Need both start and end times filled out
                errors.push(weekdays[i]);
            }
        }
        var response = new Object();
        response.errors = errors;
        response.hours_string = hours_string;
        response.hours = hours;
        return response;
    },
    'BindLocations': function(data)
    {
        $('#locResultsArea').html(can.view('template_location',
        {
            results: data
        })).find('tr.row-selectable').tooltip();
    },
    'BindPagination': function(data)
    {
        var prev = $('#locPrev');
        var next = $('#locNext');
        var current = $('#locLblCurrentPage');

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
        $('#addressArea :input').val('');
        $('#hoursArea :input').val('');
        $('#is_24_hours').prop('checked', false);
    }
});

loc_control_area = new LocationControl($('.grid-content'));

</script>