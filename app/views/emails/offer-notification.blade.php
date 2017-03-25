@extends('emails.templates.master-v2')
@section('email-title')
Semifinalists Selected
@stop
@section('email-body')
  <!-- Main Body Copy Start -->
  <tr>
    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
              The follow merchants will be out of offers within 15 days:<br>
            </td>
        </tr><tr>
            <td style="color: #333;font-family: sans-serif;font-size: 14px;line-height: 22px">
              @foreach($franchises as $franchise)
              {{$franchise->merchant_name}}{{($franchise->primary_contact!='')?' - '.$franchise->primary_contact:''}}<br>
              @endforeach
            </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Main Body Copy End -->
@stop
@section('email-signature')
@stop