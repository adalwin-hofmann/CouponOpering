@extends('emails.templates.master-v2')
@section('email-title')
Thank Your for Signing Up
@stop
@section('email-body')
  <!-- Main Body Copy Start -->
  <tr>
    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
              Thank you, {{$attendee_name}} for signing up for {{$event->name}}
              <br>
              <br>
              <strong>Event Details</strong>
              <br>
              <br>
              <strong>Event Name:</strong> {{$event->name}}
              <br>
              <br>
              <strong>Date:</strong> {{date('n/j/Y', strtotime($event->date))}} - <strong>Time:</strong> {{date('g:i a', strtotime($event->date))}}
              <br>
              <br>
              <strong>Description:</strong><br>
              {{$event->description}}
              <br>
              <br>
            </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Main Body Copy End -->
@stop
@section('email-signature')
Sincerely,<br>
SaveOn
@stop