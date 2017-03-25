<html>
    <head>
        <title>{{isset($title) ? $title : ''}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{isset($description) ? $description : ''}}">

        <!-- Bootstrap -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/css/style.css?version={{empty($csscache) ? '0' : $csscache->value}}">
        <link rel="stylesheet" href="/css/style-sm.css" media="screen  and (min-width: 550px)" />
        <link rel="stylesheet" href="/css/style-md.css" media="screen  and (min-width: 768px)" />
        <link rel="stylesheet" href="/css/style-lg.css" media="screen  and (min-width: 992px)" />
        <link rel="stylesheet" href="/css/style-xl.css" media="screen  and (min-width: 1300px)" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="/js/html5shiv.min.js"></script>
            <script src="/js/respond.min.js"></script>
            <style>
                a.item, div.item {
                    border: 1px solid #d8d8d8;
                }
            </style>
        <![endif]-->
        <!--
            <script src="/js/css3-mediaqueries.js"></script>
        -->

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

    </head>
    <body style="background-color: #FFF; padding:10px;">
        <h1>Merchant Report for {{$user_name}}</h1>
        <h2>From {{date('m-d-Y', strtotime('-1 month'))}} - {{date('m-d-Y')}}</h2>
        <a href="/reports/salesreport/{{$user_id}}/csv">Download</a> as CSV.
        <hr>
        <br />
        <table class="table table-striped table-striped-dark">
            <tr>
                <th>Merchant</th>
                <th>State</th>
                <th>Prints</th>
                <th>Views</th>
                <th>Contest Signups</th>
                <th>
                    Offers Expiring<br />
                    (Next 10 Days)
                </th>
                <th>Active Offers</th>
                <th>Has Offers</th>
                <th>Has About Us</th>
                <!-- <th>Has SEO</th> -->
            </tr>
            @foreach($franchises as $franchise)
            <tr>
                <td>{{$franchise->merchant_name}}</td>
                <td>{{$franchise->state}}</td>
                <td>{{$franchise->prints}}</td>
                <td>{{$franchise->views}}</td>
                <td>{{$franchise->applicants}}</td>
                <td>{{$franchise->expiring_offers}}</td>
                <td>{{$franchise->active_offers}}</td>
                <td>{{$franchise->active_offers ? 'Yes' : 'No'}}</td>
                <td>{{$franchise->merchant_about != '' ? 'Yes' : 'No'}}</td>
                <!-- <td style="text-align:center; border-top: solid 1px;">{{($franchise->merchant_title != '' && $franchise->merchant_description != '') ? 'Yes' : 'No'}}</td> -->
            </tr>
            @endforeach
        </table>
    </body>
</html>
