@section('email-title')
Your Save Todays
@stop

@include('emails.templates.header')

<!-- Main Body Copy Start -->
  <tr>
    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="h2" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
            Your Save Todays are about to expire
          </td>
        </tr>
        <tr>
          <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            Hello, _______
            <br><br>
            We noticed that some of the Save Todays that you have saved are getting ready to expire. We wanted to send you this friendly reminder so that you don't miss out on an opportunity to use them before it's too late!
          </td>
        </tr>
        <!-- Generic Button Start -->
        <tr>
          <td style="padding: 20px 0 0 0;">
            <table class="buttonwrapper" bgcolor="#1f709f" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="button generic" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px">
                  <a href="#" style="color: #fff;text-decoration: none">View Your Save Todays »</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <!-- Generic Button End -->
      </table>
    </td>
  </tr>
  <!-- Main Body Copy End -->

@include('emails.templates.about')
@include('emails.templates.footer')