<script>

$(document).ready(function() {
    $('.view-change a').click(function(e)
    {
        $('.view-change a').removeClass('btn-green');
        $('.view-change a').addClass('btn-white');
        $(this).addClass('btn-green');
        $(this).removeClass('btn-white');
        var container = $('#container');
        container.imagesLoaded( function() {
            var msnry = new Masonry( document.querySelector('#container'), 
            {
                itemSelector: '.item'
            });
        });
    });
});

HomeControl = can.Control(
    {
        init: function()
        {
            
        },
        //Events
        '.search-box .panel-title a click': function(element)
        {
            $('.search-box').find('.panel.open').removeClass('open');
            if (element.hasClass('collapsed')) {
                element.parents('.panel').addClass('open');
            }
            $('.main-tabs > .tab-pane.active').removeClass('active');
            panelLink = element.attr('href').replace('panel','').toLowerCase();
            $(panelLink).tab('show').addClass('active');
        },
        '.search-box .btn-travel-tab click': function(element,event)
        {
            event.preventDefault();
            $('.search-box .btn-group').find('.btn-white').addClass('btn-cyan').removeClass('btn-white');
            element.removeClass('btn-cyan').addClass('btn-white');
            $('.main-tabs > .tab-pane.active').removeClass('active');
            panelLink = element.attr('href').replace('panel','').toLowerCase();
            $(panelLink).tab('show').addClass('active');
        },
        '.travel-radio change': function(element)
        {
            parentType = element.data('parent_type');
            $('.main-tabs .tab-pane#'+parentType+' .tab-pane.active').removeClass('active');

            $('#'+element.val()).tab('show').addClass('active');
        }
        //Methods
      });

    home_control = new HomeControl($('body'));

</script>