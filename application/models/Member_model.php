<?php

class Member_model extends CI_Model {
    public function __construct(){
			parent::__construct();
    }

	function getMember($id = '')
	{
		if($id == '') {
			$sql = "SELECT * FROM `member`";
		} else {
			$sql = "SELECT * FROM `member` WHERE `email` = '$id' ";
		}
		$query = $this->db->query($sql);
		if($query->result()>0) {
			return $query;
		} else {
			return FALSE;
		}
	}

	function getAllUser() 
	{
		$sql = "SELECT * FROM `member`";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function lock($input)
	{
		$sql = "UPDATE `member` SET locking = ? WHERE id = ?";
		$query = $this->db->query($sql,array(
			$input->locking,
			$input->id
		));
		return $query;
	}
	
	function addMember($input)
	{
		$sql = "INSERT INTO `ASBS`.`member` (
			`id`, `name`, `email`, `password`, `role`, `locking`
		) VALUES ( NULL,?,?,?,?,? )";
		$query = $this->db->query($sql,array(
			$input->name,
			$input->email,
			$input->password,
			$input->role,
			$input->locking
		));
		return $query;
	}

	function deleteMember($input)
	{
		$sql = "DELETE FROM `member` WHERE `member`.`id` = ".$input;
		$result = $this->db->query($sql);
		return $result;
	}
}