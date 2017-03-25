@section('email-title')
  User Suggestion
@stop
@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          A user has submitted a suggestion.
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            Company Name: {{$suggestion->business}}<br/>
            City: {{$suggestion->city}}<br/>
            Address: {{$suggestion->address1}}<br/>
            Zipcode: {{$suggestion->zipcode}}<br/>
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
@include('emails.templates.footer')