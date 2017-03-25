<script>
Category = can.Model({
    findAll: 'GET /api/v2/category/get-by-parent-id'
},{});

$("#customStart").datepicker();
$("#customEnd").datepicker();

var MerchantControl = can.Control({
    init: function(element, options)
    {
    },
    //Events
    '#btnSearch click': function()
    {
        var location = this.GetLocation();
        location += this.GetOrder();
        this.SetLocation(location);
    },
    '.pagination button click': function(element)
    {
        var location = this.GetLocation();
        location += this.GetOrder();
        location += '&page='+element.data('page');
        this.SetLocation(location);
    },
    '.sort-link click': function(element)
    {
        var location = this.GetLocation();
        if(element.data('order') == order)
        {
            direction = direction == 'asc' ? 'desc' : 'asc';
        }
        else
            direction = 'asc';
        this.SetLocation(location+'&order='+element.data('order')+'&direction='+direction);
    },
    '.btn-print-report click': function(element)
    {
        
    },
    '#selRange change': function(element)
    {
        if(element.val() == 'custom')
        {
            $('#customStart').parent().show();
            $('#customEnd').parent().show();
        }
        else
        {
            $('#customStart').parent().hide();
            $('#customStart').val('');
            $('#customEnd').parent().hide();
            $('#customEnd').val('');
        }
    },
    '#selCategory change': function(element)
    {
        if(element.val() == '')
        {
            $('#selSubcategory').prop('disabled', true);
            $('#selSubcategory').val('');
        }
        else
        {
            $('#selSubcategory').prop('disabled', false);
            Category.findAll({parent_id: element.val()}, function(subcategories)
            {
                $('#selSubcategory').html('<option value="">-- Choose --</option>');
                $('#selSubcategory').append(can.view('template_subcategory',
                {
                    subcategories: subcategories
                }));
            });
        }
    },
    '#selOptions change': function(element)
    {
        if(element.val() == '')
        {
            $('#subcatDiv').show();
            $('#filterDiv').show();
        }
        else
        {
            $('#subcatDiv').hide();
            $('#filterDiv').hide();
        }
    },
    //Methods
    'GetLocation': function()
    {
        var location = '/merchant-list?';
        location += 'filter='+$('#merchantName').val();
        location += '&date-range='+$('#selRange').val();
        if($('#selCategory').val() != '')
            location += '&category='+$('#selCategory').val();
        if($('#selSubcategory').val() != '')
            location += '&subcategory='+$('#selSubcategory').val();
        if($('#selMarket').val() != '')
            location += '&market='+$('#selMarket').val();
        if($('#selRep').val() != '')
            location += '&rep='+$('#selRep').val();
        if($('#selOptions').val() != '')
            location += '&options='+$('#selOptions').val();
        if($('#selRange').val() == 'custom')
            location += '&custom-start='+$('#customStart').val()+'&custom-end='+$('#customEnd').val();
        return location;
    },
    'GetOrder': function()
    {
        var ord = $("#selOrder").val();
        var dir = $('#selDirection').val();
        return '&order='+ord+'&direction='+dir;
    },
    'SetLocation': function(location)
    {
        $(".loading-window-fade").fadeIn(500, function()
        {
            window.location = location;
        });
    }
});


new MerchantControl( $('#main') );

</script>