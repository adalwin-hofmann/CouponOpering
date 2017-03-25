<title>{{isset($seoContent['Title']) && $seoContent['Title'] != '' ? SoeHelper::cityStateReplace($seoContent['Title'], $geoip) : (isset($title) ? $title : '')}}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{isset($seoContent['Meta-Description']) && $seoContent['Meta-Description'] != '' ? SoeHelper::cityStateReplace($seoContent['Meta-Description'], $geoip) : (isset($description) ? $description : '')}}">
<meta name="p:domain_verify" content="cdb28c7faf153ffc2a536e331450e12d"/>
<meta name="language" content="English" />

<!--<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">-->

<meta name="apple-mobile-web-app-title" content="SaveOn">
<link rel="manifest" href="/manifest.json">

<link rel="shortcut icon" sizes="16x16" href="/img/icon-16x16.png" alt="SaveOn - Favorite Icon Small">
<link rel="shortcut icon" sizes="196x196" href="/img/icon-196x196.png" alt="SaveOn - Favorite Icon Large">
<link rel="apple-touch-icon-precomposed" href="/img/icon-152x152.png" alt="SaveOn - Favorite Icon Medium">

<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/ui-lightness/jquery-ui-datepicker.min.css">
<link rel="stylesheet" href="/css/owl.carousel.css">
<link rel="stylesheet" href="/css/leaflet.css" />
<link rel="stylesheet" href="/css/bootstrap-tour.css" />
<link rel="stylesheet" href="/css/addtohomescreen.css" />
<?php $csscache = Feature::findByName('css_cache_version'); ?>
<link rel="stylesheet" href="/css/style.css?version={{empty($csscache) ? '0' : $csscache->value}}">
<link rel="stylesheet" href="/css/style-ph.css?version={{empty($csscache) ? '0' : $csscache->value}}" media="all  and (min-width: 550px)" />
<link rel="stylesheet" href="/css/style-sm.css?version={{empty($csscache) ? '0' : $csscache->value}}" media="all  and (min-width: 768px)" />
<link rel="stylesheet" href="/css/style-md.css?version={{empty($csscache) ? '0' : $csscache->value}}" media="all  and (min-width: 992px)" />
<link rel="stylesheet" href="/css/style-lg.css?version={{empty($csscache) ? '0' : $csscache->value}}" media="all  and (min-width: 1200px)" />
<link rel="stylesheet" href="/css/style-xs.css?version={{empty($csscache) ? '0' : $csscache->value}}" media="all  and (max-width: 768px)" />

{{GeoIp::getGeoIp('js')}}

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="/js/html5shiv.min.js"></script>
	<style>
		a.item, div.item {
			border: 1px solid #d8d8d8;
		}
        .browser-warning {
            display: block;
        }
	</style>
<![endif]-->

<script type="text/javascript">
	function BrokenVehicleImage(image)
    {
        if (typeof master_control !== 'undefined')
        {
        	master_control.BrokenVehicleImage(image);
        }
        image.onerror = '';
        image.src = 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg';
        return true;
    }
    function Category()
    {
        var value = document.getElementById('category').value;
        if (value == "") return;
            else location.href = value;
    }
</script>

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon" alt="SaveOn - Favorite Icon">