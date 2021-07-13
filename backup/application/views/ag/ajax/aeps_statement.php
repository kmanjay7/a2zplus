<style type="text/css">
    .min{ width: 100%; clear: both; border-bottom: 1px dashed #ccc; }
    .divmin{ width:40%; float:left }
    .divmin70{ width:60%; float:left; text-align: right; }
    .widthd100{ width:100%; clear:both }
    .btnprnt{ background: #2e3192 ; padding: 9px 27px;border-radius: 3px;  color: #fff; border: 1px solid #2e3192 ; }
    .btnprnt:hover{  color: #ccc;  }
</style>
<?php 
if(isset($data['data'])){

     if(isset($data['data']) && !empty($data['data'])){
        $data_in = $data['data']; 

        $sk_type = $data_in['status'];
        $hdclr = ($data_in['statuscode']=='TXN')?'green':'red';

        $mini_statement = isset($data_in['data']['mini_statement'])?$data_in['data']['mini_statement']:array();
         $countmin =  count($mini_statement);

         $height =  '337';

        $amount_txn = isset($data_in['data']['amount_txn'])?$data_in['data']['amount_txn']:false;
        $text = 'MINI STATEMENT'; 


        $html =  '
        <div style="width:100%; padding:5px 15px; height: '.$height.'px;" > 

            <div style="width:40%; float:left; font-size:12px;padding-right: 15px;" >
            <center><img src="'.ADMINURL.'assets/images/AEPS-Logo.webp" style="width: 175px" /></center></h5>
             <h5 style="color:'.$hdclr.'; text-align:center"> '.$sk_type.' </h5>
            <br/>

           <div class="widthd100" >
            <div class="divmin">Status </div>
            <div class="divmin70"> '.$data_in['data']['status'].'</div>
            </div> ';

          $html .= '<div class="widthd100" >
            <div class="divmin">Order Type </div>
            <div class="divmin70"> '.$text.'</div>
            </div> ';

          $html .= '<div class="widthd100" >
            <div class="divmin">Order ID </div>
            <div class="divmin70"> '.$data['orderid'].'</div>
            </div> ';


           $html .= '<div class="widthd100" >
            <div class="divmin">Date & Time: </div>
            <div class="divmin70">'.date('d-m-Y, h:i:s A',strtotime($data_in['timestamp'])).'</div>
            </div>';


           $html .= '<div class="widthd100" >
            <div class="divmin">Txn ID: </div>
            <div class="divmin70">'.$data_in['data']['opr_id'].'</div>
            </div>';

         
            $html .= '<div class="widthd100" >
            <div class="divmin">Txn Amt </div>
            <div class="divmin70"> ₹ '.twodecimal($data_in['data']['amount']).'</div>
            </div>';
         

            $html .= '  <div class="widthd100" >
            <div class="divmin">Available A/C Balance </div>
            <div class="divmin70"> ₹ '.twodecimal($data_in['data']['balance']).'</div>
            </div>';

             $html .= '  
<br/><br/>
            <div class="widthd100" >
            <div class="divmin"><a href="'.ADMINURL.'ag/print_reciept_aeps?utp='.md5($data['orderid']).'" class="btnprnt"> Print</a></div>
            <div class="divmin70"><a href="javascript:void(0)" onclick="resetV("");" data-dismiss="modal" aria-label="Close" class="btnprnt">Close</a></div>
            </div>
  <br/><br/>



            </div>



         <div style="width:59%; float:left; border-left:1px solid #ccc;text-indent:10px; font-size:12px; height: 330px;">
         <p align="center"><b>Statement of Account</b></p>
         <div class="widthd100" >';


          if(!empty($mini_statement)){

            //echo '<pre>';
            //print_r($mini_statement);

             $html .= ' 
                 <table width="100%" style="font-size:11px; text-align:left">
                 <thead>
                 <tr>
                 <th>Txn Date</th>
                 <th>Txn Type</th>
                 <th>Txn Amount</th>
                 <th>Narration</th>
                 <tr></thead> <tbody>';

               foreach ($mini_statement as $key=>$stvalue) { 

                 $html .= ' <tr>
                 <td align="center">'. $stvalue['date'] .'</td>
                 <td align="center">'.strtoupper($stvalue['txnType']).'</td>
                 <td align="center">'.$stvalue['amount'].'</td>
                 <td>'.$stvalue['narration'].'</td>
                 <tr>';  
               }
             $html .= '</tbody></table>';  
          }


 
        $html .= '</div> 
        <br/><br/>
         <div class="widthd100" ><center>***** <b> '.$data['bankname'].'</b>  *****</center></div>

        </div>';

        echo $html;
    		 
     }  

}else{ echo '<center>Some error Occured</center>'; }
?>