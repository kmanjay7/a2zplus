<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class History extends CI_Controller{
	
	public function __construct()
	{
		parent::__construct(); 
        $this->load->model("general_model");  
        $this->load->model("complaint_model");
	}

	function index()
	{
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
		$request = requestJson();
		$loginuser_id = isset($request['loginuser_id']) ? trim($request['loginuser_id']) : false;
		$dateFrom = isset($request['from']) ? trim($request['from']) : false;
		$dateTo = isset($request['to']) ? trim($request['to']) : false;

		$where=[];

        if($dateFrom) $where["DATE(created)>="]=date("Y-m-d", strtotime($dateFrom));
        if($dateTo) $where["DATE(created)<="]=date("Y-m-d", strtotime($dateTo));

        $where["userid"]=$loginuser_id;

        $userIds=$this->general_model->getAll("users", ["parentid"=>$loginuser_id], "id");

        $ids=[];
        foreach($userIds as $userid)
        {
            array_push($ids, $userid["id"]);
        }
        array_push($ids, $loginuser_id);
        
        $rows=$this->complaint_model->getComplaints($ids, $where);

        $i=0;
        foreach($rows as $row)
        {
            $files=$this->general_model->getAll("files", ["reference"=>"complaint_manual", "ref_id"=>$row["id"]]);

            $fileUrl=[];
            foreach ($files as $file) {
                $fileUrl[]=ADMINAPIURL."uploads/".$file["file"];
            }
            $rows[$i]["files"]=$fileUrl;
            $i++;
        }

        $data["rows"]=$rows;

        $response["status"]=true;
    	$response["message"]="success";
    	$response["count"]=count($rows);
    	$response["data"]=$rows;

   		header("Content-Type: application/json");
		echo json_encode( $response );
	}

}
?>