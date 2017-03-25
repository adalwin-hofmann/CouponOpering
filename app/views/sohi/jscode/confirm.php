<script>

MainControl = can.Control(
{
    init: function()
    {
        if(typeof ga !== 'undefined' && signup == 'true')
        {
            ga('send', 'event', 'user', 'signup', 'lead-submit', signup_event_value);
        }
    },
    '#btnSubmitQuote click': function(element)
    {
        element.button('loading');
    }
});

new MainControl($('#confirm-details'));

</script>