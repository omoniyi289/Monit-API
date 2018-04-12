<!DOCTYPE html>
<html>
<head>
    <title>E360 STOCK WAYBILL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
</head>
<body>

        <div class="panel-body">
            <h1 style="margin-left: 200px">STOCK WAYBILL</h1> <hr>
            <div class="row">
                <div style="loat: right; margin-left: 500px">
                    <h3>Station</h3>
                    <label class="offset-margin">{{$data['station']['name']}}</label>
                    <label class="offset-margin">{{$data['station']['address']}}</label>
                    <label class="offset-margin">{{$data['station']['phone']}}</label>
                </div>

            <hr>
            <div style="font-size: 15px">
              <b>Date Ordered</b> :{{$data['fuelsupply']['created_at']}} 
              <span><b  style="margin-left: 200px">Date of Departure</b> : {{$data['truck_departure_time']}}</span>
            </div> <hr>
            <h3 style="margin-left: 150px">DETAILS</h3> 
            <hr>
                <div class="col-lg-9 col-md-9 col-sm-9" style="margin-left: 50px">
                    <p class="offset-margin"><b>Request Code</b> : {{$data['request_code']}}</p>
                    <p class="offset-margin"><b>Product Loaded</b> :  {{$data['product']['name']}}</p>
                    
                    <p class="offset-margin"><b>Quantity Requested</b> : {{$data['quantity_requested']}}  Litres</p>
                    <p class="offset-margin"><b>Quantity Loaded</b> : {{$data['quantity_loaded']}} Litres</p>
                    <p class="offset-margin"><b>Departure Time</b> : {{$data['truck_departure_time']}}  </p>
                   
                    <p class="offset-margin"><b>Waybill Number</b> : {{$data['waybill_number']}} </p>
                    <p class="offset-margin"><b>Vehicle Reg Number </b>: {{$data['truck_reg_number']}}</p>
                    @foreach($data['stock_seal_numbers'] as $seals)
                    <p class="offset-margin">
                      <b>Latest Seal Number/Quantity for C- {{$loop->index + 1}} </b> : {{$seals['latest_seal_number']}} with {{$seals['latest_seal_quantity']}} Litres
                    </p>
                
                    @endforeach
                </div>
                
            </div><hr>
          <br>
             <div style="margin-left: 10px">
              <table>
                <tr><th></th>
                    <th ><b style="margin :10px 10px 10px 10px">Depot Rep.<b></th>
                    <th><b style="margin :10px 10px 10px 230">Tank Driver<b></th>
                </tr>
                
                  <tr>
                    <td><b>Name : <b></td>>
                    <td></td>
                <td><b style="margin :10px 10px 10px 230"> {{$data['driver_name']}}</td>
                  </tr>
                    <tr>
                      <td><b>Signature : <b></td>
                    <td>_________________</td>
                <td><b style="margin :10px 10px 10px 230"> ___________________</td>
                  </tr>
              
              </table>
                   
                   
              </div>
                        
            
        </div>
        

</body>
<style type="text/css">

.pms {
    color: #1d8a99
;
}.ago {
    color: #c5b451
;}.dpk {
    color: #360568
;
}.bold{
     font-weight: bold;
}
.page-break {
    page-break-before: always;
}

body {
    font-family: 'Roboto';
}

</style>
</html>
