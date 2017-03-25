<script>
$('#event_date').datepicker();

DeleteEvent = can.Model({
    create: 'GET /api/v2/company-event/delete?id={event_id}'
},{});

CompanyEventSearch = can.Model({
    findAll: 'GET /api/v2/company-event/search?keyword={query}'
},{});

EventControl = can.Control({
    init: function()
    {
        //this.Search();
    },
    //Events
    '#eventResults .row-selectable click': function(element)
    {
    	$('.row-selectable').removeClass('row-selected');
    	element.addClass('row-selected');
        selectedEvent = element.data('event_id');
        CompanyEvent.findOne({event_id: selectedEvent}, function(data)
        {
            $('#event_id').val(data.id);
            $('#name').val(data.name);
            $('.url-group').removeClass('hidden');
            $('#url').val('http://www.saveon.com/events/'+data.slug);
            $('#description').val(data.description);
            $('.btn-attendees-modal').prop('disabled', false);
            $('.btn-attendees-modal').data('event_id', data.id);
            $('.btn-delete').prop('disabled', false);
            $('.btn-delete').data('event_id', data.id);

            var datetime = data.date.split(' ');
            // Convert Date
            var date = datetime[0].split('-');
            date = date[1]+'/'+date[2]+'/'+date[0];
            $('#event_date').val(date);
            // Convert Time
            var time = datetime[1].split(':');
            var suffix = (time[0] >= 12)? ' PM' : ' AM';
            var hours = (time[0] > 12)? time[0] -12 : time[0];
            hours = (hours == '00')? 12 : hours;
            time = hours+':'+time[1]+suffix;
            $('#event_time').val(time);

            if(data.end_datetime != '0000-00-00 00:00:00')
            {
                var datetime = data.end_datetime.split(' ');
                // Convert Time
                var time = datetime[1].split(':');
                var suffix = (time[0] >= 12)? ' PM' : ' AM';
                var hours = (time[0] > 12)? time[0] -12 : time[0];
                hours = (hours == '00')? 12 : hours;
                time = hours+':'+time[1]+suffix;
                $('#end_datetime').val(time);
            } else {
                $('#end_datetime').val('');
            }
            
        });
    },
    '.btn-save click': function(element)
    {
        var self = this;
        // Convert Date
        var date = $('#event_date').val();
        var dateArray = date.split('/');
        date = dateArray[2]+'-'+dateArray[0]+'-'+dateArray[1];
        // Convert Time
        var time = $('#event_time').val().toUpperCase();
        var hrs = Number(time.match(/^(\d+)/)[1]);
        var mnts = Number(time.match(/:(\d+)/)[1]);
        var format = time.match(/\s(.*)$/)[1];
        if (format == "PM" && hrs < 12) hrs = hrs + 12;
        if (format == "AM" && hrs == 12) hrs = hrs - 12;
        var hours = hrs.toString();
        var minutes = mnts.toString();
        if (hrs < 10) hours = "0" + hours;
        if (mnts < 10) minutes = "0" + minutes;
        time = hours + ":" + minutes;

        //End Time
        if ($('#end_datetime').val() != '')
        {
            // Convert Time
            var end_time = $('#end_datetime').val().toUpperCase();
            var hrs = Number(end_time.match(/^(\d+)/)[1]);
            var mnts = Number(end_time.match(/:(\d+)/)[1]);
            var format = end_time.match(/\s(.*)$/)[1];
            if (format == "PM" && hrs < 12) hrs = hrs + 12;
            if (format == "AM" && hrs == 12) hrs = hrs - 12;
            var hours = hrs.toString();
            var minutes = mnts.toString();
            if (hrs < 10) hours = "0" + hours;
            if (mnts < 10) minutes = "0" + minutes;
            end_time = hours + ":" + minutes;
        }

        var myEvent = new CompanyEvent(
        {
            event_id: $('#event_id').val(),
            name: $('#name').val(),
            slug: $('#name').val().toLowerCase().replace(/[^0-9a-z]+/g, '-'),
            date: date+' '+time+':00',
            end_datetime: ($('#end_datetime').val() != '')?date+' '+end_time+':00':'0000-00-00 00:00:00',
            description: $('#description').val()
        });
        myEvent.save(function(json)
        {
            $('.saved-message').html('Saved! URL: http://www.saveon.com/events/'+json.slug);
            self.Search();
        });
    },
    '.btn-new click': function(element)
    {
        $('.row-selectable').removeClass('row-selected');
        $('#event_id').val(0);
        $('#name').val('');
        $('#event_date').val('');
        $('#event_time').val('');
        $('#end_datetime').val('');
        $('#description').val('');
        $('.url-group').addClass('hidden');
        $('.btn-attendees-modal').prop('disabled', true);
        $('.btn-delete').prop('disabled', true);
    },
    '.btn-attendees-modal click': function(element)
    {
        var self = this;
        event_id = element.data('event_id');
        var SearchObject = new Object();
        SearchObject.event_id = event_id;
        CompanyEventAttendee.findAll(SearchObject, function(attendees)
        {
            self.BindAttendees(attendees);
        });
        $('#attendeesModal').modal('show');
    },
    '.btn-delete click': function(element)
    {
        $('.btn-delete-confirm').data('event_id', element.data('event_id'));
        $('#deleteModal').modal('show');
    },
    '.btn-delete-confirm click': function(element)
    {
        var self = this;
        var event_id = element.data('event_id');
        var myDelete = new DeleteEvent({event_id: event_id})
        myDelete.save(function(json)
        {
            self.Search();
            $('.row-selectable').removeClass('row-selected');
            $('#event_id').val(0);
            $('#name').val('');
            $('#event_date').val('');
            $('#event_time').val('');
            $('#end_datetime').val('');
            $('#description').val('');
            $('.url-group').addClass('hidden');
            $('.btn-attendees-modal').prop('disabled', true);
            $('.btn-delete').prop('disabled', true);
        });
        $('#deleteModal').modal('hide');
    },
    '#filterName keyup': function(element, event)
    {
        var self = this;
        var query = encodeURIComponent(element.val());
        if (query.length > 2)
        {
            CompanyEventSearch.findAll({query: query}, function(events)
            {
                self.BindEventList(events);
            });
        } else {
            self.Search();
        }
    },
    //Methods
    'Search': function()
    {
        var self = this;
        var SearchObject = new Object();
        CompanyEvent.findAll(SearchObject, function(events)
        {
            self.BindEventList(events);
        });
    },
    'BindEventList': function(events)
    {
        for(var i=0; i<events.length; i++)
        {
            //events[i].starts_at = this.GetDate(coupons[i].attr('starts_at'));
            var datetime = events[i].date.split(' ');
            // Convert Date
            var date = datetime[0].split('-');
            events[i].dateFormat = date[1]+'/'+date[2]+'/'+date[0];
        }
        $('#eventResults').html(can.view('template_event_list',
        {
            events: events
        }));
    },
    'BindAttendees': function(attendees)
    {
        $('#attendeesResults').html(can.view('template_attendees_list',
        {
            attendees: attendees
        }));
        if(attendees.length > 0)
        {
            $('.attendees-results').removeClass('hidden');
            $('.attendees-no-results').addClass('hidden');
        } else {
            $('.attendees-results').addClass('hidden');
            $('.attendees-no-results').removeClass('hidden');
        }
    },
});
event_control = new EventControl($('body'));

</script>