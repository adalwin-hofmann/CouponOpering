<script>
$('body').on('dragover drop', function(e){
    e.preventDefault();
});

AssetCategory = can.Model({
    findAll: 'GET /api/v2/asset-category/get'
},{});

MainDropControl = can.Control({
    init: function()
    {

    },
    '.upload-window-fade click': function()
    {
        $('.upload-window-fade').hide();
    },
    '#wrap dragover': function(element, event)
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
    '#selCategory change': function(element)
    {
        var self = this;
        if(element.val() == '')
        {
            $('#selSubCategory').val('');
            $('#selSubSubCategory').val('');
            $('#selSubCategory').prop('disabled', true);
            $('#selSubSubCategory').prop('disabled', true);
        }
        else
        {
            AssetCategory.findAll({parent_id: element.val()}, function(json)
            {
                if(typeof json !== 'undefined' && json.length > 0)
                {
                    $('#selSubCategory').html('<option value="">Subcategory</option>');
                    $('#selSubCategory').append(can.view('template_subcat',
                    {
                        results: json     
                    }));
                    $('#selSubCategory').prop('disabled', false);
                }
                else
                {
                    $('#selSubCategory').prop('disabled', true);
                }
                $('#selSubCategory').val('');
            });
        }
        $('#container').html('');
    },
    '#selSubCategory change': function(element)
    {
        var self = this;
        if(element.val() == '')
        {
            $('#selSubSubCategory').val('');
            $('#selSubSubCategory').prop('disabled', true);
        }
        else
        {
            AssetCategory.findAll({parent_id: element.val()}, function(json)
            {
                if(typeof json !== 'undefined' && json.length > 0)
                {
                    $('#selSubSubCategory').html('<option value="">Minor Category</option>');
                    $('#selSubSubCategory').append(can.view('template_subcat',
                    {
                        results: json     
                    }));
                    $('#selSubSubCategory').prop('disabled', false);
                }
                else
                {
                    $('#selSubSubCategory').prop('disabled', true);
                }
                $('#selSubSubCategory').val('');
            });
        }
        $('#container').html('');
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
                    formData.append('file_gallery_'+count, files[i]);
                    count++;
                }
            }
            formData.append('category_id', $('#selCategory').val());
            formData.append('subcategory_id', $('#selSubCategory').val());
            formData.append('subsubcategory_id', $('#selSubSubCategory').val());

            $('#container').append('<div class="row-fluid"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif"></div>');
         
            $.ajax({  
                url: "/gallery-upload",  
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
        $('#container').html('');
        for(var i=0; i<uploaded.length; i++)
        {
            $('#container').append('<div class="item"><img src="'+uploaded[i]+'"></div>')
        }
        var container = $('#container');
        // initialize Masonry after all images have loaded  
        container.imagesLoaded( function() {
            var msnry = new Masonry( document.querySelector('#container'), 
            {
                itemSelector: '.item'
            });
        });
    },
});

main_drop = new MainDropControl($('body'));

</script>