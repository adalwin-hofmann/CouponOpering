@extends('emails.templates.master-v2')
@section('email-title')
SaveOn - Contest Prize!
@stop
@section('email-body')
<!-- Main Body Copy Start -->
<tr>
<td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Congratulations!
        </td>
    </tr>
    <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          You have won the contest:<br>
          <br>
            <strong>{{ $contest['display_name'] }}</strong><br>
          <br>
          Due to the lack of advancement in teleportation technology, please allow 2 to 4 weeks while we mail you your prize.<br>
          <br>
          If you have any questions, please feel free to <a href="{{URL::abs('/')}}/contact">contact us</a>.<br>
          <br>
          Please check out our other contests, you could have more chances to win. <a href="{{URL::abs('/')}}/contests/all">Click here to explore all contests</a>
        </td>
    </tr>
  </table>
</td>
</tr>
<!-- Main Body Copy End -->
@stop
@section('email-signature')
Sincerely,<br>
The SaveOn Contest Team
@stop