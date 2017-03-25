<script>

Media = can.Model({
    findAll: 'GET /media/{merchant_id}'
},{});

Pdf = can.Model({
    create: 'POST /pdf-save/{merchant_id}'
},{});

DeleteMedia = can.Model({
    findOne: 'GET /remove-media/{merchant_id}'
},{});

MerchantLocation = can.Model({
    create: 'POST /wizard-update-location/{location_id}'
},{});

$('body').on('dragover drop', function(e){
    e.preventDefault();
});

MainDropControl = can.Control({
    init: function()
    {
        $('#ieSubmitFrame').attr('src', '');
        this.Search();
    },
    '#selLocation change': function(element)
    {
        selectedLocation = element.val();
        if(selectedLocation == 0)
        {
            $('#is_location_specific').prop('disabled', true);
            $('#is_location_specific').prop('checked', false);
        }
        else
        {
            $('#is_location_specific').prop('disabled', false);
            if(element.find('option:selected').data('specific') == '1')
                $('#is_location_specific').prop('checked', true);
            else
                $('#is_location_specific').prop('checked', false);
        }
        $("#location_id").val(selectedLocation);
        this.Search();
    },
    '#is_location_specific change': function(element)
    {
        var self = this;
        var myLocation = new MerchantLocation({location_id: selectedLocation, is_pdf_specific: element.prop('checked') ? 1 : 0});
        myLocation.save(function(json)
        {
            self.Search();
        });
    },
    '.upload-window-fade click': function()
    {
        $('.upload-window-fade').hide();
    },
    '.grid dragover': function(element, event)
    {
        if(allowDrag)
        {
            $('.upload-window-fade').fadeIn(500);
            event.preventDefault();
            return false;
        }
        else
        {
            event.preventDefault();
        }
    },
    '.upload-window-fade drop': function(element, event)
    {
        // prevent uploading location pdfs when not set as location specific
        if(selectedLocation != 0 && !$('#is_location_specific').prop('checked'))
        {
            event.preventDefault();
            return false;
        }

        if(allowDrag)
        {
            $('.upload-window-fade').fadeOut(500);
            event.preventDefault();
            this.ReadFiles(event.originalEvent.dataTransfer.files);
            return false;
        }
        else
        {
            event.preventDefault();
        }
    },
    '.pdfSave click': function(element)
    {
        var pdf_id = element.data('pdf_id');
        myPDF = new Pdf({merchant_id: selectedMerchant, pdf_id: pdf_id, long_description: $('#long_description_'+pdf_id).val()});
        myPDF.save(function()
        {
            $('#messages_'+pdf_id).hide();
            $('#messages_'+pdf_id).css('color', 'green');
            $('#messages_'+pdf_id).html('Changes Saved!');
            $('#messages_'+pdf_id).fadeIn(400, function()
            {
                $('#messages_'+pdf_id).fadeOut(4000);
            });
        });
    },
    '.pdfDelete click': function(element)
    {
        var pdf_ident = element.data('pdf_ident');
        DeleteMedia.findOne({merchant_id: selectedMerchant, type: 'pdf', identifier: pdf_ident, location_id: selectedLocation}, function(json)
        {
            $('#pdfRow_'+pdf_ident).remove();
        });
    },
    '#btnAdd click': function(element)
    {
        // prevent uploading location pdfs when not set as location specific
        if(selectedLocation != 0 && !$('#is_location_specific').prop('checked'))
            return;

        element.hide();
        $('#btnCancel').show();
        allowDrag = false;
        var count = $("#pdfArea").children().length;
        count++
        $('#individualDiv').append('<input id="file_pdf" type="file" name="file_pdf_'+count+'"><button id="btn_pdf" type="submit" class="btn btn-primary">Upload</button>');
        $('#individualDiv').show();
        $('#btn_pdf').tooltip({'title': 'Upload Selected File'});
    },
    '#btnCancel click': function(element)
    {
        allowDrag = true;
        element.hide()
        $('#btnAdd').show();
        $('#individualDiv').hide();
        $('#individualDiv').html('');
    },
    '#ieDoneButton click': function()
    {
        //IE Image Upload is finished, reload media
        $('#ieSubmitFrame').attr('src', '');
        allowDrag = true;
        $('#btnCancel').hide()
        $('#btnAdd').show();
        $('#individualDiv').hide();
        $('#individualDiv').html('');
        this.Search();
    },
    //Methods
    'ReadFiles': function(files)
    {
        var self = this;
        if (!!window.FormData) 
        {
            var formData = new FormData();
            var count = $("#pdfArea").children().length;
            count++;
            for (var i = 0; i < files.length; i++) {
                if (!!window.FormData) 
                {
                    formData.append('file_pdf_'+count, files[i]);
                    count++;
                }
            }
            formData.append('merchant_id', selectedMerchant);
            formData.append('location_id', selectedLocation);

            $('#pdfArea').append('<div class="row"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif"></div>');
         
            $.ajax({  
                url: "/pdf-upload",  
                type: "POST",  
                data: formData,
                processData: false,  
                contentType: false,  
                success: function (res) {  
                    formData = new FormData();
                    self.Search();
                }  
            }); 
        }
    },
    'Search': function()
    {
        var self = this;
        Media.findAll({merchant_id: selectedMerchant, location_id: selectedLocation}, function(json)
        {
            self.BindMedia(json);
        });
    },
    'BindMedia': function(json)
    {
        $('#pdfArea').html(can.view('template_pdf',
        {
            results: json[0].pdfs
        }));
    },
    'ManualSubmit': function()
    {
        $('#file_pdf').hide();
        $('#btn_pdf').hide();
        $('#btnCancel').hide();
        $('#individualDiv').append('<div id="pdfLoading" class="row-fluid"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif"></div>');
        return true;
    }
});

main_drop = new MainDropControl($('body'));
</script>