@section('email-title')
  One Day Left!
@stop
@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Hello, _______
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          Did you know that your SaveOn Home Improvement referral trial is almost over? <span style="font-weight:bold; color:#62BB46;">Tomorrow is the final day of your free trial.</span> Don't wait to continue receiving the benefits of SaveOn's lead referral program. <a style="color:#1F709F" target="_blank" href="http://www.saveon.com/homeimprovement/get-certified">Sign up</a> today to become one of our Save Certified contractors.
          <br /><br />
          Becoming Save Certified is quick and easy, so why wait?
        </td>
      </tr>
      <!-- Standalone Button Start -->
      <tr>
        <td style="padding: 20px 0 0 0;">
          <table class="buttonwrapper" bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="button generic" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; color: #fff;text-decoration: none">
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