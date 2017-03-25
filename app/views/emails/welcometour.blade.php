@section('email-title')
SaveOn.com 3.0
@stop
@include('emails.templates.header')

<!-- Banner Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <img class="fix" src="http://s3.amazonaws.com/saveoneverything_assets/emails/welcometour_email_banner.jpg" width="100%" border="0" alt="" style="height: auto" />
  </td>
</tr>
<!-- Banner End -->
<!-- Header/Main Body Copy -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Welcome to the new SaveOn 3.0!
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          It is our mission to keep saving you time and money, and, with our new layout, we think we've done just that.
          <br><br>
          <strong>Well, what has changed?</strong>
          <br><br>
          With SaveOn.com 3.0's updated user-friendly design and improved search, offers at SaveOn.com have never been easier to find and use.
          <br><br>
          For our members, we've also created something truly special. From the moment you log in, you are sent to a new home page that you get to create! Favorite merchants, coupons, deals, and contests shape the backdrop of savings that members see every time they log in to SaveOn.com.
          <br><br>
          Experience a brand new way to save at <a target="_blank" style="color: #1F709F" href="http://www.saveon.com/">SaveOn.com</a>
          <br><br>
          <strong>Need help?</strong>
          <br><br>
          Take our site tour! Located on the welcome window or bottom of our web page, this tour was made to help guide new users through our brand new design and layout. 

        </td>
      </tr>
      <tr>
        <td style="padding: 20px 0 0 0;">
          <table class="buttonwrapper" bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="button" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px">
                <a href="http://www.saveon.com/" style="color: #fff;text-decoration: none">See the Site »</a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- End Header/Main Body Copy -->
@include('emails.templates.about')
@include('emails.templates.footer')
