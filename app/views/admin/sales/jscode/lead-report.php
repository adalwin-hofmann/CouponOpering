<script>

Lead = can.Model({
    findAll: 'GET /api/v2/netlms/lead-report'
},{});

Franchise = can.Model({
    findOne: 'GET /find-franchise',
    findAll: 'GET /api/v2/franchise/get-by-name-with-leads?name={name}'
},{});

$(function() {
    $('#startDate').datepicker();
    $('#endDate').datepicker();
});

AssignControl = can.Control({
    init: function()
    {

    },
    //Events
    '#btnSearch click': function()
    {
        this.Search();
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
        SearchObject = {franchise_id: $('#nameSearch').data('franchise_id')};
        if($('#startDate').val() != '')
        {
            SearchObject.start = $('#startDate').val();
        }
        if($('#endDate').val() != '')
        {
            SearchObject.end = $('#endDate').val();
        }
        Lead.findAll(SearchObject, function(leads)
        {
            $('#btnSearch').button('reset');
            self.BindLeads(leads);
        });
    },
    'BindLeads': function(categories)
    {
        //console.log(leads);return;
        $('#resultsArea').html(can.view('template_lead',
        {
            categories: categories
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

$('#nameSearch').typeahead({
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
        $("#nameSearch").data("franchise_id", partsOfFranch[1]);
        return partsOfFranch[0];
    }
});

assign_control = new AssignControl($('body'));

</script>
