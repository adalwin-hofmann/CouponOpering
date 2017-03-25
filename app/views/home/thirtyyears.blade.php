@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('city-banner')
<div class="city-banner anniversary">
	<div class="city-banner-img" style="background-image: url('http://s3.amazonaws.com/saveoneverything_assets/city_images/detroit/detroit.jpg')">
		<div class="fade-left"></div>
		<div class="fade-right"></div>
		<h1 class="fancy">Celebrating 30 Years</h1>
		<h2 class="spaced">By Giving away a free car!</h2>
	</div>
</div>
@stop


@section('body')

<div class="content-bg">
	<!--<a href="{{URL::abs('/')}}/contests">
		<img class="img-responsive" src="/img/WinThisCar_ElderFord_WebBanner.jpg">
	</a>-->

	<span style="margin: 50px auto; display:block; font-size:5em;" class="h1 text-center fancy">A History of SaveOn</span>

	<div class="main">
				<ul class="cbp_tmtimeline">
					<li>
						<time class="cbp_tmtime" datetime="2013-04-10 18:30"> <span>1981</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Humble Beginnings</h2>
							<p>Michael Gauthier offers Val-U-Guide direct mail coupons. Our initial circulation was 30,000.</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-11T12:04"> <span>1984</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Marketplace Magazine</h2>
							<p>In 1984, Marketplace Magazine began with a circulation of 80,000 homes</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-13 05:36"> <span>1987</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Marketplace Coupons</h2>
							<p>Coupons are taken out of the magazine format. A new stand-alone coupon book was developed called Marketplace Coupons</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-15 13:15"> <span>1991</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>MarketShare Coupons</h2>
							<p>In 1991 we officially changed our name to MarketShare coupons.</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-16 21:30"> <span>1995</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Headquarters</h2>
							<p>We purchased our <a href="{{URL::abs('/')}}/headquarters">headquarters</a> in Troy, MI</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-17 12:11"> <span>1996</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Chicago Office</h2>
							<p>We brought our mission to help people save time &amp; money to the Chicago, where we opened our new office in 1996.</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"> <span>2002</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>The Growth Continues</h2>
							<p>In November of 2002 we acquired Innovative Media.</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"> <span>2003</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Twin Cities office</h2>
							<p>After a roaring success in Chicago, we decide to spread the savings even further with an office in the Minneapolis/St. Paul area.</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"> <span>2004</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Save On Everything</h2>
							<p>In 2004 we renamed our magazine to SAVE On Everything</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"> <span>2010</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Save On Cars &amp; Trucks</h2>
							<p>We launched our newest magazine: SAVE On Cars &amp; Trucks</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"> <span>2012</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>Save Goes Digital</h2>
							<p>In February of 2012 we officially launched our great new digital platform so that you could access savings wherever you go.</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"><span>30 Years!</span> <span>2014</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>A New Name, A New Site</h2>
							<p>Save On Everything has officially become SaveOn - An all-new saving experience!</p>
						</div>
					</li>
					<li>
						<time class="cbp_tmtime" datetime="2013-04-18 09:56"> <span>2014</span></time>
						<div class="hidden-xs cbp_tmicon glyphicon glyphicon-chevron"></div>
						<div class="cbp_tmlabel">
							<h2>SaveOn<sup>&reg;</sup> Car Giveaway</h2>
							<p>To celebrate 30 fantasic years, and the launch of our new and improved website, we are giving away a brand new Ford Focus from your friends at Elder Ford</p>
							<img style="margin:auto" class="img-responsive" src="/img/2014_FocusSE_Red.png">
							<!--<a href="{{URL::abs('/')}}/contests" class="btn btn-block btn-black">GO TO CONTESTS TO ENTER</a>-->
						</div>
					</li>
				</ul>
			</div>
</div>
@stop
@stop