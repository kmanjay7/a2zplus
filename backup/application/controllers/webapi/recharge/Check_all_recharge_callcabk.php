<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Check_all_recharge_callcabk extends CI_Controller{
	
	public function __construct(){
		parent::__construct();  
		}
		

	public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		
        $request = requestJson();
        $response = array();
   


 if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
 
        $Merchantrefno = $request['reqid'];

        $where['reqid'] = $Merchantrefno;
        $keys = 'id, user_id, amount, apiid,status,serviceid,field2,final_status ';
        $tbl_data = $Merchantrefno ? $this->c_model->getSingle('dt_rech_history', $where, $keys ) : false;

if(!empty($tbl_data) && $Merchantrefno ){

                $post['reqid'] = $Merchantrefno;
                $post['user_id'] = $tbl_data['user_id'];
                $post['id'] = $tbl_data['id'];
                $post['amount'] = $tbl_data['amount'];
                $post['serviceid'] = $tbl_data['serviceid'];
                $post['action'] = '';
                $post['final_status'] = $tbl_data['final_status'];
                if( trim($tbl_data['status']) == 'PROCESSED'){
                    $post['action'] = 'check';
                } 


                if( $tbl_data['apiid'] == 1 ){

                $postapiurl = APIURL.'webapi/recharge/Manimulti_callback';
                }else if( $tbl_data['apiid'] == 2 ){

                $postapiurl = APIURL.'webapi/recharge/Emoneycallback';
                }else if( $tbl_data['apiid'] == 5 ){
                    $post['lapuid'] = $tbl_data['field2'];
                $postapiurl = APIURL.'webapi/recharge/Mrobotics_callback';
                }else if( $tbl_data['apiid'] == 13 ){

                $postapiurl = APIURL.'webapi/recharge/Goldpay_callback';
                }

                $buffer = curlApis($postapiurl,'POST', $post,null,100 ); 
                
                $response['status'] = true;
                $response['data'] = isset($buffer['data'])?$buffer['data']:'';
                $response['message'] = $buffer['message']; 
       
        
          
}else{
$response['status'] = FALSE;
$response['message'] = 'Invalid Inputs!'; 
}


}else{
$response['status'] = FALSE;
$response['message'] = 'Bad request!'; 
}

        header("Content-Type: application/json");
        echo json_encode( $response );

}

	

}
?>