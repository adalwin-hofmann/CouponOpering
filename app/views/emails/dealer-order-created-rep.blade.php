@extends('emails.templates.master-v2')
@section('email-title')
Lead Order Created!
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
              Your SOCT Lead Order has been submitted. You will start receiving leads during the period shown below.
              <br>
              <strong>Type:</strong> {{$make['name']}}
              <br>
              <strong>Budget: </strong> {{$order['budget']}}
              <br>
              <strong>Effective: </strong> {{date('m-d-Y', strtotime($order['starts_at']))}} - {{date('m-d-Y', strtotime($order['ends_at']))}}
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