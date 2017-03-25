@section('email-title')
  Account Deleted
@stop
@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          We're sad to see you go.
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          Your SaveOn.com account has been deleted.<br /><br />
          We had so much fun getting to know you, but we hope you'll be happy wherever it is you decide to go. Before you leave, could you help us improve by filling out <a style="color:#0099cc;" href="#">this quick 5 min survey?</a>.<br /><br />
          Your feedback will help us offer a better service to all of our members.<br /><br />
          <strong>Don't forget!</strong> This doesn't have to be goodbye forever, you can still print coupons for your favorite merchants even without an account.
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
@include('emails.templates.footer')