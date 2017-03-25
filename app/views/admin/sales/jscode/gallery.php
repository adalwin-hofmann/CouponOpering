<!-- <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" /> -->
<script>

Tags = can.Model({
    findAll: 'GET /gallery-tags'
},{});

AssetTags = can.Model({
    findOne: 'GET /gallery-asset-tags'
},{});

AssetCategory = can.Model({
    findAll: 'GET /gallery-asset-categories'
},{});

CategoryAsset = can.Model({
    findAll: 'GET /gallery-asset-category-search'
},{});

ModalControl = can.Control({
    init: function()
    {

    },
    //Events
    'a.black click': function(element)
    {
        var path = $('.modal-image').find('.in').attr('src');
        if(typeof path === 'undefined' || path == '')
        {
            return;
        }
        path = encodeURIComponent(path);
        window.location = '/backoffice/sales/download_asset?file='+path;
    },
    'a.tag-link click': function(element)
    {
        $('#tags').val(element.html());
        $.getJSON( "/api/v1/asset", {
            search: $('#tags').val()
        }, BindImages );
    }
    //Methods

});

MainDropControl = can.Control({
    init: function()
    {
        //this.Search();
    },
    //Events
    '.image-link click': function(element)
    {
        $('#galModalLabel').html(element.attr('title'));
        $('#galImage').attr('src', element.find('img').attr('src'));
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
            AssetCategory.findAll({parent: element.val()}, function(json)
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
        currentPage = 0;
        this.Search();
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
            AssetCategory.findAll({parent: element.val()}, function(json)
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
        currentPage = 0;
        this.Search();
    },
    '#selSubSubCategory change': function(element)
    {
        currentPage = 0;
        this.Search();
    },
    '.pagination > ul > li > a click': function(element)
    {
        currentPage = element.data('page');
        this.Search();
    },
    '#tags keyup': function(element, event)
    {
        if(event.which == 13)
        {
            this.Search();
        }
    },
    //Methods
    'Search': function()
    {
        var self = this;
        var SearchObject = new Object();
        SearchObject.cat = $('#selCategory').val();
        SearchObject.subcat = $('#selSubCategory').val();
        SearchObject.subsubcat = $('#selSubSubCategory').val();
        var search = $('#tags').val();
        if(search != '')
        {
            SearchObject.search = search;
        }
        SearchObject.limit = 15;
        SearchObject.page = currentPage;
        

        CategoryAsset.findAll(SearchObject, function(json)
        {
            BindImages(json);
            self.BindPagination(json);
        });
    },
    'BindPagination': function(data)
    {
        var prev = $('#prev');
        var next = $('#next');
        var current = $('#lblCurrentPage');

        var lastpage = Math.floor((data.stats.total / data.stats.take)) == 0 ? 0 : Math.floor(data.stats.total / data.stats.take);

        if(data.stats.page == 0)
        {
            prev.parent().addClass('disabled');
        }
        else
        {
            prev.parent().removeClass('disabled');
            prev.data('page', Number(data.stats.page)-1);
        }
        if(data.stats.page == lastpage)
        {
            next.parent().addClass('disabled');
        }
        else
        {
            next.parent().removeClass('disabled');
            next.data('page', Number(data.stats.page)+1);
        }
        current.html((Number(data.stats.page)+1)+" of "+(Number(lastpage)+1));
    },
});

main_drop = new MainDropControl($('#main'));

new ModalControl($('#modal-gallery'));

function split( val ) {
    return val.split( /,\s*/ );
}
function extractLast( term ) {
    return split( term ).pop();
}

$('#tags').typeahead({
  minLength: 2,
  highlight: true,
  hint: false,
},
{  
    name: 'my-dataset',
    source: function (query, cb) 
    {
        Tags.findAll({term: query}, function(json){
            var arr = new Array();
            for(var i=0; i<json.length; i++)
            {
                arr[i] = {value: json[i].name};
            }
            cb(arr);
        });
    }
});

$( "#imgSearch" ).click(function(){
    CategoryAsset.findAll({
        search: $('#tags').val(),
        cat: $('#selCategory').val(),
        subcat: $('#selSubCategory').val(),
        subsubcat: $('#selSubSubCategory').val()
    }, function(data)
    {
        BindImages(data);
        main_drop.BindPagination(data);
    });
});

var tagSearch = function()
{
    CategoryAsset.findAll({
        limit: 15,
        search: $('#tags').val(),
        page: currentPage
    }, function(data)
    {
        BindImages(data);
        main_drop.BindPagination(data);
    })
}

var BindImages = function(data)
{
    var element = $('#container');
    element.html(can.view('image',
    {
        images: data
    }));
    var container = $('#container');
    // initialize Masonry after all images have loaded  
    container.imagesLoaded( function() {
        var msnry = new Masonry( document.querySelector('#container'), 
        {
            itemSelector: '.item'
        });
    });
}

$('#modal-gallery').on('load', function () {
    var modalData = $(this).data('modal');
    $('#imgTags').css('max-width', modalData.img.width);
    var img_id = modalData.$links[modalData.options.index].dataset['img_id'];
    AssetTags.findOne({img_id: img_id}, function(json)
    {
        var tags_length = json.length;
        $('#imgTags').html('')
        if(tags_length > 0)
        {
            for(var i=0; i < tags_length-1; i++)
            {
                $('#imgTags').append('<a class="tag-link" data-dismiss="modal" href="#">'+json[i].attributes.name+"</a>, ")
            }
            $('#imgTags').append('<a class="tag-link" data-dismiss="modal" href="#">'+json[tags_length-1].attributes.name+'</a>');
        }
    });
    // modalData.$links is the list of (filtered) element nodes as jQuery object
    // modalData.img is the img (or canvas) element for the loaded image
    // modalData.options.index is the index of the current link
});

tagSearch();
</script>
