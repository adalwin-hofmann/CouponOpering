@section('email-title')
Email Verification
@stop
@include('emails.templates.header')
<!-- Header/Main Body Copy -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          Welcome to Save On! To get the most out of what SaveOn.com has to offer, we need you to verify your email address.<br /><br />
          Click the link below to verify your email address.<br /><br />
          <a style="color:#0099cc;" href="{{ URL::abs('/verify-email?uniq='.$key.'&email='.$email) }}">{{ URL::abs('/verify-email?uniq='.$key.'&email='.$email) }}</a><br />
          This link will expire in {{(int) ceil($timer / 60)}} hour{{$timer > 60 ? 's' : ''}}.
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- End Header/Main Body Copy -->
@include('emails.templates.footer')