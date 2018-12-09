<!DOCTYPE html>
<html>
<head>
    <title>E360 DELIVERY NOTE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    
</head>
<body>

        <div class="panel-body">
            <h1 style="margin-left: 200px">DELIVERY NOTE</h1> <hr>
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
              <span><b  style="margin-left: 200px">Date Received</b> : {{$data['created_at']}}</span>
            </div> <hr>
            <h3 style="margin-left: 150px">DETAILS</h3> 
            <hr>
                <div class="col-lg-9 col-md-9 col-sm-9" style="margin-left: 50px">
                    <p class="offset-margin"><b>Request Code</b> : {{$data['request_code']}}</p>
                    <p class="offset-margin"><b>Product Delivered</b> :  {{$data['product']['name']}}</p>
                    <p class="offset-margin"><b>Arrival Time</b> : {{$data['arrival_time']}}</p>
                    <p class="offset-margin"><b>Quantity Requested</b> : {{$data['quantity_requested']}}  Litres</p>
                    <p class="offset-margin"><b>Quantity Loaded from Depot</b> : {{$data['quantity_loaded']}} Litres</p>
                    <p class="offset-margin"><b>Quantity Supplied to Station</b> : {{$data['quantity_supplied']}} Litres</p>
                    <p class="offset-margin"><b>Tank Volume Before Discharge</b> : {{$data['quantity_before_discharge']}}  Litres</p>
                    <p class="offset-margin"><b>Tank Volume After Discharge</b> : {{$data['quantity_after_discharge']}} Litres</p>
                    <p class="offset-margin"><b>Waybill Number</b> : {{$data['waybill_number']}} </p>
                    <p class="offset-margin"><b>Vehicle Reg Number </b>: {{$data['truck_reg_number']}}</p>
                </div>
                
            </div><hr>
          <br>
             <div style="margin-left: 10px">
              <table>
                <tr><th></th>
                    <th ><b style="margin :10px 10px 10px 10px">Station Manager<b></th>
                    <th><b style="margin :10px 10px 10px 230px">Driver<b></th>
                </tr>
                
                  <tr>
                    <td><b>Name : <b></td>>
                    <td>{{$data['station']['manager_name']}}</td>
                <td> <b style="margin :10px 10px 10px 230px">{{$data['driver_name']}}</td>
                  </tr>
                    <tr>
                      <td><b>Signature : <b></td>
                    <td>_________________</td>
                <td> <b style="margin :10px 10px 10px 230px">___________________</td>
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
