<?php defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Update_kyc extends CI_Controller{ 
    public function __construct(){
	parent::__construct();  
	}
		
public function index(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = [];
		$data = [];
		$up_where = null;

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

				$request = $_POST;		 
				$userid = isset($request['userid'])?$request['userid']:null; 
				$user_type = isset($request['user_type'])?$request['user_type']:null;
				$doctype = isset($request['doctype'])?trim($request['doctype']):null;
				
				  
            
			if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}else if( !$doctype ){
				$response['status'] = FALSE;
				$response['message'] = 'Document type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			}

			 
			$where['id'] = $userid;
			$where['user_type'] = $user_type;

			$countitem = $this->c_model->countitem('dt_users',$where);

			if( $countitem != 1 ){
				$response['status'] = FALSE;
				$response['message'] = 'User not exists!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
			} 





			$target_folder = "uploads";
			$docname = '';
			$oldimage = false;
			$uploadstatus = false;
			$new_image_name = '';
			$docwhere['tableid'] = $userid;
			$docwhere['usertype'] = $user_type;

			if($doctype == 'AP'){ $docname = 'Applicant Photo';}
			else if($doctype == 'SP'){ $docname = 'Shop Photo';}
			else if($doctype == 'PC'){ $docname = 'Pan Card';}
			else if($doctype == 'EC'){ $docname = 'Educational Certificate';}
			else if($doctype == 'BPC'){ $docname = 'Bank PB/CL';}
			else if($doctype == 'AC'){ $docname = 'Aadhaar Card';}
			else if($doctype == 'FRC'){ $docname = 'Firm/Shop/Outlet Registration Certificate';}
			$docwhere['documenttype'] = $docname;
 
			$newkeys = 'id,documentorimage';
			$doc_arr = $this->c_model->getSingle('dt_uploads', $docwhere, $newkeys );
			if(!empty($doc_arr)){
				$oldimage = $doc_arr['documentorimage'];  
				$up_where['id'] =  $doc_arr['id'];
			} 

				$config['upload_path'] = './'.$target_folder.'/';  
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '31072';  
                $config['encrypt_name'] = TRUE; 
                $config['overwrite'] = TRUE;
                $this->load->library('upload', $config);  
                if(!$this->upload->do_upload('file'))  
                {  
                        $message = strip_tags( $this->upload->display_errors() );  
						$response['status'] = FALSE;
						$response['message']= $message; 
						header("Content-Type: application/json");
						echo json_encode( $response );
						exit;
                }  
                else  
                {  
                     $data = $this->upload->data(); 
                     $new_image_name = $data["file_name"];  
                     $config1['image_library'] = 'gd2';  
                     $config1['source_image'] = './'.$target_folder.'/'.$data["file_name"];  
                     $config1['create_thumb'] = FALSE;  
                     $config1['maintain_ratio'] = true;
                     $config1['remove_spaces'] = true;  
                     $config1['quality'] = '100%';
                     $config1['width'] = 800; 
                     $config1['height'] = 480; 
                     $config1['new_image'] = './'.$target_folder.'/'.$data["file_name"];  
					 $this->load->library('image_lib', $config1); 
                     $this->image_lib->initialize($config1); 
                     $this->image_lib->resize(); 
                     $this->image_lib->clear();
					 $uploadstatus = true; 
                } 
		 
		 /*image upload script end here */ 	    
			  
					if ( $uploadstatus && !empty($oldimage) )
					{
					    $delimg = $target_folder."/".$oldimage ;
					 	if(is_file($delimg) && file_exists($delimg)){ 
					 		$DEL = unlink( $delimg );
					 	} 
					}



/*save data*/
			$isave['tableid'] = $userid;
			$isave['usertype'] = $user_type;
			$isave['documentorimage'] = $new_image_name;
			$isave['documenttype'] = $docname;
			$isave['uploaddate'] = date('Y-m-d H:i:s');
			$isave['add_by'] = $userid;
			$isave['verifystatus'] = 'no';
			$isave['status'] = 'no'; 

			$saveupdate = $this->c_model->saveupdate('uploads', $isave,null, $up_where );  


			if($saveupdate){  
				$response['status']= TRUE; 
				$response['data'] = ['imageurl'=>ADMINURL.$target_folder.'/'.$new_image_name];
				$response['message'] = 'Image Uploaded Successfully';
			}else{
				$response['status']= FALSE;
				$response['message']= 'Some error Occured!';
			}

			
}else{ 
	$response['status'] = FALSE;
	$response['message']= 'Bad request!'; 
}

   		header("Content-Type: application/json");
		echo json_encode( $response );
}



public function updatestatus(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = [];
		$data = [];
		$up_where = null;

		 $request = requestJson();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

						 
				$userid = isset($request['userid'])?$request['userid']:false; 
				$user_type = isset($request['user_type'])?$request['user_type']:false; 

				if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}


				$isave = []; 
				$isave['kyc_updated_by'] = $userid;
				$isave['kyc_status'] = 'pending';

				$up_where['id'] = $userid;
				$up_where['user_type'] = $user_type;
				$saveupdate = $this->c_model->saveupdate('dt_users', $isave,null, $up_where );

				if($saveupdate){
				$response['status'] = TRUE;
				$response['message']= 'Kyc Updated Successfully!';
				}else{
				$response['status'] = FALSE;
				$response['message']= 'Some Error Occured!';
				}


}else{ 
$response['status'] = FALSE;
$response['message']= 'Bad request!'; 
}

header("Content-Type: application/json");
echo json_encode( $response );


}

public function getstatus(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = [];
		$data = [];
		$up_where = null;

		 $request = requestJson();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

						 
				$userid = isset($request['userid'])?$request['userid']:false; 
				$user_type = isset($request['user_type'])?$request['user_type']:false; 

				if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}
 

				$where['id'] = $userid;
				$where['user_type'] = $user_type;
				$getdata = $this->c_model->getSingle('dt_users', $where,'id,kyc_status,comment' );

				

				$data['is_upload'] = 'no';
				$data['text'] = '';
				$data['reason'] = '';
				$data['kycimage'] = '';

				if( !empty($getdata) ){
					if( ($getdata['kyc_status'] == 'no') || ($getdata['kyc_status'] == '') ){
				     $data['is_upload'] = 'no';
					 $data['text'] = 'Attach Your Self Attested document';
					 $data['reason'] = '';
					 $data['kycimage'] = '';
			     	}else if( ($getdata['kyc_status'] == 'reject') ){
				     $data['is_upload'] = 'no';
					 $data['text'] = 'Attach Your Self Attested document';
					 $data['reason'] = $getdata['comment'];
					 $data['kycimage'] = '';
			     	}else if( ($getdata['kyc_status'] == 'pending') ){
				     $data['is_upload'] = 'yes'; 
				     $data['text'] = 'KYC Pending...';
				     $data['reason'] = '';
				     $data['kycimage'] = '';
			     	}else if( ($getdata['kyc_status'] == 'yes') ){
				     $data['is_upload'] = 'yes'; 
				     $data['kycimage'] = '';
				     $data['text'] = 'Your KYC has been Approved';
				     $data['reason'] = '';
				     $data['kycimage'] = ADMINURL.'assets/images/donekyc.jpeg';
			     	}


			    }

				if( !empty($getdata) ){
				$response['status'] = TRUE;
				$response['data'] = $data;
				$response['message']= 'Data Feteched Successfully!';
				}else{
				$response['status'] = FALSE;
				$response['message']= 'Some Error Occured!';
				}


}else{ 
$response['status'] = FALSE;
$response['message']= 'Bad request!'; 
}

header("Content-Type: application/json");
echo json_encode( $response );


}



public function kycdoclist(){

		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Expires: 0");

		$response = [];
		$data = [];
		$up_where = null;

		 $request = requestJson();

if( ($_SERVER['REQUEST_METHOD'] == 'POST') ){

						 
				$userid = isset($request['userid'])?$request['userid']:false; 
				$user_type = isset($request['user_type'])?$request['user_type']:false; 

				if( !$userid ){
				$response['status'] = FALSE;
				$response['message'] = 'User Id is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}else if( !$user_type ){
				$response['status'] = FALSE;
				$response['message'] = 'User type is blank!';
				header("Content-Type: application/json");
				echo json_encode( $response );
				exit;
				}
 

				$where['tableid'] = $userid;
				$where['usertype'] = $user_type;
				$keys = 'id,documenttype,documentorimage';
				$getdata = $this->c_model->getAll('dt_uploads','documenttype ASC', $where,$keys );

				if( empty($getdata) ){
					$response['status'] = FALSE;
					$response['message'] = 'No Documents on Avaialable!';
					header("Content-Type: application/json");
					echo json_encode( $response );
					exit;
				} 
				 

				foreach ($getdata as $key => $value) {
				 	$arr['id'] = $value['id'];
				 	$arr['documenttype'] = $value['documenttype'];
				 	$arr['image'] = ADMINURL.'uploads/'.$value['documentorimage'];
				 	array_push($data, $arr);
				 } 
 
				$response['status'] = TRUE;
				$response['data'] = $data;
				$response['message']= 'Data Feteched Successfully!'; 


}else{ 
$response['status'] = FALSE;
$response['message']= 'Bad request!'; 
}

header("Content-Type: application/json");
echo json_encode( $response );


}


 
 
}
?>