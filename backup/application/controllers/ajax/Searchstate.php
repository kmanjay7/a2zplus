<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Searchstate extends CI_Controller {
   public function __construct(){
    parent::__construct(); 
    $this->load->model('Common_model','dg_model');
    }
     public function index(){
            $print = '';
              $search_data = $this->input->post('search_data');
              $result = $this->dg_model->getAlllike( 'city', array('status'=>'yes'), null ,'cityname ASC',$search_data ,'cityname','both' );
             // print_r($result);
                $output='<ul class="list-unstyled">';
                if (!empty($result))
                {
                    foreach ($result as $row){
                        $output .= "<li>" . $row['cityname']."</li>";
                        
                    }
                    
                }else{
                  $output .= "<li>No Result Found</li>";  
                }
                
		 $output .='</ul>';
	echo $output;		
		}
                public function state(){
                    $cityname = $this->input->post('cityname');
                    $result = $this->dg_model->getSingle('city',array('cityname'=>$cityname),'stateid');
                   echo $resultx = $this->dg_model->getSingle('state',array('id'=>$result),'statename');
                }
  
}