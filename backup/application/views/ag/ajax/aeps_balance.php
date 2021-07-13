<style type="text/css">
    .min{ width: 100%; clear: both; border-bottom: 1px dashed #ccc; }
    .divmin{ width:40%; float:left }
    .divmin70{ width:60%; float:left; text-align: right; }
    .widthd100{ width:100%; clear:both }
    .btnprnt{ background: #2e3192 ; padding: 9px 27px;border-radius: 3px;  color: #fff; border: 1px solid #2e3192 ; }
    .btnprnt:hover{  color: #ccc;  }
</style>
<?php 
if($data['data']){

     if(isset($data['data']) && !empty($data['data'])){
        $data_in = $data['data']; 

        $sk_type = $data_in['status'];
        $hdclr = ($data_in['statuscode']=='TXN')?'green':'red';
        $amount_txn = isset($data_in['data']['amount_txn'])?$data_in['data']['amount_txn']:false;
        if($mode == 'WAP'){ $text = 'CASH WITHDRAWAL'; }
        else if($mode == 'BAP'){ $text = 'BALANCE INQUIRY'; }

         $html = '
         <h5 class="modal-title modekdh">
        <center><img src="'.ADMINURL.'assets/images/AEPS-Logo.webp" style="width: 175px" /></center></h5>
        <div style="width:100%; padding:5px 15px; height: 237px;font-size:13px" >
            <h5 style="color:'.$hdclr.'; text-align:center"> '.$sk_type.' </h5>
            <br/>
            <div class="widthd100" >
            <div class="divmin">Status </div>
            <div class="divmin70"> '.(isset($data_in['data']['status'])?$data_in['data']['status']:'').'</div>
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
            <div class="divmin70">'.(isset($data_in['data']['opr_id'])?$data_in['data']['opr_id']:'null').'</div>
            </div>';

         
            $html .= '<div class="widthd100" >
            <div class="divmin">Txn Amt </div>
            <div class="divmin70"> ₹ '.(isset($data_in['data']['amount'])?twodecimal($data_in['data']['amount']):'0').'</div>
            </div>';
         

            $html .= '  <div class="widthd100" >
            <div class="divmin">Available A/C Balance </div>
            <div class="divmin70"> ₹ '.(isset($data_in['data']['balance'])?twodecimal($data_in['data']['balance']):'0').'</div>
            </div>';

             $html .= '  
<br/><br/>
            <div class="widthd100" >
            <div class="divmin"><a href="'.ADMINURL.'ag/print_reciept_aeps?utp='.md5($data['orderid']).'" class="btnprnt"> Print</a></div>
            <div class="divmin70"><a href="javascript:void(0)" onclick="resetV("");" data-dismiss="modal" aria-label="Close" class="btnprnt">Close</a></div>
            </div>
  <br/><br/>


        </div>';
        echo $html;
    		 
     }  

}else{ echo '<center>Some error Occured</center>'; }
?>