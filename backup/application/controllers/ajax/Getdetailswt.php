<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Getdetailswt extends CI_Controller {
   public function __construct(){
    parent::__construct();  
    }


public function index(){ 
    $post = $this->input->post(); 
 
    $url = APIURL.('webapi/wallet/order_details'); 
    $buffer = curlApis($url,'POST',$post );
    $data['list'] = array();
    if($buffer['status'] && !empty($buffer['data'])){
    $data['list'] = $buffer['data'];
    } 

    $this->load->view('ajax/wallet_order_details',$data);
     
  }

 public function ptr(){ 
    $post = $this->input->post(); 
 
    $url = APIURL.('webapi/wallet/order_details_parent'); 
    $buffer = curlApis($url,'POST',$post );
    $data['list'] = array();
    if($buffer['status'] && !empty($buffer['data'])){
    $data['list'] = $buffer['data'];
    } 

    $this->load->view('ajax/wallet_order_details',$data);
     
  }


}