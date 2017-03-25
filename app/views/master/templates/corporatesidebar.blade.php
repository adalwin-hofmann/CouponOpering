

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseThree">Who We Are<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseThree" class="panel-collapse collapse in">
        <div class="panel-body explore-links">
            <ul>
                <li <?=echoActiveClassIfRequestMatches("heritage")?>><a href="{{URL::abs('/')}}/heritage">Our Heritage</a></li>
                <li>
                    <a data-toggle="collapse" href="#merchantServices">Advertising</a>
                    <div class="clearfix"></div>
                    <div id="merchantServices" class="panel-collapse collapse in">
                        <ul>
                            <li <?=echoActiveClassIfRequestMatches("whyadvertise")?>><a href="{{URL::abs('/')}}/whyadvertise">Why Advertise</a></li>
                            <li <?=echoActiveClassIfRequestMatches("digitalproducts")?>><a href="{{URL::abs('/')}}/digitalproducts">Digital Products</a></li>
                            <li <?=echoActiveClassIfRequestMatches("featuredmerchants")?>><a href="{{URL::abs('/')}}/featuredmerchants">Testimonials</a></li>
                            <li <?=echoActiveClassIfRequestMatches("printproducts")?>><a href="{{URL::abs('/')}}/printproducts">Print Products</a></li>
                            <li <?=echoActiveClassIfRequestMatches("brands")?>><a href="{{URL::abs('/')}}/brands">Our Brands</a></li>
                            <li <?=echoActiveClassIfRequestMatches("adspecs")?>><a href="{{URL::abs('/')}}/adspecs">Ad Specs</a></li>
                            <li <?=echoActiveClassIfRequestMatches("advertising-faqs")?>><a href="{{URL::abs('/')}}/advertising-faqs">Advertising FAQs</a></li>
                            <li <?=echoActiveClassIfRequestMatches("maps")?>><a href="{{URL::abs('/')}}/maps">Maps</a></li>
                            <li <?=echoActiveClassIfRequestMatches("fileupload")?>><a href="{{URL::abs('/')}}/fileupload">FTP Upload</a></li>
                            <li><a href="http://www.callsource.com/home/reporting-login">Call Capture</li>
                            <li><a href="https://save.magazinemanager.com/payonline/">Pay My Bill</a></li>
                        </ul>
                    </div>
                </li>
                <li <?=echoActiveClassIfRequestMatches("ourteam")?>><a href="{{URL::abs('/')}}/ourteam">Our Team</a></li>
                <li <?=echoActiveClassIfRequestMatches("presskit")?>><a href="{{URL::abs('/')}}/presskit">Press Kit</a></li>  
                <li <?=echoActiveClassIfRequestMatches("news")?>><a href="{{URL::abs('/')}}/news">News &amp; Views</a></li>
                <li <?=echoActiveClassIfRequestMatches("careers")?>><a href="{{URL::abs('/')}}/careers">Careers</a></li>
                <li <?=echoActiveClassIfRequestMatches("headquarters")?>><a href="{{URL::abs('/')}}/headquarters">Headquarters</a></li>
                 <li <?=echoActiveClassIfRequestMatches("faqs")?>><a href="{{URL::abs('/')}}/faqs">FAQs</a></li>
                <li <?=echoActiveClassIfRequestMatches("contact")?>><a href="{{URL::abs('/')}}/contact">Contact</a></li>
            </ul>
        </div>
    </div>
</div>


<?php 

function echoActiveClassIfRequestMatches($requestUri)
{
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        echo 'class="active"';
}

?>



