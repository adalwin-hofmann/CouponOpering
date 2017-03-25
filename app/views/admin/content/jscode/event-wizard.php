<script>
Event = can.Model({
    findOne: 'GET /wizard-event/{event_id}',
    findAll: 'GET /api/v2/event/get-by-franchise-id?franchise_id={franchise_id}',
    create: 'POST /wizard-update-event/{event_id}'
},{});

Duplicate = can.Model({
    create: 'POST /duplicate-event/{event_id}'
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


$( "#starts_at" ).datepicker();
$( "#expires_at" ).datepicker();
$( "#event_start" ).datepicker();
$( "#event_end" ).datepicker();


EventControl = can.Control({
    init: function()
    {
        $('#btnGallery').tooltip({'title': 'Select Image From Gallery Or Leave Blank To Use Merchant Logo'});
        $('.btn-copy').tooltip({'title': 'Duplicate This Event'});
        $('.btn-save').tooltip({'title': 'Save This Event'});
        $('.btn-add-event').tooltip({'title': 'Add New Event'});
        CKEDITOR.replace('description', {height:"155"});
        this.Search();
        if(selectedMerchant == 0)
        {
            return;
        }
    },
    //Events
    '.search-query keypress': function( element, event ) 
    {       
        var self = this; 
        if(element.val().length > 2)
        {
            selectedEvent = 0;
            $('.row-selectable').removeClass('row-selected');
            $('.btn-add-event').hide();
            $('.btn-save').html('Save New');
            this.SetBlank();
            currentPage = 0;
            self.Search();
        }
        else if(event.which==13)
        {
            selectedOffer = 0;
            $('.row-selectable').removeClass('row-selected');
            $('.btn-add-event').hide();
            $('.btn-save').html('Save New');
            this.SetBlank();
            currentPage = 0;
            self.Search();
        }
    },
    '#eventPaginationBottom > li > a click': function(element)
    {
        selectedOffer = 0;
        $('.row-selectable').removeClass('row-selected');
        $('.btn-add-event').hide();
        $('.btn-save').html('Save New');
        this.SetBlank();
        currentPage = element.data('page');
        this.Search();
    },
    '.row-select click': function(element)
    {
        var self = this;
        $('.row-selectable').removeClass('row-selected');
        $('.btn-add-event').show();
        $('.btn-save').html('Save');
        $('.btn-copy').show();
        element.parent().addClass('row-selected');
        selectedEvent = element.data('event_id');
        self.SetBlank();
        Event.findOne({event_id: selectedEvent}, function(data)
        {
            $('#image').val(data.path);
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
            $('#is_featured').val(data.is_featured);
            $('#status').val(data.is_active);
            $('#is_demo').val(data.is_demo);
            CKEDITOR.instances.description.setData(data.description);
            $('#starts_at').val(self.GetDate(data.attr('starts_at')));
            $('#expires_at').val(self.GetDate(data.attr('expires_at')));
            $('#event_start').val(self.GetDate(data.attr('event_start')));
            $('#event_end').val(self.GetDate(data.attr('event_end')));
            $('#short_name_line1').val(data.short_name_line1);
            $('#short_name_line2').val(data.short_name_line2);
            $('#custom_category_id').val(data.custom_category_id);
            $('#website').val(data.website);
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

        });
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
        var event_id = element.data('event_id');
        var status = element.data('status');
        var EventObject = new Object();
        EventObject.event_id = event_id;
        EventObject.status = status ? 0 : 1;
        var myEvent = new Event(EventObject);
        myEvent.save(function(json)
        {
            self.SetBlank();
            self.Search();
        });
    },
    '.btn-add-event click': function()
    {
        selectedOffer = 0;
        $('.row-selectable').removeClass('row-selected');
        $('.btn-add-event').hide();
        $('.btn-copy').hide();
        $('.btn-save').html('Save New');
        this.SetBlank();
    },
    '.btn-copy click': function(element)
    {
        element.button('loading');
        var self = this;
        var myDup = new Duplicate({event_id: selectedEvent});
        myDup.save(function(event)
        {
            element.button('reset');
            $('.event-messages').hide();
            $('.event-messages').css('color', 'green');
            $('.event-messages').html('Event Copied!');
            $('.event-messages').fadeIn(400, function()
            {
                $('.event-messages').fadeOut(4000);     
            });
            self.Search(function()
            {
                $('#eventResultsArea').find(".row-select[data-event_id='" + event.id +"']").click();
            });
        });
    },
    '#startNow click': function()
    {
        var d = new Date();
        $('#starts_at').val(Number(d.getMonth())+1+'/'+d.getDate()+'/'+d.getFullYear());
    },
    '.btn-save click': function(element)
    {
        element.button('loading');
        var self = this;
        var EventObject = this.Validate();
        if(EventObject.errors.length > 0)
        {
            for(var i=0; i<EventObject.errors.length; i++)
            {
                $('#'+EventObject.errors[i]).parent().parent().addClass('error');
            }
            $('.event-messages').hide();
            $('.event-messages').css('color', 'red');
            $('.event-messages').html('Please fill out all required fields!');
            $('.event-messages').fadeIn(400);
            element.button('reset');
        }
        else
        {
            EventObject.is_featured = $('#is_featured').val();
            EventObject.path = $('#image').val()
            EventObject.event_id = selectedEvent;
            EventObject.merchant_id = selectedMerchant;
            EventObject.franchise_id = selectedFranchise;
            EventObject.short_name_line1 = $('#short_name_line1').val();
            EventObject.short_name_line2 = $('#short_name_line2').val();
            EventObject.custom_category_id = $('#custom_category_id').val();
            EventObject.custom_subcategory_id = $('#custom_subcategory_id').val();
            EventObject.category_visible = $('#category_visible').val();
            EventObject.website = $('#website').val();
            
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
                EventObject.locations = locations;
            }
            EventObject.is_location_specific = location_specific;

            var myEvent = new Event(EventObject);
            myEvent.save(function(json)
            {
                element.button('reset');
                if(selectedEvent == 0)
                {
                    self.Search();
                    self.SetBlank();
                    $('.event-messages').hide();
                    $('.event-messages').css('color', 'green');
                    $('.event-messages').html('New Event Added!');
                    $('.event-messages').fadeIn(400, function()
                    {
                        $('.event-messages').fadeOut(4000);     
                    });
                }
                else
                {
                    self.Search();
                    self.SetBlank();
                    $('.event-messages').hide();
                    $('.event-messages').css('color', 'green');
                    $('.event-messages').html('Event Saved!');
                    $('.event-messages').fadeIn(400, function()
                    {
                        $('.event-messages').fadeOut(4000);     
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
    //Methods
    'Search': function(callback)
    {
        var self = this;
        var query = $('.search-query').val();
        if(selectedMerchant == 0)
        {
            return;
        }
        Event.findAll({filter: '%'+query+'%', franchise_id: selectedFranchise, page: currentPage, limit: 5, is_active: ($('#showInactive').prop('checked') ? '-1' : '1')}, function(events)
        {
            self.BindEvents(events);
            self.BindPagination(events);
            if(typeof callback !== "undefined")
            {
                callback();
            }
        });
    },
    'Validate': function()
    {
        $('.event-messages').hide();
        $('.form-group').removeClass('error');
        var EventObject = new Object();
        
        EventObject.name = $('#name').val();
        EventObject.description = CKEDITOR.instances.description.getData();
        EventObject.starts_at = $('#starts_at').val();
        EventObject.expires_at = $('#expires_at').val();
        EventObject.event_start = $('#event_start').val();
        EventObject.event_end = $('#event_end').val();
        EventObject.status = $('#status').val();
        EventObject.is_demo = $('#is_demo').val();
        EventObject.errors = [];

        for(var input in EventObject)
        {
            if(EventObject[input] == '' && input != 'errors')
            {
                EventObject.errors.push(input);
            }
        }

        return EventObject;
    },
    'BindEvents': function(events)
    {
        for(var i=0; i<events.length; i++)
        {
            events[i].starts_at = this.GetDate(events[i].attr('starts_at'));
            events[i].expires_at = this.GetDate(events[i].attr('expires_at'));
        }
        $('#eventResultsArea').html(can.view('template_event',
        {
            results: events
        }));
        $('.tooltip').remove();
        $('.row-selectable').tooltip();
    },
    'BindPagination': function(data)
    {
        var prev = $('#eventPrev');
        var next = $('#eventNext');
        var current = $('#eventLblCurrentPage');

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

event_control_area = new EventControl($('.grid-content'));
modal_control = new ModalControl($('#myModal'));
upload_control = new UploadControl($('#uploadModal'));

</script>