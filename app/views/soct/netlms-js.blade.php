$(function() {
    $('#netlms-root').html('<div class="netlms-form-control"></div>');
    $('#netlms-root .netlms-form-control').html('<div class="form-title-control"></div>');
    $('#netlms-root .form-title-control').append('<label class="form-title">Contact Seller</label>');
    $('#netlms-root .netlms-form-control').append('<div class="contact-first-control"></div>');
    $('#netlms-root .contact-first-control').append('<label class="contact-first-label">First Name</label>');
    $('#netlms-root .contact-first-control').append('<input class="contact-first" type="text">');
    $('#netlms-root .netlms-form-control').append('<div class="contact-last-control"></div>');
    $('#netlms-root .contact-last-control').append('<label class="contact-last-label">Last Name</label>');
    $('#netlms-root .contact-last-control').append('<input class="contact-last" type="text">');
    $('#netlms-root .netlms-form-control').append('<div class="contact-email-control"></div>');
    $('#netlms-root .contact-email-control').append('<label class="contact-email-label">Email Address</label>');
    $('#netlms-root .contact-email-control').append('<input class="contact-email" type="text">');
    $('#netlms-root .netlms-form-control').append('<div class="contact-phone-control"></div>');
    $('#netlms-root .contact-phone-control').append('<label class="contact-phone-label">Phone Number</label>');
    $('#netlms-root .contact-phone-control').append('<input class="contact-phone" type="text">');
    $('#netlms-root .netlms-form-control').append('<div class="contact-zip-control"></div>');
    $('#netlms-root .contact-zip-control').append('<label class="contact-zip-label">Zip Code</label>');
    $('#netlms-root .contact-zip-control').append('<input class="contact-zip" type="text">');
    $('#netlms-root .netlms-form-control').append('<input class="category" type="hidden" value="{{ $vehicle->make }}">');
    $('#netlms-root .netlms-form-control').append('<input class="model" type="hidden" value="{{ $vehicle->model }}">');
    $('#netlms-root .netlms-form-control').append('<input class="year" type="hidden" value="{{ $vehicle->year }}">');
    $('#netlms-root .netlms-form-control').append('<input class="vin" type="hidden" value="{{ $vehicle->vin }}">');
    $('#netlms-root .netlms-form-control').append('<input class="trim" type="hidden" value="{{ $vehicle->trim_level }}">');
    $('#netlms-root .netlms-form-control').append('<input class="vendor-inventory-id" type="hidden" value="{{ $vehicle->vendor_inventory_id }}">');
    $('#netlms-root .netlms-form-control').append('<input class="directed-to" type="hidden" value="{{ $vehicle->netlms_id }}">');
    $('#netlms-root .netlms-form-control').append('<div class="form-submit-control"></div>');
    $('#netlms-root .form-submit-control').append('<button class="btn-submit" type="button" onclick="netlmsFormSubmit()">Submit</button><br/>');
    $('#netlms-root .form-submit-control').append('<span class="submit-message" style="display:none;"></span>');

    var viewData = {vehicle_entity_id: '{{$vehicle->id}}'};
    $.ajax({
        type: 'POST',
        url: '{{ $viewUrl }}',
        contentType: 'application/json',
        crossDomain: true,
        data: JSON.stringify(viewData)
    });
});

function netlmsFormSubmit() {
    if($('#netlms-root .btn-submit').prop('disabled'))
        return;

    var valid = true;
    $('#netlms-root input[class^=contact-]').each(function() {
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

    $('#netlms-root .btn-submit').html('Submitting...');
    $('#netlms-root .btn-submit').prop('disabled', true);
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
    data.details.category = $('#netlms-root .category').val();
    data.details.model = $('#netlms-root .model').val();
    data.details.year = $('#netlms-root .year').val();
    data.details.vin = $('#netlms-root .vin').val();
    data.details.trim = $('#netlms-root .trim').val();
    data.details.vendor_inventory_id = $('#netlms-root .vendor-inventory-id').val();
    data.details.address = {};
    data.details.address.line_1 = '';
    data.details.address.line_2 = '';
    data.details.address.city = '';
    data.details.address.state = '';
    data.details.address.zipcode = $('#netlms-root .contact-zip').val();
    data.details.address.country = 'usa';
    data.directed_to = $('#netlms-root .directed-to').val();

    var success = function(data) {
        $('#netlms-root .btn-submit').html('Submit');
        $('#netlms-root .btn-submit').prop('disabled', false);
        $('#netlms-root input[class^=contact-]').val('');
        $('#netlms-root .submit-message').html('Submitted!');
        $('#netlms-root .submit-message').fadeIn(400, function() {
            $('#netlms-root .submit-message').fadeOut(3000);
        });
    }

    var error = function(data) {
        $('#netlms-root .btn-submit').html('Submit');
        $('#netlms-root .btn-submit').prop('disabled', false);
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

    var quoteData = {
        vehicle_entity_id: '{{$vehicle->id}}',
        email: $('#netlms-root .contact-email').val(),
        zipcode: $('#netlms-root .contact-zip').val()
    };

    $.ajax({
        type: 'POST',
        url: '{{ $quoteUrl }}',
        contentType: 'application/json',
        crossDomain: true,
        data: JSON.stringify(quoteData)
    });
}