@extends('emails.templates.master-v2')
@section('email-title')
Quote Request Received!
@stop
@section('email-body')
  <!-- Main Body Copy Start -->
  <tr>
    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
              Hello{{ isset($first_name) ? ' '.$first_name : '' }},
              <br>
              <br>
              We have received your request. Someone will reach out to you shortly to answer any questions you have and get you your quote.
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