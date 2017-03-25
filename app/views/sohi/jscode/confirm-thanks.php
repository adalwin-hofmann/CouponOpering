<script>

MainControl = can.Control(
{
    init: function()
    {
        if(typeof ga !== 'undefined')
        {
            ga('send', 'event', 'user', 'lead-submit', quote_type+':'+quote_id, lead_event_value);
        }
    }
});

new MainControl($('#confirm-thankyou'));

</script>