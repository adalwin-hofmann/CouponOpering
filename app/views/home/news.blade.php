@extends('master.templates.master')
@section('page-title')
<h1>News &amp; Views</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">News &amp; Views</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop

@section('body')
 <div class="content-bg">
 <div class="span9 main-column two-columns-right">
    <h2 class="h1">See What People Are Saying About SaveOn<sup>&reg;</sup> Online</h2>
    <br>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <br><br>
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/thedetroithub.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">December 2nd, 2015</span>
            <a href="http://blog.thedetroithub.com/2015/12/02/local-execs-sleep-in-boxes-out-in-the-cold-and-raise-345000-to-help-homeless-youth">
                <h2>Local execs sleep in boxes out in the cold and raise $345,000 to help homeless youth</h2>
            </a>
            <p>Rod Alberts walked out of the plush hotel in Los Angeles into beautiful 75 degree weather and headed for the airport to fly back to Detroit. Once there he was greeted, not with a comfy bed and a warm house, but with two cardboard boxes for a bed, a sleeping bag from The Empowerment Plan for a blanket and ice cold concrete for a mattress.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/freep.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">May 16th, 2014</span>
            <a href="http://www.freep.com/article/20140514/ENT03/305140185/color-of-rain-lacey-chabert">
                <h2>The Hallmark Movie Channel original movie &quot;The Color of Rain&quot;</h2>
            </a>
            <p>On May 15th, SaveOn<sup>&reg;</sup> sponsored the premiere of &quot;The Color of Rain&quot; at the Emagine Royal Oak movie theater. The Color of Rain will debut on May 31st on the Hallmark Movie Channel.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/dbusiness.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 1st, 2014</span>
            <a href="http://www.dbusiness.com/DBusiness/March-April-2014/SAVE-On-Everything-Changes-Name-to-SaveOn/">
                <h2>SAVE On Everything<sup>&reg;</sup> Changes Name to SaveOn<sup>&reg;</sup></h2>
            </a>
            <p>Save On Everything Inc., a direct mail and digital coupon company based in Troy, has changed its name to SaveOn.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/ticker.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">February 2014</span>
            <a href="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/022-Ticker2-Mon14.pdf">
                <h2>Interview With Heather Uballe</h2>
            </a>
            <p>"Our business has changed tremendously over the last 10 years, and that accelerated after the (2008 economic) recession."</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/dbusiness.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">December 2013</span>
            <a href="http://www.dbusiness.com/DBusiness/November-December-2013/Save-on-Everything-Names-New-President/">
                <h2>Save On Everything<sup>&reg;</sup> Names New President</h2>
            </a>
            <p>Uballe, of Clarkston, joined SAVE On Everything in 2004 as the marketing manager which led to the launching of the division of SAVE On Cars & Trucks.</p>
        </div>
    </div>

    
    
    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/crain_detroit.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">December 2nd, 2013</span>
            <a href="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/news/Crains-December2nd2013.pdf">
                <h2>Heather Uballe, President of Save On Everything<sup>&reg;</sup></h2>
            </a>
            <p>Heather Uballe to president, Save On Everything<sup>&reg;</sup>, Troy, from vice president of operations.</p>
        </div>
    </div>

    <hr>



    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-mother.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 23rd 2013</span>
            <a href="https://www.facebook.com/photo.php?fbid=634579463234376&set=a.425695040789487.123032.234474246578235&type=1&theater">
                <h2>www.facebook.com/saveoneverything</h2>
            </a>
            <p>With Mother's Day just around the corner, Save On invites couponers to check out national brands to save on shopping from home.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-referee.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 17th 2013</span>
            <a href="https://www.facebook.com/photo.php?fbid=631848433507479&set=a.425695040789487.123032.234474246578235&type=1&theater">
                <h2>www.facebook.com/saveoneverything</h2>
            </a>
            <p>Parents are some of the greatest “referees” out there, especially when there are several kids involved at a grocery store. SAVE hopes to make it easier to coupon, so parents can focus on the “game.” </p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-diet.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 13th 2013</span>
            <a href="https://twitter.com/saveonevery/status/323087708715102211">
                <h2>www.twitter.com/saveonevery</h2>
            </a>
            <p>It is hard to keep up with diets, especially on the weekends when there is so much going on and deals all over the place. SAVE tries to keep it lighthearted.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-margarita.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 11th 2013</span>
            <a href="https://www.facebook.com/photo.php?fbid=628884003803922&set=a.425695040789487.123032.234474246578235&type=1&theater">
                <h2>www.facebook.com/saveoneverything</h2>
            </a>
            <p>Although money can’t buy happiness, the things SAVE helps people save money on can help them bring a little joy to their lives, even margaritas.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-tanning.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 11th 2013</span>
            <a href="https://twitter.com/SaveOnMI/status/322440470867357697">
                <h2>www.twitter.com/saveonevery</h2>
            </a>
            <p>The rainy weather of spring in the Midwest leaves something to be desired in the way of sunshine, so tanning salons are a great help.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-grocery.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 9th 2013</span>
            <a href="https://www.facebook.com/photo.php?fbid=627923267233329&set=a.425695040789487.123032.234474246578235&type=1&theater">
                <h2>www.facebook.com/saveoneverything</h2>
            </a>
            <p>Going to the grocery store for just one item never works when there are so many deals to be found with grocery coupons from SAVE.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-windows.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 5th 2013</span>
            <a href="https://twitter.com/SaveOnMN/status/320174503852404736">
                <h2>www.twitter.com/saveonevery</h2>
            </a>
            <p>It’s important to plan ahead for summer projects and SAVE will help couponers with everything, including windows.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-redplum.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">April 4th 2013</span>
            <a href="http://www.oaklandcountyprosper.com/innovationnews/saveandredplumtwincities.aspx">
                <h2>www.oaklandcountyprosper.com</h2>
            </a>
            <p>SAVE has partnered with Red Plum to bring money-saving coupons to Minneapolis/St. Paul.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-video.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 29th 2013</span>
            <a href="https://twitter.com/saveonevery/status/317701562703179776">
                <h2>www.twitter.com/saveonevery</h2>
            </a>
            <p>SAVE loves Extreme Couponing on TLC, but we also love a great laugh. The Marissa &amp; Trey Show created a great parody of the perils of couponing.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/corporate-frugal.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 21st 2013</span>
            <a href="http://www.webwire.com/ViewPressRel.asp?aId=169341">
                <h2>www.webwire.com</h2>
            </a>
            <p>Save On Offers Online Grocery Coupons for Healthy and Frugal Lifestyles. A place for couponers to find name brand coupons for healthy foods to fit any lifestyle.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/detroit.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 21st 2013</span>
            <a href="http://www.saveon.com/blog">
                <h2>www.saveon.com/blog</h2>
            </a>
            <p>As all Detroiters know, shopping is definitely an integral component of the culture.  This market is where visitors and residents alike can make use of online printable coupons in Detroit.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/time-money.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 20th 2013</span>
            <a href="http://www.youtube.com/watch?v=U1HceFk50ao">
                <h2>www.youtube.com</h2>
            </a>
            <p>Save On with Chicago Coupons Today. As the video and website show, SAVE gives customers the biggest savings available. From Minneapolis coupons to Chicago coupons to Detroit coupons, customers are bound to save big on the next shopping date.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/win-5k.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 20th 2013</span>
            <a href="http://www.saveoneverything.com/win5k">
                <h2>www.saveoneverything.com/win5k</h2>
            </a>
            <p>Think You Have The Winning Numbers? The cover of every SAVE magazine has a code that possibly will match the number as it appears on the website. Winners should provide certain information and will be contacted the information has been reviewed for accuracy.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/irish.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 17th 2013</span>
            <a href="https://www.facebook.com/photo.php?fbid=616017381757251&set=a.425695040789487.123032.234474246578235&type=1&theater">
                <h2>www.facebook.com/saveoneverything</h2>
            </a>
            <p>All holidays should be full of cheer and joyful moments; it is hard to go wrong on a holiday dedicated to the libations and comfort food of the Irish.</p>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/summer.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <span class="h3">March 15th 2013</span>
            <a href="https://www.facebook.com/photo.php?fbid=616017381757251&set=a.425695040789487.123032.234474246578235&type=1&theater">
                <h2>www.facebook.com/saveoneverything</h2>
            </a>
            <p>With summer approaching fast, many people are using coupons while listening to the swimsuit’s advice more than the sweatpants’ advice.</p>
        </div>
    </div>

</div>
</div>
@stop
