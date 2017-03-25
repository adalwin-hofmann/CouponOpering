@section('email-title')
Welcome Aboard
@stop
@include('emails.templates.header')
<!-- Header/Main Body Copy -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Hey, Welcome to SaveOn.com!
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          We're excited to help you get started.
          The moment you login to our site as a member, your SaveOn homepage will turn into an interactive playground for you to find all the deals you could ever want. And since we never want you to miss out on any updates to the offers you love, you'll have the option to favorite merchants, coupons, and contests so that you can relocate them at any time.<!--  Speaking of offers! We've placed three below to help get you started. --> 
        </td>
      </tr>
    </table>
  </td>
</tr>
@include('emails.templates.about')
@include('emails.templates.footer')
