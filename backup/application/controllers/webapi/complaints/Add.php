<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Add extends CI_Controller{
	
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
        $status=true;

		// if(count($_FILES["files"]["name"])>5)
        // {
        //    $status=false;
        // }


        if($status)
        {
            $user=$this->general_model->getSingle("users", ["id"=>$loginuser_id]);
            $save["name"]=$user["ownername"];
            $save["email_id"]=$user["emailid"];
            $save["complaint_message"]=$request["complaint_message"];
            $save["user_id"]=$user["uniqueid"];
            $save["userid"]=$user["id"];
            $save["created"]=date("Y-m-d H:i:s");

            $id=$this->general_model->save("complaint_manual", $save);


            // if($_FILES["files"]["name"][0]!="")
            // {
            //     $files=upload_files(false, false, $_FILES["files"], "Complaint_manual_");

            //     foreach ($files as $file) 
            //     {
            //         $fileData["reference"]="complaint_manual";
            //         $fileData["ref_id"]=$id;
            //         $fileData["file"]=$file;
            //         $fileData["created"]=date("Y-m-d H:i:s");
            //         $this->general_model->save("files", $fileData);
            //     }
            // }

            $response["status"]=true;
            $response["message"]="success";
            $response["complaint_id"]=$id;
        }
        
        if(!$status)
        {
            $response["status"]=false;
            $response["message"]="error";
        }

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}

}
?>