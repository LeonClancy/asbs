<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Space_model extends CI_Model{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    // 場地的CRUD

    public function getSpace($id)
    {
        $sql = "SELECT * FROM `space` WHERE id = ".$id;
        $query = $this->db->query($sql);
        return $query;
    }

    public function addSpace($input)
    {
        $sql = "INSERT INTO `ASBS`.`space` (`name`,`info`,`vacancy`,`f_id`) SELECT ?,?,?,? ";
        $this->db->query($sql, array(
            $input->name,
            $input->info,
            $input->vacancy,
            $input->f_id
        ));
    }
    
    public function delSpace($input)
    {
        $sql = "DELETE FROM `space` WHERE `space`.`id` = ".$input;
        return $this->db->query($sql);
    }
    
    public function modifySpace($input)
    {
        $sql = "UPDATE `space` 
                SET `name` = ?,
                    `info` = ?,
                    `vacancy` = ?
                WHERE `space`.`id` = ?";

        $this->db->query($sql, array(
            $input->name,
            $input->info,
            $input->vacancy,
            $input->id
        ));
    }

    public function getSpacelist($input)
    {
        if($input != 0) {
            $f_id = $input;
        }else{
            $f_id = 0;
        }
        $sql = "SELECT * FROM `space` WHERE f_id = ".$f_id;
        return $query = $this->db->query($sql);
    }
}