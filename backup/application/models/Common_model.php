<?php defined("BASEPATH") OR exit("No Direct Access Allowed!"); 
class Common_model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		}
		
		
	public function getAll( $table, $orderby = null, $where = null,$keys = null,$limit = null ){
		if(!is_null($keys)){
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
		
		return $this->db->get($table)->result_array();
	    }
	
	
	public function getSingle( $table,$where = null,$keys = null , $orderby = null,$limit = null,$inkey=null,$invalue=null,$not_inkey=null,$not_invalue = null ){
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

        public function getcolumnRech( $table,$where,$key , $orderby = null,$limit = null ){
	    
		if(!is_null($key)){
		$this->db->select($key);
		}
		if(!is_null($limit)){
		$this->db->limit($limit);
		}
		if(!is_null($orderby)){
		$this->db->order_by($orderby);
		}
		$data = $this->db->where($where)->get($table)->row_array();
		return $data;
		}


	public function getcolumn( $table,$where,$key , $orderby = null,$limit = null ){
		if(!is_null($key)){
		$this->db->select($key);
		}
		if(!is_null($limit)){
		$this->db->limit($limit);
		}
		if(!is_null($orderby)){
		$this->db->order_by($orderby);
		}
		$data = $this->db->where($where)->get($table)->row_array();
		return $data[$key];
		}
	
	
	public function delete($table,$where){
		return $this->db->where($where)->delete($table);
	    }
		
	
	
	public function countitem($table,$where = null, $whereor = null,$whereorkey = null ){
		
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
		
	
	public function saveupdate($table, $dataarray, $validation = null, $where = null, $id = null ){
	
	if(!is_null( $where )){
		$status = $this->db->where( $where )->update($table,$dataarray);
		return !is_null( $id ) ? $id :  $status;
	}else{
		 
		 if(!is_null($validation)){
	     $this->db->where($validation);
	     }
	 
		 if( !is_null($validation) && $this->db->get($table)->num_rows() > 0 ){
			return false;
		 }else {
			$this->db->insert($table,$dataarray);
			return $this->db->insert_id();
		 }
    }
	    
}	
	
	
public function getfilter($tablename,$wherearray = null,$limit = null,$start = null, $orderby = null, $orderbykey = null, $whereor = null,$whereorkey = null, $like = null, $likekey = null,  $getorcount = null, $infield = null, $invalue = null, $keys = null ,$groupby = null ){

if( !is_null($keys) ){ $this->db->select($keys); }	
if( !is_null($groupby) ){ $this->db->group_by($groupby); }	
		 
if( !is_null($limit) && !is_null($start) && $start > 0 && $limit > 0 ){ 

if( !is_null($orderby) && ( $orderby == 'ASC' || $orderby == 'DESC')){ $query = $this->db->order_by($orderbykey, $orderby ); }

if(!is_null($likekey) && !is_null($like)){ $this->db->like($likekey,$like,'both'); }
		

$query = $this->db->limit($limit, $start);
	if( !is_null($whereor) && !is_null($whereorkey) ){ 
		$query = $this->db->group_start();
		foreach($whereor as $row){ $query = $this->db->or_where($whereorkey,$row ); } 
		$query = $this->db->group_end();
	}
	
	if( !is_null($whereor) && !is_null($whereorkey) && !empty($whereand)){ 
		$query = $this->db->group_start();
		foreach($whereand as $datet){ $query = $this->db->or_where($whereorkey,$datet ); }
		$query = $this->db->group_end();
	}
	
    if( !is_null($infield) && !is_null($invalue) && !empty($infield) && !empty($invalue) ){
	$query = $this->db->where_in($infield,(explode(',',$invalue)));
    }	
if( !is_null($wherearray)){$query = $this->db->where($wherearray);}
$query = $this->db->get($tablename);
}




else if( !is_null($limit) && $limit > 0){

if( !is_null($orderby) && ( $orderby == 'ASC' || $orderby == 'DESC')){ $query = $this->db->order_by($orderbykey, $orderby ); }

if(!is_null($likekey) && !is_null($like)){ $this->db->like($likekey,$like,'both'); }
$query = $this->db->limit($limit);
	if( !is_null($whereor) && !is_null($whereorkey) ){ 
		$query = $this->db->group_start();
		foreach($whereor as $row){ $query = $this->db->or_where($whereorkey,$row ); }
		$query = $this->db->group_end();
	}
    
	if( !is_null($infield) && !is_null($invalue) && !empty($infield) && !empty($invalue) ){
	$query = $this->db->where_in($infield, (explode(',',$invalue)));
    }	
if( !is_null($wherearray)){$query = $this->db->where($wherearray);}
$query = $this->db->get($tablename);

}

else{ 	
if( !is_null($orderby) && ( $orderby == 'ASC' || $orderby == 'DESC')){ $query = $this->db->order_by('id', $orderby ); }

if(!is_null($likekey) && !is_null($like)){ $this->db->like($likekey,$like,'both'); }

	if( !is_null($whereor) && !is_null($whereorkey)){ 
		$query = $this->db->group_start();
		foreach($whereor as $row){ $query = $this->db->or_where($whereorkey,$row ); }
		$query = $this->db->group_end();
	}
    
	if( !is_null($infield) && !is_null($invalue) && !empty($infield) && !empty($invalue) ){
	$query = $this->db->where_in($infield, (explode(',',$invalue)));
    }
if( !is_null($wherearray)){$query = $this->db->where($wherearray);}
$query = $this->db->get($tablename);
}


 
 $output = ($getorcount == 'count') && !is_null($getorcount) ? $query->num_rows() : $query->result_array();

return  $output ? $output : '' ;
}				




public function getAlllike( $table, $where = null,$keys = null ,$orderby = null, $like = null,$likekey = null,$likeaction = null ){ 
		if(!is_null($keys)){
		$this->db->select($keys);
		}
		
		if(!is_null($orderby)){
		$this->db->order_by($orderby);
		}
		
		
		if(!is_null($like) && !is_null($likekey) && !is_null($likeaction) ){
		$this->db->like($likekey,$like,$likeaction);
		}
		
		if(!is_null($where)){
			$this->db->where($where);
		}
		
		return $this->db->get($table)->result_array();
	    }
		
				

public function getAllwherein( $table, $where = null,$keys = null ,$orderby = null, $inkey = null,$inlistarray = null ){ 

		if(!is_null($keys)){
		$this->db->select($keys);
		}
		
		if(!is_null($orderby)){
		$this->db->order_by($orderby);
		}
		
		
		if(!is_null($inkey) && !is_null($inlistarray) ){
		$this->db->where_in($inkey, $inlistarray);
		}
		
		if(!is_null($where)){
			$this->db->where($where);
		}
		
		return $this->db->get($table)->result_array();
	    }
					

public function joindata( $select, $where, $from, $jointable, $joinon, $jointype,$groupby=null,$orderby=null,$jointable3 = null,$joinon3 = null, $jointype3 = null,$limit=null, $offset=null,$getorcount=null,$jointable4 = null,$joinon4 = null, $jointype4 = null){ 
		if(!is_null( $select )){ $this->db->select( $select ); }
		if(!is_null( $groupby )){ $this->db->group_by( $groupby ); }
		if(!is_null( $orderby )){ $this->db->order_by( $orderby ); }
		if(!is_null( $from )){ $this->db->from( $from ); }
		if(!is_null( $jointable ) && !is_null( $jointype ) ){ $this->db->join( $jointable, $joinon, $jointype ); } 
		if(!is_null( $jointable3 ) && !is_null( $jointype3 ) ){ $this->db->join( $jointable3, $joinon3, $jointype3 ); } 
		if(!is_null( $jointable4 ) && !is_null( $jointype4 ) ){ $this->db->join( $jointable4, $joinon4, $jointype4 ); }
		if(!is_null( $where )){ $this->db->where( $where ); }
		if(!is_null( $limit ) && !is_null($offset)){ $query = $this->db->limit($limit, $offset); }
		else if(!is_null( $limit )){ $this->db->limit( $limit ); } 
		 $query = $this->db->get();
		 $output = ($getorcount == 'count') && !is_null($getorcount) ? $query->num_rows() : $query->result_array(); 
		return  $output ? $output : '' ; 
	    }
            
public function joindatalimit( $select, $where, $from, $jointable, $joinon, $jointype,$groupby=null,$orderby=null, $limit=null, $offset=null){ 
		if(!is_null( $select )){ $this->db->select( $select ); }
		if(!is_null( $groupby )){ $this->db->group_by( $groupby ); }
		if(!is_null( $orderby )){ $this->db->order_by( $orderby ); }
		if(!is_null( $from )){ $this->db->from( $from ); }
		if(!is_null( $jointable ) && !is_null( $jointype ) ){ $this->db->join( $jointable, $joinon, $jointype ); } 
		if(!is_null( $where )){ $this->db->where( $where ); }
		if(!is_null( $limit )){ $this->db->limit( $limit, $offset ); }
		$q=$this->db->get();
		// echo $this->db->last_query();die();
		return $q->result_array();
	    }


public function joinmultiple( $select, $where, $from, $join, $groupby ,$orderby, $limit, $offset, $getorcount, $inkey = null, $inlistarray = null ){ 
		if(!is_null( $select )){ $this->db->select( $select ); }
		if(!is_null( $groupby )){ $this->db->group_by( $groupby ); }
		if(!is_null( $orderby )){ $this->db->order_by( $orderby ); }
		if(!is_null( $from )){ $this->db->from( $from ); }

		if(!is_null( $join ) && !is_null( $join ) ){
			 foreach ($join as $key => $value) {
			 	$this->db->join( $value['table'], $value['joinon'], $value['jointype'] );
			 } 
		 }  

		if(!is_null($inkey) && !is_null($inlistarray) ){
		$this->db->where_in($inkey, $inlistarray);
		}
		 
		if(!is_null( $where )){ $this->db->where( $where ); }
		if(!is_null( $limit )){ $this->db->limit( $limit, $offset ); }
		 $query = $this->db->get();
		 $output = ($getorcount == 'count') && !is_null($getorcount) ? $query->num_rows() : $query->result_array();
		return  $output;
	    }
 
       
	 

}
?>