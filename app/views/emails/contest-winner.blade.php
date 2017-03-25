@extends('emails.templates.master-v2')
@section('email-title')
A Winner Has Been Chosen
@stop
@section('email-body')
<!-- Main Body Copy Start -->
<tr>
<td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          A winner has been chosen for <strong>{{ $contest['display_name'] }}</strong>.<br>
          <br>
          {{$contest_disclaimer['winner_name']}}<br>
          {{$contest_disclaimer['address']}}<br>
          {{$contest_disclaimer['city_state_zip']}}<br>
          <br>
          @if($contest['is_automated']) 
          This was an automated contest with an online prize.<br>
          URL: <a href="{{URL::abs('/contest-verify')}}?vk={{$contest_winner['verify_key']}}">{{URL::abs('/contest-verify')}}?vk={{$contest_winner['verify_key']}}</a>
          @else
          <strong>This prize needs to be mailed out!</strong><br>
          Print the contest letter by going <a href="{{URL::abs('/contest-letter')}}?vk={{$contest_winner['verify_key']}}">here</a>.</td>
          @endif
          @if($contest['total_inventory'] > 0)
          <br>
          <br>
          There are {{$contest['current_inventory']}} prizes left.
          @endif
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