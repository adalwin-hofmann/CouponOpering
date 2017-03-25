@section('email-title')
Great New Offers
@stop
@include('emails.templates.header')

<!-- Main Body Copy Start -->
                  <tr>
                    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="h2" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                            Your Favorite Merchant(s) Have New Offers!
                          </td>
                        </tr>
                        <tr>
                          <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                            It's that time again... Some of your favorite merchants have posted new offers. Log in to your SaveOn.com account to see all the great new money-saving coupons, offers, and contests!
                          </td>
                        </tr>
                        <!-- Generic Button Start -->
                        <tr>
                          <td style="padding: 20px 0 0 0;">
                            <table class="buttonwrapper" bgcolor="#000" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td class="button generic" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px">
                                  <a href="#" style="color: #fff;text-decoration: none">My Favorite Merchants »</a>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <!-- Generic Button End -->
                      </table>
                    </td>
                  </tr>
                  <!-- Main Body Copy End -->

@include('emails.templates.about')
@include('emails.templates.footer')