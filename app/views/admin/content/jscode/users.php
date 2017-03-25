<script>
Franchise = can.Model({
    findOne: 'GET /find-franchise',
    findAll: 'GET /api/franchise/get-by-name?name={name}'
},{});

User = can.Model({
    findOne: 'GET /api/v2/user/find?id={user_id}',
    findAll: 'GET /api/v2/user/get-by-filter?filter={filter}&type={type}',
    create: 'POST /update-user'
},{});

UserAssignment = can.Model({
    findAll: 'GET /api/v2/user-assignment-type/get-by-user?user_id={user_id}'
},{});

FranchiseUser = can.Model({
    findOne: 'GET /api/v2/user/get-franchises',
    create: 'POST /franchise-user'
},{});

DeleteUser = can.Model({
    create: 'POST /delete-franchise-user'
},{});

UsersControl = can.Control({
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
    '#filter keydown': function(element, event)
    {
        if(event.which != 13)
        {
            clearTimeout(typingTimer);
        }
    },
    '#typeFilter keydown': function(element, event)
    {
        if(event.which != 13)
        {
            clearTimeout(typingTimer);
        }
    },
    '#filter keyup': function(element, event)
    {
        var self = this; 
        if(event.which!=13)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(user_control.TriggerSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingTimer);
            currentPage = 0;
            self.Search();
        }
    },
    '#typeFilter keyup': function(element, event)
    {
        var self = this; 
        if(event.which!=13)
        {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(user_control.TriggerSearch, doneTypingInterval);
        }
        else if(event.which==13)
        {
            clearTimeout(typingTimer);
            currentPage = 0;
            self.Search();
        }
    },
    '.btn-edit click': function(element)
    {
        var self = this;
        User.findOne({user_id: element.data('user_id')}, function(user)
        {
            self.BindUser(user);
            selectedUser = element.data('user_id');
            FranchiseUser.findOne({user_id: selectedUser}, function(franchises)
            {
                self.BindFranchises(franchises);
            });
        });
    },
    '.btn-save click': function(element)
    {
        if($('#password').val() != '' || $('#password_confirmation').val() != '')
        {
            if($('#password').val() != $('#password_confirmation').val())
            {
                $('#editMessage').html('Passwords must match!');
                $('#editMessage').css('color', 'red');
                $('#editMessage').fadeIn(400, function()
                {
                    $('#editMessage').fadeOut(10000);
                });
                return;
            }
        }
        var UserObject = {
            user_id: element.data('user_id'),
            type: $("#editTypes").val(),
            name: $('#editName').val(),
        };

        var checkedVals = $('.assignment-type:checkbox:checked').map(function() {
            return this.value;
        }).get();

        UserObject.assignment_types = checkedVals.join(',');

        if($('#password').val() != '' && $('#password_confirmation').val() != '')
        {
            UserObject.password = $('#password').val();
        }
        var myUser = new User(UserObject);
        myUser.save(function(json)
        {
            $('#editMessage').html('Saved!');
            $('#editMessage').css('color', 'green');
            $('#editMessage').fadeIn(400, function()
            {
                $('#editMessage').fadeOut(10000);
            });
        });
    },
    '.btn-close click': function()
    {
        $('#filter').val('');
        currentPage = 0;
        this.Search();
        $('#editGrid').fadeOut(400, function()
        {
            $('#searchGrid').fadeIn(400);
        });
    },
    '#franchiseSearch typeahead:selected': function(element, event, dataset)
    {
        element.data('franchise_id', dataset.id);
        var myUser = new FranchiseUser({user_id: selectedUser, franchise_id: dataset.id});
        myUser.save(function(franchise)
        {
            $('#franchiseAssociations').append(can.view('template_franchise',
            {
                franchise: franchise
            }));
        });
    },
    '.btn-remove-association click': function(element)
    {
        var myDelete = new DeleteUser({user_id: selectedUser, franchise_id: element.data('franchise_id')});
        myDelete.save(function()
        {
            element.parent().parent().remove();
        });
    },
    // Methods
    'TriggerSearch': function()
    {
        currentPage = 0;
        user_control.Search();
    },
    'Search': function()
    {
        var self = this;
        var UserObject = new Object();
        UserObject.filter = $('#filter').val();
        UserObject.type = $('#typeFilter').val();
        UserObject.page = currentPage;
        UserObject.limit = 12;
        User.findAll(UserObject, function(users)
        {
            self.BindUsers(users);
            self.BindPagination(users);
        });
    },
    'BindFranchises': function(franchises)
    {
        console.log(franchises);
        $('#franchiseAssociations').html(can.view('template_franchise_association', 
        {
            franchise: franchises
        }));
    },
    'BindUser': function(user)
    {
        UserAssignment.findAll({user_id: user.id}, function(types)
        {
            $('.assignment-type:checkbox').prop('checked', false);
            for(var i=0; i<types.length; i++)
            {
                $('.assignment-type:checkbox[value="'+types[i].assignment_type_id+'"]').prop('checked', true);
            }
            $('#editName').val(user.name);
            $('#editTypes').val(user.type);
            $('.btn-save').data('user_id', user.id);
            $('#searchGrid').fadeOut(400, function()
            {
                $('#editGrid').fadeIn(400);
            });
        });
    },
    'BindUsers': function(users)
    {
        $('#resultsGrid').html(can.view('template_user',
        {
            users: users
        }));
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

user_control = new UsersControl($('#main'));

$('#franchiseSearch').typeahead({
  minLength: 2,
  highlight: true,
  hint: false,
},
{  
    name: 'my-dataset',
    source: function(query, cb){
        Franchise.findAll({name: query}, function(json)
        {
            var arr = new Array();
            for(var i=0; i<json.length; i++)
            {
                arr[i] = {value: json[i].display, id: json[i].id};
            }
            cb(arr);
        });
    },
    templates: {
        suggestion: function(item){
            return '<p data-franchise_id="'+item.id+'">'+item.value+'</p>';
        }
    }
});

</script>