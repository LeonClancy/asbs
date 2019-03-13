<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_module { 

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

 function __construct()
 {	 
    $this->load->database();
    $this->load->library('cmrdb_competence');
    $this->load->library('session');
}

/*
    $module = array( 'module_en' => 'asdasd', 'module_ch' => '-3-' );
    echo $this->cmrdb_module->add_module($module);
*/
    public function add_module($module)
    {        
        // 取得資料庫 module_function 裡 module_en 資料
     $result = $this->db->get_where('module', array('module_en' => $module['module_en']));

     $hash = md5($module['module_en'].$module['module_ch']);
        // 查詢資料庫有沒有相同名字 和 有無符號 有則回傳 1
     if($result->num_rows()  < 1 && preg_match('/^[ a-zA-Z0-9_-]*$/', $module['module_en'])){
        $module['hash'] = $hash;
                // 新增群組名稱至資料庫
        $new_module = $this->db->insert('module', $module); 
        if($new_module){
            $this->cmrdb_competence->add_module($hash);
            return TRUE;
        } else{
            return FALSE;
        }   
        return TRUE;
    }else{
        return FALSE;
    }
}

/*
    $module_data = array( 'id' => 1 ,'module_en' => '21wq', 'module_ch' => '-3-');
    echo $this->cmrdb_module->modify_module($module_data);
*/
    public function modify_module($module_data)
    {
        // 取得資料庫 module_function 裡 module_en 資料
     $result = $this->db->get_where('module', array('module_en' => $module_data['module_en'])); 

        // 查詢資料庫有沒有相同名字 和 有無符號 有則回傳 1
     if($result->num_rows()  < 1 && preg_match('/^[a-zA-Z0-9_-]*$/', $module_data['module_en'])){
                // where module_function ID
        $this->db->where('id', $module_data['id']);
                // 依 id 更新該筆資料
        $this->db->update('module', $module_data);
        return TRUE;
    } else{
        return FALSE;
    }
}

/*
    $module_id = array( 'id' => 1);
    echo $this->cmrdb_module->delete_module($module_id);
*/
    public function delete_module($module_id)
    {        
        // 查詢是否有該筆 module_function 資料
        $result = $this->db->get_where('module', array('id' => $module_id['id'])); 
        // 如有該筆資料則刪除
        if($result->num_rows() == 1){
            $hash = $result->row()->hash;
            $result = $this ->db->delete('module', $module_id);
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

/*
    foreach ($this->cmrdb_module->all_module()->result() as $row) {
    echo $row->module_en.' ';
    echo $row->module_ch.'</br>';
 } 
*/
 public function all_module()
 {
    $all_module = $this->db->query('SELECT * FROM `module`');
    return $all_module;
}
}
?>