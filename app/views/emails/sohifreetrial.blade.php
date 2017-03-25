@section('email-title')
  14-Day Free Trial
@stop
@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Hello, _______
        </td>
      </tr>
      <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          To celebrate the launch of SaveOn Home Improvement, we are giving you a 14-day free trial to use our new lead referral program!<br /><br />

          <span style="font-weight:bold; color:#62BB46;">Early Access</span><br />
          This free trial puts you at the front of the pack, allowing you the chance to be one of the first merchants to join our referral program. Do you know what that means? Less competition for leads! Leads will be sent to this email address as they are submitted. If you would like to use a different email address, please register with the correct email by signing up as a Save Certified merchant.
          <br /><br />
          <span style="font-weight:bold; color:#62BB46;">Become Save Certified Now</span><br />
          Sign up soon to ensure that you will continue to receive leads long after your 14-day free trial ends.
        </td>
      </tr>
      <!-- Standalone Button Start -->
      <tr>
        <td style="padding: 20px 0 0 0;">
          <table bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; color: #fff;text-decoration: none">
                <a style="color:#FFF;text-decoration:none;" target="_blank" href="http://www.saveon.com/homeimprovement/get-certified">Become Save Certified »</a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <!-- Standalone Button End -->
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
@include('emails.templates.footer')