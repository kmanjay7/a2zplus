<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_details_parent extends CI_Controller{
	
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

       $where['id'] = $id;
       $keys = 'subject,referenceid,beforeamount, amount,finalamount,credit_debit,usertype,userid,odr,surch,comi,tds,flag ';
       $value = $this->c_model->getSingle('dt_wallet',$where,$keys );

        $referenceid = $value['referenceid'];
        $usertype = $value['usertype'];
       
       $plusminus = '';
       if($value['credit_debit']=='debit'){ $plusminus = '-'; }

       $data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = $value['amount'];
       $data['amount'] = 0; 
       $data['opening_bal'] = $value['beforeamount'];
       $data['closing_bal'] = $value['finalamount'];
       $data['totalamount'] = $value['amount'];


       if($value['flag']){
            $data['surcharge'] = $value['surch'];
            $data['tds'] = twodecimal($value['tds']);
            $data['comission'] = twodecimal($value['comi']);
            $data['amount'] = $value['odr'];
            if(in_array($value['subject'], ['aeps_m_t_c','dmt_1_comi','mob_rech_comi','dth_rech_comi','pan_c'])){
            $data['amount'] = 0;
            $data['surcharge'] = 0;;
            }
             
       }
       else if(in_array($value['subject'], ['aeps_m_t_c'])){
       $details = $this->getaepswalt($referenceid,$usertype);
       
       $data['odr_amount'] = 0;
       $data['crdr_amount'] = (string) $plusminus.$value['amount'];
       $data['surcharge'] = (string) 0;
       $data['tds'] = (string) $details['tds'];
       $data['comission'] = (string)$details['comission'];
        
       }
       else if(in_array($value['subject'], ['dmt_1_comi','mob_rech_comi','dth_rech_comi','pan_c'])){
       $details = $this->getmainwalt($referenceid,$usertype,$value['subject']);
 
       $data['odr_amount'] = (string)0;
       $data['crdr_amount'] = (string) $plusminus.$value['amount'];
       $data['surcharge'] = (string) 0;
       $data['tds'] = (string) $details['tds'];
       $data['comission'] = (string)$details['comission'];
       $data['closing_bal'] = (string) twodecimal($value['finalamount']);
       }
       else if(in_array($value['subject'], ['borrow_debit','borrow_credit','fill_wt_off','plan_sub','plan_sub_com'])){
       $data['odr_amount'] = (string) $value['amount'];
       $data['amount'] = (string) $value['amount']; 
       $data['comission'] = 0;
       $data['totalamount'] = $plusminus.$value['amount'];
       }
       else if( in_array($value['subject'], ['fill_wt_onl']) ){  //paytm transfer  
            $dkey = 'amount,sur_charge'; 
            $dtmarr = $this->c_model->getSingle('dt_paytmlog',['orderid'=>$value['referenceid']],$dkey );
      $data['surcharge'] = $dtmarr['sur_charge'];  
      $data['amount'] = $dtmarr['amount'];  
      $data['comission'] = 0;
          } 


       $response['status'] = true;
       $response['data'] = $data;

			   
		
/*token check end*/	
}else{ 
	  $response['status'] = FALSE;
    $response['message'] = "Bad Request!";
}
    //header("Content-Type:application/json");
		echo json_encode( $response );
	 }


public function getmainwalt($odrid,$usertype,$subject){
       $comi = '';
     $tds = '';
     if($usertype =='BP'){
    $comi = 'bp_comi';   $tds = 'bp_tds';
     }else if($usertype =='AD'){
    $comi = 'ad_comi';   $tds = 'ad_tds';
     }else if($usertype =='MD'){
    $comi = 'md_comi';   $tds = 'md_tds';
     }
     
       $data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = 0;
       $data['amount'] = 0;
      

      if($subject == 'dmt_1_comi'){
            $dkey = 'sur_charge,'.$tds.','.$comi; 
            $dtmarr = $this->c_model->getSingle('dt_dmtlog',array('orderid'=>$odrid), $dkey );  
             
            $data['tds'] = (float)$dtmarr[$tds];
            $data['comission'] = (float)$dtmarr[$comi];
            $data['amount'] = ($data['tds'] + $data['comission']);  
      }

      else if(in_array($subject, ['mob_rech_comi','dth_rech_comi'])){
            $dkey = 'sur_charge,'.$tds.','.$comi; 
            $dtmarr = $this->c_model->getSingle('dt_rech_history',array('reqid'=>$odrid), $dkey ); 
            $data['tds'] = (float)$dtmarr[$tds];
            $data['comission'] = (float)$dtmarr[$comi];
            $data['amount'] = ($data['tds'] + $data['comission']); 
      }
      else if(in_array($subject, ['pan_c'])){
            $dkey = $tds.','.$comi; 
            $dtmarr = $this->c_model->getSingle('dt_pancard',array('orderid'=>$odrid), $dkey ); 
            $data['tds'] = (float)$dtmarr[$tds];
            $data['comission'] = (float)$dtmarr[$comi];
            $data['amount'] = ($data['tds'] + $data['comission']); 
      }
             

       return $data; 

}



public function getaepswalt($odrid,$usertype){ 
     $comi = '';
     $tds = '';
     if($usertype =='BP'){
    $comi = 'bp_comi';   $tds = 'bp_tds';
     }else if($usertype =='AD'){
    $comi = 'ad_comi';   $tds = 'ad_tds';
     }else if($usertype =='MD'){
    $comi = 'md_comi';   $tds = 'md_tds';
     }
     
       $data['surcharge'] = 0;
       $data['tds'] = 0;
       $data['comission'] = 0;
       $data['amount'] = 0;
      
            $dkey = 'sur_charge,'.$tds.','.$comi; 
            $dtmarr = $this->c_model->getSingle('dt_aeps',array('sys_orderid'=>$odrid), $dkey );  
            $data['tds'] = (float)$dtmarr[$tds];
            $data['comission'] = (float)$dtmarr[$comi];
            $data['amount'] = ($data['tds'] + $data['comission']);  

       return $data;
}


}
?>