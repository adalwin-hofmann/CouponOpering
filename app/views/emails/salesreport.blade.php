@section('email-title')
Weekly Sales Report
@stop
@include('emails.templates.header')

<!-- Header/Main Body Copy -->
<tr>
  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Hello, {{$name}}!
        </td>
      </tr>
      <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          Your weekly status report is ready to be viewed. This report shows an up-to-date analysis of how your merchants have been doing over the past month.
        </td>
      </tr>
      
    </table>
  </td>
</tr>
<!-- End Header/Main Body Copy -->
<tr>
  <td style="padding: 30px 30px 0px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="text-align: left;font-size: 20px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Status Report for {{$start}} &ndash; {{$end}}
          </td>
        </tr>
        <!-- End Table Title -->
      </table>
  </td>
</tr>
<!-- 3 Column Table (Top 5 Locations) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Prints &amp; Views
          </td>
        </tr>
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Merchant Name
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Views
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Prints
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              @foreach($list as $merchant)
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  {{$merchant['name']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$merchant['views']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$merchant['prints']}}
                </td>
              </tr>
              @endforeach
              <!-- End Row -->
              <!-- Begin Row -->
              <!-- <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr> -->
              <!-- End Row -->
              <!-- Begin Row -->
              <!-- <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr> -->
              <!-- End Row -->
              <!-- Begin Row -->
              <!-- <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr> -->
              <!-- End Row -->
              <!-- Begin Row -->
              <!-- <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr> -->
              <!-- End Row -->
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 3 Column Table (Top 5 Locations) End -->
<!-- 2 Column Table (Contest Sign Ups) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Contest Sign Ups
          </td>
        </tr>
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Row Begin -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Merchant
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Entries
                </td>
              </tr>
              <!-- Row End -->
              <!-- Row Begin -->
              @foreach($list as $merchant)
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  {{$merchant['name']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$merchant['signups']}}
                </td>
              </tr>
              @endforeach
              <!-- Row End -->
              <!-- Row Begin -->
              <!-- <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
              </tr> -->
              <!-- Row End -->
              <!-- Row Begin -->
              <!-- <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
              </tr> -->
              <!-- Row End -->
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 2 Column Table (Contest Sign Ups) End -->

<!-- 3 Column Table (Top 5 Locations) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Warnings
          </td>
        </tr>
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Merchant
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Coupons Expiring Soon
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Has Offers?
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Has About Us?
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              @foreach($list as $merchant)
              @if($merchant['expiring'] > 0 || $merchant['about'] == 'No' || $merchant['active'] == 'No')
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  {{$merchant['name']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$merchant['expiring']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$merchant['active']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$merchant['about']}}
                </td>
              </tr>
              @endif
              @endforeach
              <!-- End Row -->
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 3 Column Table (Top 5 Locations) End -->
<!-- Standalone Button -->
<tr>
  <td style="padding: 30px 30px 30px 30px;">
    <a href="{{url('/reports/salesreport/'.$user_id.'/csv')}}" style="color: #fff;text-decoration: none">
      <table bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; color: #fff;text-decoration: none">
            Download a CSV »
          </td>
        </tr>
      </table>
    </a>
  </td>
</tr>
<!-- End Standalone Button -->
<!-- Bottom Body Copy Start -->
<tr>
  <td class="innerpadding bodycopy" style="border-top: 1px solid #f2eeed; padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    If you have any questions or concerns regarding this report, please fill out a feedback ticket here.<br /><br />
    Thank you,<br />
    The SaveOn Digital Team 
  </td>
</tr>
<!-- Bottom Body Copy End -->
@include('emails.templates.footer')