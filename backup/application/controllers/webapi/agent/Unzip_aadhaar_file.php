<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unzip_aadhaar_file extends CI_Controller{
	
	 public function __construct() {
		parent::__construct();
	 }
		
	public function index() {

		//header("Pragma: no-cache");
		//header("Cache-Control: no-cache");
		//header("Expires: 0");
			
			$response = array(); 
			    $data = array();
				
			   $table = 'dt_users';
				
			 $request = requestJson();
			
	   /****  check Method  start ****/ 
	   if( ($_SERVER['REQUEST_METHOD'] != 'POST') ){ 
			$response['status'] = FALSE;
			$response['message'] = "Bad Request!"; 
			header("Content-Type:application/json");
			echo json_encode( $response );
			exit;
		}	
			
	
	        $id = !empty($request['id']) ? $request['id'] : '';			
			 			
	         $where['id'] = $id;
	         $where['status'] = 'yes'; 
			 $checkuser = $this->c_model->countitem($table,$where  );
			
			if( $checkuser == 1 ){
			
			if( $id ){ 
			$post  = array('imeidevice'=>'0','loginstatus'=>'no'); 	
			$updatedata = $this->c_model->saveupdate($table,$post,null,$where) ;
			}
			
			$status = 1;
			
			}else{ $status = 2; }
			 
			
			if($status == 1 ){ 
			$response['status'] = TRUE;
		    $response['message'] = "You logged out successfully!";
			}else if($status == 2 ){
			
			$response['status'] = FALSE;
		    $response['message'] = "User not exists!";		
			}
			
		/*token check end*/	 	
		
	}


	public function unzipme(){
		$foldername = 'zipaadhaar';
		if(!is_dir($foldername)){ mkdir($foldername,0777,true); }
		$filename = 'offlineaadhaar20210112125728744.zip';
		$password = '1234';
		$basePath = base_url($foldername.'/');
		$filepath = $basePath.$filename;
		$extractpath = "zipaadhaar/";
		$extractfilepath = $foldername.'/offlineaadhaar20210112125728744.xml';


		// Get data about the file
                   // $uploadData = $this->upload->data(); 
                   // $filename = $uploadData['file_name'];
 $message = ''; 
                    ## Extract the zip file ---- start
                    $zip = new ZipArchive;
                    $res = $zip->open("zipaadhaar/".$filename);
                    if ($res === TRUE) {
                        $extractpath = "zipaadhaar/";
                        if ($zip->setPassword($password)){
                             if (!$zip->extractTo( $extractpath )){
                             $message = "Extraction failed (wrong password?)";  
                             }
                        }
        
                        $zip->extractTo($extractpath);
                        $zip->close();

                        $message = 'Extract successfully.';
                    } else {
                        $message = 'Failed to extract.';
                    }
                    ## ---- end
                    
                    $fileContents= file_get_contents($extractfilepath);
                    $json = xmltojson( $fileContents );
                    $array = json_decode( $json,true);
                    $json = json_encode($array);
                    echo '<pre>';
                    print_r($array);
        

echo $message;





	}
		
}

?>