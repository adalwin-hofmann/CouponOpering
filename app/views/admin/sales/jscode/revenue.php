<script>
$('#custStart').datepicker();
$('#custEnd').datepicker();

var RevenueControl = can.Control({
    init: function(element, options)
    {
    },
    //Events
    '.btn-search click': function(element)
    {
        if($('#selDate').val() != 'custom' || ($('#custStart').val() != '' && $('#custEnd').val() != ''))
        {
            var location = '/revenue-report?date-range='+$('#selDate').val();
            if($('#selDate').val() == 'custom')
                location += '&cust-start='+$('#custStart').val()+'&cust-end='+$('#custEnd').val();
            window.location = $('#selMarket').val() != 'all' ? location+'&market='+$('#selMarket').val() : location;
        }
    },
    '#selDate change': function(element)
    {
        if(element.val() != 'custom')
        {
            $('#custDiv').hide();
            $('#custStart').val('');
            $('#custEnd').val('');
        }
        else
        {
            $('#custDiv').show();
        }
    }
    //Methods

});


new RevenueControl( $('#main') );

</script>