<script>

PasswordMail = can.Model(
{
    findOne: 'GET /passwordresetemail'
},{});

User = can.Model({
    findOne: 'GET /api/user/find?id={user_id}',
    findAll: 'GET /api/user/get',
    create: 'GET /api/user/create'
},{});

MainControl = can.Control({
    init: function()
    {
    },
    '#forgotPasswordModal .btn-green click': function (element, event)
    {
        var self = this;
        email = $('#forgotPasswordModal #signInEmail').val();
        if (email != '') {
            var UserObject = new Object;
            UserObject['where|email'] = email;
            User.findAll(UserObject, function (user) {
                if (user.length > 0)
                {
                    //Success
                    $('#forgotPasswordThankYouModal .user-email').html(email);
                    $('#forgotPasswordModal').modal('hide');
                    $('#forgotPasswordThankYouModal').modal('show');

                    var PasswordObject = new Object;
                    PasswordObject.email = email;
                    PasswordObject.name = user['0'].name;
                    PasswordMail.findOne(PasswordObject, function(password) {

                    });

                } else {
                    $('#forgotPasswordModal .no-email-alert').show();
                    $('#forgotPasswordModal .email-group').addClass('has-error');
                }
            });
        } else {
            $('#forgotPasswordModal .no-email-alert-entered').show();
            $('#forgotPasswordModal .email-group').addClass('has-error');
        }

    }
    //Methods
});

main_control = new MainControl($('#main'));
</script>