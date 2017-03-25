<script>

Franchise = can.Model({
    findOne: 'GET /api/v2/franchise/find?id={id}',
    create: 'POST /syndication-update'
},{});

BannerClear = can.Model({
    findOne: 'GET /franchise-banner-clear'
},{});

SyndicationControl = can.Control({
    init: function(element, options)
    { 
        
    },
    //Events
    '#can_syndicate click': function(element)
    {
        if(element.prop('checked') == true)
            $('#syndDiv').show();
        else
        {
            $('#syndication_radius').val(0);
            $('#syndication_rating').val('');
            $('#syndDiv').hide();
            $('#banner_728x90').attr('href', '');
            $('#banner_728x90').parent().hide();
            $('#form_banner_728x90').val('');
            $('#banner_300x600').attr('href', '');
            $('#banner_300x600').parent().hide();
            $('#form_banner_300x600').val('');
            $('#banner_300x250').attr('href', '');
            $('#banner_300x250').parent().hide();
            $('#form_banner_300x250').val('');
        }
    },
    '.btn-banner click': function(element)
    {
        element.button('loading');
    },
    '.btn-clear click': function(element)
    {
        var link = $('#'+element.data('banner'));
        link.attr('href', '');
        link.parent().hide();
        $('#form_'+element.data('banner')).val('');
        BannerClear.findOne({franchise_id: selectedFranchise, banner: element.data('banner')});
    },
    '#ieDoneButton click': function()
    {
        this.LoadBanners();
    },
    '.btn-prev click': function()
    {
        var myFranchise = new Franchise({
            franchise_id: selectedFranchise,
            syndication_rating: $('#syndication_rating').val(),
            banner_728x90: $('#form_banner_728x90').val(),
            banner_300x600: $('#form_banner_300x600').val(),
            banner_300x250: $('#form_banner_300x250').val(),
            can_syndicate: $('#can_syndicate').prop('checked') ? 1 : 0,
            syndication_radius: $('#syndication_radius').val(),
            click_pay_rate: $('#click_pay_rate').val(),
            impression_pay_rate: $('#impression_pay_rate').val()
        });
        myFranchise.save(function(json)
        {
            window.location = '/pdf?viewing='+selectedFranchise;
        });
    },
    '.btn-next click': function()
    {
        var myFranchise = new Franchise({
            franchise_id: selectedFranchise,
            syndication_rating: $('#syndication_rating').val(),
            banner_728x90: $('#form_banner_728x90').val(),
            banner_300x600: $('#form_banner_300x600').val(),
            banner_300x250: $('#form_banner_300x250').val(),
            can_syndicate: $('#can_syndicate').prop('checked') ? 1 : 0,
            syndication_radius: $('#syndication_radius').val(),
            click_pay_rate: $('#click_pay_rate').val(),
            impression_pay_rate: $('#impression_pay_rate').val()
        });
        myFranchise.save(function(json)
        {
            window.location = '/finish?viewing='+selectedFranchise;
        });
    },
    //Methods
    'LoadBanners': function()
    {
        Franchise.findOne({id: selectedFranchise}, function(franchise)
        {
            $('#banner_728x90').attr('href', franchise.banner_728x90);
            $('#form_banner_728x90').val(franchise.banner_728x90);
            $('#banner_300x600').attr('href', franchise.banner_300x600);
            $('#form_banner_300x600').val(franchise.banner_300x600);
            $('#banner_300x250').attr('href', franchise.banner_300x250);
            $('#form_banner_300x250').val(franchise.banner_300x250);

            if($('#banner_728x90').attr('href') == '')
                $('#banner_728x90').parent().hide();
            else
                $('#banner_728x90').parent().show();

            if($('#banner_300x600').attr('href') == '')
                $('#banner_300x600').parent().hide();
            else
                $('#banner_300x600').parent().show();

            if($('#banner_300x250').attr('href') == '')
                $('#banner_300x250').parent().hide();
            else
                $('#banner_300x250').parent().show();

            $('.btn-banner').button('reset');
        });
    }
});

new SyndicationControl( $('.grid-content') );
</script>