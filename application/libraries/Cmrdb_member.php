<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_member { //將此lib視為與資料模型最靠近的接口

    private $token = '';
    private $certification_url = '';
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
     * __construct
     *
     * Initialize cmrdb_member module
     *
     * load database, url helper, session, cmrdb_email 
     *
     * @access  public
     * @param   $param = array('gmail' => '', 'passwd' => '')
     * @return  none
     */
    function __construct($param)
    {	 
        $this->load->database();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('cmrdb_email', $param);
        $this->load->library('cmrdb_log');
        $this->load->library('cmrdb_time');
        $this->load->library('cmrdb_competence');
    }

    /**
     * add_member
     *
     * for administrator to add new member
     *
     * 原始資料欄位
     * $origin = array('email' => , 'passwd' => , 'name' => ,);
     *
     * 擴充資料欄位
     * $others = array('' => ,);
     * 
     * 個人權限預設為'D'
     * $per_permission = 'D'
     *
     * @access  public
     * @param   $origin, $per_permission, $others
     * @return  $result: 0/1
     */
    public function add_member($origin, $per_permission = 'D', $others = NULL)
    {
        $result = 0;
        $data = $this->db->get_where('member', array('email' => $origin['email']));
        if ($data->num_rows() > 0 ) {
            return $result;
        } else {
            // 初始會員狀態
            $origin['activation'] = 1; //預設帳號已啟用
            $origin['locking'] = 0; //預設帳號未鎖定
            $origin['passwd'] = md5($origin['passwd']); // md5加密
            $origin['per_permission'] = $per_permission; //個人權限預設為會員'D'
            $origin['register_time'] = $this->cmrdb_time->system_time();
            $origin['token'] = md5($origin['email'].$origin['name']);

            // 若有擴充欄位則合併兩結合陣列，反之則直接加入資料庫
            if ($others != NULL) {
                $data = array_merge($origin, $others);     
            } else {
                $data = $origin;
            }   
            $email = $origin['email'];
            $result = $this->db->insert('member', $data);
            if($result)
            {
                $this->cmrdb_competence->add_competence_model($email);
                return TRUE;
            } else{
                return FALSE;
            }
            // this is a system record
            if ($result) {
                $json = json_encode(array('email' => $origin['email'],));
                $this->cmrdb_log->add_log('add member', $json);
            }
            return $result; 
        }
    }

    /**
     * search_member
     *
     * this function used to search member
     *
     * $keyword: 搜尋的關鍵字
     *
     * $field_array: 用來搜尋的欄位陣列 
     *
     * $fuzzy: 是否做模糊搜尋 0/1
     *
     * @access  public
     * @param   $keyword, $field_array, $fuzzy
     * @return  $result
     */
    public function search_member($keyword, $field_array, $fuzzy = 0)
    {
        $result = '';
        // $fuzzy == 0 不進行模糊搜尋
        if ($fuzzy == 0) {
            $sql = "SELECT * FROM `member` WHERE ";
            for ($i=0; $i < count($field_array); $i++) { 
                $sql .= "`".$field_array[$i]."` = '".$keyword."'";
                if ($i == count($field_array) - 1 ) {
                    break;
                } else {
                    $sql .= ' OR ';
                }
            }
            $result = $this->db->query($sql);
        } else {
            $this->db->select('*');
            $this->db->from('member');
            $this->db->like('email', $keyword);
            foreach ($field_array as $field) {
                $this->db->or_like($field, $keyword);    
            } 
            // SELECT * FROM `member` WHERE `email` LIKE '%pu%' OR `name` LIKE '%pu%' 
            // 產生： WHERE title LIKE '%match%' OR body LIKE '%match%'
            $result = $this->db->get();
        }

        if($result->num_rows() > 0){
            return $result;
        } else {
            $result = FALSE;
            return $result;
        }
    }

    /**
     * edit_member
     *
     * update the information of member
     * changing passwd here is forbidden
     *
     * 會員的資料欄位
     * $member = array('' => , );
     *
     * @access  public
     * @param   $id
     * @return  $result
     */
    public function edit_member($member_data)
    {
        if (isset($member_data['passwd'])) {
            unset($member_data['passwd']);
        }
        $this->db->where('id', $member_data['id']);
        $result = $this->db->update('member', $member_data);
        // this is a system record
        if ($result) {

            $json = json_encode($member_data);
            $this->cmrdb_log->add_log('edit member', $json);   
        }
        return $result; // 1 or 0
    }

    /**
     * delete_member
     *
     * delete a member
     *
     * 會員id
     * $id
     *
     * @access  public
     * @param   $id
     * @return  $result
     */
    public function delete_member($id)
    {
        $result = 0;
        $num = $this->db->get_where('member', array('id' => $id));
        $email = $num->row()->email;
        if ($num->num_rows() > 0 ) { 
            $result = $this->db->delete('member', array('id' => $id));
            // this is a system record
            $this->cmrdb_log->add_log('delete member',json_encode(array('id' => $id, 'email' => $email)));
            return $result; // 1 or 0
        } else {
            return $result;
        }
        
    }

    /**
     * lock_member
     *
     * 鎖定某會員帳號
     *
     * 會員id
     * $id
     *
     * $key等於1時為鎖定，等於0時為解鎖
     * $key = 1
     *
     * @access  public
     * @param   $id, $key
     * @return  $result
     */
    public function lock_member($id, $key = 1)
    {
        $result = 0;
        $num = $this->db->get_where('member', array('id' => $id));
        if ($num->num_rows() > 0) {
            if ($key == 1) { //鎖定帳號
                $data = array('id' => $id, 'locking' => 1);
                $this->db->where('id', $id);
                $result = $this->db->update('member', $data);
                // this is a system record
                $this->cmrdb_log->add_log('lock member', json_encode($data));
                return $result;
            } else if ($key == 0) { //解鎖帳號
                $data = array('id' => $id, 'locking' => 0);
                $this->db->where('id', $id);
                $result = $this->db->update('member', $data);
                // this is a system record
                $this->cmrdb_log->add_log('unlock member', json_encode($data));
                return $result;
            } else {
                return $result;    
            }
        } else {
            return $result; 
        }
    }

    /**
     * register
     *
     * 會員註冊
     * 
     * $origin: 原始欄位
     *
     * $others: 擴充欄位
     *
     * $validation: 判斷是否要用email認證, e.g.0/1
     * 
     * $email_content: array('gmail' => , 'sender' => , 'subject' => , 'content' => );
     *
     * $countr_func_path: 認證連結的controller跟function名稱, e.g.".../controller/function/..."
     *
     * @access  public
     * @param   $origin, $others, $emails, $countr_func_path
     * @return  none
     */
    public function register($origin, $others = NULL, $validation = 1, $contr_func_path = NULL)
    {
        $origin['activation'] = 0; // 預設帳號未啟用
        $origin['locking'] = 0; // 預設帳號未鎖定
        $origin['passwd'] = md5($origin['passwd']); // md5加密
        $origin['per_permission'] = 'D'; // 個人權限預設為會員'D'

        // time format: 2015-05-31 23:23:23
        $origin['register_time'] = $this->cmrdb_time->system_time();
        $origin['token'] = md5($origin['email'].$origin['name']);
        $this->token = $origin['token'];
        // 認證信網址
        $this->certification_url = site_url($contr_func_path.'/'.$origin['token']);
        
        //判斷email是否已經存在database
        $mail_in_db = $this->db->get_where('member', array('email' => $origin['email']));
        
        if ( $mail_in_db->num_rows() > 0 ) {
            // email has already existed
            return FALSE;
        } else {
            // email address 不存在,判斷是否需要email認證
            if ($validation == 1) {
                // 判斷是否有擴充欄位
                if ($others != NULL) {
                    $data = array_merge($origin, $others);
                    $this->db->insert('member', $data);
                } else {
                    $data = $origin;
                    $this->db->insert('member', $data);
                }
                $email = $origin['email'];

                $this->cmrdb_competence->add_competence_model($email);

                // 用CI session紀錄該註冊者id
                $user_data = $this->db->get_where('member', array('email' => $origin['email']));
                $session_data = array('id' => $user_data->row()->id);
                $this->session->set_userdata($session_data);
                // this is a system record
                $this->cmrdb_log->add_log('register', json_encode($session_data['email'] = $origin['email']));
                
                // 回傳認證網址
                return $this->certification_url;
            } else {
                $origin['activation'] = 1; //不須認證則預設帳號啟用
                if ($others != NULL) {
                    $data = array_merge($origin, $others);
                    $result = $this->db->insert('member', $data); 
                } else {
                    $data = $origin;
                    $result = $this->db->insert('member', $data);
                }
                // 用CI session紀錄該註冊者id
                $user_data = $this->db->get_where('member', array('email' => $origin['email']));
                $session_data = array('id' => $user_data->row()->id);
                $this->session->set_userdata($session_data);
                // this is a system record
                $this->cmrdb_log->add_log('register', json_encode($session_data['email'] = $origin['email']));
                
                return $result;
            }
        }
    }

    /**
     * send_validation_email
     *
     * 寄送認證信
     *
     * $email_content: array('gmail' => , 'sender' => , 'subject' => , 'content' => );
     *
     * @access  public
     * @param   $emails
     * @return  $result
     */
    public function send_validation_email($email_content)
    {
        $data = $this->db->get_where('member', array('token' => $this->token));
        // member_id array
        $member_id = array($data->row()->id,);       
        $result = $this->cmrdb_email->send_some_email($member_id, $email_content);
        $json = json_encode(array('member_id' => $member_id));
        if ($result) {
            $this->cmrdb_log->add_log('send validation email', $json, TRUE);
        } 
        return $result;
    }

    /**
     * validate_member
     *
     * 會員認證
     *
     * $token: 由email和name使用md5加密而來
     *
     * @access  public
     * @param   $token
     * @return  boolean
     */
    public function validate_member($token) //點下認證信內的連結，將token傳送過來
    {
        $result = $this->db->get_where('member', array('token' => $token));
        if ($result->num_rows() > 0) {
            // 註冊時間
            $register_time = $result->row()->register_time;
            // 目前系統時間
            $now_time = $this->cmrdb_time->system_time();
            // 必須在15分鐘內完成認證
            if ($this->cmrdb_time->time_diff($register_time, $now_time) <= 15) {
                $data = array('token' => $token, 'activation' => 1);
                $this->db->where('token', $token);
                $result = $this->db->update('member', $data);
                // this is a system record
                if ($result) {
                    $this->cmrdb_log->add_log('validates member', json_encode(array('token' => $token)), TRUE);
                }        
                return $result;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * change_passwd
     *
     * 變更密碼
     *
     * $email
     *
     * $old_passwd
     *
     * $new_passwd
     *
     * @access  public
     * @param   $id, $old_passwd, $new_passwd
     * @return  boolean
     */
    public function change_passwd($email, $old_passwd, $new_passwd)
    {
        // check email existed
        $result = $this->db->get_where('member', array('email' => $email));
        if ($result->num_rows() > 0) {
            // exist, check old_passwd
            if ($result->row()->passwd == md5($old_passwd)) {
                $this->db->where('email', $email);
                $this->db->update('member', array('passwd' => md5($new_passwd),));
                // this is a system record
                $this->cmrdb_log->add_log('change passwd', json_encode(array('email' => $email)));
                return TRUE; 
            } else {
                return FALSE;
            }
        } else {
            // email doesn't exist
            return FALSE;
        }
    }

    /**
     * logged_status
     *
     * 登入狀態
     *
     * @access  public
     * @param   none
     * @return  boolean
     */
    public function logged_status() //檢查登入狀態
    {
        return (bool) $this->session->userdata('logged_in');
    }

    /**
     * login
     *
     * 登入
     *
     * $email
     *
     * $passwd
     *
     * @access  public
     * @param   $email, $passwd
     * @return  boolean
     */
    public function login($email, $passwd)
    {
        $user_data = $this->db->get_where('member', array('email' => $email));
        
        if ($user_data->num_rows() > 0) { //判斷email是否存在
            if ($user_data->row()->passwd == $passwd) { //判斷密碼是否符合
                if ($user_data->row()->locking == 0 ) { //判斷是否被鎖定
                    $session_data = array(
                        'id' => $user_data->row()->id,
                        'email' => $user_data->row()->email,
                        'name' => $user_data->row()->name,
                        'logged_in' => TRUE,
                    );
                    $this->session->set_userdata($session_data);
                    
                    return TRUE;
                } else {
                    return FALSE;
                }
                
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * logout
     *
     * 登出
     *
     * @access  public
     * @param   none
     * @return  boolean
     */
    public function logout()
    {
        // gets some data from session
        $id = $this->session->userdata('id');
        $email = $this->session->userdata('email');
        $name = $this->session->userdata('name');
        $user_data = array('id' => $id, 'email' => $email, 'name' => $name);
        // this is a system record
        $this->cmrdb_log->add_log('logout', json_encode($user_data));
        return (bool) $this->session->sess_destroy();
    }

    /**
     * forget_passwd
     *
     * 忘記密碼
     *
     * $email
     *
     * @access  public
     * @param   $email
     * @return  boolean
     */
    public function forget_passwd($email, $contr_func_path)
    {
        $result = $this->db->get_where('member', array('email' => $email));
        if ($result->num_rows() > 0) { // 有此帳號  
            // 全域變數token
            $this->token = $result->row()->token;
            // 全域變數certification_url
            $this->certification_url = site_url($contr_func_path.'/'.$this->token);            
            return $this->certification_url;
        } else {
            return FALSE;
        }
        
    }

    /**
     * send_forget_passwd_email
     *
     * 寄送忘記密碼信
     *
     * $email_content: array('gmail' => , 'sender' => , 'subject' => , 'content' => );
     *
     * @access  public
     * @param   $emails
     * @return  $result
     */
    public function send_forget_passwd_email($email_content)
    {
        $data = $this->db->get_where('member', array('token' => $this->token));
        // member_id array
        $member_id = array($data->row()->id,);       
        $result = $this->cmrdb_email->send_some_email($member_id, $email_content);
        $json = json_encode(array('member_id' => $member_id));
        if ($result) {
            $this->cmrdb_log->add_log('send forget passwd email', $json, TRUE);
        } 
        return $result;
    }

    public function change_forget_passwd($token, $new_passwd, $check_again)
    {
        if ($new_passwd == $check_again) {
            $this->db->where('token', $token);
            $result = $this->db->update('member', array('passwd' => md5($new_passwd),));
            $json = json_encode(array('token' => $token));
            if ($result) {
                $this->cmrdb_log->add_log('change forget passwd', $json, TRUE);
            }
            return $result;
        } else {
            return FALSE;
        }
    }

}

?>