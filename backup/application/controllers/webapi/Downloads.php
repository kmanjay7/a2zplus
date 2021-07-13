<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Downloads extends CI_Controller{
	
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
        $user=$this->general_model->getSingle("users", ["id"=>$loginuser_id]);
        $rows = $this->general_model->getAll("downloads", "`usertype`='".$user["user_type"]."' or `usertype`='' or `usertype`='null'");

        $i=0;
        foreach ($rows as $row) 
        {
            $rows[$i]["image"]=ADMINAPIURL."uploads/".$row["image"];
            if($row["file"])
            {
                $rows[$i]["file"]=ADMINAPIURL."uploads/".$row["file"];
            }
            $i++;
        }

        $response["status"]=true;
        $response["message"]="success";
        $response["data"]=$rows;
            

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}

}
?>