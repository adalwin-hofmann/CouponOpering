@section('email-title')
  FTP Upload
@stop
@include('emails.templates.header')
<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #1f904d;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          File uploaded sucessfully.
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            Company Name: {{$input_data["inputName"]}}<br/>
            Client Contact: {{$input_data["inputClientContact"]}}<br/>
            Phone: {{$input_data["inputPhone"]}}<br/>
            Advertiser: {{$input_data["inputAdvertiser"]}}<br/>
            Market (City): {{$input_data["inputCity"]}}<br/>
            Issue (Month): {{$input_data["inputMonth"]}}<br/>
            Sales Rep: {{$input_data["inputSalesRep"]}}<br/>
            Email: {{$input_data["inputEmail"]}}<br/>
            <br/>
            The following files have been uploaded:<br/>
            @if($uploaded[0]["status"] == "error")
            {{$uploaded[0]["message"]}}
            @else
            <a href="{{$link}}">{{$link}}</a><br/>
            @endif
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
@include('emails.templates.footer')