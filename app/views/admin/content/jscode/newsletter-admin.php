<script>
NewsletterSchedule = can.Model({
    findOne: 'GET /api/v2/newsletter-schedule/find?type={type}',
    findAll: 'GET /api/v2/newsletter-schedule/get',
    create: 'GET /api/v2/newsletter-schedule/update?type={type}'
},{});

Feature = can.Model({
    findOne: 'GET /api/v2/feature/find-by-name?name={name}&remember=0',
    create: 'GET /api/v2/feature/update-by-name?name={name}'
},{});

Merchant = can.Model({
    findOne: 'GET /api/v2/merchant/find?id={merchant_id}'
},{});

Franchise = can.Model({
    findOne: 'GET /find-franchise',
    findAll: 'GET /api/franchise/get-by-name?name={name}'
},{});

$('#editDate').datepicker();

NewsletterControl = can.Control({
    // Events
    '.btn-edit click': function(element)
    {
        var self = this;
        NewsletterSchedule.findOne({type: element.data('newsletter_type'), batch_id: element.data('batch_id')}, function(newsletter)
        {
            self.BindNewsletter(newsletter);
        });
    },
    '.btn-new click': function(element)
    {
        this.CreateNewsletter();
    },
    '.btn-delete click': function(element)
    {
        var now = new Date();
        var date = Number(now.getMonth() + 1)+'/'+now.getDate()+'/'+now.getFullYear();
        var mySchedule = new NewsletterSchedule({batch_id: element.data('batch_id'), type: element.data('newsletter_type'), sent_at: this.GetTimeStamp(date)});
        mySchedule.save(function(json)
        {
            window.location = '/newsletter-admin';    
        });
    },
    '.btn-save click': function(element)
    {
        var valid = true;
        $('#editGrid input.form-control').each(function()
        {
            if($(this).attr('id') != 'zipcode' && $(this).attr('id') != 'radius' && $(this).val() == '' && $(this).attr('id') != 'featuredMerchant')
                valid = false;
        });
        if(!valid)
        {
            $('#editMessage').html('All fields required!');
            $('#editMessage').css('color', 'red');
            $('#editMessage').fadeIn(400, function()
            {
                $('#editMessage').fadeOut(10000);
            });
            return;
        }

        var NewsletterObject = {
            schedule_id: element.data('id'),
            batch_id: element.data('batch_id'),
            type: element.data('newsletter_type'),
            send_at: this.GetTimeStamp($('#editDate').val()),
            send_interval: $('#editInterval').val(),
            send_hour: this.Pad($('#editHour').val(),2),
            first_category: $('#first_category').val(),
            second_category: $('#second_category').val(),
            third_category: $('#third_category').val(),
            zipcode: $('#zipcode').val(),
            radius: $('#radius').val() * 1607,
            subject_line: $("#subjectLine").val(),
            intro_paragraph: $("#intro").val(),
            schedule_name: $('#schedule_name').val(),
            featured_merchant_id: $('#featuredMerchant').data('featured_merchant_id')
        };
        var mySchedule = new NewsletterSchedule(NewsletterObject);
        mySchedule.save(function(json)
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
        /*$('#editGrid').fadeOut(400, function()
        {
            $('#searchGrid').fadeIn(400);
        });*/
        window.location = '/newsletter-admin';
    },
    '#featuredMerchant change': function(element)
    {
        if(element.val() == '')
        {
            element.data('featured_merchant_id', 0);
        }
    },
    '#featuredMerchant typeahead:selected': function(element, event, dataset)
    {
        element.data('featured_merchant_id', dataset.id);
    },
    '.btn-clear-featured click': function(element, event) {
        $('#featuredMerchant').val('');
        $('#featuredMerchant').data('featured_merchant_id', 0);
    },
    // Methods
    'CreateNewsletter': function()
    {
        $('#editType').html('Member Newsletter');
        $('#editDate').val('');
        $('#editInterval').val(14);
        $('#editHour').val(0);
        $('#first_category').val(0);
        $('#second_category').val(0);
        $('#third_category').val(0);
        $('#intro').val('');
        $('#subjectLine').val('');
        $('#zipcode').val('');
        $('#radius').val(0);
        $('.btn-save').data('newsletter_type', 'member_newsletter');
        $('.btn-save').data('batch_id', 0);
        $('.btn-save').data('id', 0);
        $('#schedule_name').val('');
        $('#featuredMerchant').data('featured_merchant_id', 0);
        $('#featuredMerchant').val('');
        $('#searchGrid').fadeOut(400, function()
        {
            $('#editGrid').fadeIn(400);
        });
    },
    'BindNewsletter': function(newsletter)
    {
        $('#editType').html(this.UpperCase(newsletter.type));
        $('#editDate').val(this.GetDate(newsletter.send_at));
        $('#editInterval').val(newsletter.send_interval);
        $('#editHour').val(newsletter.send_hour);
        $('#first_category').val(newsletter.first_category);
        $('#second_category').val(newsletter.second_category);
        $('#third_category').val(newsletter.third_category);
        $('#intro').val(newsletter.intro_paragraph);
        $('#subjectLine').val(newsletter.subject_line);
        $('#zipcode').val(newsletter.zipcode);
        $('#radius').val(newsletter.radius ? Math.floor(newsletter.radius / 1607) : 0);
        $('.btn-save').data('newsletter_type', newsletter.type);
        $('.btn-save').data('batch_id', newsletter.batch_id);
        $('.btn-save').data('id', newsletter.id);
        $('#schedule_name').val(newsletter.schedule_name);
        $('#featuredMerchant').data('featured_merchant_id', newsletter.featured_merchant_id);
        Merchant.findOne({merchant_id: newsletter.featured_merchant_id}, function(merchant)
        {
            $('#featuredMerchant').val(merchant ? merchant.display : '');
        });
        $('#searchGrid').fadeOut(400, function()
        {
            $('#editGrid').fadeIn(400);
        });
    },
    'UpperCase': function(str)
    {
        return (str+'').replace('_', ' ').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
            return $1.toUpperCase();
        });
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

$('#featuredMerchant').typeahead({
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
                arr[i] = {value: json[i].display, id: json[i].merchant_id};
            }
            cb(arr);
        });
    },
    templates: {
        suggestion: function(item){
            return '<p data-featured_merchant_id="'+item.merchant_id+'">'+item.value+'</p>';
        }
    }
});

newsletter_control = new NewsletterControl($('#main'));

</script>