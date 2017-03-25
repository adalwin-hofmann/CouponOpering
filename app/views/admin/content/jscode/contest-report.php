<script>
Contest = can.Model({
  findOne: 'GET /api/contest/find?id={contest_id}',
  findAll: 'GET /api/contest/get-by-name?name={name}',
  create:  'POST /update-contest'
},{});

Applicant = can.Model({
    findAll: 'GET /api/v2/contest/get-applicant-users?contest_id={contest_id}'
},{});

/*Winner = can.Model({
    findAll: 'GET /api/v2/contest/get-winners?contest_id={contest_id}',
    create: 'POST /update-winner'
},{});*/

DeleteWinner = can.Model({
    create: 'GET /api/v2/contest-winner/delete?contest_winner_id={contest_winner_id}'
},{});

User = can.Model({
    findAll: 'GET /api/v2/user/get-search-email?email={email}'
},{});

CustomWinner = can.Model({
    create: 'POST /custom-winner'
},{});

ContestEmail = can.Model({
    create: 'GET /api/v2/contest/send-ending-email'
},{});

Winners = can.Model({
    findOne: 'GET /api/v2/contest/get-winners?contest_id={contest_id}',
    findAll: 'GET /api/v2/contest/get-all-winners-detail'
},{});

WinnerInfo = can.Model({
    create: 'POST /contest-report-winner-save',
    findOne: 'GET /api/v2/contest/get-winner-info?winner_id={winner_id}',
},{});

$(function() {
    $( "#applicantStart" ).datepicker();
    $( "#applicantEnd" ).datepicker();
});

ContestControl = can.Control({
    init: function()
    {
        //this.Search();
        $('#filterName').val('');
        $('#filterMerchant').val('');
        $('#orderBy').prop('selectedIndex',0);
        $('#orderByOrder').prop('selectedIndex',0);
    },
    // Events
    '#filterName keyup': function(element, event)
    {
        var self = this;
        var query = encodeURIComponent(element.val());
        $('.search-controls input').not(element).val('');
        if (query.length > 2)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(self.Search, doneTypingInterval);
        }
    },
    '#filterMerchant keyup': function(element, event)
    {
        var self = this;
        var query = encodeURIComponent(element.val());
        $('.search-controls input').not(element).val('');
        if (query.length > 2)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(self.Search, doneTypingInterval);
        }
    },
    '#filterEmail keyup': function(element, event)
    {
        var self = this;
        var query = encodeURIComponent(element.val());
        $('.search-controls input').not(element).val('');
        if (query.length > 2)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(self.Search, doneTypingInterval);
        }
    },
    '#filterLastName keyup': function(element, event)
    {
        var self = this;
        var query = encodeURIComponent(element.val());
        $('.search-controls input').not(element).val('');
        if (query.length > 2)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(self.Search, doneTypingInterval);
        }
    },
    '#orderBy change': function(element, event)
    {
        this.Search();
    },
    '#orderByOrder change': function(element, event)
    {
        this.Search();
    },
    '.btn-edit click': function(element, event)
    {
        var self = this;
        winner_id = element.data('winner_id');
        WinnerInfo.findOne({winner_id: winner_id}, function(winner)
        {
            self.BindModal(winner);
        });
    },
    '.btn-save click': function(element, event)
    {
        event.preventDefault();
        var WinnerObject = new Object();
        WinnerObject.winner_id = element.data('winner_id');
        WinnerObject.first_name = $('#first_name').val();
        WinnerObject.last_name = $('#last_name').val();
        WinnerObject.email = $('#email').val();
        WinnerObject.address = $('#address').val();
        WinnerObject.city = $('#city').val();
        WinnerObject.state = $('#state').val();
        WinnerObject.zip = $('#zip').val();
        var myWinnerInfo = new WinnerInfo(WinnerObject);
        myWinnerInfo.save(function(json)
        {
            console.log('send');
        });
    },
    // Methods
    'Search': function()
    {
        orderBy = $('#orderBy').val();
        orderByOrder = $('#orderByOrder').val();
        var self = this;
        var SearchObject = new Object();
        if($('#filterName').val().length > 2)
            SearchObject.contest = encodeURIComponent($('#filterName').val());
        if($('#filterMerchant').val().length > 2)
            SearchObject.merchant = encodeURIComponent($('#filterMerchant').val());
        if($('#filterEmail').val().length > 2)
            SearchObject.email = encodeURIComponent($('#filterEmail').val());
        if($('#filterLastName').val().length > 2)
            SearchObject.lastname = encodeURIComponent($('#filterLastName').val());
        SearchObject.orderBy = orderBy;
        SearchObject.orderByOrder = orderByOrder;
        Winners.findAll(SearchObject, function(winners)
        {
            contest_control.BindAllWinners(winners);
        });
    },
    'BindAllWinners': function(contests)
    {
        for(var i=0; i<contests.stats.returned; i++)
        {
            contests[i].date = this.GetDate(contests[i].winner_state_verified_at);
        }
        $('#contestReportResults').html(can.view('template_contest_report',
        {
            contests: contests
        }));
    },
    'BindModal': function(winner)
    {
        console.log(winner);
        $('.modal .contest-title').html(winner.display_name);
        $('#first_name').val(winner.first_name);
        $('#last_name').val(winner.last_name);
        $('#email').val(winner.email);
        $('#address').val(winner.address);
        $('#city').val(winner.city);
        $('#state').val(winner.state);
        $('#zip').val(winner.zip);
        $('#reward_url').val('http://www.saveon.com/contest-reward?vk='+winner.verify_key);
        $('.modal .btn-save').data('winner_id',winner.id);
        $('#winnerEdit').modal('show');
    },
    'GetDate': function(time)
    {
        if(typeof time === 'object')
        {
            time = time.date
        }
        var c = time.split(/[- :]/);
        time = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
        var myDate = this.Pad(Number((time.getMonth())+1),2)+'/'+this.Pad(time.getDate(),2)+'/'+time.getFullYear();

        return myDate;
    },
    'GetTimeStamp': function(date)
    {
        var aPieces = date.split('/');
        var timestamp = aPieces[2]+'-'+this.Pad(aPieces[0], 2)+'-'+this.Pad(aPieces[1],2)+' 00:00:00';
        return timestamp;
    },
    'Pad': function(number, length) 
    {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
        return str;
    },
});

contest_control = new ContestControl($('#main'));

</script>