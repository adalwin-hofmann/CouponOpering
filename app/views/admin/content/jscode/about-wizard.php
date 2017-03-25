<script>
About = can.Model({
    create: 'POST /save-about'
},{});

Location = can.Model({
    findOne: 'GET /api/v2/location/find?id={location_id}'
},{});

AboutControl = can.Control({
    init: function()
    {
        $('#btnSynonym').tooltip({'title': 'Comma Separated List', 'placement': 'right'});
        $('.property-top').tooltip({'title': 'Insert Property'});
        $('.property-bottom').tooltip({'title': 'Insert Property', 'placement': 'bottom'});
        CKEDITOR.replace('about', {height:"400"});
        CKEDITOR.replace('locAbout', {height:"400"});
    },
    //Events
    '.dynamic click': function(element)
    {
        var text = element.data('text');
        if(text == '')
        {
            text = '{}'
        }
        this.InsertText(text);
    },
    '#selLocation change': function(element)
    {
        if(element.val() == 0)
        {
            CKEDITOR.instances.locAbout.setData(CKEDITOR.instances.about.getData());
            $('#locTitle').val($('#page_title').val());
            $('#locKeywords').val($('#keywords').val());
            $('#locDescription').val($('#meta_description').val());
            return;
        }
        Location.findOne({location_id: element.val()}, function(json)
        {
            console.log(json);
            CKEDITOR.instances.locAbout.setData(json.about);
            $('#locTitle').val(json.page_title);
            $('#locKeywords').val(json.keywords);
            $('#locDescription').val(json.meta_description);
        });
    },
    '#btnLocSave click': function(element)
    {
        if($('#selLocation').val() == 0)
            return;
        var AboutObject = {};
        AboutObject.franchise_id = selectedFranchise;
        AboutObject.about = CKEDITOR.instances.locAbout.getData();
        AboutObject.page_title = $('#locTitle').val();
        AboutObject.keywords = $('#locKeywords').val();
        AboutObject.meta_description = $('#locDescription').val();
        AboutObject.location_id = $('#selLocation').val();
        var myAbout = new About(AboutObject);
        myAbout.save(function(json)
        {
            $("#locMessages").html('Location Saved!');
            $("#locMessages").fadeIn(500, function()
            {
                $("#locMessages").fadeOut(10000);   
            })
        });
    },
    //Methods
    'InsertText': function(text) 
    {
        var editor = CKEDITOR.instances.about;
        var orig_selection = editor.getSelection().getRanges()[0];
        var orig_container = orig_selection.startContainer;
        editor.insertText(text);
        var selection = editor.getSelection().getRanges()[0];
        var container = selection.startContainer;
        var offset = text == '{}' ? 1 : text.length;

        if(container.type != CKEDITOR.NODE_TEXT && orig_container.type != CKEDITOR.NODE_TEXT)
        {
            selection.setStart(container.getLast().getPrevious(), offset);
            selection.setEnd(container.getLast().getPrevious(), offset);
            selection.select();
        }
        else
        {
            if(orig_selection.startOffset == 0)
            {
                selection.setStart(orig_container.getPrevious(), offset);
                selection.setEnd(orig_container.getPrevious(), offset);
                selection.select();
            }
            else
            {
                selection.setStart(orig_container.getNext(), offset);
                selection.setEnd(orig_container.getNext(), offset);
                selection.select();
            }
        }
    }
});

new AboutControl($('.grid-content'));

</script>