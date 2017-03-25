<script>

Lead = can.Model({
    findAll: 'GET /api/v2/netlms/lead-search',
    create: 'GET /api/v2/netlms/lead-assign'
},{});

Franchise = can.Model({
    findOne: 'GET /find-franchise',
    findAll: 'GET /api/v2/franchise/get-by-name-with-leads?name={name}'
},{});

AssignControl = can.Control({
    init: function()
    {

    },
    //Events
    '#nameSearch keyup': function(element, event)
    {
        if(event.which == 13)
        {
            this.Search();
        }
    },
    '#btnSearch click': function()
    {
        this.Search();
    },
    '.lead-row click': function(element)
    {
        this.MerchantClear();
        selectedLead = element.data('lead_id');
        $('#assignmentModal').modal('show');
    },
    '#merchantClear click': function(element)
    {
        this.MerchantClear();
    },
    '#btnAssign click': function(element)
    {
        if($('#merchantSearch').val() == '' || $('#merchantSearch').data('franchise_id') == 0)
            return;
        var myLead = new Lead({lead_id: selectedLead, franchise_id: $('#merchantSearch').data('franchise_id')});
        myLead.save(function(json)
        {
            if(json.data[0].status == 200)
            {
                $('#assignMessages').css('color', 'green');
                $('#assignMessages').hide();
                $('#assignMessages').html('Lead Assigned!');
                $('#assignMessages').fadeIn(500, function()
                {
                    $('#assignMessages').fadeOut(10000); 
                });
            }
            else
            {
                $('#assignMessages').css('color', 'red');
                $('#assignMessages').hide();
                $('#assignMessages').html('Assignment Failure!');
                $('#assignMessages').fadeIn(500, function()
                {
                    $('#assignMessages').fadeOut(10000); 
                });
            }
        });
    },
    //Methods
    'MerchantClear': function()
    {
        $('#merchantSearch').val('');
        $('#merchantSearch').data('franchise_id', 0);
    },
    'Search': function()
    {
        var self = this;
        if($('#nameSearch').val() == '')
            return;
        $('#btnSearch').button('loading');
        Lead.findAll({name: $('#nameSearch').val()}, function(leads)
        {
            $('#btnSearch').button('reset');
            self.BindLeads(leads);
        });
    },
    'BindLeads': function(leads)
    {
        $('#resultsArea').html(can.view('template_lead',
        {
            leads: leads
        }));
    },
    'GetDate': function(time)
    {
        if(typeof time === 'object')
        {
            time = time.date
        }
        var c = time.split(/[- :]/);
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
    }
});

$('#merchantSearch').typeahead({
    source: function (query, process) 
    {
        if(query.length < 3)
        {
            return process([]);
        }
        else
        {
            return Franchise.findAll({name: query}, function(json)
            {
                var arr = new Array();
                for(var i=0; i<json.length; i++)
                {
                    arr[i] = json[i].merchant_display+' - '+json[i].maghub_id+'<span style="display:none;">|'+json[i].id+'</span>';
                }
                return process(arr);
            });
        }
    },
    matcher: function(item) {
        return true
    },
    updater: function (item) {
        var newitem = item.replace(/(<([^>]+)>)/ig, "");
        var partsOfFranch = newitem.split('|');
        $("#merchantSearch").data("franchise_id", partsOfFranch[1]);
        return partsOfFranch[0];
    }
});

assign_control = new AssignControl($('body'));

</script>
