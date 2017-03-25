<script>
Franchise = can.Model({
    findOne: 'GET /api/franchise/find?id={franchise_id}',
    create: 'GET /api/franchise/update?id={franchise_id}'
},{});

Review = can.Model({
    create: 'POST /backoffice/sales/review_update'
},{});

LiveFranchise = can.Model({
    create: 'POST /go-live/{franchise_id}'
},{});

MainControl = can.Control({
    init: function()
    {
        this.Search();
    },
    '#btnFinish click': function()
    {
        var demo = $('#demo').val();
        var myMerchant = new Franchise({franchise_id: selectedFranchise, is_demo: demo});
        myMerchant.save(function()
        {
            /*myReview = new Review({merchant_id: selectedMerchant, type: 'review'});
            myReview.save(function()
            {
                window.location = "/wizard";
            });*/

            var myLive = new LiveFranchise({franchise_id: selectedFranchise, is_demo: demo});
            myLive.save(function(json)
            {
                window.location = "/wizard";
            });
        });
    },
    //Methods
    'Search': function()
    {
        var self = this;
        Franchise.findOne({franchise_id: selectedFranchise, with_trashed: true}, function(json)
        {
            $('#demo').val(json.is_demo);
        });
    }
});

main_control = new MainControl($('.grid-content'));
</script>