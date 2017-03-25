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
              <h2>Chamber Email</h2>
              <p>First Name: {{$firstname}}<br>
                Last Name: {{$lastname}}<br>
                Company: {{$company}}<br>
                Phone Number: {{$phone}}<br>
                Email: {{$email}}</p>
            </td>
        </tr>
      </table>
    </td>
</tr>
<!-- Main Body Copy End -->
@stop
@section('email-signature')
@stop