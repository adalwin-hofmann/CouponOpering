<script>
Video = can.Model({
    findAll: 'GET /media/{merchant_id}',
    create: 'POST /save-video/{merchant_id}'
},{});

MerchantLocation = can.Model({
    create: 'POST /wizard-update-location/{location_id}'
},{});

VideoControl = can.Control({
    init: function()
    {
        this.Search();
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
        this.Search();
    },
    '#is_location_specific change': function(element)
    {
        var self = this;
        var myLocation = new MerchantLocation({location_id: selectedLocation, is_video_specific: element.prop('checked') ? 1 : 0});
        myLocation.save(function(json)
        {
            self.Search();
        });
    },
    '#medPreview click': function( element, event ) 
    {       
        // prevent uploading location video when not set as location specific
        if(selectedLocation != 0 && !$('#is_location_specific').prop('checked'))
            return;

        var video_code = $('#medVideoCode').val();
        var title = $('#videoTitle').val();
        if(video_code == '')
        {
            var video = new Video({merchant_id: selectedMerchant, video: '', title: title, location_id: selectedLocation});
            video.save();
            $('#medVideoCodeArea').fadeOut(500, function()
            {
                $('#videoPlayer').html('');
                $('#medVideoArea').fadeIn(500);
            });
        }
        else
        {
            var video = new Video({merchant_id: selectedMerchant, video: video_code, title: title, location_id: selectedLocation});
            video.save(function()
            {
                $('#medVideoCodeArea').fadeOut(500, function()
                {
                    $('#videoPlayer').html('');
                    var video = '<iframe width="600" height="350" src="/preview-video/'+selectedMerchant+'?location_id='+selectedLocation+'" frameborder="0" allowfullscreen></iframe>';
                    $('#videoPlayer').html(video);
                    $('#medVideoArea').fadeIn(500);
                });
            });
        }
    },
    '#medEdit click': function(element, event)
    {
        $('#medVideoArea').fadeOut(500, function(){
            $('#medVideoCodeArea').fadeIn(500);
        });
    },
    '#goBack click': function(element, event)
    {
        var video_code = $('#medVideoCode').val();
        var title = $('#videoTitle').val();
        if(video_code == '')
        {
            var video = new Video({merchant_id: selectedMerchant, video: '', title: title, location_id: selectedLocation});
            video.save(function(){
                window.location = "/pictures?viewing="+selectedFranchise;
            });
        }
        else
        {
            var video = new Video({merchant_id: selectedMerchant, video: video_code, title: title, location_id: selectedLocation});
            video.save(function(){
                window.location = "/pictures?viewing="+selectedFranchise;
            });
        }
    },
    '#goNext click': function(element, event)
    {
        var video_code = $('#medVideoCode').val();
        var title = $('#videoTitle').val();
        if(video_code == '')
        {
            var video = new Video({merchant_id: selectedMerchant, video: '', title: title, location_id: selectedLocation});
            video.save(function(){
                window.location = "/pdf?viewing="+selectedFranchise;
            });
        }
        else
        {
            var video = new Video({merchant_id: selectedMerchant, video: video_code, title: title, location_id: selectedLocation});
            video.save(function(){
                window.location = "/pdf?viewing="+selectedFranchise;
            });
        }
    },
    //Methods
    'Search': function()
    {
        var self = this;
        var query = $('.search-query').val();
        Video.findAll({merchant_id: selectedMerchant, location_id: selectedLocation}, function(json)
        {
            if(typeof json[0].video.path !== 'undefined' && json[0].video.path != '')
            {
                $('#videoTitle').val(json[0].video.long_description);
                $('#medVideoCode').val(json[0].video.path);
                var video = '<iframe width="600" height="350" src="/preview-video/'+selectedMerchant+'?location_id='+selectedLocation+'" frameborder="0" allowfullscreen></iframe>';
                $('#videoPlayer').html(video);
                $('#medVideoCodeArea').hide();
                $('#medVideoArea').show();
            }
            else
            {
                $('#videoTitle').val('');
                $('#medVideoCode').val('');
                $('#videoPlayer').html('');
                $('#medVideoCodeArea').show();
                $('#medVideoArea').hide();
            }
        });
    }
});

vid_control_area = new VideoControl($('.grid'));

</script>