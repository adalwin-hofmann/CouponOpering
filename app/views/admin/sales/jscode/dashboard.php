<script>
$('#custStart').datepicker();
$('#custEnd').datepicker();

var DashControl = can.Control({
    init: function(element, options)
    {
    },
    //Events
    '#custGo click': function(element)
    {
        if($('#custStart').val() != '' && $('#custEnd').val() != '')
        {
            var location = '/?start=custom&cust-start='+$('#custStart').val()+'&cust-end='+$('#custEnd').val();
            window.location = $('#selMarket').val() != 'all' ? location+'&market='+$('#selMarket').val() : location;
        }
    },
    '#selDate change': function(element)
    {
        if(element.val() != 'custom')
        {
            var location = '/?start='+element.val();
            window.location = $('#selMarket').val() != 'all' ? location+'&market='+$('#selMarket').val() : location;
        }
        else
        {
            $('#custDiv').show();
        }
    },
    '#selMarket change': function(element)
    {
        var location = '/?start='+$("#selDate").val();
        if(element.val() != 'all')
            location += '&market='+element.val()
        window.location = location;
    }
    //Methods

});


new DashControl( $('#main') );

</script>