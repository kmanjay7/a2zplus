<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_details extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
	 
		
	public function index() {
			
			$response = array();
			    $data = array();  
			  
			   $table = 'dt_wallet';  
			$request = requestJson();
			
 /****  check Request  Status start ****/ 
 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){  

         $id = $request['id'];
         $type = $request['type'];

        if( $type == 'mwt' ){ 
          $buffer  = $this->getmainwalt($id);
          $response['status'] = true;
          $response['data'] = $buffer;
        }else if( $type == 'aepswt' ){ 
          $buffer  = $this->getaepswalt($id);
          $response['status'] = true;
          $response['data'] = $buffer;
        }
			
			   
		
/*token check end*/	
}else{ 
	$response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
// header("Content-Type:application/json");
		echo json_encode( $response );
	 }



public function getmainwalt($id){
       $where['referenceid'] = $id;
       $keys = 'subject,referenceid,beforeamount, amount,finalamount,credit_debit,odr,surch,comi,tds,flag ';
       $value = $this->c_model->getSingle('dt_wallet',$where,$keys,'id ASC', 1 );

       $referenceid = $value['referenceid'];
	   $subject = $value['subject'];

       /*$data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = 0;
       $data['amount'] = 0;
       $data['opening_bal'] = $value['beforeamount'];
       $data['closing_bal'] = 0;*/

       $data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = 0;
       $data['amount'] = $value['amount'];
       $data['opening_bal'] = $value['beforeamount'];
       $data['closing_bal'] = $value['finalamount'];
       $data['totalamount'] = $value['amount'];


        
          $dwhere = ''; $dkey = '';
          if($value['flag']){
            $data['surcharge'] = twodecimal($value['surch']);
            $data['tds'] = twodecimal($value['tds']);
            $data['comission'] = twodecimal($value['comi']);
            $data['amount'] = twodecimal($value['odr']); 
          }else if( in_array($subject, ['dmt_1','money_transfer']) ){  //money transfer 
          	 
          	$dkey = 'amount,sur_charge,ag_comi,ag_tds';
          	//print_r($value); exit;
          	$dtmarr = $this->c_model->getSingle('dt_dmtlog',array('orderid'=>$referenceid), $dkey );
			$data['surcharge'] = $dtmarr['sur_charge'];
			$data['tds'] = $dtmarr['ag_tds'];
			$data['comission'] = $dtmarr['ag_comi'];
			$data['amount'] = $dtmarr['amount']; 
			$data['totalamount'] = (($dtmarr['amount'] + $dtmarr['sur_charge']) - ($dtmarr['ag_comi'])); 
			//$data['closing_bal'] = ( $value['beforeamount'] - $data['totalamount'] );
          }
          else if( in_array($subject, ['fill_wt_onl']) ){  //paytm transfer  
            $dkey = 'amount,sur_charge'; 
            $dtmarr = $this->c_model->getSingle('dt_paytmlog',['orderid'=>$value['referenceid']],$dkey );
      $data['surcharge'] = $dtmarr['sur_charge'];  
      $data['amount'] = $dtmarr['amount']; 
      $data['totalamount'] = (($dtmarr['amount'] - $dtmarr['sur_charge'])); 
          }
           else if( in_array($subject, ['fill_wt_off','fill_wt_aeps']) ){  //refill offline  
			$data['amount'] = $value['amount']; 
			$data['totalamount'] = $value['amount']; 
			//$data['closing_bal'] = ( $value['beforeamount'] + $data['totalamount'] );
          }
          else if($subject == 'account_verify'){  //account_verify  
			$data['amount'] = $value['amount']; 
			$data['totalamount'] = $value['amount'];
          }
          else if( in_array($subject,['mob_rech','recharge_mobile','dth_rech','recharge_dth'])){//mobile recharge  
          	 
          	$dkey = 'amount,sur_charge,ag_comi,ag_tds';
          	$dtmarr = $this->c_model->getSingle('dt_rech_history',['reqid'=> $value['referenceid']], $dkey );
          	$data['tds'] = $dtmarr['ag_tds'];
			$data['comission'] = $dtmarr['ag_comi'];
			$data['amount'] = $dtmarr['amount'];  
          } 
          
       
       $plusminus = '';
       if($value['credit_debit']=='debit'){ $plusminus = '-'; }  
       $data['totalamount'] = $plusminus.$data['totalamount'];
        


       return $data;
}



public function getaepswalt($id){
       $where['referenceid'] = $id;
       $keys = 'subject,referenceid,beforeamount, amount,finalamount,transctionid,credit_debit,odr,surch,comi,tds,flag ';
       $value = $this->c_model->getSingle('dt_wallet_aeps',$where,$keys,'id ASC', 1 );

       $referenceid = $value['referenceid'];
	     $subject = $value['subject'];
       $transctionid = $value['transctionid'];
	   
       $data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = 0;
       $data['amount'] = $value['amount'];
       $data['opening_bal'] = $value['beforeamount'];
       $data['closing_bal'] = $value['finalamount'];
       $data['totalamount'] = $value['amount'];

        
          $dwhere = ''; $dkey = '';
          if($value['flag']){
            $data['surcharge'] = twodecimal($value['surch']);
            $data['tds'] = twodecimal($value['tds']);
            $data['comission'] = twodecimal($value['comi']);
            $data['amount'] = twodecimal($value['odr']);
          }else if( in_array($subject, ['aeps_m_t']) ){  //money transfer  
          	$dkey = 'amount,sur_charge,ag_comi,ag_tds'; 
          	$dtmarr = $this->c_model->getSingle('dt_aeps',array('sys_orderid'=>$referenceid), $dkey );
        		$data['surcharge'] = $dtmarr['sur_charge'];
            $data['tds'] = $dtmarr['ag_tds'];
            $data['comission'] = $dtmarr['ag_comi'];
            $data['amount'] = $dtmarr['amount']; 
          } 

          else if( in_array($subject, ['aeps_tr_bk']) ){  //dmt transfer  
          $dkey = 'amount,sur_charge,ag_comi,ag_tds'; 
          $dtmarr = $this->c_model->getSingle('dt_dmtlog_aeps',array('id'=>$transctionid), $dkey );
          $data['surcharge'] = $dtmarr['sur_charge']; 
          $data['amount'] = $dtmarr['amount'];  
          }
        

       $plusminus = '';
       if($value['credit_debit']=='debit'){ $plusminus = '-'; }  
       $data['totalamount'] = $plusminus.$data['totalamount'];


       return $data;
}

			
}
?>