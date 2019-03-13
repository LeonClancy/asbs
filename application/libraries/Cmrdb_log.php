<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_log { //將此lib視為與資料模型最靠近的接口

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * 來自Ion Auth Library
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var)
    {
       return get_instance()->$var;
    }

    /**
     * CMRDB CodeIgniter2.2 Library
     *
     * Cmrdb_log
     *
     * __construct
     *
     *
     * @access  public
     * @param   none
     * @return  none
     */
    function __construct()
    {    
        $this->load->database();
        $this->load->library('Cmrdb_time');
        date_default_timezone_set('Asia/Taipei');
    }

    /**
     * check_log
     *
     * 檢查紀錄
     *
     * $from_time: 2015-05-31 23:23:23 DATETIME
     * 
     * $to_time: 2015-05-31 23:23:23 DATETIME
     *
     * @access  public
     * @param   
     * @return  
     */
    public function check_log($from_time, $to_time)
    {
        if (strtotime($to_time) - strtotime($from_time) != 0) {
            // if to_time smaller than from_time, swap them!
            if (strtotime($to_time) - strtotime($from_time) < 0) {
                $tmp = $from_time;
                $from_time = $to_time;
                $to_time = $tmp;
            }
            $str = "SELECT * FROM `log` 
                      WHERE `time` BETWEEN '".$from_time."' AND '".$to_time."'";
            $result = $this->db->query($str);
            return $result;
        } else {
            // if to_time equals to from_time, query by the only one time
            $str = "SELECT * FROM `log` 
                      WHERE `time` = '".$from_time."'" ;
            $result = $this->db->query($str);
            return $result;
        } 
    }

    /**
     * add_log
     *
     * 新增紀錄
     *
     * $action: 動作
     *
     * $object: 受影響之部份
     *
     * @access  public
     * @param   $action, $object
     * @return  $result  
     */
    public function add_log($action, $object, $system = FALSE)
    {   
        // gets who does this action
        if ($system == FALSE) {
            $member = $this->session->userdata('id');
        } else {
            // system action
            $member = 'sys';
        }
        
        //get system time
        $time = $this->cmrdb_time->system_time();
        $ip = $this->session->userdata('ip_address');
        $data = array('member' => $member, 
                      'action' => $action, 
                      'object' => $object, 
                      'time' => $time,
                      'ip' => $ip, 
                      );
        $result = $this->db->insert('log', $data);
        return $result;
    }
}