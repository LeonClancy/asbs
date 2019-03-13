<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_time {

    function __construct()
    {    
        date_default_timezone_set('Asia/Taipei');
    }

    /**
     * system_time
     *
     * 取得系統時間
     *
     * @access  public
     * @param   none
     * @return  string
     */
    public function system_time()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $hour = date('G');
        $minute = date('i');
        $second = date('s');
        // time format: 2015-05-31 23:23:23
        $time = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
        return $time;
    }

    /**
     * time_diff
     *
     * 計算時間差異
     * (strtotime($time1) - strtotime($time2));             // 計算相差之秒數
     * (strtotime($time1) - strtotime($time2))/ (60);       // 計算相差之分鐘數 
     * (strtotime($time1) - strtotime($time2))/ (60*60);    // 計算相差之小時數 
     * (strtotime($time1) - strtotime($time2))/ (60*60*24); // 計算相差之天數
     *
     * @access  public
     * @param   $time1, $time2
     * @return  $diff(type: int)
     */
    public function time_diff($time1, $time2)
    {
        // 計算相差之分鐘數
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        if ($time1 - $time2 > 0) {
            $diff = ($time1 - $time2) / 60;
            return $diff;
        } else {
            $diff = ($time2 - $time1) / 60;
            return $diff;
        }
    }
}