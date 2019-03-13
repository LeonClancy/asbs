<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_group { 

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
     * Initialize cmrdb_group module
     *
     * load database, session 
     *
     * @access  public
     * @param   
     * @return  none
     */

    function __construct()
    {    
        $this->load->database();
        $this->load->library('cmrdb_competence');
        $this->load->library('session');
    }

    /** 
    * Add_group 
    *
    * 新增群組 
    *
    * $group: 原始欄位 
    *
    * @access public 
    * @param $group 
    * @return TRUE FALSE
    */

/* 
I : Array, O : Boolean
$group = array( 'group_en' => 'qwdasew', 'group_ch' => '-3-', 'description' => '123' );
echo $this->cmrdb_group->add_group($group);
*/
     public function add_group($group)
     {        
        // 取得資料庫 group 裡 name_en 資料
       $result = $this->db->get_where('group', array('group_en'  => $group['group_en']));

       $hash = md5($group['group_en'].$group['group_ch']); 
      
        // 查詢資料庫有沒有相同名字 和 有無符號 有則回傳 1
        if($result->num_rows()  < 1 && preg_match('/^[ a-zA-Z0-9_-]*$/', $group['group_en'])){
                $group['hash'] = $hash;
                // 新增群組名稱至資料庫
                 $new_group = $this->db->insert('group', $group); 
                 if($new_group){
                    $this->cmrdb_competence->add_group($group['hash']);
                    return TRUE;
                } else{
                    return FALSE;
                }   
                 return TRUE;
        } else{
            return FALSE;
        }
     }

    /** 
    *
    * Modify_group
    *
    * 修改群組名稱 
    *
    * $group_data: 原始欄位 
    *
    * @access public 
    * @param $group_data 
    * @return TRUE FALSE
    */

/*
I : Array, O : Boolean
$group = array( 'id' => 2 ,'name_en' => '21wq', 'name_ch' => '-3-', 'description' => '123' );
$this->cmrdb_group->modify_group($group);
*/


     public function modify_group($group_data)
     {
        // 取得資料庫 group 裡 name_en 資料
       $result = $this->db->get_where('group', array('group_en'  => $group_data['group_en'])); 
        // 查詢資料庫有沒有相同名字 和 有無符號 有則回傳 1
                // where group ID
                $this->db->where('id', $group_data['id']);
                // 依 id 更新該筆資料
                $query = $this->db->update('group', $group_data);
                if($query) {
                    return TRUE;
                }else{
                    return FALSE;
                }
                
        
    }

    /** 
    * Delete_group
    *
    * 刪除群組 
    *
    * $group_id: 原始欄位 
    *
    * @access public 
    * @param $group_id 
    * @return TRUE FALSE
    */

/*
I : Array, O : Boolean
$group = array( 'id' => 2);
$this->cmrdb_group->delete_group($group);
*/
     public function delete_group($group_id)
     {        
        // 查詢是否有該筆 group 資料
        $result = $this->db->get_where('group', array('id'  => $group_id['id'])); 

        // 如有該筆資料則刪除
        if($result->num_rows() == 1){
            $hash = $result->row()->hash;
            $result = $this ->db->delete('group', $group_id);
            if($result){
                $this->cmrdb_competence->detele_module($hash);
                return TRUE;
            } else{
                return FALSE;
            }
            return TRUE;
        } else{
            return FALSE;
        }
     }

    /** 
    * Search_group
    *
    * 搜尋群組 
    *
    * $group_name: 原始欄位 
    *
    * @access public 
    * @param $group_name 
    * @return TRUE FALSE
    */

/*
I : String, O : CIObject/Boolean
foreach ($this->cmrdb_group->search_group('s')->result() as $key => $value) {
            echo $value->id;
        }
*/
     public function search_group($search_data = NULL)
     {
        //若$id為NULL, 則拿取所有群組資料
        if ($search_data == NULL) { 
            $result = $this->db->get('group');
        } else{
            // 找 group 裡 name_en and id 欄位把查詢相同的撈出來
            $result = $this->db->query( "SELECT * FROM `group` WHERE `group_en` LIKE binary '%".$search_data."%' 
                                                                                                          OR `group_ch` LIKE binary '%".$search_data."%'" );
            // $result = $this->db->get_where('group', array('name_en' => $group_name_en['name_en']));         
        }
        if($result->num_rows() > 0)
        {
            return $result;
        } else{
            return FALSE;
        }   
     }

    /** 
    * Add_member_group
    *
    * 新增會員至群組 
    *
    * $member_in_grp: 原始欄位 
    *
    * $permission: 預設欄位
    *
    * @access public 
    * @param $member_in_grp,  $permission
    * @return TRUE FALSE
    */

/*
I : Array & String || NULL, O : Boolean
$member_in_grp = array('member_id' => 1, 'grp_id' => 3);
$this->cmrdb_group->add_member_group($member_in_grp, 'SSS');
*/
     public function add_member_group($member_in_grp, $permission = NULL)
     {
        // 判斷 member_in_grp 會員id 有沒有加入過 grp_id 
        $result = $this->db->query("SELECT * FROM `member_in_grp` where `member_id` = ".$member_in_grp['member_id']."
                                                                                                                   and `grp_id` = ".$member_in_grp['grp_id']."");
        // 如果沒加入過此群組則新增一筆資料
        if($result->num_rows() < 1){
             $this->db->insert('member_in_grp', $member_in_grp); 
             return TRUE;
        } else{
            return FALSE;
        }           
     }

    /** 
    * Add_member_group
    *
    * 刪除群組中的會員 
    *
    * $member_in_grp: 原始欄位 
    *
    * @access public 
    * @param $member_in_grp
    * @return TRUE FALSE
    */

/*
I : int, O : Boolean
$this->cmrdb_group->delete_member_group(4);
*/
     public function delete_member_group($member_in_grp)
     {           
           // 查詢是否有該筆資料
           $result = $this->db->query("SELECT * FROM `member_in_grp` where `member_id` = ".$member_in_grp['member_id']."
                                                                                                                   and `grp_id` = ".$member_in_grp['grp_id']);

            // 如果有該筆資料則刪除
            if($result->num_rows() ==1){
                $result = $this ->db->delete('member_in_grp', array('member_id'=>$member_in_grp['member_id'], 'grp_id'=>$member_in_grp['grp_id']));
                return TRUE;
            } else{
                return FALSE;
            }
     }

     public function get_group_member($grp_id)
     {           
           // 查詢是否有該筆資料
           $result = $this->db->query("SELECT * FROM `member_in_grp` where `grp_id` = ".$grp_id);

            // 如果有該筆資料則刪除
            if($result->num_rows() > 0){
                return $result;
            } else{
                return FALSE;
            }
     }
     public function get_member_in_group($member_id, $grp_id)
     {           
           // 查詢是否有該筆資料
           $result = $this->db->query("SELECT * FROM `member_in_grp` where `member_id` = ".$member_id." AND `grp_id` = ".$grp_id);

            // 如果有該筆資料則刪除
            if($result->num_rows() > 0){
                return $result;
            } else{
                return FALSE;
            }
     }
     public function get_group_data($group_en)
     {           
           // 查詢是否有該筆資料
           $result = $this->db->query('SELECT * FROM `group` where `group_en` = "'.$group_en.'"');

            // 如果有該筆資料則刪除
            if($result->num_rows() > 0){
                return $result;
            } else{
                return FALSE;
            }
     }
     public function get_group_data_by_id($id)
     {           
           $result = $this->db->query('SELECT * FROM `group` where `id` = "'.$id.'"');
            if($result->num_rows() > 0){
                return $result;
            } else{
                return FALSE;
            }
     }
}
        
?>