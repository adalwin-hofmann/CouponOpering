<script>

HomeControl = can.Control(
    {
        init: function()
        {
            $('#collapseMakes').collapse('show');
            $('.panel-title a[href="#collapseMakes"]').removeClass('collapsed');
        },
        //Events
        //Methods
      });

    home_control = new HomeControl($('body'));

</script>