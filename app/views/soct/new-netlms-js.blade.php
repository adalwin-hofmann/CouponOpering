$(function() {
    $('#netlms-root').html('<div class="netlms-form-control"></div>');
    $('#netlms-root .netlms-form-control').html('<div class="form-submission-control"></div>');
    $('#netlms-root .form-submission-control').html('<div class="form-title-control"></div>');
    $('#netlms-root .form-title-control').append('<label class="form-title">Get A Quote</label>');
    $('#netlms-root .form-submission-control').append('<div class="contact-first-control"></div>');
    $('#netlms-root .contact-first-control').append('<label class="contact-first-label">First Name</label>');
    $('#netlms-root .contact-first-control').append('<input class="contact-first" type="text">');
    $('#netlms-root .form-submission-control').append('<div class="contact-last-control"></div>');
    $('#netlms-root .contact-last-control').append('<label class="contact-last-label">Last Name</label>');
    $('#netlms-root .contact-last-control').append('<input class="contact-last" type="text">');
    $('#netlms-root .form-submission-control').append('<div class="contact-email-control"></div>');
    $('#netlms-root .contact-email-control').append('<label class="contact-email-label">Email Address</label>');
    $('#netlms-root .contact-email-control').append('<input class="contact-email" type="text">');
    $('#netlms-root .form-submission-control').append('<div class="contact-phone-control"></div>');
    $('#netlms-root .contact-phone-control').append('<label class="contact-phone-label">Phone Number</label>');
    $('#netlms-root .contact-phone-control').append('<input class="contact-phone" type="text">');
    $('#netlms-root .form-submission-control').append('<div class="contact-zip-control"></div>');
    $('#netlms-root .contact-zip-control').append('<label class="contact-zip-label">Zip Code</label>');
    $('#netlms-root .contact-zip-control').append('<input class="contact-zip" type="text">');
    $('#netlms-root .form-submission-control').append('<div class="vehicle-year-control"></div>');
    $('#netlms-root .vehicle-year-control').append('<label class="vehicle-year-label">Year</label>');
    $('#netlms-root .vehicle-year-control').append('<select class="vehicle-year"><option value="">--Choose--</option><option value="{{date('Y')}}" {{$vehicle && $vehicle->year == date('Y') ? 'selected="selected"' : ''}}>{{date('Y')}}</option><option value="{{date('Y', strtotime('+1 year'))}}" {{$vehicle && $vehicle->year == date('Y', strtotime('+1 year')) ? 'selected="selected"' : ''}}>{{date('Y', strtotime('+1 year'))}}</option></select>');
    $('#netlms-root .form-submission-control').append('<div class="vehicle-make-control"></div>');
    $('#netlms-root .vehicle-make-control').append('<label class="vehicle-make-label">Make</label>');
    $('#netlms-root .vehicle-make-control').append('<select class="vehicle-make"><option value="">--Choose--</option>@foreach($makes as $make)<option value="{{$make->slug}}" {{$vehicle && $vehicle->make_slug == $make->slug ? 'selected="selected"' : ''}}>{{$make->name}}</option>@endforeach</select>');
    $('#netlms-root .form-submission-control').append('<div class="vehicle-model-control"></div>');
    $('#netlms-root .vehicle-model-control').append('<label class="vehicle-model-label">Model</label>');
    $('#netlms-root .vehicle-model-control').append('<select class="vehicle-model" {{!$vehicle ? 'disabled="disabled"' : ''}}><option value="">--Choose--</option>@foreach($models as $model)<option value="{{$model->slug}}" {{$vehicle && $vehicle->model_slug == $model->slug ? 'selected="selected"' : ''}}>{{$model->name}}</option>@endforeach</select>');
    $('#netlms-root .form-submission-control').append('<div class="form-submit-control"></div>');
    $('#netlms-root .form-submit-control').append('<button class="btn-form-submit" type="button">Submit</button><br/>');
    $('#netlms-root .form-submit-control').append('<span class="submit-message" style="display:none;"></span>');
    $('#netlms-root .netlms-form-control').append('<div class="dealer-submission-control" style="display:none;"></div>');
    $('#netlms-root .dealer-submission-control').append('<div class="dealer-form-title-control"></div>');
    $('#netlms-root .dealer-form-title-control').append('<label class="dealer-form-title">Select Dealers</label>');
    $('#netlms-root .dealer-submission-control').append('<div class="dealer-form-list-control"></div>');
    $('#netlms-root .dealer-submission-control').append('<div class="dealer-submit-control"></div>');
    $('#netlms-root .dealer-submit-control').append('<button class="btn-dealer-submit" type="button">Submit</button><br/>');
    $('#netlms-root .dealer-submit-control').append('<span class="dealer-submit-message" style="display:none;"></span>');

    var makeUpdate = function(data){
        var models = $.parseJSON(data);
        $('#netlms-root .vehicle-model').html('<option value="">--Choose--</option>');
        for(var i=0; i<models.length; i++)
        {
            $('#netlms-root .vehicle-model').append('<option value="'+models[i].slug+'">'+models[i].name+'</option>')
        }
        if(models.length)
            $('#netlms-root .vehicle-model').prop('disabled', false)
        else
            $('#netlms-root .vehicle-model').prop('disabled', true)
    };

    $('#netlms-root select.vehicle-make').change(function(){
        $.ajax({
            type: 'GET',
            url: '{{ URL::abs('/api/v2/vehicle-model/get-new-by-make') }}',
            contentType: 'application/json',
            crossDomain: true,
            data: {make: $(this).val()},
            success: makeUpdate
        });
    });

    window['netlms'] = {};
    netlms.quoteTrack = function() {
        var quoteData = {
            email: $('#netlms-root .contact-email').val(),
            zipcode: $('#netlms-root .contact-zip').val(),
            year: $('#netlms-root .vehicle-year option:selected').text(),
            make: $('#netlms-root .vehicle-make option:selected').text(),
            model: $('#netlms-root .vehicle-model option:selected').text(),
        };

        $.ajax({
            type: 'POST',
            url: '{{ $quoteUrl }}',
            contentType: 'application/json',
            crossDomain: true,
            data: JSON.stringify(quoteData)
        });
    }

    $('#netlms-root .btn-form-submit').click(function() {
        if($('#netlms-root .btn-form-submit').prop('disabled'))
            return;

        var valid = true;
        $('#netlms-root input[class^=contact-]').each(function() {
            if($(this).val() == '')
                valid = false;
        });
        $('#netlms-root input[class^=vehicle-]').each(function() {
            if($(this).val() == '')
                valid = false;
        });
        if (!valid) {
            $('#netlms-root .submit-message').html('Please fill out all fields!');
            $('#netlms-root .submit-message').fadeIn(400, function() {
                $('#netlms-root .submit-message').fadeOut(5000);
            });
            return;
        }

        $('#netlms-root .btn-form-submit').html('Submitting...');
        $('#netlms-root .btn-form-submit').prop('disabled', true);
        $('#netlms-root .submit-message').html('Submitting your request, this may take a moment!');
        $('#netlms-root .submit-message').show();
        var data = {};
        data.provider_id = $('#netlms-root').data('provider');
        data.provider_key = $('#netlms-root').data('key');
        data.type = 'automotive';
        data.client = {};
        data.client.first = $('#netlms-root .contact-first').val();
        data.client.last = $('#netlms-root .contact-last').val();
        data.client.email = $('#netlms-root .contact-email').val();
        data.client.phone = $('#netlms-root .contact-phone').val();
        data.details = {};
        data.details.category = $('#netlms-root .vehicle-make').val();
        data.details.model = $('#netlms-root .vehicle-model').val();
        data.details.year = $('#netlms-root .vehicle-year').val();
        data.details.vin = '';
        data.details.trim = '';
        data.details.vendor_inventory_id = 0;
        data.details.address = {};
        data.details.address.line_1 = '';
        data.details.address.line_2 = '';
        data.details.address.city = '';
        data.details.address.state = '';
        data.details.address.zipcode = $('#netlms-root .contact-zip').val();
        data.details.address.country = 'usa';
        data.directed_to = $('#netlms-root .directed-to').val();

        var populateDealers = function(dealers) {
            $('#netlms-root .dealer-form-list-control').html('');
            for(var i=0; i<dealers.length; i++)
            {
                $('#netlms-root .dealer-form-list-control').append('<div class="dealer-list-item"><label><input type="checkbox" class="newDealerCheck" data-new-dealer-id="'+dealers[i].id+'"> <strong>'+dealers[i].name+'</strong></label><address><p>'+dealers[i].address+'<br/>'+dealers[i].city+', '+dealers[i].state+' '+dealers[i].zipcode+'</p></address></div>');
            }
        }

        var success = function(data) {
            if(typeof data.id !== 'undefined')
            {
                netlms.quoteTrack()
                $('#netlms-root .btn-form-submit').html('Submit');
                $('#netlms-root .btn-form-submit').prop('disabled', false);
                $('#netlms-root input[class^=contact-]').val('');
                $('#netlms-root .submit-message').hide();
                $('#netlms-root .submit-message').html('Quote Request Submitted!');
                $('#netlms-root .submit-message').fadeIn(400, function() {
                    $('#netlms-root .submit-message').fadeOut(5000);
                });
                return;
            }
            var lead_id = data.lead_id;
            var seller = data.seller;

            populateDealers(data.dealers);
            $('#netlms-root .btn-dealer-submit').data('new-lead-id', data.lead_id);
            $('#netlms-root .btn-dealer-submit').data('new-seller', data.seller);
            $('#netlms-root .form-submission-control').fadeOut(400, function()
            {
                $('#netlms-root .dealer-submission-control').fadeIn(400);
            });

            $('#netlms-root .btn-form-submit').html('Submit');
            $('#netlms-root .btn-form-submit').prop('disabled', false);
        }

        var error = function(data) {
            $('#netlms-root .btn-form-submit').html('Submit');
            $('#netlms-root .btn-form-submit').prop('disabled', false);
            $('#netlms-root .submit-message').hide();
            $('#netlms-root .submit-message').html('There was an error with submission!');
            $('#netlms-root .submit-message').fadeIn(400, function() {
                $('#netlms-root .submit-message').fadeOut(5000);
            });
        }

        $.ajax({
            type: 'POST',
            url: '{{ $postUrl }}',
            contentType: 'application/json',
            crossDomain: true,
            data: JSON.stringify(data),
            success: success,
            error: error
        });
    });

    $('#netlms-root .btn-dealer-submit').click(function() {
        if($('#netlms-root .btn-dealer-submit').prop('disabled'))
            return;

        var DealerObject = {};
        DealerObject.dealers = '';
        $('#netlms-root .newDealerCheck:checked').each(function(i)
        {
            DealerObject.dealers += $(this).data('new-dealer-id')+',';
        });
        if(DealerObject.dealers == '')
        {
            $('#netlms-root .dealer-submit-message').html('Please select at least one dealer!');
            $('#netlms-root .dealer-submit-message').fadeIn(400, function() {
                $('#netlms-root .dealer-submit-message').fadeOut(5000);
            });
            return;
        }
        $('#netlms-root .btn-dealer-submit').html('Submitting...');
        $('#netlms-root .btn-dealer-submit').prop('disabled', true);

        DealerObject.dealers.slice(0, - 1);
        DealerObject.lead_id = $('#netlms-root .btn-dealer-submit').data('new-lead-id');
        DealerObject.seller = $('#netlms-root .btn-dealer-submit').data('new-seller');

        $.ajax({
            type: 'GET',
            url: '{{ $postDealerUrl }}',
            contentType: 'application/json',
            crossDomain: true,
            data: DealerObject
        });

        netlms.quoteTrack();
        $('#netlms-root .dealer-submission-control').fadeOut(400, function()
        {
            $('#netlms-root .btn-dealer-submit').html('Submit');
            $('#netlms-root .btn-dealer-submit').prop('disabled', false);
            $('#netlms-root input[class^=contact-]').val('');
            $('#netlms .dealer-form-list-control').html('');
            $('#netlms-root .form-submission-control').fadeIn(400);
            $('#netlms-root .submit-message').html('Quote Request Submitted!');
            $('#netlms-root .submit-message').fadeIn(400, function() {
                $('#netlms-root .submit-message').fadeOut(5000);
            });
        });
    });
});