<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SaveOn Deals</title>
  </head>
  <body yahoo="" bgcolor="#d7d9d6" style="margin: 0;padding: 0;min-width: 100% !important">
  <table width="100%" bgcolor="#d7d9d6" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <!--[if (gte mso 9)|(IE)]>
        <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
              <![endif]-->     
              <table bgcolor="#D7D9D6" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%;max-width: 600px">
                <!-- View Email Link Start -->
                <tr>
                  <td bgcolor="#D7D9D6" style="padding: 0px">
                    <!--[if (gte mso 9)|(IE)]>
                    <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td>
                          <![endif]-->
                          <table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;">
                            <tr>
                              <td height="20">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td style="padding: 5px 0 5px; 0;color: #000;font-family: sans-serif;font-size: 12px;line-height: 18px;">
                                      <center>Go to <a href="http://www.saveon.com/" target="_blank" style="color: #1F709F; text-decoration:none;">SaveOn.Com</a> | <a style="color:#1F709F; text-decoration:none;" target="_blank" href="{{URL::abs('/members/myinterests')}}">Customize your interests</a></center>
                                    </td>
                                  </tr>
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
                <!-- View Email Link End -->
                <!-- Header Start -->
                <tr>
                  <td bgcolor="#FFFFFF" style="padding: 10px 0 20px 0">
                    <!--[if (gte mso 9)|(IE)]>
                    <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td>
                          <![endif]-->
                          <table align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;">
                            <tr>
                              <td height="70">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td style="padding: 0 0 0 0;font-size: 15px;color: #fff;font-family: sans-serif;letter-spacing: 3px">
                                      <img alt="SaveOn" width="600" height="60" border="0" alt="" style="height: 60px" src="http://s3.amazonaws.com/saveoneverything_assets/emails/saveon_logo_deals.jpg">
                                    </td>
                                  </tr>
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
                <tr>
                  <td style="background:#FFFFFF;padding: 10px 20px 10px 20px;border-bottom: 1px solid #f2eeed">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     
                      <tr>
                        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                          {{$intro}}
                        </td>
                      </tr>
                      <tr>
                        <td style="color: #A5A6A4;font-family: sans-serif;font-size: 12px;line-height: 16px; text-align: right">
                          {{date('n/j/Y')}}
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <!-- Header End -->
                
                <!-- 20px Spacer Start -->
                <tr>
                  <td style="background:#D7D9D6;padding: 10px;">
                  <!-- 20px spacer -->
                  </td>
                </tr>
                <!-- 20px Spacer End -->

                @if($featured)
                <tr>
                  <td style="background:#FFFFFF;padding: 10px 20px 10px 20px;border-bottom: 1px solid #f2eeed">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     
                      <tr>
                        <td style="color: #333;font-family: sans-serif;font-size: 18x;line-height: 22px">
                          <img alt="Featured Deals" style="" src="http://s3.amazonaws.com/saveoneverything_assets/emails/featured-deals.jpg">
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- 10px Spacer Start -->
                <tr>
                  <td style="background:#D7D9D6;padding: 5px;">
                  <!-- 10px spacer -->
                  </td>
                </tr>
                <!-- 10px Spacer End -->

                <tr>
                  <td>
                    <table width="600" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr valign="top" style="vertical-align:top">
                          <td width="600" valign="top" style="vertical-align:top; padding: 0 0 20px 0">
                            <table style="background:#FFFFFF; border:1px solid #C1C1C1;" border="0" cellpadding="0" cellspacing="0" width="598">
                            <tbody>
                                <tr valign="top" style="vertical-align:top; border-collapse: collapse;">
                                    <td width="288" style="border-collapse: collapse;font-family: sans-serif; padding: 20px;">
                                        <a href="{{URL::abs('/coupons/'.$featured['category_slug'].'/'.$featured['subcategory_slug'].'/'.$featured['merchant_slug'].'/'.$featured['location_id'].'?showeid='.$featured['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><center><img width="288" height="auto" style="height:auto; max-height: 175px" src="{{$featured['path']}}"></center></a>
                                    </td>
                                    <td width="252" style="border-collapse: collapse;font-family: sans-serif;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="252">
                                            <tr style="border-collapse: collapse; vertical-align: top" valign="top">
                                              <td width="100%" style="padding:20px 10px 0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;">
                                                  <a style="color:#1F9F5F; text-decoration:none;" target="_blank" href="{{URL::abs('/coupons/'.$featured['category_slug'].'/'.$featured['subcategory_slug'].'/'.$featured['merchant_slug'].'/'.$featured['location_id'].'?showeid='.$featured['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter">{{$featured['merchant_name']}}</a>
                                              </td>
                                          </tr>
                                          <tr style="border-collapse: collapse">
                                              <td width="100%" style="border-collapse: collapse; color: #333;font-family: sans-serif; padding:5px 10px 10px 10px; margin-bottom: 0;font-size: 18px; font-weight: bold">
                                                  <a style="color:#333; text-decoration:none;" target="_blank" href="{{URL::abs('/coupons/'.$featured['category_slug'].'/'.$featured['subcategory_slug'].'/'.$featured['merchant_slug'].'/'.$featured['location_id'].'?showeid='.$featured['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter">{{$featured['name']}}</a>
                                              </td>
                                          </tr>
                                          <tr style="border-collapse: collapse;">
                                              <td width="100%" style="padding-bottom:10px;border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                                  <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="{{URL::abs('/coupons/'.$featured['category_slug'].'/'.$featured['subcategory_slug'].'/'.$featured['merchant_slug'].'/'.$featured['location_id'].'?showeid='.$featured['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><img alt="View Coupon for {{$featured['name']}}" style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_coupon_btn.jpg"></a>
                                              </td>
                                          </tr>
                                        </table>
                                    </td>
                                </tr>
                                  
                              </tbody>
                            </table>
                          </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                @endif

                <tr>
                  <td style="background:#FFFFFF;padding: 10px 20px 10px 20px;border-bottom: 1px solid #f2eeed">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     
                      <tr>
                        <td style="color: #333;font-family: sans-serif;font-size: 18x;line-height: 22px">
                          <img alt="More Great Deals" style="" src="http://s3.amazonaws.com/saveoneverything_assets/emails/more-great-deals.jpg">
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- 10px Spacer Start -->
                <tr>
                  <td style="background:#D7D9D6;padding: 5px;">
                  <!-- 10px spacer -->
                  </td>
                </tr>
                <!-- 10px Spacer End -->

                <tr>
                  <td>
                    <table width="600" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr valign="top" style="vertical-align:top">
                        @for($i=0; $i < count($coupons); $i++)
                          <td width="194" valign="top" style="vertical-align:top; {{(($i+1) % 3 == 0)?'padding: 0 0 20px 0':'padding: 0 20px 20px 0'}}">
                            <table style="background:#FFFFFF; border:1px solid #C1C1C1; min-height: 250px" border="0" cellpadding="0" cellspacing="0" width="192">
                              <tbody>
                                  <tr valign="top" style="vertical-align:top; border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse;font-family: sans-serif">
                                          <a href="{{URL::abs('/coupons/'.$coupons[$i]['category_slug'].'/'.$coupons[$i]['subcategory_slug'].'/'.$coupons[$i]['merchant_slug'].'/'.$coupons[$i]['location_id'].'?showeid='.$coupons[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><center><img width="192" height="auto" style="height:auto; max-height: 175px" src="{{$coupons[$i]['path']}}"></center></a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse; vertical-align: top" valign="top">
                                      <td width="100%" style="padding:10px 10px 0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" href="{{URL::abs('/coupons/'.$coupons[$i]['category_slug'].'/'.$coupons[$i]['subcategory_slug'].'/'.$coupons[$i]['merchant_slug'].'/'.$coupons[$i]['location_id'].'?showeid='.$coupons[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter">{{$coupons[$i]['merchant_name']}}</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse; color: #333;font-family: sans-serif; padding:5px 10px 10px 10px; margin-bottom: 0;font-size: 18px; font-weight: bold">
                                          <a style="color:#333; text-decoration:none;" target="_blank" href="{{URL::abs('/coupons/'.$coupons[$i]['category_slug'].'/'.$coupons[$i]['subcategory_slug'].'/'.$coupons[$i]['merchant_slug'].'/'.$coupons[$i]['location_id'].'?showeid='.$coupons[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter">{{$coupons[$i]['name']}}</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse;">
                                      <td width="100%" style="padding-bottom:10px;border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="{{URL::abs('/coupons/'.$coupons[$i]['category_slug'].'/'.$coupons[$i]['subcategory_slug'].'/'.$coupons[$i]['merchant_slug'].'/'.$coupons[$i]['location_id'].'?showeid='.$coupons[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><img alt="View Coupon for {{$coupons[$i]['name']}}" style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_coupon_btn.jpg"></a>
                                      </td>
                                  </tr>
                              </tbody>
                            </table>
                          </td>
                          @if (($i+1) % 3 == 0)
                            </tr>
                            <tr valign="top" style="vertical-align:top">
                          @endif
                        @endfor
                      </tr>
                    </table>
                  </td>
                </tr>

                @if(count($contests) >= 2)
                <tr>
                  <td style="background:#FFFFFF;padding: 10px 20px 10px 20px;border-bottom: 1px solid #f2eeed">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                     
                      <tr>
                        <td style="color: #333;font-family: sans-serif;font-size: 18x;line-height: 22px">
                          <img alt="Featured Contests" style="" src="http://s3.amazonaws.com/saveoneverything_assets/emails/featured-contests.jpg">
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>

                <!-- 10px Spacer Start -->
                <tr>
                  <td style="background:#D7D9D6;padding: 5px;">
                  <!-- 10px spacer -->
                  </td>
                </tr>
                <!-- 10px Spacer End -->

                <tr>
                  <td>
                    <table width="600" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr valign="top" style="vertical-align:top">
                        @for($i=0; $i < count($contests); $i++)
                          <td width="280" valign="top" style="vertical-align:top; {{($i % 2)?'padding: 0 0 20px 0':'padding: 0 20px 20px 0'}}">
                            <table style="background:#FFFFFF; border:5px solid #E3582C;" border="0" cellpadding="0" cellspacing="0" width="288">
                              <tbody>
                                  <tr valign="top" style="vertical-align:top; border-collapse: collapse">
                                      <td width="100%" valign="top" style="vertical-align:top; border-collapse: collapse;font-family: sans-serif">
                                          <center><a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="{{URL::abs('/coupons/'.$contests[$i]['category_slug'].'/'.$contests[$i]['subcategory_slug'].'/'.$contests[$i]['merchant_slug'].'?showeid='.$contests[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><img width="278" height="auto" style="height:auto;" src="{{$contests[$i]['path']}}"></a></center>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse; vertical-align: top" valign="top">
                                      <td width="100%" valign="top" style="vertical-align:top; padding:10px 10px 0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;">
                                          <a style="color:#E3582C; text-decoration:none;" target="_blank" href="{{URL::abs('/coupons/'.$contests[$i]['category_slug'].'/'.$contests[$i]['subcategory_slug'].'/'.$contests[$i]['merchant_slug'].'?showeid='.$contests[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter">{{$contests[$i]['merchant_name']}}</a>
                                      </td>
                                    </tr>
                                    <tr style="border-collapse: collapse; vertical-align: top" valign="top">
                                      <td width="100%" valign="top" style="vertical-align:top; padding:5px 10px 10px 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 16px;">
                                          <a style="color:#333; text-decoration:none;" target="_blank" href="{{URL::abs('/coupons/'.$contests[$i]['category_slug'].'/'.$contests[$i]['subcategory_slug'].'/'.$contests[$i]['merchant_slug'].'?showeid='.$contests[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><strong style="font-size: 18px">{{$contests[$i]['display_name']}}</strong></a>
                                      </td>
                                  </tr>
                                  <tr>
                                    <td height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 0 10px 0">
                                        <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="{{URL::abs('/coupons/'.$contests[$i]['category_slug'].'/'.$contests[$i]['subcategory_slug'].'/'.$contests[$i]['merchant_slug'].'?showeid='.$contests[$i]['id'])}}?utm_source=newsletter&utm_medium=email&utm_campaign=member%20newsletter"><img alt="View Contest for {{$contests[$i]['display_name']}}" style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_contest_btn.jpg"></a>
                                    </td>
                                  </tr>
                                      
                              </tbody>
                            </table>
                          </td>
                          @if ($i % 2)
                            <tr valign="top" style="vertical-align:top">
                          @endif
                        @endfor
                      </tr>
                    </table>
                  </td>
                </tr>

                @endif

                
                <!-- Fluid Coupon Row (2 Across) Start -->
                <!-- <tr>
                  <td style="padding: 0 0 0 20px;">
                    <table width="268" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top" width="268" style="border-collapse: collapse;font-family: sans-serif; padding:0px 20px 20px 0px;">
                          <table style="background:#FFFFFF; border:1px solid #C1C1C1;" border="0" cellpadding="0" cellspacing="0" width="268">
                              <tbody>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse;font-family: sans-serif">
                                          <a href="#"><center><img width="268" height="auto" style="height:auto;" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/sliders/16451544c91df29f.jpg"></center></a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="padding:0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 45px">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" href="#">Loccino Italian Grill & Bar in Troy, MI</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse; color: #333;font-family: sans-serif;padding: 0 10px 15px 10px;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                                          <a style="color:#333; text-decoration:none;" target="_blank" href="#">$6 OFF 2 Lunch Entrees</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse;">
                                      <td width="100%" style="padding-bottom:20px;border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="#"><img style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_coupon_btn.jpg"></a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <table width="268" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top" width="268" style="border-collapse: collapse;font-family: sans-serif; padding:0px 20px 20px 0px;">
                          <table style="background:#FFFFFF; border:1px solid #C1C1C1;" border="0" cellpadding="0" cellspacing="0" width="268">
                              <tbody>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse;font-family: sans-serif">
                                          <a href="#"><center><img width="268" height="auto" style="height:auto;" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/sliders/16451544c91df29f.jpg"></center></a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="padding:0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 45px">
                                          <a style="color:#1f709f; text-decoration:none;" target="_blank" href="#">Loccino Italian Grill & Bar in Troy, MI</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse; color: #333;font-family: sans-serif;padding: 0 10px 15px 10px;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                                          <a style="color:#333; text-decoration:none;" target="_blank" href="#">$6 OFF 2 Lunch Entrees</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse;">
                                      <td width="100%" style="padding-bottom:20px;border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="#"><img style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_deal_btn.jpg"></a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                
                <tr>
                  <td style="padding: 0 0 0 20px;">
                    <table width="268" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top" width="268" style="border-collapse: collapse;font-family: sans-serif; padding:0px 20px 20px 0px;">
                          <table style="background:#FFFFFF; border:1px solid #C1C1C1;" border="0" cellpadding="0" cellspacing="0" width="268">
                              <tbody>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse;font-family: sans-serif">
                                          <a href="#"><center><img width="268" height="auto" style="height:auto;" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/sliders/16451544c91df29f.jpg"></center></a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="padding:0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 45px">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" href="#">Loccino Italian Grill & Bar in Troy, MI</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse; color: #333;font-family: sans-serif;padding: 0 10px 15px 10px;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                                          <a style="color:#333; text-decoration:none;" target="_blank" href="#">$6 OFF 2 Lunch Entrees</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse;">
                                      <td width="100%" style="padding-bottom:20px;border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="#"><img style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_coupon_btn.jpg"></a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <table width="268" align="left" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td valign="top" width="268" style="border-collapse: collapse;font-family: sans-serif; padding:0px 20px 20px 0px;">
                          <table style="background:#FFFFFF; border:1px solid #C1C1C1;" border="0" cellpadding="0" cellspacing="0" width="268">
                              <tbody>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse;font-family: sans-serif">
                                          <a href="#"><center><img width="268" height="auto" style="height:auto;" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/sliders/16451544c91df29f.jpg"></center></a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="padding:0 10px; border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 45px">
                                          <a style="color:#1f709f; text-decoration:none;" target="_blank" href="#">Loccino Italian Grill & Bar in Troy, MI</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse">
                                      <td width="100%" style="border-collapse: collapse; color: #333;font-family: sans-serif;padding: 0 10px 15px 10px;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                                          <a style="color:#333; text-decoration:none;" target="_blank" href="#">$6 OFF 2 Lunch Entrees</a>
                                      </td>
                                  </tr>
                                  <tr style="border-collapse: collapse;">
                                      <td width="100%" style="padding-bottom:20px;border-collapse: collapse; color: #1F9F5F;font-family: sans-serif;font-size: 14px;line-height: 40px; font-weight: bold; text-align: center; text-transform: uppercase;">
                                          <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="#"><img style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/view_deal_btn.jpg"></a>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr> -->
                <!-- Fluid Coupons Row (2 Across) End -->
                
                <!-- Header/Main Body Copy -->
                <tr>
                  <td style="background:#FFFFFF;padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                          Thank You!
                        </td>
                      </tr>
                      <tr>
                        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                          Thanks for being an official <a href="http://www.saveon.com/" target="_blank" style="color: #1F709F">SaveOn.com</a> member! We are excited to bring you all sorts of amazing offers. Be sure to try your luck in one of our many <a href="http://www.saveon.com/contests/all" target="_blank" style="color: #1F709F">SaveOn Contests</a> where you can win prizes, trips, or even cash!
                          <br>
                          <br>
                          SaveOn can bring the savings in many different areas including restaurants, retail, automotive, and even <a href="http://www.saveon.com/groceries" target="_blank" style="color: #1F709F">groceries</a>. If you are in the market for a new or used car, be sure to check out <a href="http://www.saveon.com/cars" target="_blank" style="color: #1F709F">SaveOn Cars &amp; Trucks</a>.
                          <br>
                          <br>
                          <strong>Click <a href="http://www.saveon.com/members/dashboard" target="_blank" style="color: #1F709F">here</a> to check out your &quot;members only&quot; custom coupon dashboard with these deals and more!</strong>
                          <!--Thanks for being an official SaveOn.com member. We are excited to bring you all sorts of amazing offers, contests, and daily deals, as well as your "members only" custom coupon dashboard. <strong>Here are a few of our favorites just for you...</strong>-->
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <!-- End Header/Main Body Copy -->
                
                <!-- 10px Spacer Start -->
                <tr>
                  <td style="background:#D7D9D6;padding: 5px;">
                  <!-- 10px spacer -->
                  </td>
                </tr>
                <!-- 20px Spacer End -->

                <!-- Bottom Body Copy Start -->
                  <tr>
                    <td class="innerpadding bodycopy" style="background:#FFFFFF;padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                      <table style="background:#FFFFFF;" border="0" cellpadding="0" cellspacing="0">
                        <tr valign="top" style="vertical-align: top">
                          <td width="260" style="padding: 0 20px 0 0; color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                            If you haven't already, please take a moment to set up your interests, so we can be sure to show you the savings and offers you are most likely to love.
                            <br><br>
                            <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="{{URL::abs('/members/myinterests')}}"><img style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/customize_interests_btn.jpg"></a> <br><br>
                          </td>
                          <td width="260" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                            Did you know that we have a SaveOn Blog? Check it out for topics like Health, Home Improvement, Tips & Tricks, Coupon Savings and more!
                            <br><br>
                            <a style="color:#1F9F5F; text-decoration:none;" target="_blank" alt="View Coupon" href="http://www.saveon.com/blog/"><img style="vertical-align:middle;" src="http://s3.amazonaws.com/saveoneverything_assets/emails/visit_blog_btn.jpg"></a> <br><br>
                          </td>
                        </tr>
                      </table>
                      
                      Thanks for joining!<br><br>
                      Sincerely,<br>
                      <!-- The SaveOn Team<br>-->
                      <img alt="The SaveOn Team" style="" src="http://s3.amazonaws.com/saveoneverything_assets/emails/the-saveon-team.jpg">
                    </td>
                    <td width="290"></td>
                  </tr>
                  <!-- Bottom Body Copy End -->
                  <!-- Footer Start -->
                  <tr>
                    <td class="footer" bgcolor="#62BB46" style="padding: 20px 30px 15px 30px">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td align="left" class="footercopy" style="font-family: sans-serif;font-size: 14px;color: #333; line-height:18px;">
                            <!-- Update your email preferences <a style="color:#0099cc;" href="/members/mynotifications">here.</a><br /> -->
                            <!-- If you don't want to receive amazing emails from us, <a style="color:#FFF;" href="#">Unsubscribe.</a>
                            <br /><br /> -->
                            <a style="color:#FFF;" href="http://www.saveon.com">SaveOn.com</a>
                            1000 W. Maple, Troy, MI 48084<br />
                            (248) 362-9119
                          </td>

                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Footer End -->
                </table>
                <br><br><!--  Line breaks were added here to push down the Unsubscribe link that is automatically added when the email is sent -->
                <!--[if (gte mso 9)|(IE)]>
              </td>
            </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
    </table>
  </body>
</html>
