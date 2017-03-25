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
              <h2>Congratulations – you are now SAVE Certified!</h2>
              <img style="weight: 150px; height: 179px; display: block; margin-bottom: 10px;" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
              <p>Should you have any questions, please feel free to reach out to us.<br>
                Thank you for your partnership!</p>
            </td>
        </tr>
      </table>
    </td>
</tr>
<!-- Main Body Copy End -->
@stop
@section('email-signature')
@stop