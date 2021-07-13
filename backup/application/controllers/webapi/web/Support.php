<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Support extends CI_Controller{
    
    public function __construct(){
        parent::__construct();  
    }


    function index(){

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $request = requestJson();
        $select = 'id,parentid,parenttype,firmname,ownername,emailid,address,mobileno';
        $userid = isset($request['userid']) ? trim($request['userid']) : false; 

        if(!$userid){
            $response["status"] = false; 
            $response["message"] = "User not exists!"; 
            header("Content-Type: application/json");
            echo json_encode( $response );
            exit;
        }


        $loginuser = $this->c_model->getSingle("dt_users", ["id"=>$userid], $select );
        
        $users = [];
        $parentid = $loginuser["parentid"];
        
        $k = 0;
        while(1)
        {
            $user = $this->c_model->getSingle("dt_users", ["id"=>$parentid],$select );
            $users[] = $user;
            if($user["id"] == 1 ) break;
            $parentid = $user["parentid"];
        }

        $sortArray = array();

        foreach($users as $user){
            foreach($user as $key=>$value){
                if(!isset($sortArray[$key])){
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }

        $orderby = "parentid";

        array_multisort($sortArray[$orderby],SORT_ASC,$users); 

        $response["status"] = true;
        $response["data"] = $users;
        $response["message"] = "success";
        
            

        //header("Content-Type: application/json");
        echo json_encode( $response );
    }

}
?>