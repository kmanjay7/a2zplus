<?php
defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Callback extends CI_Controller{
    var $ipaddress;
    public function __construct(){
        parent::__construct();
        $this->load->library('bbps_recharge');
        $this->load->model('Creditdebit_model','cr_model');  
        $this->ipaddress = '208.109.12.98';
        $this->load->library('curl');
    }
    

public function index(){

    $ip = $this->get_IP_address();
    $post = requestJson();
    
    $table = 'dt_rech_history';
    $response = [];

      if(isset($_GET['rechargeId']) && isset($_GET['status']) && isset($_GET['operatorId']))
      {
          $rechargeId=$_GET['rechargeId'];
          $requestId=$_GET['requestId'];
          $status=$_GET['status'];
          $operatorId=$_GET['operatorId'];

          if($requestId)
          {
            $log = $this->pushlog($requestId,'digipaycall','O',$_POST);
          }

          if($status && $requestId)
          {

              if($status=='Pending'){
                $upstatus = 'PROCESSED';
              }

              if($status=='Success'){
                $upstatus = 'SUCCESS';
              }

              if($status=='Failed'){
                $upstatus = 'FAILURE';
              }

              $savedt['status']=$upstatus;
              $savedt['remark']=$remark;
              $savedt['a2z_reach_status']=$request->statusId;
              $savedt['op_transaction_id']=$request->txnId;
              $savedt['status_update'] = date('Y-m-d H:i:s');
               
              $update = $this->c_model->saveupdate($table,$savedt,null,['reqid'=>$requestId ] );
              if($update)
              {
                  echo 'Success!';
              }       
          }else{
              echo 'Invalid Request';exit;
          }

      }
    




}



private function pushlog($odr,$type,$io,$payload){
    $payload = json_encode($payload, JSON_UNESCAPED_SLASHES);
    $insert = [];
    $insert['odr'] = $odr;
    $insert['type'] = $type;
    $insert['io'] = $io;
    $insert['req_res'] = $payload;
    $insert['timeon'] = date('Y-m-d H:i:s'); 
    return $this->c_model->saveupdate('dt_pushlog',$insert );
}

public function get_IP_address(){
    foreach (array('HTTP_CLIENT_IP',
                   'HTTP_X_FORWARDED_FOR',
                   'HTTP_X_FORWARDED',
                   'HTTP_X_CLUSTER_CLIENT_IP',
                   'HTTP_FORWARDED_FOR',
                   'HTTP_FORWARDED',
                   'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                $IPaddress = trim($IPaddress); // Just to be safe

                if (filter_var($IPaddress,
                               FILTER_VALIDATE_IP,
                               FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                    !== false) {

                    return $IPaddress;
                }
            }
        }
    }
}
    
}
?>