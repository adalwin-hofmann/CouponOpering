@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Merchant Review
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            Every project has a story, and we want to hear about yours!
            <br/><br/>
            Did your SaveOn Home Improvement expert provide you with the service you expected? Please let us know by clicking the "Merchant Review" button below!
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
                <a href="http://www.saveon.com/" style="color: #fff;text-decoration: none">Merchant Review »</a>
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