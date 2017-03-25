<script>
    ContestReward = can.Model({
        findOne: 'GET /api/v2/contest/redeem-prize'
    },{});
    PrizeControl = can.Control(
    {
        init: function()
        {

        },
        //Events
        '.btn-prize-print click': function(element)
        {
            var printSection = $("#printSection");
            $('html').addClass("print-modal");
            $("#printSection").empty();
            $("#printSection").html($(".prize-printable").clone());
            window.print();
        },
        '.btn-prize-redeem click': function(element)
        {
            if(element.hasClass('disabled'))
                return;

            $('#confirmModal').modal('show');
        },
        '.btn-prize-redeem-confirm click': function(element)
        {
            ContestReward.findOne({winner_id: $('.btn-prize-redeem').data('winner_id')}, function(json)
            {
                $('.btn-prize-redeem').addClass('disabled');
                $('.redemption-message').html('Redeemed!');
                $('.redemption-message').addClass('alert alert-success');
                $('.prize-printable').addClass('redeemed');
                $('#confirmModal').modal('hide');
            });
        }
    });

    prize_control = new PrizeControl($('body'));
</script>
