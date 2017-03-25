@extends('emails.templates.master-v2')
@section('email-title')
Not Enough Contest Winners
@stop
@section('email-body')
<!-- Main Body Copy Start -->
<tr>
    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
              The contest, <strong>{{ $contest['display_name'] }}</strong>, has closed, but not enough winners were selected.<br>
              <br>
              {{ $selected }} out of {{ $date['winners'] }} were selected.
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