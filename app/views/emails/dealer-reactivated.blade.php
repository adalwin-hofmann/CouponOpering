@extends('emails.templates.master-v2')
@section('email-title')
Dealer Reactivated!
@stop
@section('email-body')
  <!-- Main Body Copy Start -->
  <tr>
    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
              Hello,
              <br>
              <br>
              {{$merchant['display']}} has been reactivated and is ready to be launched live. We will reach out to you when their order is ready.
              <br>
              <br>
              Have a great day!
            </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Main Body Copy End -->
@stop
@section('email-signature')
Sincerely,<br>
<strong>SaveOn.com</strong>
@stop