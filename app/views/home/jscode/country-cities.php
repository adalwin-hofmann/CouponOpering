<script>

    HomeControl = can.Control(
    {
        init: function()
        {

        },
        //Events
        '#usedState change': function(element)
        {
            var self = this;
            if(element.val() != 'all')
            {
                window.location = element.val();
            }
        }
        //Methods
    });

    home_control = new HomeControl($('body'));
</script>
