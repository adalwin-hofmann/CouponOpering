<script>
var DashControl = can.Control({
    init: function(element, options)
    {
        if(query_showexpired == 1)
        {
            $('#show_expired').addClass('active');
            $('#show_expired_value').val(1);
        }

        var display_value = $('.order-option[value="'+query_orderbyname+'"]').html()
        $("#orderbyname").attr('value', query_orderbyname);
        $("#orderbyparent").html("Order By: <strong>" + display_value + "</strong>");

        $('.orderbydir[value="'+query_orderbydir+'"]').addClass('active');
        $("#orderbydir").attr('value', query_orderbydir);
    },
    //Events
    '.orderbydir click': function(element, event)
    {
        $('.orderbydir').removeClass('active');
        console.log(element);
        //element.addClass('ksdfnxdkj');
        var value = element.attr('value');
        $("#orderbydir").attr('value', value);
    },
    //Methods

});


new DashControl( $('#main') );

</script>