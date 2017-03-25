@extends('master.templates.master')
@section('page-title')
<h1>Why Advertise</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Why Advertise</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="margin-bottom-10">What makes advertising work?</h2>
            <h3 class="margin-bottom-10">There are four basic keys to making sure your marketing dollars are successful.</h3>
            <div>
                <p>1. Timing - You are not going to get anyone happily involved in buying your product without them having an interest in your product or service. This is why timing is so important. You have to be there when the client is ready to buy! That's why large companies have sales every week! As a consumer, you ignore the advertising until you have decided you need their product or service. Then, like magic, you see their advertising.</p>
                <p>2. Repetition - With repetition you automatically get timing. Stay consistent with the method you have chosen to advertise in for a minimum of 6 months. Trying advertising once or twice and making a decision based on inadequate evidence is a big mistake companies make! You may have missed out on what could have been the best way possible to market your business. Do you think spending $300-$500 twice on advertising should change your company with respect to attracting new business and gaining increased sales? Over 30 years, I've seen companies make this mistake over and over. Commit to a minimum of 6 months to make advertising worth your while.</p>
                <p>3. Offer - The purpose of an ad/coupon is to entice a person to use your company or buy your product. Once you get a new client, it's up to you to keep them. Make sure the offer is enough to make a switch from where they currently do business or to add your business to their list of places they frequent. Your offer should be at least 25% off to create a response from the consumer.</p>
                <p>4. Product - When choosing where to spend your money, make sure the advertising product reaches the people you want to do business with. If you sell boats, a boating magazine or boat show makes sense. Ask questions and make sure you choose wisely. Monitor closely your responses with call tracking, scanning or manually with paper. Take the time to measure the response (for example: the average sale per response and number of responses). One product may give you more returns, but a lower response sale.</p>
                
                <p>Lastly, act like a big company! Determine a budget, find the right product or products and run them for at least six months if not a full year. At the end of that time, measure your returns then make adjustments. It will save you time and money. I guarantee it!</p>

                <p>Mike Gauthier</p>
                <p>Founder / Owner</p>
            </div>
        </div>
    </div>
</div>
@stop