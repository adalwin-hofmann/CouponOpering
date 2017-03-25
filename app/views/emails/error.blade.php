@section('email-title')
SaveOn.Com Error Report
@stop
@include('emails.templates.header')

<!-- Main Body Copy Start -->
                  <tr>
                    <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td class="h2" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
                            An Error has Occurred! - {{date('m-d-Y H:i:s')}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            Referrer: {{$referrer}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            Current URL: {{$current}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            City: {{$city}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            State: {{$state}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            Latitude: {{$latitude}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            Longitude: {{$longitude}}
                          </td>
                        </tr>
                        <tr>
                          <td class="h3" style="color: #333;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 20px;line-height: 28px;font-weight: bold">
                            Inputs: <?php print_r($inputs) ?>
                          </td>
                        </tr>
                        <tr>
                          <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
                            {{$exception}}
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <!-- Main Body Copy End -->

@include('emails.templates.footer')