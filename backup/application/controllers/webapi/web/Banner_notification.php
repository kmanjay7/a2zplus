<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Banner_notification extends CI_Controller{
    
    public function __construct(){
        parent::__construct();  
    }


    function index(){

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0"); 

        $response = [];
        $data = [];

        $request = requestJson();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){
      
        $userid = isset($request['userid']) ? trim($request['userid']) : false;
        $user_type = isset($request['usertype']) ? trim($request['usertype']) : false; 

        if(!$userid){
            $response["status"] = false; 
            $response["message"] = "User not exists!"; 
            header("Content-Type: application/json");
            echo json_encode( $response );
            exit;
        }


        
        $n_Key = 'id,content';
        $where = "(`usertype` like '".$user_type."' OR usertype='') AND ";
        $where.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
        $notifications = $this->c_model->getAll("notification","id desc",$where,$n_Key );
        $notifi_arr = [];
        if(!empty($notifications)){
            foreach ($notifications as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['content'] = strip_tags($value['content']);
                array_push($notifi_arr, $arr );
            } 
        }
         


        $where_b = "(`usertype` like '".$user_type."' OR usertype='') AND ";
        $where_b.="((CONCAT(`from_date`,' ', `from_time`) <='".date("Y-m-d H:i:s")."' AND CONCAT(`end_date`,' ', `end_time`)>='".date("Y-m-d H:i:s")."') OR (`from_date` is NULL AND `end_date` is NULL))";
        $banner  = $this->c_model->getAll("banner", "id desc", $where_b,'id,image' );
        $banner_arr = [];
        if(!empty($banner)){
            foreach ($banner as $bkey => $bvalue) {
                $arr_b['id'] = $bvalue['id'];
                $arr_b['image'] = ADMINAPIURL.'uploads/'.($bvalue['image']);
                array_push($banner_arr, $arr_b );
            } 
        }


        $response["status"] = true; 
        $response['notification'] = $notifi_arr;
        $response['banner'] = $banner_arr;
        $response["message"] = "Success";
        


 
}else{
    $response['status'] = false;
    $response['message'] = 'Bad Request!';
} 
            

        //header("Content-Type: application/json");
        echo json_encode( $response );
        
            
 
    }

}
?>