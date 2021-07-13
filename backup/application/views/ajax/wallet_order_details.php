<style type="text/css">
    .min{ width: 100%; clear: both; border-bottom: 1px dashed #ccc; }
    .divmin{ width:40%; float:left }
    .divmin70{ width:60%; float:left; text-align: right; }
    .widthd100{ width:100%; clear:both }
    .btnprnt{ background: #2e3192 ; padding: 9px 27px;border-radius: 3px;  color: #fff; border: 1px solid #2e3192 ; }
    .btnprnt:hover{  color: #ccc;  }
    .bdr_t{ border-top: 1px solid #111; }
    .bdr_bt{ border-bottom: 1px solid #111;  height: 46px; padding: 5px 0px;margin-top: 10; }
    .divmin33{ width:33%; float:left ; text-align: center; }
    .bdrll{ border-left: 1px solid #111; }
    .bold{ font-weight: 600; }
</style>
<?php 

     if(isset($list) && !empty($list)){ 

         $html = '
        <div style="width:100%; padding:5px 15px; height: 237px;font-size:13px" >
            <h5 style="color:; text-align:center"></h5>
            <br/>
            <div class="widthd100" >
            <div class="divmin">Order Amount </div>
            <div class="divmin70">₹ '.$list['amount'].'</div>
            </div> ';

          $html .= '<div class="widthd100" >
            <div class="divmin">Surcharge Amt </div>
            <div class="divmin70">₹ '.$list['surcharge'].'</div>
            </div> ';

          $html .= '<div class="widthd100" >
            <div class="divmin">Commission  </div>
            <div class="divmin70">₹ '.( $list['comission'] + $list['tds']) .'</div>
            </div> '; 

           $html .= '<div class="widthd100" >
            <div class="divmin">TDS @ 5% </div>
            <div class="divmin70">₹ '.$list['tds'].'</div>
            </div>';

         
            $html .= '<div class="widthd100" >
            <div class="divmin">Total Transaction Amount </div>
            <div class="divmin70 bold"> ₹ '.$list['totalamount'].'</div>
            </div>';
         

            $html .= '<div class="widthd100 bdr_t"s style="clear:both;margin-top: 26px;" >
            <div class="widthd100 bdr_bt">
            <div class="divmin33">Opening Bal <br/><span class="bold"> ₹  '.$list['opening_bal'].'</span></div>
            <div class="divmin33 bdrll">Txn Amt <br/><span class="bold"> ₹  '.$list['totalamount'].'</span></div>
            <div class="divmin33 bdrll">Closing Bal <br/><span class="bold"> ₹  '.$list['closing_bal'].'</span></div>
            </div>
            </div>'; 

             $html .= '  
<br/><br/>
            
  <br/><br/>


        </div>';
        echo $html;
    		 
     }  
 
?>