@section('email-title')
Lead Report
@stop
@include('emails.templates.header')
<!-- Header/Main Body Copy -->
<tr>
  <td style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
          Hello!
        </td>
      </tr>
      <tr>
        <td style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
          Your weekly Lead report is ready to be viewed. This report shows an overview of all of the leads you received this month.
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
            Status Report for {{date('m/1/Y')}} - {{date('m/d/Y')}}<br/>
            {{$merchant['display']}}
          </td>
        </tr>
        <!-- End Table Title -->
      </table>
  </td>
</tr>
<!-- 3 Column Table (Week 1) Begin -->
<tr>
  <td style="padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- Table Title -->
        <!-- <tr>
          <td bgcolor="" style="border-bottom: 1px solid #f2eeed; color: #62BB46;text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;padding: 10px; text-decoration: none">
            Week 1: [Date] &ndash; [Date]
          </td>
        </tr> -->
        <!-- End Table Title -->
        <tr>
          <td style="text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Date
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold; text-decoration: none; padding: 10px;">
                  Name
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Project Type
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: bold;text-decoration: none; padding: 10px;">
                  Price
                </td>
              </tr>
              <!-- End Row -->
              <?php $counter = 0; ?>
             @foreach($categories as $category => $leads)
             @foreach($leads as $lead)
             <?php $counter++; ?>
             <!-- Begin Row -->
              <tr>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  {{date('m/d/Y', strtotime($lead['created_at']))}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  {{$lead['first']}} {{$lead['last']}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  {{$category}}
                </td>
                <td style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal;text-decoration: none; padding: 10px;">
                  ${{$lead['price']}}
                </td>
              </tr>
              <!-- End Row -->
              @endforeach
              @endforeach
              @if($counter == 0)
              <tr>
                <td colspan="4" style="border-bottom: 1px solid #f2eeed; text-align: left;font-size: 16px;font-family: sans-serif;font-weight: normal; text-decoration: none; padding: 10px;">
                  No Leads To Report
                </td>
              </tr>
              @endif
            </table>
          </td>
        </tr>
      </table>
  </td>
</tr>
<!-- 3 Column Table (Week 1) End -->

<!-- Standalone Button -->
<!-- <tr>
  <td style="padding: 30px 30px 30px 30px;">
    <a href="#" style="color: #fff;text-decoration: none">
      <table bgcolor="#1F709F" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; color: #fff;text-decoration: none">
            Download a CSV »
          </td>
        </tr>
      </table>
    </a>
  </td> -->
</tr>
<!-- End Standalone Button -->
<!-- Bottom Body Copy Start -->
<tr>
  <td class="innerpadding bodycopy" style="border-top: 1px solid #f2eeed; padding: 30px 30px 30px 30px;color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
    If you have any questions or concerns regarding this report, please contact your sales rep.<br /><br />
    Thank you,<br />
    The SaveOn Digital Team 
  </td>
</tr>
<!-- Bottom Body Copy End -->
@include('emails.templates.footer')