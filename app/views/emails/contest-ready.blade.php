@extends('emails.templates.master-v2')
@section('email-title')
Contest Ready For Awarding
@stop
@section('email-body')
<!-- Main Body Copy Start -->
<tr>
<td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          The contest, <strong>{{ $contest['display_name'] }}</strong>, is ready to be awarded.
        </td>
    </tr>
  </table>
</td>
</tr>
<!-- Main Body Copy End -->
@stop
@section('email-signature')
Sincerely,<br>
Your Friendly Contest Automator
@stop