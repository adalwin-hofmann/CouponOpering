<script>

    HomeControl = can.Control(
    {
        init: function()
        {
            initalLoad = 1;

            var self = this;
        },
        //Events
        '#check-all click': function()
        {
            $(':checkbox').each(function()
            {
                $(this).prop('checked', true);
            });
        },
        '#uncheck-all click': function()
        {
            $(':checkbox').each(function()
            {
                $(this).prop('checked', false);
            });
        },
        //Methods
    });

    home_control = new HomeControl($('body'));

</script>
