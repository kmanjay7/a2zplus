<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Make_complaint extends CI_Controller {
    public function __construct(){
    parent::__construct();  
    }


 public function index(){
         $post = $this->input->post(); 
         $searchurl = APIURL.('webapi/agent/make_complaint');
         $buffer = curlApis( $searchurl,'POST',$post ); 
         echo json_encode($buffer);  
           }
  
}