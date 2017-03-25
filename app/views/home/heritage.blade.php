@extends('master.templates.master')
@section('page-title')
<h1>Our Heritage <small>You Dream It, We'll Build It</small></h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Our Heritage</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')

@stop

@section('body')                      
<div class = "content-bg">

        <img class = "img img-responsive pull-right" alt= "" style = "width: 50%; margin-left:10px; margin-bottom:10px;" src="http://s3.amazonaws.com/saveoneverything_assets/images/mikeg.png"/>
        
        <p>Founded as a company with a goal to provide local savings to its customers, Mike's Marketshare Coupons<sup>sm</sup> was created by Mike Gauthier in the Spring of 1984. Over the last 30 years, the company has grown and transformed into a multi media enterprise with national operations and an innovative digital presence. Recently, the company changed its name to SaveOn.com and received a complete makeover with a new logo and website. As the name implies the company seeks to assist its users save on almost every aspect of their life.</p>
        <p>Built on a strong network of local businesses offering exceptional products and services at a discount, SaveOn<sup>&reg;</sup> currently has 3.3 million magazines distributed each month between Metro Detroit, Chicago and Minnesota. Additionally, SaveOn<sup>&reg;</sup> has seen tremendous success through a digital marketing solution focused on providing local coupons, deals and contests. As part of this online presence, our family of websites includes SaveOn<sup>&reg;</sup> Groceries<sup>sm</sup>, SaveOn Cars &amp; Trucks<sup>&reg;</sup>, and SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup>. Content from these sites is ultimately distributed to the local consumers looking to SAVE what they value most: Time &amp; Money. </p>
        <p>SaveOn<sup>&reg;</sup> has established an unprecedented reputation in the industry based on 4 key areas:</p>
        <ol>
            <li>A Talented Management team </li>
            <li>Cost-effective marketing solutions</li>
            <li>Innovative marketing solutions through a multimedia platform</li>
            <li>Technological processes, systems, and software that creates an effective and efficient digital environment</li>
        </ol> 
        <p>It is out of these four areas that SaveOn<sup>&reg;</sup>'s mission was created:</p>
        <div class="clearfix"></div>
                                   
        <blockquote><p>Our mission at SaveOn<sup>&reg;</sup> is to improve the quality of life for every home we mail to. Our new digital platform will allow those same great savings even on the go! We accomplish this by providing great offers from only reputable businesses. We are leaders in marketing innovation and are committed to the growth of our business partners. We will be the #1 used savings product in the U.S., reaching 18 million households monthly to save them time and money</p>
        <p class="author">Mike Gauthier</p>
        </blockquote>

</div>


@stop