<script src="/js/bootstrap-tour.min.js"></script>
<script>

// Find first coupon and modal, then add a unique class to them so they can be selected in the tour
tourFirst = function(){
  var found = false;  $('#container .item.coupon').each(function(){if(!found){$(this).addClass('first-coupon');found=true;}});
  var found = false;  $('#container .item.contest').each(function(){if(!found){$(this).addClass('first-contest');found=true;}});
}

// Instance the tour
var tour = new Tour({
  
  steps: [
  {
    title: "New to SaveOn Home Improvement?",
    content: "Take our tour! Hit &ldquo;Next&rdquo; to begin, or click &ldquo;End Tour&rdquo; at any time to stop the tour.",
    container: "body",
    orphan: true,
    backdrop: true
  },
  {
    element: "#tour-signin",
    title: "Sign In or Sign Up",
    content: "Log in or sign up as a member to get access to SaveOn's member features which include all of our exciting contests.",
    container: "body",
    placement: "right",
    backdrop: true
  },
  { //MEMBERS
    element: "#tour-signout",
    title: "Membership Area",
    content: "Click your name to access your membership area from anywhere on the site.",
    container: "body",
    placement: "right",
    backdrop: true
  },
  {
    element: "#locationMenu",
    title: "Edit Location",
    content: "Change your location by selecting this dropdown and searching for your city or zip code.",
    container: 'body',
    placement: "left",
    backdrop: true
  },
  {
    element: ".city-banner.sohi",
    title: "SaveOn Home Improvement",
    content: "Find the right contractor for your home improvement job with SaveOn.com.",
    container: 'body',
    placement: "bottom",
    backdrop: true
  },
  {
    element: ".sidebar.main-menu > #tour-quote.tour-quote > img",
    title: "Get a Quote",
    content: "Want to get a quote from one of our contractors? To begin, hit the &ldquo;Get Started&rdquo; button.",
    container: 'body',
    placement: "right",
    backdrop: true
  },
  {
    element: "#container .first-contest",
    title: "Contests",
    content: "A benefit of becoming a member at SaveOn.com, on top of all the great contractors you'll get to choose from, is the chance to win big prizes by entering one of our contests.",
    container: 'body',
    placement: "right",
    backdrop: true
  },
  {
    element: "#container .first-coupon",
    title: "Coupons",
    content: "Click the merchant logo or name to get more offers and information.",
    container: 'body',
    placement: "right",
    backdrop: true
  },
  {
    element: "#container .first-coupon .btn-get-coupon",
    title: "Get It",
    content: "Click &ldquo;Get It&rdquo; to review a coupon's details. This is also where you will have the option to print your coupon.",
    container: 'body',
    placement: "top",
    backdrop: true
  },
  {
    element: "#container .first-coupon .btn-save-coupon",
    title: "Save It",
    content: "Members can save coupons to their member dashboard by clicking &ldquo;Save It&rdquo;. This makes it easy to find all of your favorite coupons in one place!",
    container: 'body',
    placement: "bottom",
    backdrop: true
  },
  {
    element: "#container .first-coupon .btn-coupon-quote",
    title: "Quote It",
    content: "Get a quote from this Home Improvement merchant by clicking here.",
    container: 'body',
    placement: "right",
    backdrop: true
  },
  {
    element: ".panel.panel-default.how-it-works",
    title: "How It Works",
    content: "At SaveOn our members are given the opportunity to find the contractor of their dreams. To learn more, reference the highlighted section, which can be collapsed and expanded by clicking the minus/plus icon in the corner.",
    container: 'body',
    placement: "top",
    backdrop: true
  },
  {
    element: "#uvTab",
    title: "Feedback",
    content: "Questions or suggestions? Click &ldquo;Feedback&rdquo; and a SaveOn Customer Service Representative will be notified to help.",
    container: 'body',
    placement: "left",
    backdrop: true
  }
]});

// This code makes the tour link show up in the footer and welcome modal.
$(document).ready(function() {
    /*$('.links-line .col-sm-6').each(function(){
        $(this).addClass('col-sm-4');
    });
    $('.links-line .tour-column, .tour-start').removeClass('hidden');*/
    $('.tour-show, .tour-start').removeClass('hidden');
    $('.tour-hide').addClass('hidden');
});





</script>
