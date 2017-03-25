@section('email-title')
Welcome To SaveOn.com
@stop
@include('emails.templates.header')
<!-- Banner Start -->
<tr>
  <td class="innerpadding borderbottom" style="border-bottom: 1px solid #f2eeed">
    <img class="fix" src="http://s3.amazonaws.com/saveoneverything_assets/emails/welcome_banner.jpg" width="100%" border="0" alt="" style="height: auto" />
  </td>
</tr>
<!-- Banner End -->
<!-- Header/Main Body Copy -->
<tr>
  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Congratulations, {{$name}}!
        </td>
      </tr>
      <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          You are now an official SaveOn.com member. That means you'll have access to all sorts of amazing offers and contests, as well as your own custom coupon dashboard. <strong>Here are a few of our favorites to get you started...</strong>
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- End Header/Main Body Copy -->

<!-- Fluid Coupon Row (2 Across) Start -->
<tr>
  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <!-- Coupon Start -->
    @foreach($recommendations as $recommendation)
    <a target="_blank" href="{{URL::abs('/?showeid='.$recommendation->id)}}">
      <table width="250" align="left" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" width="250" style="border-collapse: collapse;font-family: sans-serif; padding:0px 20px 20px 0px;">
            <table style=" border-top:1px solid #f2eeed; border-left:1px solid #f2eeed; border-right:3px solid #f2eeed; border-bottom:3px solid #f2eeed; padding:10px;" border="0" cellpadding="0" cellspacing="0" width="250">
                <tbody>
                    <tr style="border-collapse: collapse">
                        <td width="250" style="border-collapse: collapse;font-family: sans-serif">
                            <a target="_blank" href="{{URL::abs('/?showeid='.$recommendation->id)}}">
                                <center><img width="250" height="auto" style="width:140px; height:auto;" src="{{$recommendation->path}}"></center>
                            </a>
                        </td>
                    </tr>
                    <tr style="border-collapse: collapse">
                        <td width="250" style="border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 50px">
                            <a style="color:#1F9F5F; text-decoration:none;" target="_blank" href="{{URL::abs('/?showeid='.$recommendation->id)}}">{{$recommendation->merchant_name}}</a>
                        </td>
                    </tr>
                    <tr style="border-collapse: collapse">
                        <td width="250" style="border-collapse: collapse; color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                            <a style="color:#333; text-decoration:none;" target="_blank" href="{{URL::abs('/?showeid='.$recommendation->id)}}">{{$recommendation->name}}</a>
                        </td>
                    </tr>
                    <tr style="border-collapse: collapse; border-bottom: 1px solid #f2eeed">
                        <td width="250" style="border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; border-top:1px solid #f2eeed; text-align: center; text-transform: uppercase;">
                            <a style="color:#1F9F5F; text-decoration:none;" target="_blank" href="{{URL::abs('/?showeid='.$recommendation->id)}}"><img style="vertical-align:middle; width:25px;" src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_coupon.png"> Get It</a>
                        </td>
                    </tr>
                </tbody>
            </table>
          </td>
        </tr>
      </table>
    </a>
    @endforeach
    <!-- Coupon End -->
  </td>
</tr>
<!-- Fluid Coupons Row (2 Across) End -->

<!-- Bottom Body Copy Start -->
  <tr>
    <td class="innerpadding bodycopy" style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
      If you haven't already, please take a moment to set up your interests, so we can be sure to show you the savings and offers you are most likely to love.<br>
      <a style="color: #E3582C;" href="{{URL::abs('/members/myinterests')}}">Customize My Interests &raquo;</a> <br><br>
      Thanks for joining!<br><br>
      Sincerely,<br>
      The SaveOn Team
    </td>
  </tr>
  <!-- Bottom Body Copy End -->

@include('emails.templates.footer')
