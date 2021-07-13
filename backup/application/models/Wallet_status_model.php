<?php defined("BASEPATH") OR exit("No Direct Access Allowed!"); 

class Wallet_status_model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		}
		

public function countitem_wt($table,$where = null, $whereor = null,$whereorkey = null ){
		
		if(!is_null($where)){
			
		$query = $this->db->where($where);
		
			if( !is_null($whereor)){ 
			$query = $this->db->group_start();
			foreach($whereor as $row){ $query = $this->db->or_where($whereorkey,$row ); }
			$query = $this->db->group_end();
			}
			
		$query = $this->db->get($table);
		}else{ $query = $this->db->get($table);}
		$count = $query->num_rows();
		return ( $count > 0 ? $count : 0 );
}

public function getSingle_wt( $table,$where = null,$keys = null , $orderby = null,$limit = null,$inkey=null,$invalue=null,$not_inkey=null,$not_invalue = null ){
		if(!is_null($keys)){
		$keys = trim($keys);
		$this->db->select($keys);
		}
		if(!is_null($limit)){
		$this->db->limit($limit);
		}
		if(!is_null($orderby)){
		$this->db->order_by($orderby);
		}
		if(!is_null($where)){
		$this->db->where($where);
		}
		if( !is_null($inkey) && !is_null($invalue) && !empty($inkey) && !empty($invalue) ){
	    $query = $this->db->where_in($inkey,(explode(',',$invalue)));
        }
        if( !is_null($not_inkey) && !is_null($not_invalue) && !empty($not_inkey) && !empty($not_invalue) ){
	    $query = $this->db->where_not_in($not_inkey,(explode(',',$not_invalue)));
        }
        if(strpos($keys, ',')!==false){
        return $this->db->get($table)->row_array();
        }else if(strpos($keys, '*')!==false){
        return $this->db->get($table)->row_array();
        }else{
		$rows = $this->db->get($table)->row_array();
		return $rows[$keys];
	    }
		}


}
?>