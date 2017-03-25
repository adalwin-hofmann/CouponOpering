<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Coming Soon Page" />
        <meta name="keywords" content="coming soon, responsive" />
        <meta name="author" content="ZTApps" />
        <link rel="shortcut icon" href="img/favicon.ico">
        <title>Save On is Coming Soon!</title>
        
        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>
        
        <link rel="stylesheet" media="screen" href="/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/css/comingsoon.css" />

        
    </head>
    
    <body>
        <header>    
            
            <div class="container content">
                <div class="row">
                    <div class="header-left-text">
                        <h5>SaveOn.com</h5>
                    </div>
                    <div class="header-right-text">
                        <h5><a target="_blank" href="https://saveoneverything.uservoice.com/clients/widgets/widget2?link_color=007DBF&locale=en&mode=support&primary_color=6F675E&referrer=http%3A%2F%2Ffeedback.saveoneverything.com%2Fknowledgebase/?utm_medium=thankyouB1&utm_source=customerio&utm_campaign=contestentryB1">Feedback</a></h5>
                    </div>
                </div>
                <h1 id="main-text">SaveOn.com</h1>
                <h3 id="second-text">The New Face of Save On Everything is Coming Soon</h3>
         
         
            </div>
        </header>

        <section id="social">
            <div class="container content">
                <ul class="social-icon-container">
                    <li>
                        <a href="http://facebook.com/saveoneverything" target="_blank">
                            <img src="img/social-icons/facebook.png" alt="" />
                        </a>
                    </li>
                    <li>
                        <a href="http://twitter.com/saveonevery" target="_blank">
                            <img src="img/social-icons/twitter.png" alt="" />
                        </a>
                    </li>
                </ul>
            </div>
        </section>
        

        
        <a href="#" class="scrollup"></a>
        
        <script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="/js/jquery.lwtCountdown-1.0.js" type="text/javascript"></script>
        
        <script>

            $(document).ready(function(){ 
                
                $(window).scroll(function(){
                    if ($(this).scrollTop() > 100) {
                        $('.scrollup').fadeIn();
                    } else {
                        $('.scrollup').fadeOut();
                    }
                }); 
         
                $('.scrollup').click(function(){
                    $("html, body").animate({ scrollTop: 0 }, 600);
                    return false;
                });
         
            });
            
        </script>
        
    </body>

</html>