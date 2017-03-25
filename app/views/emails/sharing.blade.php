@section('email-title')
Check Out This Offer
@stop
@include('emails.templates.header')

<!-- Main Body Copy Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="h2" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          {{$sharer_name}} Wrote:
        </td>
      </tr>
      <tr>
        <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          {{$message_text}}
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- Main Body Copy End -->
<!-- Coupon Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="133" style="padding: 0 20px 20px 0;">
          <img class="fix" src="{{$entity->path}}" width="200" height="133" border="0" alt="" style="height: auto" />
        </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
    <table width="380" align="left" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          <![endif]-->
          <table class="col380" align="left" border="0" cellpadding="0" cellspacing="0" style=" max-width: 380px;">
            <tr>
              <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                      <strong style="font-size: 20px">{{$entity->name}}</strong><br />
                      {{$entity->merchant_name}}
                    </td>
                  </tr>
                  <!-- Coupon Button Start -->
                  <tr>
                    <td style="padding: 20px 0 0 0;">
                      <table class="buttonwrapper" bgcolor="#1f9f5f" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="button coupon" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px">
                            <a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.$entity->category_slug.'/'.$entity->subcategory_slug.'/'.$entity->merchant_slug.'/'.$entity->location_id.'/?showeid='.$entity->id.'&tm=email&share='.Auth::user()->id)}}" style="color: #fff;text-decoration: none">View Coupon »</a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Coupon Button End -->
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
        </td>
      </tr>
    </table>
    <![endif]-->
  </td>
</tr>
<!-- Coupon End -->
@include('emails.templates.about')
@include('emails.templates.footer')
