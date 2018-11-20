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
         <b style="font-size: 18px">E360 COMMERCIAL ONLINE PRICING REPORT</b><br><br>
          </div>
    <br>
    <div class="col-sm-12" >
            <table class="table  table-striped" style="font-size: 15px">
                <tr>
                    <th>DATE IN VIEW</th>
                    <th>STATION NAME</th>
                    <th>SURVEY SUBMITTED BY</th>
                     <th>SUBMITTED ON</th>
                </tr>

                <tr >
                    <th>{{$finale['survey_date']}}</th>                  
                    <th>{{$finale->station['name']}}</th>
                    <th>{{$finale->uploader['fullname']}}</th>
                    <th>{{$finale['created_at']}} </th>
                </tr>
            </table>
    </div>
    
    <div class="col-sm-12 ">
   
        <div class ="bg-info"  align="center"></div>
        <div class="alert-info" style="font-size: 17px; ">Primary competition within 600m
radius, in order of proximity </div>
            <table class="table  table-striped" >       
                   <tr>
                   
                        <td></td>
                        <th>Competitor's Name</th>
                        <th>PMS</th>
                        <th>AGO</th>
                        <th>HHK</th>
                    </tr>
                    <tr>   
                         <th> 1</th>
                        <td>{{$finale['pc1_name']}}</td>
                        <td>{{$finale['pc1_price_pms']}}</td>
                        <td>{{$finale['pc1_price_ago']}}</td> 
                        <td>{{$finale['pc1_price_dpk']}}</td>        
                    </tr>
                    <tr>   
                        <th> 2</th>
                        <td>{{$finale['pc2_name']}}</td>
                        <td>{{$finale['pc2_price_pms']}}</td>
                        <td>{{$finale['pc2_price_ago']}}</td> 
                        <td>{{$finale['pc2_price_dpk']}}</td>           
                    </tr>
                    <tr>   
                        <th> 3</th>
                        <td>{{$finale['pc3_name']}}</td>
                        <td>{{$finale['pc3_price_pms']}}</td>
                        <td>{{$finale['pc3_price_ago']}}</td> 
                        <td>{{$finale['pc3_price_dpk']}}</td>         
                    </tr>            
            </table>        

            <div class="alert-info" style="font-size: 17px; ">Secondary competition within 600m - 2.5km radius, in order of proximity</div>
            <table class="table  table-striped" >       
                   <tr>
                   
                        <td></td>
                        <th>Competitor's Name</th>
                        <th>PMS</th>
                        <th>AGO</th>
                        <th>HHK</th>
                    </tr>
                    <tr>   
                        <th> 1</th>
                        <td>{{$finale['sc1_name']}}</td>
                        <td>{{$finale['sc1_price_pms']}}</td>
                        <td>{{$finale['sc1_price_ago']}}</td> 
                        <td>{{$finale['sc1_price_dpk']}}</td>          
                    </tr>
                    <tr>   
                        <th> 2</th>
                        <td>{{$finale['sc2_name']}}</td>
                        <td>{{$finale['sc2_price_pms']}}</td>
                        <td>{{$finale['sc2_price_ago']}}</td> 
                        <td>{{$finale['sc2_price_dpk']}}</td>        
                    </tr>
                    <tr>   
                        <th> 3</th>
                        <td>{{$finale['sc3_name']}}</td>
                        <td>{{$finale['sc3_price_pms']}}</td>
                        <td>{{$finale['sc3_price_ago']}}</td> 
                        <td>{{$finale['sc3_price_dpk']}}</td>          
                    </tr>            
            </table>            
             <div class="alert-info" style="font-size: 17px; ">Nearest Depot</div>
            <table class="table  table-striped" >       
                   <tr>
                        <th>Depot Name</th>
                        <th>PMS</th>
                        <th>AGO</th>
                        <th>HHK</th>
                    </tr>
                    <tr>   
                        <td>{{$finale['nearest_depot_name']}}</td>
                        <td>{{$finale['nearest_depot_pms']}}</td>
                        <td>{{$finale['nearest_depot_ago']}}</td> 
                        <td>{{$finale['nearest_depot_dpk']}}</td>                        
                    </tr>                         
            </table> 
             <div class="alert-info" style="font-size: 17px; ">Current Selling Price</div>
            
            <table class="table  table-striped" >       
                   <tr>
                        <th>PMS</th>
                        <th>AGO</th>
                        <th>HHK</th>
                    </tr>
                    <tr>   
                         <td>{{$finale['current_selling_price_pms']}}</td>
                         <td>{{$finale['current_selling_price_ago']}}</td> 
                         <td>{{$finale['current_selling_price_dpk']}}</td>                         
                    </tr>                         
            </table> 
            <div class="alert-info" style="font-size: 17px; ">Recommended Selling Price</div>
            <table class="table  table-striped" >       
                   <tr>
                        <th>PMS</th>
                        <th>AGO</th>
                        <th>HHK</th>
                    </tr>
                    <tr>   
                         <td>{{$finale['recommended_selling_price_pms']}}</td>
                         <td>{{$finale['recommended_selling_price_ago']}}</td> 
                         <td>{{$finale['recommended_selling_price_dpk']}}</td>                     
                    </tr>                         
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