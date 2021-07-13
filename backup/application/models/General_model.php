<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_model extends CI_Model {

	function getSingle($tbl, $where, $keys=false)
	{
		if($keys) $this->db->select($keys);
		$this->db->where($where);
		$this->db->from($tbl);
		$q=$this->db->get();
		// _query();
		return $q->row_array();
	}

	function getAll($tbl, $where=false, $keys=false, $orderBy=false, $limit=false, $offset=false, $search=[])
	{
		if(count($search)) $this->db->or_like($search);
		if($keys) $this->db->select($keys);
		if($where) $this->db->where($where);
		if($limit) $this->db->limit($limit, $offset);
		if($orderBy) $this->db->order_by($orderBy);
		$this->db->from($tbl);
		$q=$this->db->get();
		// _query();
		return $q->result_array();
	}

	function update($tbl, $save, $where)
	{
		$this->db->where($where);
		$this->db->update($tbl, $save);
		// die(_query());
	}

	function save($tbl, $save)
	{
		$this->db->insert($tbl, $save);
		return $this->db->insert_id();
	}

	function delete($tbl, $where)
	{
		$this->db->where($where);
		$this->db->delete($tbl);
	}

	function joinLeft($tbl1, $tbl2, $on, $where=false, $keys=false, $limit=false, $offset=false, $orderBy=false)
	{
		if($keys) $this->db->select($keys);
		if($where) $this->db->where($where);
		if($limit) $this->db->limit($limit, $offset);
		if($orderBy) $this->db->order_by($orderBy);
		$this->db->from($tbl1);
		$this->db->join($tbl2, $on, "Left");
		$q=$this->db->get();
		return $q->result_array();
	}

	function count($tbl, $where=false)
	{
		if($where) $this->db->where($where);
		$this->db->from($tbl);
		$q=$this->db->get();
		return $q->num_rows();
	}
}
