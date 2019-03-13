<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_competence { 

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
    $str = array( 'email' => 'boy28213@yahoo.com.tw');
    echo $this->cmrdb_competence->add_competence_model($str);
*/
    public function add_competence_model($email)
    {
        //取得 email
        $member_id = $this->db->get_where('member',array('email' => $email)); 
        //取得 competence->member_id 資料
        $competence_id = $this->db->get_where('competence',array('member_id'=>$member_id->row()->id));

        $group = $this->db->get('group');            //取得 group 資料
        $module = $this->db->get('module');          //取得 module 資料
        $function = $this->db->get('function');      //取得 function 資料

        $permission = array();
        $fun = '';
        $mod = '';

        //確定 member 有這筆 mail && competence 資料庫沒有該筆 mail 資料
        if($member_id->num_rows() > 0 && $competence_id->num_rows() < 1)
        {
            foreach ($group->result() as $group_hash) {
                foreach ($module->result() as $module_hash) {
                    foreach ($function->result() as $function_hash) { 
                        if($module_hash->id == $function_hash->module_id) {
                            $fun[$function_hash->hash] = 0;
                        }               
                    }
                    $mod[$module_hash->hash] = $fun; 
                    $fun = '';
                }
                $permission[$group_hash->hash] = $mod; 
            }
            //取 member id
            $member_id = $member_id->row()->id;
            $this->db->insert('competence', array('member_id' => $member_id,
              'permission' => json_encode($permission)));  
            return TRUE;
        } else{
            return FALSE;
        }
    }

    public function add_group($new_group)
    {
        $hash = $new_group;

        $competence_id = $this->db->get('competence');  // 取得 competence 資料庫資料

        $module = $this->db->get('module');          //取得 group 資料
        $function = $this->db->get('function');      //取得 function 資料

        $fun = '';
        $mod = '';

        foreach ($competence_id->result() as $row) {
            $competenceJson = (array)json_decode($row->permission);  //  (array)轉陣列 json轉集合陣列
            $moduleJson ='' ;
            foreach ($module->result() as $module_hash) {
                foreach ($function->result() as $function_hash) { 
                    if($module_hash->id == $function_hash->module_id) {
                        $fun[$function_hash->hash] = 0;
                    }               
                }
                $moduleJson[$module_hash->hash] = $fun; 
                $fun = '';
            }
            $competenceJson[$hash] = $moduleJson;

            $competenceJson = json_encode($competenceJson);
            $this->db->where('member_id', $row->member_id);
            $this->db->update('competence', array('permission' => $competenceJson));
        }
    }

    public function add_module($new_module)
    {
        $hash = $new_module;

        $competence_id = $this->db->get('competence');  // 取得 competence 資料庫資料

        foreach ($competence_id->result() as $row) {
            $competenceJson = (array)json_decode($row->permission);  //  (array)轉陣列 json轉集合陣列
            foreach ($competenceJson as $key => $group) {
                $group_array = (array)$group;          
                foreach ($group_array as $key1 => $value) {
                    $group_array[$hash] = "";
                }
                $competenceJson[$key] = $group_array;
            }
            $competenceJson = json_encode($competenceJson);
            $this->db->where('member_id', $row->member_id);
            $this->db->update('competence', array('permission' => $competenceJson));
        }
    }

    public function add_function($new_function)
    {
        $function_id = $this->db->get_where('function', array('hash' => $new_function));

        $hash = $new_function;

        $competence_id = $this->db->get('competence');  // 取得 competence 資料庫資料

        $group = $this->db->get('group');            //取得 group 資料
        $module = $this->db->get('module');          //取得 module 資料
        
        $new_module_function_id = $function_id->row()->module_id;
        $old_module_hash = $this->db->get_where('module', array('id' => $new_module_function_id));

        $function = $this->db->get('function');      //取得 function 資料

        $permission = array();
        $fun = '';
        $mod = '';

        foreach ($competence_id->result() as $row) {
            $competenceJson = (array)json_decode($row->permission);  //  (array)轉陣列 json轉集合陣列
            foreach ($competenceJson as $key => $group) {
                $group_array = (array)$group;
                foreach ($group_array as $key1 => $module_hash) {
                    if($key1 == $old_module_hash->row()->hash){
                        $function_hash = (array)$module_hash;
                        $function_hash[$hash] = 0;                        
                    } else{
                        $function_hash = (array)$module_hash;                          
                    }
                    $group_array[$key1] = $function_hash;
                    $function_hash = '';
                }
                $competenceJson[$key] = $group_array;
                
            }

            $competenceJson = json_encode($competenceJson);
            $this->db->where('member_id', $row->member_id);
            $this->db->update('competence', array('permission' => $competenceJson));
        }
    }

    public function delete_group($delete_group)
    {
        $hash = $delete_group;

        $competence_id = $this->db->get('competence');  // 取得 competence 資料庫資料

        foreach ($competence_id->result() as $row) {
            $competenceJson = json_decode($row->permission);
            unset($competenceJson->$hash);

            $competenceJson = json_encode($competenceJson);
            $this->db->where('member_id', $row->member_id);
            $this->db->update('competence', array('permission' => $competenceJson));
        }
    }

    public function detele_module($delete_module)
    {
        $hash = $delete_module;

        $competence_id = $this->db->get('competence');  // 取得 competence 資料庫資料

        foreach ($competence_id->result() as $row) {
            $competenceJson = json_decode($row->permission);
            foreach ($competenceJson as $key => $group) {
                unset($group->$hash);
            }

            $competenceJson = json_encode($competenceJson);
            $this->db->where('member_id', $row->member_id);
            $this->db->update('competence', array('permission' => $competenceJson));
        }
    }

    public function detele_function($delete_function)
    {
        $hash = $delete_function;

        $competence_id = $this->db->get('competence');  // 取得 competence 資料庫資料
        //走訪每筆資料
        foreach ($competence_id->result() as $row) {
            $competenceJson = json_decode($row->permission);            
            // 走訪group
            foreach ($competenceJson as $key => $group) {
                // 走訪module
                foreach ($group as $key1 => $value) {
                    // 走訪function
                    foreach ($value as $key2 => $module) {
                        unset($value->$hash);
                    }
                }
            }     
            $competenceJson = json_encode($competenceJson);
            $this->db->where('member_id', $row->member_id);
            $this->db->update('competence', array('permission' => $competenceJson));
        }
    }

    public function permission_data($id)
    {
        $member_id = $this->db->get_where('competence', array('member_id' => $id));
        $member_permission = $member_id->row()->permission;
        return $member_permission;
    }

}
?>