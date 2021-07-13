<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Contacts extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct(); 
        $this->load->model("general_model");  
        $this->load->model("complaint_model");
        $this->load->helper("rajan_function");
    }

    function index()
    {
        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        $request = requestJson();
        $loginuser_id = isset($request['loginuser_id']) ? trim($request['loginuser_id']) : false;
        $complaint_message = isset($request['complaint_message']) ? trim($request['complaint_message']) : false;
        $loginuser=$this->general_model->getSingle("users", ["id"=>$loginuser_id]);
        
        $users=[];
        $parentid=$loginuser["parentid"];
        
        $k=0;
        while(1)
        {
            $user=$this->general_model->getSingle("users", ["id"=>$parentid]);
            $users[]=$user;
            if($user["id"]==1) break;
            $parentid=$user["parentid"];
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

        $response["status"]=true;
        $response["message"]="success";
        $response["data"]=$users;
            

        header("Content-Type: application/json");
        echo json_encode( $response );
    }

}
?>