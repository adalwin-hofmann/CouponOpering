@section('email-title')
  YOU HAVE A NEW LEAD!
@stop
@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 16px;line-height: 19px;font-weight: bold">
          Project Details
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            <b>Category</b>: {{$category}}<br/>
            <b>Time Frame</b>: {{$timeframe}}<br/>
            <b>Project Description</b>: {{$description}}<br/>
        </td>
      </tr>
      <br/>
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 16px;line-height: 19px;font-weight: bold">
          Contact Info
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            <b>Name</b>: {{$name}}<br/>
            <b>Email</b>: {{$email}}<br/>
            <b>Phone</b>: {{$phone}}<br/>
            <b>Address</b>: {{$address}}<br/>
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
@include('emails.templates.footer')