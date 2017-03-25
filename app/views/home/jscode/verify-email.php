<script>
Recovery = can.Model(
{
    findOne: "/api/v2/user/set-verification-recovery?email={email}"
},{});

PageControl = can.Control(
{
    init: function()
    {
        
    },
    // Events
    '.new-verify click': function()
    {
        Recovery.findOne({email: encodeURIComponent(email)}, function(json)
        {
            $('.verify-box').fadeOut(500, function()
            {
                $('.new-box').fadeIn(500);
            });
        });
    }
    // Methods

});

page_control = new PageControl($('.main-content'));

</script