@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get A Quote</small></h1>
@stop
@section('body')

<div class="panel panel-default margin-top-20 how-it-works">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        How It Works
        <div class="clearfix"></div>
      </span>
    </div>
    <div>
        <div class="panel-body explore-links">
            <div class="row">
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-md-3">
                            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/works_icon-1.png">
                        </div>
                        <div class="col-md-9">
                            <span class="h2">Tell us about your project</span>
                            <br>
                            <p>Get started by giving us the details on what you need done. We have  hundreds of trusted contractors for every home improvement need. </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-md-3">
                            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/works_icon-2.png">
                        </div>
                        <div class="col-md-9">
                            <span class="h2">We do the leg work</span>
                            <br>
                            <p>We'll send your details to a Save Certified contractor that matches your project’s needs. They will contact you to set up a time to meet for an estimate.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="row">
                        <div class="col-md-3">
                            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/works_icon-3.png">
                        </div>
                        <div class="col-md-9">
                            <span class="h2">No cost to you</span>
                            <br>
                            <p>It's free for all SaveOn.com<sup>&reg;</sup> members! Just one more way we help you save time and money. </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Save Certified -->
            <hr style="margin-left:-15px; margin-right:-15px;">
            <div class="row">
                <div class="col-sm-2">
                    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                </div>
                <div class="col-sm-10">
                    <span class="h2">SaveOn Guarantee!</span>
                    <p>We guarantee that any contractor we send you has gone through our 5 point inspection.</p><br>
                    <p>All companies must be:</p>
                    <ul class="check_list">
                        <li><strong>Licensed</strong> - Not every industry requires a license</li>
                        <li><strong>Bonded</strong> - When needed</li>
                        <li><strong>Insured</strong> - Workman's Comp / Company Liability</li>
                        <li><strong>Background checks of workers, by the company</strong></li>
                        <li><strong>Better Business Bureau</strong></li>
                    </ul>
                    <p>Also: Every job will be reviewed when completed for job satisfaction. Companies that don’t live up to our Save Satisfaction will be removed from our referral program.</p>
                </div>
            </div> 
            <!-- Get Started -->
            <hr style="margin-left:-15px; margin-right:-15px;">
            <div class="row">
                <div class="col-sm-12">
                    <a href="{{URL::abs('/')}}/homeimprovement/projecttype?franchise_id={{Input::get('franchise_id', 0)}}&offer_id={{Input::get('offer_id', 0)}}" class="btn btn-green pull-right">GET STARTED <span class="glyphicon glyphicon-chevron-right"></span></a>
                </div>
            </div>            
        </div>
    </div>
</div> 
<p class="margin-top-20">We have a contractor for every category!</p>
<div class="project-type-tiles row">
    <div class="col-sm-6 col-md-4">
        <div class="content-bg">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/quote_landing/plumbing.jpg">
            <div class="row">
                <div class="col-xs-12">
                    <span class="h1">Plumbing</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="content-bg">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/quote_landing/landscaping.jpg">
            <div class="row">
                <div class="col-xs-12">
                    <span class="h1">Landscaping &amp; Gardening</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="content-bg">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/quote_landing/kitchen.jpg">
            <div class="row">
                <div class="col-xs-12">
                    <span class="h1">Kitchen &amp; Bath</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="content-bg">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/quote_landing/windows.jpg">
            <div class="row">
                <div class="col-xs-12">
                    <span class="h1">Windows / Doors</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="content-bg">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/quote_landing/painting.jpg">
            <div class="row">
                <div class="col-xs-12">
                    <span class="h1">Painting</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="content-bg">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/quote_landing/electrical.jpg">
            <div class="row">
                <div class="col-xs-12">
                    <span class="h1">Electric</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row margin-top-20">
    <div class="col-xs-12">
        <a href="{{URL::abs('/')}}/homeimprovement/projecttype?franchise_id={{Input::get('franchise_id', 0)}}&offer_id={{Input::get('offer_id', 0)}}" class="btn btn-green pull-right">GET STARTED <span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
</div>
@stop