<script>

MerchantSearch = can.Control({
    init: function()
    {

    },
    // Events
    '#showMore click': function()
    {
        var show = Number(offers) + 20;
        var location = '/merchant-report?franchise='+franchise_id+'&date-range='+range+'&offers='+show;
        if(range == 'custom')
        {
            location += '&cs='+customStart+'&ce='+customEnd;
        }
        window.location = location;
    },
    '#showLess click': function()
    {
        var show = Number(offers) - 20;
        var location = '/merchant-report?franchise='+franchise_id+'&date-range='+range+'&offers='+show;
        if(range == 'custom')
        {
            location += '&cs='+customStart+'&ce='+customEnd;
        }
        window.location = location;
    }
    // Methods

});

merchant_search = new MerchantSearch( $( '#main' ) );

</script>