<script>
DeleteSection = can.Model({
    create: 'GET /api/v2/training-section/delete?id={section_id}'
},{})

DeletePage = can.Model({
    create: 'GET /api/v2/training-page/delete?id={page_id}'
},{})

Children = can.Model({
    findOne: 'GET /api/v2/training-section/get-children-by-parent'
},{});

TrainingControl = can.Control({
    init: function()
    {
    	CKEDITOR.replace('training', {height:"400"});
    },
    //Events
    '.static-page-link click': function(element)
    {
        activePage = element.data('page_id');
        $('#staticModal').modal('show');
        staticUrl = element.attr('href');
        return false;
    },
    '.btn-static-view click': function()
    {
        window.location = staticUrl;
    },
    '.btn-static-edit click': function()
    {
        $('#staticModal').modal('hide');
        this.Edit();
    },
    '.edit-button button click': function(element)
    {
        this.Edit();
    },
    '.content-dropdown click': function(element)
    {
        if(element.data('loaded') == '1')
            return;
        var self = this;
        Children.findOne({parent_id: element.data('parent_id')}, function(children)
        {
            element.data('loaded', '1');
            self.BindChildren(children, element.data('parent_id'), element.data('parent_slug'));
            $('#parent-'+element.data('parent_id')).find('.content-subdropdown').each(function(i){
                self.LoadChildren($(this));
            });
        });
    },
    /*'.content-subdropdown click': function(element, event)
    {
        if(element.data('loaded') == '1')
            return;
        var self = this;
        Children.findOne({parent_id: element.data('parent_id')}, function(children)
        {
            element.data('loaded', '1');
            self.BindChildren(children, element.data('parent_id'), element.data('parent_slug'));
        });
    },
    '.content-subdropdown hover': function(element, event)
    {
        if(element.data('loaded') == '1')
            return;
        var self = this;
        Children.findOne({parent_id: element.data('parent_id')}, function(children)
        {
            element.data('loaded', '1');
            self.BindChildren(children, element.data('parent_id'), element.data('parent_slug'));
        });
    },*/
    '#editSectionsBtn click': function()
    {
        this.SearchSections();
    },
    '.cancel-button button click': function(element)
    {
    	$('.edit-button').show();
    	$('.cancel-button').hide();
    	$('.save-button').hide();
        $('.add-button').hide();
        $('.remove-button').hide();
    	$('.training-editable').hide();
        $('.training-content').show();
    },
    '.page-type change': function(element)
    {
        if(element.val() == 'content')
        {
            $('.training-textbox-holder').show();
            $('.page-static-url').hide();
        } 
        else if(element.val() == 'static')
        {
            $('.training-textbox-holder').hide();
            $('.page-static-url').show();
        }
    },
    '.delete-section-button click': function(element)
    {
        $('.btn-remove-section-confirm').data('section_id', element.data('section_id'));
        $('#removeSectionModal').modal('show');
    },
    '.save-button button click': function()
    {
        var valid = true;
        if($('#trainingPageTitle').val() == '')
            valid = false;
        if($('#trainingPageType').val() == '')
            valid = false;
        if($('#trainingPageSection').val() == '')
            valid = false;
        if($('#trainingPageType').val() == 'content' && CKEDITOR.instances.training.getData() == '' || $('#trainingPageType').val() == 'static' && $('#trainingUrl').val() == '')
            valid = false;

        if(!valid)
        {
            $('#editPageMessages').css('color', 'red');
            $('#editPageMessages').html('Please fill out all required fields!');
            $('#editPageMessages').fadeIn(500, function()
            {
                $('#editPageMessages').fadeOut(5000);
            });
            return;
        }
        var myPage = new TrainingPage(
            {
                page_id: selectedPage,
                name: $('#trainingPageTitle').val(),
                type: $('#trainingPageType').val(),
                order: $('#trainingPageOrder').val(),
                section_id: $('#trainingPageSection').val(),
                content: CKEDITOR.instances.training.getData(),
                url: $('#trainingUrl').val()
            });
        myPage.save(function(json)
        {
            if(selectedPage == 0)
            {
                $('.add-button').show();
            }
            selectedPage = json.id;
            $('#editPageMessages').css('color', 'green');
            $('#editPageMessages').html('Saved!');
            $('#editPageMessages').fadeIn(500, function()
            {
                $('#editPageMessages').fadeOut(5000);
            });
            if(json.type == 'static')
                window.location = '/onlinetraining/content-page/Home/home';
            else
                window.location = '/onlinetraining/content-page/'+json.section_slug+'/'+json.slug;
        });
    },
    '.btn-apply click': function()
    {
        window.location = '/onlinetraining/content-page/Home/home';
    },
    '.add-button button click': function()
    {
        $('.add-button').hide();
        selectedPage = 0;
        this.ClearPage();
    },
    '.btn-remove-confirm click': function(element)
    {
        var self = this;
        var myDelete = new DeletePage({page_id: selectedPage})
        myDelete.save(function(json)
        {
            window.location = '/onlinetraining/content-page/home';
        });
    },
    '.btn-remove-section-confirm click': function(element)
    {
        var self = this;
        var myDelete = new DeleteSection({section_id: element.data('section_id')})
        myDelete.save(function(json)
        {
            currentPage = 0;
            self.SearchSections();
            $('#removeSectionModal').modal('hide');
        });
        
    },
    '.save-section-button click': function(element)
    {
        var self = this;
        var row = element.parent().parent();
        var SectionObject = new Object();
        var roles = [];
        row.find('input[name="sectionType"]:checked').each(function(index){
            roles.push($(this).val());
        })
        if(roles.length)
        {
            roles = roles.join();
            SectionObject.roles = roles;
        }
        SectionObject.name = row.find('.section-name').val();
        SectionObject.order = row.find('.section-order').val();
        SectionObject.section_id = element.data('section_id');
        SectionObject.parent_id = row.find('.section-parent').val()
        var newTrainingSection = new TrainingSection(SectionObject);
        newTrainingSection.save(function(json)
        {
            if(element.data('section_id') == 0)
            {
                row.find('input[name="sectionType"]').prop('checked', false);
                row.find('.section-name').val('');
                row.find('.section-order').val('');
            }
            currentPage = 0;
            self.SearchSections();
            $('#editSectionMessages').css('color', 'green');
            $('#editSectionMessages').html('Saved!');
            $('#editSectionMessages').fadeIn(500, function(){
                $('#editSectionMessages').fadeOut(5000);
            })
        });
    },
    '#sectionsModal .paginate click': function(element)
    {
        currentPage = element.data('page');
        this.SearchSections();
    },
    '.print-button button click': function(element)
    {
        $(".print-area").print();
    },
    //Methods
    'Edit': function()
    {
        if(activePage != 0)
        {
            TrainingPage.findOne({page_id: activePage}, function(page)
            {
                $('#trainingPageTitle').val(page.name);
                $('#trainingPageType').val(page.type);
                $('#trainingPageOrder').val(page.order);
                $('#trainingPageSection').val(page.section_id);
                CKEDITOR.instances.training.setData(page.content);
                $('#trainingUrl').val(page.url);
                if(page.type == 'content')
                {
                    $('.training-textbox-holder').show();
                    $('.page-static-url').hide();
                } 
                else if(page.type == 'static')
                {
                    $('.training-textbox-holder').hide();
                    $('.page-static-url').show();
                }
            });
            $('.save-button').show();
            $('.add-button').show();
        }
        else
        {
            $('.save-button').show();
            $('.add-button').hide();
            this.ClearPage();
        }
        selectedPage = activePage;
        $('.edit-button').hide();
        $('.cancel-button').show();
        $('.remove-button').show();
        $('.training-editable').show();
        $('.training-content').hide();
    },
    'LoadChildren': function(element)
    {
        if(element.data('loaded') == '1')
            return;
        var self = this;
        Children.findOne({parent_id: element.data('parent_id')}, function(children)
        {
            element.data('loaded', '1');
            self.BindChildren(children, element.data('parent_id'), element.data('parent_slug'));
        });
    },
    'BindChildren': function(children, parent_id, parent_slug)
    {
        var parent = $('#parent-'+parent_id);
        parent.html(can.view('template_section_child', {
            children: children.data,
            parent_id: parent_id,
            parent_slug: parent_slug
        }))
    },
    'SearchSections': function()
    {
        var self = this;
        TrainingSection.findAll({page: currentPage, limit: 12}, function(sections){
            self.BindPagination(sections);
            $('#sectionArea').html(can.view('template_section',{
                sections: sections
            }));
            $("#sectionDropdown").html('<option value="0">None</option>');
            $('#sectionDropdown').append(can.view('template_section_option',{
                sections: sections
            }));
            $('#sectionsModal').modal('show');
        });

    },
    'BindPagination': function(sections)
    {
        var prev = $('#sectionsModal').find('.prev-page');
        var next = $('#sectionsModal').find('.next-page');
        var maxpage = Math.ceil(sections.stats.total / sections.stats.take);
        maxpage = maxpage ? maxpage : 1;
        if(sections.stats.page == 0)
            prev.prop('disabled', true);
        else
            prev.prop('disabled', false);

        if(sections.stats.page < maxpage - 1)
            next.prop('disabled', false);
        else
            next.prop('disabled', true);

        prev.data('page', sections.stats.page - 1);
        next.data('page', sections.stats.page + 1);
    },
    'ClearPage': function()
    {
        $('#trainingPageTitle').val('');
        $('#trainingPageType').val('');
        $('#trainingPageSection').val('');
        CKEDITOR.instances.training.setData('');
    }
});

new TrainingControl($('body'));
</script>