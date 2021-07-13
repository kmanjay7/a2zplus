<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller{
	
	public function __construct()
    {
    	parent::__construct();
    	$this->load->library('session');
        adsession_check();
	}

    public function index()
    {
        $data['title'] = 'Contacts';
        $data['folder'] = 'ag';
        $data['pagename'] = 'contacts';
        $users=[];
        $parentid = $this->session->userdata("parentid");
        $keys = 'id,firmname,ownername,alt_mobileno,emailid,address,mobileno,parentid,parenttype';
        while(1)
        {
            $user=$this->c_model->getSingle("users",["id"=>$parentid],$keys);
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

        $data["users"]=$users;
//echo '<pre>';
     //   print_r($users);

        adview('contact', $data);
    }  


}