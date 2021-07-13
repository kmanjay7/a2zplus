<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_outlet extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
		
	public function index() {

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");
			
			$response = array(); 
			    $data = array(); 
			   $table = 'dt_users'; 
			
	   /****  check Method  start ****/ 
	   if( ($_SERVER['REQUEST_METHOD'] != 'POST') ){ 
			$response['status'] = FALSE;
			$response['message'] = "Bad Request!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}	 
		/****  check Method  start ****/ 


		$req = $_POST;

		$tableid = !empty($req['tableid']) ? trim($req['tableid']) : FALSE;
		$uniqueid = !empty($req['uniqueid']) ? trim($req['uniqueid']) : FALSE;
		$usertype = 'AGENT';
		$outletname = !empty($req['outletname']) ? trim($req['outletname']) : FALSE;
		$outletaddress = !empty($req['outletaddress']) ? trim($req['outletaddress']) : FALSE;
		$outfilename = !empty($_FILES['outfilename']['name']) ? $_FILES['outfilename']['name'] : FALSE;
		$geotag = !empty($req['geotag']) ? trim($req['geotag']) : FALSE;

		if(!$tableid){
			$response['status'] = FALSE;
			$response['message'] = "User ID is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$uniqueid){
			$response['status'] = FALSE;
			$response['message'] = "Unique ID is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$usertype){
			$response['status'] = FALSE;
			$response['message'] = "User Type is Blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$outletname ){
			$response['status'] = FALSE;
			$response['message'] = "Enter 10 digit valid pan number!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$outfilename){
			$response['status'] = FALSE;
			$response['message'] = "Choose outlet image file!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}else if(!$geotag){
			$response['status'] = FALSE;
			$response['message'] = "Geo Tags are blank!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


		/*CHECK EXISTING USER RECORD START*/
		$where['id'] = $tableid;
		$where['uniqueid'] = $uniqueid;
		$where['user_type'] = $usertype; 
		$check = $this->c_model->countitem('dt_users',$where);
		/*CHECK EXISTING USER RECORD END*/
		if($check != 1){
			$response['status'] = FALSE;
			$response['message'] = "User not exists!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


		$filepath = 'uploads/';
		$foldername = 'uploads';
		$filename = 'outfilename';
		$target_file = $filepath.$outfilename;
		$ext = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$rawfile = strtolower(pathinfo($target_file,PATHINFO_FILENAME)); 
		$newfile = $tableid.'_'.$uniqueid.'_'.$usertype.'_OUTLET.'.$ext;


		if(!in_array($ext,['png','jpg','jpeg'])){
			$response['status'] = FALSE;
			$response['message'] = "Only PNG,JPEG,JPG Files Allowed!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}


    /*delete old record start script*/
	$doc_where['tableid'] = $tableid;
	$doc_where['usertype'] = $usertype;
	$doc_where['documenttype'] = 'Shop Photo'; 
	$doc_olddata = $this->c_model->getSingle('dt_uploads',$doc_where,'documentorimage,id'); 
	if(!empty($doc_olddata)){ 
		$deletimagepath = ("uploads/".$doc_olddata['documentorimage'] );
	    if(is_file($deletimagepath) && file_exists($deletimagepath)){ 
	        $unlink = unlink( $deletimagepath );
	    } 
    $delete = $this->c_model->delete('dt_uploads',$doc_where ) ;
    }
	/*delete old record end script*/


	$uploadres = $this->upload_panfile($outfilename,$filepath,$filename,$newfile);
	$verifyfilename = '';
		if(empty($uploadres['status'])){
			$response['status'] = FALSE;
			$response['message'] = $uploadres['message']; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		} 

/*save pan copy start script */
$outletimage = !empty($uploadres['newfilename']) ? $uploadres['newfilename'] :'';
if($outletimage){ 
	    $savepn['documentorimage'] = $outletimage;
	    $savepn['uploaddate'] = date('Y-m-d H:i:s');
	    $savepn['add_by'] = $tableid; 
		$savepn['tableid'] = $tableid;
		$savepn['usertype'] = $usertype;
		$savepn['documenttype'] = 'Shop Photo';
		$savepn['verifystatus'] = 'yes';
		$savepn['status'] = 'yes'; 
		$savepn['geotag'] = $geotag;  
	$update = $this->c_model->saveupdate('dt_uploads', $savepn, $savepn  );
}
/*save pan copy start script */


	
	$saverec['firmname'] = $outletname;
	$saverec['outletaddress'] = $outletaddress;
	// $saverec['kyc_status'] = 'blink_face';
	$saverec['kyc_status'] = 'yes';
	$update = $this->c_model->saveupdate($table, $saverec, null, $where );
	
	
	    $response['status'] = true;
	    // $response['data'] = ['kyc_status'=>'blink_face'];
	    $response['data'] = ['kyc_status'=>'yes'];
		$response['message'] = 'Request was Successfull'; 
		header("Content-Type:application/json");
		echo json_encode( $response );
		exit;
	}


public function upload_panfile($file,$folder,$filename,$newfile){
				$response = [];
				$response['status'] = false;
				$response['newfilename'] = '';
				$response['message'] = '';
				$new_image_name = '';

				if(!is_dir($folder)){ mkdir($folder,0777,true); }  

                $config['upload_path'] = './'.$folder.'/';  
                $config['allowed_types'] = 'jpg|png|jpeg';  
                $config['overwrite'] = TRUE;  
                $config['file_name'] = $newfile;  

                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload($filename)){  
                     $message = $this->upload->display_errors();   
                     $response['status'] = false;
                     $response['message'] = strip_tags($message);  
                }else{  
                     $data = $this->upload->data();  
					 $response['status'] = true;
					 $response['newfilename'] = $data["file_name"]; 
					 $response['message'] = 'uploaded'; 
                }  
                
              return $response; 
         }	
 

private function pushlog($odr,$type,$io,$payload){
	$insert = [];
	$insert['odr'] = $odr;
	$insert['type'] = $type;
	$insert['io'] = $io;
	$insert['req_res'] = $payload;
	$insert['timeon'] = date('Y-m-d H:i:s'); 
	return $this->c_model->saveupdate('dt_pushlog',$insert );
}


}
?>