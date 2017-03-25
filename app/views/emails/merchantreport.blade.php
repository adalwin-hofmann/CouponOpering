@section('email-title')
Status Report
@stop
@include('emails.templates.header')

<!-- Header/Main Body Copy -->
<tr>
  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Hello, [Name]!
        </td>
      </tr>
      <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          Your status report is ready to be viewed. This report shows an up-to-date analysis of how your offers have been doing.
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
            Status Report for 1/2/2014 &ndash; 4/3/2014
          </td>
        </tr>
        <!-- End Table Title -->
      </table>
  </td>
</tr>
<!-- 2 Column Table (Overview) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Overview
          </td>
        </tr>
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Row Begin -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Total Views
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- Row End -->
              <!-- Row Begin -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Total Prints
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
              </tr>
              <!-- Row End -->
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 2 Columb Table (Overview) End -->
<!-- 3 Column Table (Top 5 Locations) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Top 5 Locations
          </td>
        </tr>
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Location Name
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
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 3 Column Table (Top 5 Locations) End -->
<!-- 3 Column Table (Offers Breakdown) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Offer Breakdown
          </td>
        </tr>
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Offer
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Views
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Prints/Signups
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Win a FREE $100 Gas Card
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Buy One Get One FREE
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  $5 OFF Any Purchase
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  Belle Tire Holland
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  1000
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  100
                </td>
              </tr>
              <!-- End Row -->
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 3 Column Table (Offers Breakdown) End -->
<!-- Standalone Button -->
<tr>
  <td style="padding: 30px 30px 30px 30px;">
    <a href="#" style="color: #fff;text-decoration: none">
      <table bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; color: #fff;text-decoration: none">
            Download a PDF »
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
    If you have any questions or concerns regarding this report, please contact your sales representative.<br /><br />
    Thank you,<br />
    The SaveOn Team 
  </td>
</tr>
<!-- Bottom Body Copy End -->
@include('emails.templates.footer')