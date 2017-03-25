@extends('emails.templates.master-v2')
@section('email-title')
SaveOn - Contest Semi-Finalist!
@stop
@section('email-body')

<!-- Main Body Copy Start -->
<tr>
<td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Congratulations, {{$winner['first_name']}}!
        </td>
    </tr>
    <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          You have been selected as one of the Semi-Finalists for the SaveOn contest:
          <p>
            <strong>{{ $contest['display_name'] }}</strong>
          </p>
          In order to be selected as the winner of the contest, you must complete the <a href="{{URL::abs('/contest-verify').'?vk='.$winner['verify_key']}}">Contest Disclaimer Form</a>.<br>
          Only the first Semi-Finalist to submit their Contest Disclaimer will be selected as the winner, so submit yours now!
          <p>
            <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="Submit Form" href="{{URL::abs('/contest-verify').'?vk='.$winner['verify_key']}}"><img alt="Submit Form" src="http://s3.amazonaws.com/saveoneverything_assets/emails/submit_form_btn.jpg"></a>
          </p>
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