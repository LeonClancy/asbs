<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_function { 

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
    $this->load->library('session');
   }

/*
    $function = array( 'module_id'=>'1', 'function_en' => 'sdsadw', 'function_ch' => '-3-' );
    echo $this->cmrdb_function->add_function($function);
*/
    public function add_function($function)
    {        
        // 取得資料庫 function 裡 function_en 資料
       $result = $this->db->get_where('function', array('function_en' => $function['function_en']));

       $hash = md5($function['function_en'].$function['function_ch']);

       $module_id = $this->db->get_where('module', array('id' => $function['module_id']));

       if($module_id->num_rows() > 0)
       {      
        // 查詢資料庫有沒有相同名字 和 有無符號 有則回傳 1
        if($result->num_rows()  < 1 && preg_match('/^[ a-zA-Z0-9_-]*$/', $function['function_en']))
        {
            $function['hash'] = $hash;
                // 新增群組名稱至資料庫
            $new_function = $this->db->insert('function', $function);
            if($new_function){
                $this->cmrdb_competence->add_function($hash);
                return TRUE;
            } else{
                return FALSE;
            }    
            return TRUE;
        } else{
            return FALSE;
        }
        }
    }


/*
    echo $this->cmrdb_function->modify_function('1','zvczvdsf','23333332');
*/
    public function modify_function($function_id, $function_en, $function_ch)
    {
        // 取得資料庫 function 裡 function_en 資料
       $result = $this->db->get_where('function', array('function_en' => $function_en));

        // 查詢資料庫有沒有相同名字 和 有無符號 有則回傳 1
       if($result->num_rows()  < 1 && preg_match('/^[a-zA-Z0-9_-]*$/', $function_en))
       {
                // where function ID
        $this->db->where('id', $function_id);
                // 依 id 更新該筆資料
        $this->db->update('function', array('id' => $function_id, 
            'function_en' => $function_en,
            'function_ch' => $function_ch));
        return TRUE;
    } else{
       return FALSE;
   }
}


/*
    $function_id = array( 'id' => 1);
    echo $this->cmrdb_function->delete_function($function_id);
*/
    public function delete_function($function_id)
    {        
        // 查詢是否有該筆 function 資料
        $result = $this->db->get_where('function', array('id' => $function_id['id']));
        // 如有該筆資料則刪除
        if($result->num_rows() == 1)
        {
            $hash = $result->row()->hash;
            $result = $this ->db->delete('function', $function_id);
            if($result)
            {
                $this->cmrdb_competence->detele_function($hash);
                return TRUE;
            } else{
                return FALSE;
            }
            return TRUE; 
        } else{
                return FALSE;
        }
        
    }
}
?>