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
          You have won the contest:
          <p>
            <strong>{{ $contest['display_name'] }}</strong>
          </p>
          To print or redeem your prize, go to the <a href="{{URL::abs('/contest-reward').'?vk='.$winner['verify_key']}}">Contest Prize Page</a>.
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