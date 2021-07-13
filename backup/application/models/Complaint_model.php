<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Complaint_model extends CI_Model {

	function getComplaints($userIds=false, $where=false)
	{
		if($userIds) $this->db->where_in("userid", $userIds);
		if($where) $this->db->where($where);
		$this->db->order_by("id desc");
		$this->db->from("complaint_manual");
		$q=$this->db->get();
		return $q->result_array();
	}

	function getComplaintAdmin($where=false, $limit=false, $offset=0)
	{
		$this->db->select("cm.*, u.ownername, u.user_type");
		if($where) $this->db->where($where);
		if($limit) $this->db->limit($limit, $offset);
		$this->db->order_by("cm.created desc");
		$this->db->from("complaint_manual cm");
		$this->db->join("users u", "u.id=cm.userid", "Left");
		$q=$this->db->get();
		return $q->result_array();
	}

	function countComplaintAdmin($where=false)
	{
		$this->db->select("cm.id");
		if($where) $this->db->where($where);
		$this->db->from("complaint_manual cm");
		$this->db->join("users u", "u.id=cm.userid", "Left");
		$q=$this->db->get();
		return $q->num_rows();
	}

}
