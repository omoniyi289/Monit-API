<?php

?>
<!DOCTYPE html>
<html>
<head>
    <title>E360 Commercial Online Pricing Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
</head>
<body>
<div class="col-sm-12">
     <div class="col-sm-12 text-center" style="background-color: ">
        <br>
         <b style="font-size: 12px">E360 COMMERCIAL ONLINE PRICING REPORT</b><br><br>
          </div>
     <div class="col-sm-12" >
            <table class="table  table-striped" style="font-size: 10px">
                <tr>
                    <th>DATE IN VIEW</th>
                    <th>COMPANY NAME</th>
                    <th>SURVEY SUBMITTED BY</th>
                     <th>SUBMITTED ON</th>
                </tr>

                <tr >
                    <th>{{$finale[0]->survey_date}}</th>                  
                    <th>{{$finale[0]->company['name']}}</th>
                    <th>{{$finale[0]->uploader['fullname']}}</th>
                    <th>{{$finale[0]->created_at}} </th>
                </tr>
            </table>
    </div>
    
    <div class="col-sm-12" >
                   <table class="table  table-striped" >
                      <tr   style="font-size: 10px;">
                          <th colspan="4" style="text-align: left; background-color: #FF8000" >B2B</th>
                          <th colspan="2" style="text-align: center; background-color: #3399FF" >PMS</th>
                          <th colspan="2" class="ago" style="text-align: center ;background-color: #99FF99" >AGO</th>
                          <th colspan="2" class="dpk" style="text-align: center; background-color: #FFFF00">DPK</th>
                          <!-- <th colspan="2"  style="text-align: center; background-color: #FF0000" >LUBES</th> -->
                          <th colspan="2"  style="text-align: center; background-color: #808080" >LPG(12.5)KG</th>
                      </tr>
                      <tr style="text-align: center">
                          <th colspan="4" ></th>
                          <th class="pms">OMP</th>
                          <th class="pms">COMPANY</th>
                          
                          <th class="ago">OMP</th>
                          <th class="ago">COMPANY</th>
                          
                          <th class="dpk">OMP</th>
                          <th class="dpk">COMPANY</th>
                          
                         <!--  <th >OMP</th>
                          <th >COMPANY</th> -->

                          <th >OMP</th>
                          <th >COMPANY</th>
                      </tr> 

                      <tr >
                          <th colspan="4"  style="font-size: 8px; background-color: #E0E0E0">LOCATION</th>
                          
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          
                          <th ></th>
                          <th ></th>
                          
                          <!-- <th ></th>
                          <th ></th> -->

                          <th ></th>
                          <th ></th>
                      </tr> 
                      @foreach($finale as $data)
                        @if($data->location != null )
                      <tr > 
                          <td colspan="4"  > {{$data->location}} </td>
                          <td > {{$data->omp_pms}} </td>
                          <td >{{$data->company_pms}} </td>

                          <td >{{$data->omp_ago}}  </td>
                          <td > {{$data->company_ago}} </td>
                          
                          <td >{{$data->omp_dpk}} </td>
                          <td >{{$data->company_dpk}} </td>
                          
                          <!-- <td >{{$data->omp_lube}} </td>
                          <td > {{$data->company_lube}} </td> -->

                          <td > {{$data->omp_lpg}} </td>
                          <td > {{$data->company_lpg}} </td>
                          
                         
                      </tr>    
                      @endif
                      @endforeach

                      <tr >
                          <th colspan="4" style="font-size: 8px; background-color: #FF9999" >COMPETITORS PRICE</th>
                          
                          <th ></th>
                          
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          
                          <!-- <th ></th>
                          <th ></th> -->

                          <th ></th>
                          <th ></th>
                      </tr> 

                      @foreach($finale as $data)
                        @if($data->competitor != null )
                      <tr > 
                          <td colspan="4"  > {{$data->competitor}} </td>
                          <td > {{$data->omp_pms}} </td>
                          <td >{{$data->company_pms}} </td>

                          <td >{{$data->omp_ago}}  </td>
                          <td > {{$data->company_ago}} </td>
                          
                          <td >{{$data->omp_dpk}} </td>
                          <td >{{$data->company_dpk}} </td>
                          
                          <!-- <td >{{$data->omp_lube}} </td>
                          <td > {{$data->company_lube}} </td>
 -->
                          <td > {{$data->omp_lpg}} </td>
                          <td > {{$data->company_lpg}} </td>
                          
                         
                      </tr>    
                      @endif
                      @endforeach  

                      <tr >
                          <th colspan="4" style="font-size: 8px; background-color: #FF8000" >D2D</th>
                        
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          <th ></th>
                          
                          <th ></th>
                          <th ></th>
                          
                          <!-- <th ></th>
                          <th ></th>
 -->
                          <th ></th>
                          <th ></th>
                      </tr> 

                      @foreach($finale as $data)
                        @if($data->d2d != null )
                      <tr > 
                          <td colspan="4"  > {{$data->d2d}} </td>
                          <td > {{$data->omp_pms}} </td>
                          <td >{{$data->company_pms}} </td>

                          <td >{{$data->omp_ago}}  </td>
                          <td > {{$data->company_ago}} </td>
                          
                          <td >{{$data->omp_dpk}} </td>
                          <td >{{$data->company_dpk}} </td>
                          
                          <!-- <td >{{$data->omp_lube}} </td>
                          <td > {{$data->company_lube}} </td>
 -->
                          <td > {{$data->omp_lpg}} </td>
                          <td > {{$data->company_lpg}} </td>
                          
                         
                      </tr>    
                      @endif
                      @endforeach               
                      

                  </table>
                </div>
    <br >

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

.table{
 font-size: 11px;
}
 table, th, td {
       border: 0.5px solid #EEEEEE;
      }
      .hide_right{
        border-right-style:hidden;
      }
      
     .hide_all{
        border-bottom-style :hidden;
        border-top-style :hidden;
        background-color: white;
      }
  }
body {
    font-family: 'Roboto';
}
</style>
</html>