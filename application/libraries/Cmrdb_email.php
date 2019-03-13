<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_email { //將此lib視為與資料模型最靠近的接口

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
       date_default_timezone_set("Asia/Taipei");
    }

    /**
     * CMRDB CodeIgniter2.2 Library
     *
     * Cmrdb_mail
     *
     * __construct
     *
     * initializes Cmrdb_mail module
     *
     * $params: concludes gmail account and passwd
     *
     * $params = array('gmail' => ,'passwd' => );
     *
     * @access  public
     * @param   $params
     * @return  none
     */
    function __construct($params)
    {	 
        $config = array('protocol' => 'smtp',
                        'smtp_host' => 'ssl://smtp.googlemail.com',
                        'smtp_port' => '465',
                        '$wrapchars' => '76',
                        '$newline' => '\r\n',
                        'smtp_user' => $params['gmail'], // 'cmrdb.service@gmail.com',
                        'smtp_pass' => $params['passwd'], // 'cmrdbserviceProvidence',
                        'mailtype' => 'html',
                        );
        $this->load->database();
        $this->load->library('email', $config);
    }

    /**
     * CMRDB CodeIgniter2.2 Library
     *   
     * Cmrdb_mail
     * 
     * send_group_mail
     * 
     * sends emails to the whole group
     *
     * $grp_id : group id
     *
     * $mail : the details of this email
     *
     * $mail = array('gmail' => , 'sender' => , 'subject' => , 'content' => );
     *
     * @access  public
     * @param   $grp_id, $mail
     * @return  boolean
     */
    public function send_group_email($grp_id, $mail)
    {
        // 將該群組內成員輸出成array
        $people_in_grp = $this->db->get_where('member_in_grp', array('grp_id' => $grp_id));
        
        if ($people_in_grp->num_rows() > 0) {
            // email陣列
            $email_array = array();
            // 取得email array
            foreach ($people_in_grp->result() as $row) {
                $person = $this->db->get_where('member', array('id' => $row->member_id));
                $email_array[] = $person->row()->email;
            }
            // 對該群組內所有成員寄信
            foreach ($email_array as $each_mail) {
                $this->email->from($mail['gmail'], $mail['sender']); // 寄件email, 寄件人名稱
                $this->email->to($each_mail); // 收件人email
                $this->email->subject($mail['subject']); // 主旨
                $this->email->message($mail['content']); // 內容
                $this->email->send();    
            }
            return TRUE;
        } else {
            return FALSE; // empty group
        }
    }

    /**
     * CMRDB CodeIgniter2.2 Library
     *
     * Cmrdb_mail
     *
     * sends some emails to someone
     * 
     * $id_array: id array 
     *
     * $mail: the details of this email
     *
     * $mail = array('gmail' => , 'sender' => , 'subject' => , 'content' => );
     *
     * @access  public
     * @param   $id_array, $mail
     * @return  boolean
     */
    public function send_some_email($id_array, $mail)
    {
        if (count($id_array) > 0) {
            // get email from id_array
            foreach ($id_array as $id) {
                // 取得該成員資料
                $result = $this->db->get_where('member', array('id' => $id));
                // 寄信程序   
                $this->email->from($mail['gmail'], $mail['sender']); // 寄件email, 寄件人名稱
                $this->email->to($result->row()->email); // 收件人email
                $this->email->subject($mail['subject']); // 主旨
                $this->email->message($mail['content']); // 內容
                $this->email->send();
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function contact_us_email($mail, $receiver)
    {
        // 寄信程序   
        $this->email->from($mail['gmail'], $mail['sender']); // 寄件email, 寄件人名稱
        $this->email->to($receiver); // 收件人email
        $this->email->subject($mail['subject']); // 主旨
        $this->email->message($mail['content']); // 內容
        $this->email->send();
        return TRUE;
    }
}