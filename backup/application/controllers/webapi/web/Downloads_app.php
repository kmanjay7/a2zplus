<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Downloads_app extends CI_Controller{
    
    public function __construct(){
        parent::__construct();  
    }


    function index(){

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0"); 


        $request = requestJson();

        $select = 'id,parentid,user_type';
        $userid = isset($request['userid']) ? trim($request['userid']) : false; 

        if(!$userid){
            $response["status"] = false; 
            $response["message"] = "User not exists!"; 
            header("Content-Type: application/json");
            echo json_encode( $response );
            exit;
        }


        $user = $this->c_model->getSingle("dt_users", ["id"=>$userid], $select );
        
        $data = [];
        $parentid = $user["parentid"];
        
        $rows = $this->c_model->getAll("downloads",null,null,'id,title,url as downloadurl,image as iconimage' );

        $i = 0; $lastentry = '';
        foreach ($rows as $row) 
        {    
            if(strpos( strtolower($row["title"]), 'android') !== false ){
                $arr["id"] = $row["id"]; 
                $arr["title"] = $row["title"]; 
                $arr["downloadurl"] = $row["downloadurl"];
                $arr["iconimage"] = ADMINAPIURL."uploads/".$row["iconimage"]; 
                array_push($data, $arr );
            } 
            $i++; 
        }

        $arr["id"] = ''; 
        $arr["title"] = 'Certificate'; 
        $arr["downloadurl"] = ADMINURL."ag/certificate/print_c?uid=".md5($userid);
        $arr["iconimage"] = ADMINURL.'assets/images/certificate.png';
        array_push($data, $arr );
        
       

        $response["status"] = true;
        $response["message"] = "success";
        $response["data"] = $data;
            

        header("Content-Type: application/json");
        echo json_encode( $response );
        
            
 
    }

}
?>