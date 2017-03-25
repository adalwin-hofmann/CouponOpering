<script>
$('body').on('dragover drop', function(e){
    e.preventDefault();
});

MainDropControl = can.Control({
    init: function()
    {

    },
    '.upload-window-fade click': function()
    {
        $('.upload-window-fade').hide();
    },
    '#wrapper dragover': function(element, event)
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
    //Methods
    'ReadFiles': function(files)
    {
        var self = this;
        if (!!window.FormData) 
        {
            var formData = new FormData();
            var count = 0;
            for (var i = 0; i < files.length; i++) {
                if (!!window.FormData) 
                {
                    formData.append(files[i].name, files[i]);
                    count++;
                }
            }

            //$('#container').append('<div class="row-fluid"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif"></div>');
         
            $.ajax({  
                url: "/conversion",  
                type: "POST",  
                data: formData,
                processData: false,  
                contentType: false,  
                success: function (uploaded) {  
                    formData = new FormData();
                    self.ShowUploaded(uploaded);
                }  
            }); 
        }
    },
    'ShowUploaded': function(uploaded)
    {
        $('#container').html('<label>Right click on each file and select "Save Link As".</label>');
        for(var i=0; i<uploaded.length; i++)
        {
            $('#container').append('<div class="item"><a href="'+uploaded[i]+'">'+uploaded[i]+'</a></div>')
        }
    },
});

main_drop = new MainDropControl($('body'));

</script>