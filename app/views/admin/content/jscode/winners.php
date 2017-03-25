<script>
Contest = can.Model({
  findOne: 'GET /api/contest/find?id={contest_id}',
  findAll: 'GET /api/contest/get-by-name?name={name}',
  create:  'POST /update-contest'
},{});

Applicant = can.Model({
    findAll: 'GET /api/v2/contest/get-applicant-users?contest_id={contest_id}'
},{});

Winner = can.Model({
    findAll: 'GET /api/v2/contest/get-winners?contest_id={contest_id}',
    create: 'POST /update-winner'
},{});

DeleteWinner = can.Model({
    create: 'GET /api/v2/contest-winner/delete?contest_winner_id={contest_winner_id}'
},{});

User = can.Model({
    findAll: 'GET /api/v2/user/get-search-email?email={email}'
},{});

NewWinner = can.Model({
    create: 'POST /new-winner'
},{});

CustomWinner = can.Model({
    create: 'POST /custom-winner'
},{});

ContestEmail = can.Model({
    create: 'GET /api/v2/contest/send-ending-email'
},{});

$(function() {
    $( "#applicantStart" ).datepicker();
    $( "#applicantEnd" ).datepicker();
});

ContestControl = can.Control({
    init: function()
    {
        this.Search();
    },
    // Events
    'a.pagingButton click': function(element, options)
    {
        currentPage = element.data('page')
        this.Search();
    },
    'a.app_pagingButton click': function(element, options)
    {
        currentAppPage = element.data('page')
        this.ApplicantSearch();
    },
    '#nameSearch keydown': function(element, event)
    {
        if(event.which != 13)
        {
            clearTimeout(typingTimer);
        }
    },
    '#nameSearch keyup': function(element, event)
    {
        var self = this; 
        if(event.which!=13)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(contest_control.TriggerSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingTimer);
            currentPage = 0;
            self.Search();
        }
    },
    '#applicantSearch keydown': function(element, event)
    {
        if(event.which != 13)
        {
            clearTimeout(typingAppTimer);
        }
    },
    '#applicantSearch keyup': function(element, event)
    {
        var self = this; 
        if(event.which!=13)
        {
            clearTimeout(typingAppTimer);
            typingAppTimer = setTimeout(contest_control.TriggerAppSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingAppTimer);
            currentAppPage = 0;
            self.ApplicantSearch();
        }
    },
    '#applicantStart change': function(element)
    {
        currentAppPage = 0;
        this.ApplicantSearch()
    },
    '#applicantEnd change': function(element)
    {
        currentAppPage = 0;
        this.ApplicantSearch()
    },
    '#userSearch keydown': function(element, event)
    {
        if(event.which != 13)
        {
            clearTimeout(typingUserTimer);
        }
    },
    '#userSearch keyup': function(element, event)
    {
        var self = this; 
        if(event.which!=13)
        {
            clearTimeout(typingUserTimer);
            typingUserTimer = setTimeout(contest_control.TriggerUserSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingUserTimer);
            currentUserPage = 0;
            self.UserSearch();
        }
    },
    '.contest-row click': function(element)
    {
        if (!$('#newWinner').hasClass('in'))
        {
            $('.contest-row').removeClass('row-selected');
            element.addClass('row-selected');
            selectedContest = element.data('contest_id');
            selectedEmailSent = element.data('email_sent');
            currentAppPage = 0;
            $('#applicantSearch').val('');
            $('.new-winner-parent').removeClass('disabled');
            $('.new-winner-button').removeClass('disabled').data('contest_id', selectedContest);
            this.ApplicantSearch();
            this.WinnerSearch();
        }
    },
    '.applicant-row click': function(element)
    {
        if (!$('#newWinner').hasClass('in'))
        {
            $('.applicant-row').removeClass('row-selected');
            element.addClass('row-selected');
            $('.applicant-row .btn-save-winner:not(.disabled)').addClass('disabled');
            element.find('.btn-save-winner').removeClass('disabled');
        }
    },
    '.users-row click': function(element)
    {
        $('.users-row').removeClass('row-selected');
        element.addClass('row-selected');
        $('.users-row .btn-add-winner:not(.disabled)').addClass('disabled');
        element.find('.btn-add-winner').removeClass('disabled');
    },
    '.btn-save-winner click': function(element)
    {
        var self = this;
        var WinnerObject = new Object;
        WinnerObject.application_id = element.data('applicant_id');
        //WinnerObject.application_name = element.data('applicant_name');
        //WinnerObject.application_user_id = element.data('applicant_user_id');
        var myWinner = new Winner(WinnerObject);
        myWinner.save(function(json)
        {
            self.WinnerSearch();
        });
    },
    '.winner-delete click': function(element)
    {
        var self = this;
        var WinnerDeleteObject = new Object;
        WinnerDeleteObject.contest_winner_id = element.data('applicant_id');
        var winnerDelete = new DeleteWinner(WinnerDeleteObject);
        winnerDelete.save(function(json)
        {
            self.WinnerSearch();
        });
    },
    '.new-winner-button click': function(element)
    {
        var self = this;
        if (!element.hasClass('disabled'))
        {
            $('#newWinner').modal('show');
        }
    },
    '.btn-add-winner click': function(element)
    {
        var self = this;
        var WinnerObject = new Object;
        WinnerObject.user_id = element.data('user_id');
        WinnerObject.contest_id = selectedContest;
        var myWinner = new NewWinner(WinnerObject);
        myWinner.save(function(json)
        {
            self.ApplicantSearch();
            self.WinnerSearch();
            $('#newWinner').modal('hide');
            $('#userSearch').val('');
            self.UserSearch();
        });
    },
    '.close-new-winner click': function(element)
    {
        var self = this;
        $('#userSearch').val('');
        currentUserPage = 0;
        contest_control.UserSearch();
    },
    '.btn-custom-winner click': function(element)
    {
        var self = this;
        var CustomFirstName = $('#CustomFirstName').val();
        var CustomLastName = $('#CustomLastName').val();
        var CustomCity = $('#CustomCity').val();
        var CustomState = $('#CustomState').val();
        if (CustomFirstName == '')
        {
            $('#CustomFirstName').parent().addClass('error');
        } else {
            $('#CustomFirstName').parent().removeClass('error');
        }
        if (CustomLastName == '')
        {
            $('#CustomLastName').parent().addClass('error');
        } else {
            $('#CustomLastName').parent().removeClass('error');
        }
        if ((CustomFirstName == '') || (CustomLastName == ''))
        {
            return;
        }
        var WinnerObject = new Object;
        WinnerObject.user_id = 0;
        WinnerObject.contest_id = selectedContest;
        WinnerObject.first_name = CustomFirstName;
        WinnerObject.last_name = CustomLastName;
        WinnerObject.city = CustomCity;
        WinnerObject.state = CustomState;
        var myWinner = new CustomWinner(WinnerObject);
        myWinner.save(function(json)
        {
            self.ApplicantSearch();
            self.WinnerSearch();
            $('#newWinner').modal('hide');
            $('#userSearch').val('');
            currentUserPage = 0;
            self.UserSearch();
            $('#CustomFirstName').val('');
            $('#CustomLastName').val('');
            $('#CustomCity').val('');
            $('#CustomState').val('');
        });
    },
    '#btnContestEndEmail click': function(element)
    {
        element.fadeOut(500, function()
        {
            $('#confirmDiv').fadeIn();
        });
    },
    '#btnEmailCancel click': function(element)
    {
        $('#confirmDiv').fadeOut(500, function()
        {
            $('#btnContestEndEmail').fadeIn(500); 
        });
    },
    '#btnEmailConfirm click': function()
    {
        $('#confirmDiv').fadeOut(500, function()
        {
            $('#btnContestEndEmail').fadeIn(500);
            $('#btnContestEndEmail').button('loading');
        });
        var myEmail = new ContestEmail({contest_id: selectedContest}); 
        myEmail.save(function(json)
        {
            $('#btnContestEndEmail').html('Email Sent!');
        });
    },
    '#btnContestEndTest click': function(element)
    {
        var email = $('#testEmail').val();
        if(email == '')
            return;
        element.button('loading');
        var myEmail = new ContestEmail({contest_id: selectedContest, test_email: email});
        myEmail.save(function(json)
        {
            element.button('reset');
            var span = element.find('span');
            //console.log(email);
            span.html('Email Sent!');
            span.fadeOut(5000, function()
            {
                span.html('Send Test Email');
                span.show();
            });
        });
    },
    // Methods
    'TriggerSearch': function()
    {
        currentPage = 0;
        contest_control.Search();
    },
    'TriggerAppSearch': function()
    {
        currentAppPage = 0;
        contest_control.ApplicantSearch();
    },
    'TriggerUserSearch': function()
    {
        currentUserPage = 0;
        contest_control.UserSearch();
    },
    'Search': function()
    {
        var self = this;
        var ContestObject = new Object();
        ContestObject.name = $('#nameSearch').val();
        ContestObject.page = currentPage;
        ContestObject.limit = 10;
        Contest.findAll(ContestObject, function(contests)
        {
            selectedContest = 0;
            currentAppPage = 0;
            self.ApplicantSearch();
            self.WinnerSearch();
            self.BindContests(contests);
            self.BindPagination(contests);
        });
    },
    'ApplicantSearch': function()
    {
        var self = this;
        var SearchObject = {
            name: $('#applicantSearch').val(),
            contest_id: selectedContest,
            page: currentAppPage, 
            limit: 10
        };
        if($('#applicantStart').val() != '')
            SearchObject.start = this.GetTimeStamp($('#applicantStart').val());
        if($('#applicantEnd').val() != '')
            SearchObject.end = this.GetTimeStamp($('#applicantEnd').val());
        Applicant.findAll(SearchObject, function(applicants)
        {
            $('#totalApplicants').html(applicants.stats.total);
            self.BindApplicants(applicants);
            self.BindAppPagination(applicants);
        });
        $('#applicantCSVLink').attr('href', '/winners-csv?contest_id='+selectedContest+'&name='+$('#applicantSearch').val()+'&start='+$('#applicantStart').val()+'&end='+$('#applicantEnd').val());
        $('#applicantCSVLink').show();
    },
    'WinnerSearch': function()
    {
        var self = this;
        Winner.findAll({contest_id: selectedContest}, function(winner)
        {
            self.BindWinner(winner);
        });
    },
    'UserSearch': function()
    {
        var self = this;
        User.findAll({email: $('#userSearch').val(),  page: currentUserPage, limit: 10}, function(users)
        {
            self.BindUsers(users);
        });
    },
    'BindContests': function(contests)
    {
        for(var i=0; i<contests.stats.returned; i++)
        {
            contests[i].start = this.GetDate(contests[i].starts_at);
            contests[i].end = this.GetDate(contests[i].expires_at);
        }
        $('#resultsArea').html(can.view('template_contest',
        {
            contests: contests
        }));
    },
    'BindApplicants': function(applicants)
    {
        $('#applicantsArea').html(can.view('template_applicant',
        {
            applicants: applicants
        }));
        if(selectedContest != 0)
        {
            $('#btnContestEndEmail').show();
            $('#divTestEmail').show();
            if(selectedEmailSent != 0)
            {
                $('#btnContestEndEmail').prop('disabled', true);
                $('#btnContestEndEmail').addClass('disabled');
                $('#btnContestEndEmail').html('Email Sent!');
            }
            else
            {
                $('#btnContestEndEmail').prop('disabled', false);
                $('#btnContestEndEmail').removeClass('disabled');
                $('#btnContestEndEmail').html('Send Contest End Email');
            }
        }
        else
        {
            $('#applicantCSVLink').hide();
            $('#btnContestEndEmail').hide();
            $('#divTestEmail').hide();
        }
    },
    'BindWinner': function(winner)
    {
        $('#winnersResults').html(can.view('template_winner',
        {
            winner: winner
        }));
        if (winner.stats.returned > 0)
        {
            $('#winnersArea').show();
        }
    },
    'BindUsers': function(users)
    {
        $('#usersArea').html(can.view('template_users',
        {
            users: users
        }));
        if (users.length > 0)
        {
            $('#newWinner .table.user-search').removeClass('hidden');
        } else {
            $('#newWinner .table.user-search').addClass('hidden');
        }
        console.log(users.length);
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
    'BindAppPagination': function(data)
    {
        var first = $('#app_first');
        var prev = $('#app_prev');
        var next = $('#app_next');
        var last = $('#app_last');

        var lastpage = Math.floor((data.stats.total / data.stats.take)) == 0 ? 0 : Math.floor((data.stats.total / data.stats.take));

        if(data.stats.page == 0)
        {
            first.parent().addClass('disabled');
            prev.parent().addClass('disabled');
        }
        else
        {
            first.parent().removeClass('disabled');
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
            first.data('page', 0);
        }
        if(data.stats.page == lastpage)
        {
            last.parent().addClass('disabled');
            next.parent().addClass('disabled');
        }
        else
        {
            last.parent().removeClass('disabled');
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
            last.data('page', lastpage);
        }
    },
    'BindPagination': function(data)
    {
        var first = $('#first');
        var prev = $('#prev');
        var next = $('#next');
        var last = $('#last');

        var lastpage = Math.floor((data.stats.total / data.stats.take)) == 0 ? 0 : Math.floor((data.stats.total / data.stats.take));

        if(data.stats.page == 0)
        {
            first.parent().addClass('disabled');
            prev.parent().addClass('disabled');
        }
        else
        {
            first.parent().removeClass('disabled');
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
            first.data('page', 0);
        }
        if(data.stats.page == lastpage)
        {
            last.parent().addClass('disabled');
            next.parent().addClass('disabled');
        }
        else
        {
            last.parent().removeClass('disabled');
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
            last.data('page', lastpage);
        }
    }
});

contest_control = new ContestControl($('#main'));

</script>