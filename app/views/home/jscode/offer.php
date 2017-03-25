<script>
    HomeControl = can.Control(
    {
        init: function()
        {
            //this.BindGroceriesSidebar();
            $('body').addClass('modal-open')
        },
        //Events
        '#leaseModal hide.bs.modal':function(event)
        {
            window.location = '/coupons/'+geoip_region()+'/'+geoip_city().replace(' ', '-')+'/'+category_slug+'/'+subcategory_slug+'/'+merchant_slug+'/'+location_id;
        },
        '#saveTodayModal hide.bs.modal':function(event)
        {
            window.location = '/coupons/'+geoip_region()+'/'+geoip_city().replace(' ', '-')+'/'+category_slug+'/'+subcategory_slug+'/'+merchant_slug+'/'+location_id;
        },
        '#contestModal hide.bs.modal': function(event)
        {
            window.location = '/coupons/'+geoip_region()+'/'+geoip_city().replace(' ', '-')+'/'+category_slug+'/'+subcategory_slug+'/'+merchant_slug+'/'+location_id;
        },
        '#couponModal hide.bs.modal':function(event)
        {
            window.location = '/coupons/'+geoip_region()+'/'+geoip_city().replace(' ', '-')+'/'+category_slug+'/'+subcategory_slug+'/'+merchant_slug+'/'+location_id;
        },
        //Methods
    });

    home_control = new HomeControl($('body'));
</script>