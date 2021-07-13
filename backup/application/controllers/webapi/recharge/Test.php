<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Test extends CI_Controller{
    
 public function __construct(){
        parent::__construct();  
               $this->load->library('rechargeapi'); 
        }
        

    public function index(){ 


        $post = $this->input->post()?$this->input->post():$this->input->get(); 
        $Merchantrefno = $post['reqid'];
        if(!$Merchantrefno){
            echo 'no id'; exit;
        }

        $obj = new $this->rechargeapi;
        $buffer = $obj->goldpay_rech_status($Merchantrefno);
        //$buffer = $this->goldpay_rech_status( $Merchantrefno );

print_r($buffer); 

}

   

   public function goldpay_rech_status($orderid){  
  $url = "https://www.goldpay.live/Recharge/Status"; 
  $body = [];
  $body['userid'] = GOLDPAY_USERID;
  $body['tokenid'] = GOLDPAY_TOKEN;
  $body['ClientRchid'] = $orderid; 
  $jsonstring = json_encode($body); 
  $header = array('Content-Type: application/json');  
  $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonstring); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 5 );
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);  
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header ); 
    echo $jsondata = curl_exec($curl);
    curl_close($curl);
    $arrayres = json_decode($jsondata, TRUE);   
  $output = [];
  if( !is_null($arrayres) && !empty($arrayres) ){
    $output['status'] = $this->goldpay_statusd($arrayres['Status']);
    $output['remark'] = !empty($arrayres['Errormsg'])?$arrayres['Errormsg']:'';
    $output['balaftrech'] = $arrayres['Remain'];
    $output['reqid'] = $arrayres['Yourrchid'];
    $output['op_transaction_id'] = $arrayres['Transid'];
    $output['apirefid'] = $arrayres['RechargeID']; 
  }
    return $output;
} 

public function goldpay_statusd($status){
   $status = strtoupper( trim( $status ) );
   $out = 'FAILURE';
   if($status == 'FAILED'){
    $out = 'FAILURE';
   }else if($status == 'SUCCESS'){
    $out = 'SUCCESS';
   }else if($status == 'PENDING'){
    $out = 'PROCESSED';
   }else if($status == 'REFUND'){
    $out = 'FAILURE';
   }else if($status == 'FREQUENT'){
    $out = 'FAILURE';
   }
   return $out;
}



}
?>