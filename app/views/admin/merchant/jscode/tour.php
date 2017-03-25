<script src="/js/bootstrap-tour.min.js"></script>
<script>

// Add class first-coupon to first coupon

tourFirst = function(){
  //var found = false;  $('#container .item.coupon').each(function(){if(!found){$(this).addClass('first-coupon');found=true;}});
  //var found = false;  $('#container .item.contest').each(function(){if(!found){$(this).addClass('first-contest');found=true;}});
}
// Instance the tour
var tour = new Tour({
  
  steps: [
  {
    title: "New to the Merchant Portal?",
    content: "Take our tour! Hit &ldquo;Next&rdquo; to begin, or click &ldquo;End Tour&rdquo; at any time to stop the tour.",
    container: "body",
    orphan: true,
    //backdrop: true
  },
  {
    element: "#location",
    title: "Select Location",
    content: "Select which location you'd like to see reports on or view all of them together.",
    container: "body",
    placement: "right",
    //backdrop: true
  },
  { //MEMBERSHIP AREA
    element: "#tour-signout",
    title: "Membership Area",
    content: "Click your name to access your membership area from anywhere on the site.",
    container: "body",
    placement: "right",
    backdrop: true
  },
  {
    element: "#selDate",
    title: "Change the Date Range",
    content: "Change the date range to get a better idea of how your business is doing.",
    container: 'body',
    placement: "left",
    //backdrop: true
  },
  {
    element: ".tour-stats p",
    title: "Your Reports",
    content: "Get your up to date reports right here.",
    container: 'body',
    placement: "right",
    //backdrop: true
  },
  {
    element: "#offerModal",
    title: "Get More Info",
    content: "Click here to see more deatils on your offers and contests.",
    container: 'body',
    placement: "right",
    //backdrop: true
  },
  /*{
    element: "#container .first-coupon",
    title: "Coupons",
    content: "Click the merchant logo or name to get more offers and information.",
    container: 'body',
    placement: "top",
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
    element: "#container .first-coupon .btn-coupon-share",
    title: "Share It",
    content: "Click &ldquo;Share It&rdquo; to share the coupon with friends via email or Facebook.",
    container: 'body',
    placement: "top",
    backdrop: true
  },
  { //MEMBERSHIP AREA
    element: "#tour-mycoupons",
    title: "My Saved Coupons",
    content: "See all of your saved coupons in one place for easy access on any device.",
    container: 'body',
    placement: "bottom",
    backdrop: true
  },
  { //MEMBERSHIP AREA
    element: "#tour-mysavetodays",
    title: "My Save Todays",
    content: "Save Today's are daily deals worth 40% Off or more. You will find all of the deals that you have saved on your &ldquo;My Save Todays&rdquo; page.",
    container: 'body',
    placement: "bottom",
    backdrop: true
  },
  { //MEMBERSHIP AREA
    element: "#tour-mycontests",
    title: "My Contests",
    content: "See recommended contests and keep track of contests that you have already entered.",
    container: 'body',
    placement: "bottom",
    backdrop: true
  },
  { //MEMBERSHIP AREA
    element: "#tour-myfavoritemerchants",
    title: "My Favorite Merchants",
    content: "Easily keep up with your favorite merchants by checking out your &ldquo;My Favorite Merchants&rdquo; page.",
    container: 'body',
    placement: "bottom",
    backdrop: true
  },
  { //MEMBERSHIP AREA
    element: "#tour-mystuff",
    title: "My Stuff",
    content: "You can also access your saved offers, favorited merchants, and account settings from the &ldquo;My Stuff&rdquo; sidebar.",
    container: 'body',
    placement: "right",
    backdrop: true
  },*/
  {
    element: "#uvTab",
    title: "Feedback",
    content: "Questions or suggestions? Click &ldquo;Feedback&rdquo; and a SaveOn Customer Service Representative will be notified to help.",
    container: 'body',
    placement: "left",
    //backdrop: true
  }
]});

$(document).ready(function() {
    /*$('.links-line .col-sm-12').each(function(){
        $(this).addClass('col-sm-6');
    });
    $('.links-line .tour-column, .tour-start').removeClass('hidden');*/
    //$('.tour-show, .tour-start').removeClass('hidden');
    //$('.tour-hide').addClass('hidden');
});
</script>
