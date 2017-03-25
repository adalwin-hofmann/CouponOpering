@section('email-title')
Redemption Chart
@stop
@include('emails.templates.header')

<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Keep Track of your Coupon Redemptions!
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          We know it's important for you to keep track of the coupons that are redeemed at your business. That is why we've created this simple chart to help you keep track of coupons that have been redeemed.
          <br><br>
          To learn more about how it works and download the chart, click the link below.
        </td>
      </tr>
      <!-- Standalone Button Start -->
      <tr>
        <td style="padding: 20px 0 0 0;">
          <a href="#">
            <table class="buttonwrapper" bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="button generic" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; color: #fff;text-decoration: none">
                  Download the chart »
                </td>
              </tr>
            </table>
          </a>
        </td>
      </tr>
      <!-- Standalone Button End -->
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
<!-- Bottom Body Copy Start -->
  <tr>
    <td class="innerpadding bodycopy" style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
      <strong>Have you filled up your chart?</strong> It's time to get a new one! Click <a target="_blank" style="color: #1F709F" href="http://www.saveon.com/">here</a>  to get a fresh chart with all new coupon codes on it.
    </td>
  </tr>
  <!-- Bottom Body Copy End -->
@include('emails.templates.footer')