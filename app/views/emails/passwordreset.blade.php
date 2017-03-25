@section('email-title')
Password Reset
@stop
@include('emails.templates.header')
<!-- Header/Main Body Copy -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          A password reset was requested for this account. If you did not request to reset your password, please ignore this email.<br /><br /><a style="color:#0099cc;" href="{{ URL::abs('/reset-password?uniq='.$key) }}">Click here to reset your password</a>. This link will expire in 1 hour.
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- End Header/Main Body Copy -->
@include('emails.templates.footer')