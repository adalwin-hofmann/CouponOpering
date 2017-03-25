<script>
OfferStats = can.Model({
    findOne: 'GET /api/v2/offer/stat-report'
},{});

ContestStats = can.Model({
    findOne: 'GET /api/v2/contest/stat-report'
},{});

DashControl = can.Control({
    init: function()
    {
        $('[data-toggle="tooltip"]').tooltip();
        if((typeof fireFirstTimeModal != 'undefined') && fireFirstTimeModal == 1)
        {
            $('#firstTimeModal').modal('show');
        }
    },
    //Events
    '#offerModal click': function(element)
    {
        var self = this;
        OfferStats.findOne({
            franchise_id: franchise_id,
            location_id: location_id,
            "date-range": $("#selDate").val()
        }, function(stats){
            self.BindStats(stats);
        });
    },
    '#contestModal click': function(element)
    {
        var self = this;
        ContestStats.findOne({
            franchise_id: franchise_id,
            location_id: location_id
        }, function(stats){
            self.BindContestStats(stats);
        });
    },
    '#selDate change':function(element)
    {
        $('#dashboardSearch').submit();
    },
    '#location change':function(element)
    {
        $('#dashboardSearch').submit();
    },
    //Methods
    'BindStats': function(stats)
    {
        $('#offers').html(can.view('template_offer', 
        {
            offers: stats.data
        }));
        $('#offerDetails').modal('show');
    },
    'BindContestStats': function(stats)
    {
        $('#offers').html(can.view('template_contest', 
        {
            contests: stats.data
        }));
        $('#offerDetails').modal('show');
    },
    'GetDate': function(time)
    {
        if(typeof time === 'object')
        {
            time = time.date
        }
        var c = time.split(/[- :]/);
        time = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
        time = Number(time.getMonth())+1+'/'+time.getDate()+'/'+time.getFullYear();

        return time;
    }
    
});

dash_control = new DashControl($('.main-window'));

</script>