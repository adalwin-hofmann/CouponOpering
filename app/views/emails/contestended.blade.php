@section('email-title')
Everybody Wins at SaveOn
@stop
@include('emails.templates.header')
<?php
if ((!isset($winners)) && (!isset($entity)))
{
  $contest = $data['contest'];
  $winners = $data['winners'];
  $location = $data['location'];
  $entity = $data['entity'];
}
$current = 1;
?>
<!-- Banner Start -->
<tr>
	<td style="padding: 0;border-bottom: 1px solid #f2eeed">
		<img class="fix" src="{{$contest['banner']}}" width="100%" border="0" alt="" style="height: auto" />
	</td>
</tr>
<!-- Banner End -->
<!-- Main Body Copy Start -->
<tr>
	<td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;border-bottom: 1px solid #f2eeed">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="h2" style="color: #62BB46;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold">
	        Congratulations to @if(count($winners))@foreach($winners as $winner){{$winner['first'].' '.substr(ucwords($winner['last']),0,1).'.'}} in {{ucwords(strtolower($winner['city']))}}{{count($winners) > 1 ? ($current == (count($winners) - 1) ? ' and ' : ($current == count($winners) ? '' : ', ')) : ''}}<?php $current++ ?>@endforeach@else our winners@endif!
	      </td>
	    </tr>
	    <tr>
	      <td class="bodycopy" style="color: #333;font-family: sans-serif;font-size: 16px;line-height: 22px">
            @if(count($winners))
            <?php $current = 1; ?>
	        @foreach($winners as $winner){{$winner['first']}}{{count($winners) > 1 ? ($current == (count($winners) - 1) ? ' and ' : ($current == count($winners) ? ' ' : ', ')) : ' '}}<?php $current++ ?>@endforeach {{count($winners) > 1 ? 'are the winners':'is the winner'}} of the {{$contest['name']}} contest from {{$merchant['display']}}.
            @else
            Thank you for participating in the {{$contest['name']}} contest!
            @endif
            <br><br>
            {{$contest['text']}}
		    <br><br>
		    Thank you!
	      </td>
	    </tr>
	  </table>
	</td>
</tr>
<!-- Main Body Copy End -->
<!-- Coupon Start -->
<tr>
  <td class="innerpadding borderbottom" style="padding: 30px 30px 30px 30px;">    
    <table width="100%" align="center" boder="0" cellpadding="0" cellspacing="0">
      <tr>
        <td style="padding: 15px 15px 15px 15px;border-top:1px solid #f2eeed; border-left:1px solid #f2eeed; border-right:3px solid #f2eeed; border-bottom:3px solid #f2eeed;">
          <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="center" style="padding: 0px 15px 15px 0px; text-align:center;">
                <a target="_blank" href="http://www.saveon.com/coupons/{{strtolower($location['state'])}}/{{SoeHelper::getSlug($location['city'])}}/{{$entity['category_slug']}}/{{$entity['subcategory_slug']}}/{{$entity['merchant_slug']}}?showeid={{$entity['id']}}"><img src="{{$entity['path']}}" width="100%" height="auto" border="0" alt="" style="height: auto" /></a>
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
                            <a style="color:#333; text-decoration:none;font-family: sans-serif;padding: 0 0 15px 0;margin-bottom: 0;font-size: 24px;line-height: 28px;font-weight: bold" target="_blank" href="http://www.saveon.com/coupons/{{strtolower($location['state'])}}/{{SoeHelper::getSlug($location['city'])}}/{{$entity['category_slug']}}/{{$entity['subcategory_slug']}}/{{$entity['merchant_slug']}}?showeid={{$entity['id']}}">{{$entity['name']}}</a><br>
                            <a style="color:#1F9F5F; text-decoration:none; font-family: sans-serif;font-size: 14px;line-height: 20px;" target="_blank" href="http://www.saveon.com/coupons/{{strtolower($location['state'])}}/{{SoeHelper::getSlug($location['city'])}}/{{$entity['category_slug']}}/{{$entity['subcategory_slug']}}/{{$entity['merchant_slug']}}?showeid={{$entity['id']}}">{{$merchant['name']}}</a>
                          </td>
                        </tr>
                        <tr>
                          <td style="padding: 20px 0 0 0;">
                            <table class="buttonwrapper" bgcolor="#1F9F5F" border="0" cellspacing="0" cellpadding="0" style="background: #1F9F5F">
                              <tr>
                                <td class="button" height="45" style="text-align: center;font-size: 18px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px; vertical-align: middle">
                                  <a href="http://www.saveon.com/coupons/{{strtolower($location['state'])}}/{{SoeHelper::getSlug($location['city'])}}/{{$entity['category_slug']}}/{{$entity['subcategory_slug']}}/{{$entity['merchant_slug']}}?showeid={{$entity['id']}}" style="color: #fff;text-decoration: none">View {{$entity['entitiable_type']}} »</a>
                                </td>
                              </tr>
                            </table>
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
    </table>
  </td>
</tr>
<!-- Coupon End -->
@include('emails.templates.footer')