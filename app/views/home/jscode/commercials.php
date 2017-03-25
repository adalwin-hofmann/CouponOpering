<script>
    CommercialControl = can.Control(
    {
        init: function()
        {

        },
        //Events
        '.view-more-container .btn click': function(element)
        {
            $('.view-more-container').hide();
            $('.hidden-content').removeClass('hidden');
        },
    });

    commercial_control = new CommercialControl($('body'));
</script>
