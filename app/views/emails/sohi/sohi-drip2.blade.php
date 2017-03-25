@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          How's it Going?
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            It's been a little over 30 days since you originally started working with your SaveOn Home Improvement merchant.
            <br/><br/>
            Let us know how things have been going by clicking the button below and filling out a quick survey. Your feedback will help us provide better customer service to all of our customers!
            <br/><br/>
            Thank you,<br/>
            - The SaveOn Home Improvement Team
        </td>
      </tr>
      <!-- Standalone Button Start -->
      <tr>
        <td style="padding: 20px 0 0 0;">
          <table class="buttonwrapper" bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="button" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px">
                <a href="http://www.saveon.com/" style="color: #fff;text-decoration: none">Take the Survey »</a>
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