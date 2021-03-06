@extends('master.templates.master')

@section('page-title')
<h1>Contact</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Contact</li>
@stop

@section('sidebar')
    @include('master.templates.corporatesidebar')
@stop

@section('body')
<div class="content-bg">
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a href="#detroit" data-toggle="tab" class="detroit-tab">Detroit</a></li>
        <li><a href="#chicago" data-toggle="tab" class="chicago-tab">Chicago</a></li>
        <li><a href="#twincities" data-toggle="tab" class="twincities-tab">Twin Cities</a></li>
        <li><a href="#grandrapids" data-toggle="tab" class="grandrapids-tab">Grand Rapids</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade in active" id="detroit">
            <div class="row">
                <div class="col-xs-12">
                    <h2>Detroit Office</h2>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div id="map">
                </div>
                <br>
                </div>
                <div class="col-sm-4">
                    <address>
                        1000 W. Maple Road,<br />
                        Troy, MI 48084
                        USA<br />
                        Phone: 800.495.5464<br />
                        Fax: 248.362.2177<br />
                        Detroit Contact: <a href="mailto:detroitcontact@saveon.com">detroitcontact@saveon.com</a><br />
                        Web: <a href="{{URL::abs('/')}}">www.saveon.com</a>
                    </address>
                    <ul class="socials unstyled">
                        <li><a href="#" class="flickr"></a></li>
                        <li><a href="#" class="twitter"></a></li>
                        <li><a href="#" class="facebook"></a></li>
                        <li><a href="#" class="youtube"></a></li>
                        <li><a href="#" class="dribbble"></a></li>
                        <li><a href="#" class="pinterest"></a></li>
                    </ul>
                    <h3>Hours of Business Operation</h3><br /><br />
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="small">Monday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Tuesday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Wednesday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Thursday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Friday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Saturday:</td>
                                <td>Closed</td>
                            </tr>
                            <tr>
                                <td class="small">Sunday:</td>
                                <td>Closed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="chicago">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Chicago Office</h2>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div id="mapChicago">
                </div>
                <br>
                </div>
                <div class="col-sm-4">
                    <address>
                        1930 N. Thoreau Drive, Suite 177<br />
                        Schaumburg,
                        IL 60173 USA<br />
                        Phone: 800.495.5464<br />
                        Fax: 847.925.0419<br /><br />
                        Chicago Contact: <a href="mailto:chicagocontact@saveon.com">chicagocontact@saveon.com</a><br />
                        Web: <a href="{{URL::abs('/')}}">www.saveon.com</a>
                    </address>
                    <ul class="socials unstyled">
                        <li><a href="#" class="flickr"></a></li>
                        <li><a href="#" class="twitter"></a></li>
                        <li><a href="#" class="facebook"></a></li>
                        <li><a href="#" class="youtube"></a></li>
                        <li><a href="#" class="dribbble"></a></li>
                        <li><a href="#" class="pinterest"></a></li>
                    </ul>
                    <h3>Hours of Business Operation</h3><br /><br />
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td class="small">Monday:</td>
                                    <td class="bold">8am to 6pm</td>
                                </tr>
                                <tr>
                                    <td class="small">Tuesday:</td>
                                    <td class="bold">8am to 6pm</td>
                                </tr>
                                <tr>
                                    <td class="small">Wednesday:</td>
                                    <td class="bold">8am to 6pm</td>
                                </tr>
                                <tr>
                                    <td class="small">Thursday:</td>
                                    <td class="bold">8am to 6pm</td>
                                </tr>
                                <tr>
                                    <td class="small">Friday:</td>
                                    <td class="bold">8am to 6pm</td>
                                </tr>
                                <tr>
                                    <td class="small">Saturday:</td>
                                    <td>Closed</td>
                                </tr>
                                <tr>
                                    <td class="small">Sunday:</td>
                                    <td>Closed</td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="twincities">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Twin Cities Office</h2>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div id="mapMinnesota">
                </div>
                <br>
                </div>
                <div class="col-sm-4">
                    <address>
                        1272 Helmo Ave<br />
                        Oakdale,
                        MN 55128 USA<br />
                        Phone: 800.495.5464<br />
                        Fax: 651.888.7499<br /><br />
                        Twin Cities Contact: <a href="mailto:minneapoliscontact@saveon.com ">minneapoliscontact@saveon.com </a><br />
                        Web: <a href="{{URL::abs('/')}}">www.saveon.com</a>
                    </address>
                    <ul class="socials unstyled">
                        <li><a href="#" class="flickr"></a></li>
                        <li><a href="#" class="twitter"></a></li>
                        <li><a href="#" class="facebook"></a></li>
                        <li><a href="#" class="youtube"></a></li>
                        <li><a href="#" class="dribbble"></a></li>
                        <li><a href="#" class="pinterest"></a></li>
                    </ul>
                    <h3>Hours of Business Operation</h3><br /><br />
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="small">Monday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Tuesday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Wednesday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Thursday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Friday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Saturday:</td>
                                <td>Closed</td>
                            </tr>
                            <tr>
                                <td class="small">Sunday:</td>
                                <td>Closed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="grandrapids">
            <div class="row">
                <div class="col-sm-12">
                    <h2>Grand Rapids Office</h2>
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div id="mapGrandrapids"></div>
                </div>
                <div class="col-sm-4">
                    <address>
                        852 47th St.<br>
                        Wyoming, MI 49509 USA<br>
                        Phone: 616.735.9283<br />
                        <br>
                        <!--Fax: 651.888.7499<br /><br />
                        Twin Cities Contact: <a href="mailto:minneapoliscontact@saveon.com ">minneapoliscontact@saveon.com </a><br />-->
                        Web: <a href="{{URL::abs('/')}}">www.saveon.com</a>
                    </address>
                    <h3>Hours of Business Operation</h3><br /><br />
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="small">Monday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Tuesday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Wednesday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Thursday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Friday:</td>
                                <td class="bold">8am to 6pm</td>
                            </tr>
                            <tr>
                                <td class="small">Saturday:</td>
                                <td>Closed</td>
                            </tr>
                            <tr>
                                <td class="small">Sunday:</td>
                                <td>Closed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@stop