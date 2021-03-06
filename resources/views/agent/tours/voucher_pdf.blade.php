
    <table width="100%" border="0" cellspacing="5" cellpadding="5" >
	<tr>
	  <td width="200" align="left"  valign="middle">
         <img src="{{ asset('assets/img/logo.png') }}" alt="" style="width: 160px;" />
        </td>
	  <td  valign="top">
            <div class="col-xs-12"><h4><B>BREAKBAG HOLIDAYS PRIVATE LIMITED</b></h4> </div>
            <div class="col-xs-12"><p><b>Address &nbsp;</b>:RDB Boulevard, Plot K-1, Sector V, Block EP & GP, Salt Lake City, 8th Floor, Kolkata, West Bengal 700091</p></div>
            <div class="col-xs-6"><p><b>Tel &nbsp;</b>:91 7699002674</p> </div>
            <div class="col-xs-6"><p><b>Fax &nbsp;</b>:0343 2606733</p> </div>
            <div class="col-xs-12"><p><b>Email &nbsp;</b>:tradesupport@breakbag.com</p></div>
      </td>
      </tr>
      </table>
                <div class="black">
                  <div style="background-color:#87CEFA;text-align:center;padding:2px;color:#FFF;font-size:24px; font-weight:bold;">ACCOMMODATION VOUCHER </div>
                  <div class="clearfix">&nbsp;</div>
                  <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">HOTEL INFORMATION </div>
                    <div class="col-xs-12">
                    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
                    <tr>
                        <td width="200"><strong>Hotel Name </strong></td>
                        <td>{{ $booking_data->BookingRequest->Booking->Name }}, {{ $hotel->country_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Address </strong></td>
                        <td> {{ $hotel->hotel_address }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tel</strong></td>
                        <td></td>
                    </tr>
                    </table>
                    </div>


                  <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">BOOKING DETAILS </div>

                 <div class="col-xs-12">
     <table width="100%"  cellspacing="2" cellpadding="5">
    <tr>
    <td width="200"><strong>Check in Date </strong></td>
      <td>
      @php
		$arrival_date = $booking_data->BookingRequest->Booking->ArrivalDate;
		$ex = explode('/',$arrival_date);
		 $day = $ex[0];
		$year = $ex[2];
	 $month = date("M", strtotime($ex[2]));
	echo $day." ".$month." ".$year;
	@endphp
       </td>
      <td><strong>Voucher # </strong></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Check out Date </strong> </td>
      <td>
      @php

        $departure_date = $booking_data->BookingRequest->Booking->DepartureDate;
		$ex1 = explode('/',$departure_date);
		//print_r($ex);
		 $day = $ex1[0];
		$year = $ex1[2];
	 $month = date("M", strtotime($ex[2]));
	echo $day." ".$month." ".$year;

	@endphp
       </td>
      <td><strong>PNR # </strong></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>No. of Night(s) </strong></td>
      <td>{{ (int)($booking_data->BookingRequest->Booking->DepartureDate)-
        (int)($booking_data->BookingRequest->Booking->ArrivalDate) }}</td>
      <td><strong>Agent Ref # </strong></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Meal Plan </strong></td>
      <td></td>
      <td><strong>Confirmation Number # </strong></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Room(s) </strong></td>
      <td> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->TotalRooms}} </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Adult(s) </strong> </td>
      <td>{{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Adults}} </td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Number of Child(ren) </strong></td>
      <td> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Children}} </td>
      <td></td>
      <td></td>
    </tr>
  </table>
</div>



                       <div style="background-color:#929292;padding:5px;color:#FFF; font-size:20px; font-weight:bold;">GUEST DETAILS </div>
                       <div class="col-xs-12">
    <table width="100%"  cellspacing="2" cellpadding="5" class="tbl_1">
    <tr>
      <td width="200"><strong>Guest Nationality </strong></td>
      <td> @php
        $cID = $booking_data->BookingRequest->Booking->GuestNationality;
        $gcountry = DB::table('countries')->where('code', $cID)->first();
        echo $gcountry->name;
        @endphp </td>
      <td></td>
    </tr>
    <tr>
      <td><strong>Room # </strong> </td>
      <td> {{$booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Type}} </td>
      <td> @php
        $dd = $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest;
        //print_r(($dd));
        if(is_array($dd) && !empty($dd)){
        foreach ($dd as $_gdata) {
        $guest =  $_gdata->LastName." / ".$_gdata->FirstName." ".$_gdata->Salutation."."." &nbsp;&nbsp;";
        echo $guest;

        }
        }else{
        echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->LastName;
        echo " / ";
        echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->FirstName;
        echo " ";
        echo $booking_data->BookingRequest->Booking->RoomDetails->RoomDetail->Guests->Guest->Salutation;
        echo ". ";
        }

        @endphp </td>
    </tr>
  </table>
</div>

                    <div class="col-xs-12"><h4><i><strong>IMPORTANT NOTE TO HOTEL :</strong></i></h4> </div>
                <div class="col-xs-12"><p>This is a prepaid booking. Please do not collect any payment from the guest. Please contact our customer service center at +62 2949 5888 if you
have any question or doubt.</p> </div>

            <div class="col-xs-12"><h4><strong>Remark</strong></h4> </div>
             <div class="col-xs-12"><p>Bed type is not guarantee. It is subject to availability upon request.</p>
                <p>Extra bed is not allowed in standard room.</p> </div>

            <div class="col-xs-12"><h4><strong>Special Request</strong></h4> </div>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>

               <div class="col-xs-12" align="center">
               <div align="left"><h4><strong>Emergency Numbers</strong></h4> </div>
               <table border="1" cellpadding="5" cellspacing="3" style="border-collapse:collapse">
                     <tr>
                        <td><p><strong>Thailand </strong> <span class="padleft20">+66865706260</span> </p></td>
                        <td><p><strong> Hong Kong</strong><span class="padleft20"> +852 98043709</span></p></td>
                        <td><p><strong>  Macau </strong> <span class="padleft20">+853 66995729/+853 87973069</span></p></td>
                    </tr>
                </table>
             </div>
             <div class="clearfix">&nbsp;</div>
              <div class="col-xs-12 text-center"><p>INDIA - We are available 24 X 7 for on tour support at +91 9582805803</p></div>
              <div class="clearfix">&nbsp;</div>

              </div>
            @php
            $ibookings = DB::table('bookings')->where('id', $id)->first();
            $created_at =  $ibookings->created_at;
           // $createdAt= $created_at->format('d-M-Y');
            @endphp
              <div class="col-xs-4"> <p>Issued Date : {{date('d-M-Y g:i A', strtotime($created_at))}}</p></div>
              <div class="col-xs-4"><p>Issued By: </p></div>

