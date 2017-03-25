<script>

Media = can.Model({
    findAll: 'GET /media/{merchant_id}'
},{});

DeleteMedia = can.Model({
    findOne: 'GET /remove-media/{merchant_id}'
},{});

Asset = can.Model({
    findOne: 'GET /get-asset/{merchant_id}',
    create: 'POST /edit-asset/{merchant_id}'
},{});

MerchantLocation = can.Model({
    create: 'POST /wizard-update-location/{location_id}'
},{});

MediaControl = can.Control({
    init: function(element, options)
    { 
        $('[class^="merchant-thumb"]').tooltip({'title': 'Edit This Image'});
        $('#medRemove').tooltip({'title': 'Delete Image, Title, And Description'});
        $('#medChange').tooltip({'title': 'Upload A Different Image'});
        $('#medSave').tooltip({'title': 'Save All Changes'});
        $('#medCancel').tooltip({'title': 'Cancel Upload'});
        $('#medSubmit').tooltip({'title': 'Upload Selected File'});
        $('#ieSubmitFrame').attr('src', '');
        var self = this;
        formdata = false;
        this.GetMedia();
          
        if (window.FormData) {  
            formdata = new FormData();  
        }
        CKEDITOR.replace('short_description', {height:"100"});
        CKEDITOR.replace('long_description', {height:"300"});
    },
    //Events
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
        this.GetMedia();
    },
    '#is_location_specific change': function(element)
    {
        var self = this;
        var myLocation = new MerchantLocation({location_id: selectedLocation, is_about_specific: element.prop('checked') ? 1 : 0});
        myLocation.save(function(json)
        {
            self.GetMedia();
        });
    },
    '.merchant-thumb click': function(element, options)
    {
        // prevent uploading location images when not set as location specific
        if(selectedLocation != 0 && !$('#is_location_specific').prop('checked'))
            return;

        var pieces = element.attr('id').split('_');
        var thumb = pieces[2];
        $('.merchant-thumb').removeClass('merchant-thumb-active');
        $('.merchant-thumb-large').removeClass('merchant-thumb-active');
        element.addClass('merchant-thumb-active');
        if(element.attr('src') === 'http://placehold.it/100X100' || element.attr('src') === 'http://placehold.it/320X300')
        {
            $('#medRemove').hide();
            $('#medChange').hide();
            $('#medSave').hide();
            $('#medCancel').hide();
            $('.imgupload').hide()
            $('#medFilesDiv').show();
            $('#file_Thumb_'+thumb).show();
            $('#file_Thumb_'+thumb).focus();
            $('#detailsArea').show();
            CKEDITOR.instances.short_description.setData('');
            CKEDITOR.instances.long_description.setData('');
        }
        else
        {
            $('#medRemove').show();
            $('#medChange').show();
            $('#medSave').show();
            $('#medCancel').hide();
            $('#medFilesDiv').hide();
            Asset.findOne({merchant_id: selectedMerchant, type: 'image', number: pieces[2], location_id: selectedLocation}, function(json)
            {
                $('#detailsArea').show();
                CKEDITOR.instances.short_description.setData(json.short_description);
                CKEDITOR.instances.long_description.setData(json.long_description);
            });
        }
    },
    '#medChange click': function(element)
    {
        var thumb = $('.merchant-thumb-active');
        pieces = thumb.attr('id').split('_');
        $('#medRemove').hide();
        $('#medChange').hide();
        $('#medSave').hide();
        $('#medCancel').show();
        $('.imgupload').hide()
        $('#medFilesDiv').show();
        $('#file_Thumb_'+pieces[2]).show();
        $('#file_Thumb_'+pieces[2]).focus();
    },
    '#medCancel click': function(element)
    {
        $('#medRemove').show();
        $('#medChange').show();
        $('#medSave').show();
        $('#medCancel').hide();
        $('#medFilesDiv').hide();
    },
    '#medRemove click': function(element, options)
    {
        var thumb = $('.merchant-thumb-active');
        thumb.removeClass('merchant-thumb-active');
        if(thumb.hasClass('merchant-thumb-large'))
        {
            thumb.attr('src', 'http://placehold.it/320X300');
        }
        else
        {
            thumb.attr('src', 'http://placehold.it/100X100');
        }
        $('#detailsArea').hide();
        pieces = thumb.attr('id').split('_');
        DeleteMedia.findOne({merchant_id: selectedMerchant, type: 'image', identifier: pieces[2], location_id: selectedLocation}, function(json)
        {
            $('#file_Thumb_'+pieces[2]).remove();
            $('#medFilesGroup').append('<input id="file_Thumb_'+pieces[2]+'" name="file_Thumb_'+pieces[2]+'" type="file" class="imgupload" style="display:none;">');
        });
    },
    '#medSave click': function(element, options)
    {
        $('#ieSubmitFrame').attr('src', '');
        var thumb = $('.merchant-thumb-active');
        var pieces = thumb.attr('id').split('_');
        var title = CKEDITOR.instances.short_description.getData();
        var description = CKEDITOR.instances.long_description.getData();
        var myAsset = new Asset({merchant_id: selectedMerchant, type: 'image', number: pieces[2], short_description: title, long_description: description, location_id: selectedLocation});
        myAsset.save(function(json)
        {
            $('#messages').hide();
            $('#messages').css('color', 'green');
            $('#messages').html("Changes Saved!");
            $('#messages').fadeIn(500, function()
            {
                $('#messages').fadeOut(4000);     
            });
        });
    },
    'input[type=file].imgupload change': function(element, options)
    {
        var self = this;
        var pieces = element.attr('id').split('_');
        var thumb = pieces[2];
        var thumb_img = $('#img_Thumb_'+thumb);
        var src = thumb_img.hasClass('merchant-thumb-medium') ? 'http://placehold.it/100X100' : 'http://placehold.it/320X300';
        thumb_img.attr('src', src);
    },
    '#ieDoneButton click': function()
    {
        //IE Image Upload is finished, reload media
        $('#ieSubmitFrame').attr('src', '');
        $('#medRemove').show();
        $('#medChange').show();
        $('#medSave').show();
        $('#medCancel').hide();
        $('#medFilesDiv').hide();
        $('.imgupload').hide();
        this.GetMedia();
    },
    //Methods
    'ShowUploadedItem': function(thumb, source)
    {
        $('#img_Thumb_'+thumb).attr('src', source); 
    },
    'GetMedia': function()
    {
        var self = this;
        Media.findAll({merchant_id: selectedMerchant, location_id: selectedLocation}, function(json)
        {
            self.BindMedia(json[0]);
        });
    },
    'BindMedia': function(data)
    {
        $('.merchant-thumb').attr('src', 'http://placehold.it/100X100');
        $('.merchant-thumb-large').attr('src', 'http://placehold.it/320X300');
        $('.merchant-thumb').removeClass('merchant-thumb-active');
        $('.merchant-thumb-large').removeClass('merchant-thumb-active');
        $('#detailsArea').hide();

        for(var i = 0; i < data.thumbs.length; i++)
        {
            var img_num = '';
            if(data.thumbs[i].name.length > 11)
            {
                img_num = data.thumbs[i].name.slice(-2);
            }
            else
            {
                img_num = data.thumbs[i].name.slice(-1);
            }
            var myPath = data.thumbs[i].path;
            $('#img_Thumb_'+img_num).attr('src', myPath);
        }
    }
});

media_area = new MediaControl( $('.grid') );
</script>