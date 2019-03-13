<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cmrdb_article { 

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


    /** 
    * add_article
    *
    * 新增文章 
    *
    * 原始資料欄位
    * $origin = array( 'title' =>, 'date' =>, 'content' =>,  ) ;
    *
    * 擴充資料欄位
    * $others = arrat( '' =>, );
    *
    * hide 預設為 1, 即顯示文章; 0 則為隱藏
    *
    * @access public 
    * @param $member_in_grp
    * @return TRUE FALSE
    */
    public function add_article($origin)
    {

        $id = $this->session->userdata('id'); //user id
        $origin['author'] = $id; //取得使用者 name
        $origin['hide'] = 1; //預設顯示文章

        $data = $origin;
        $this->db->insert('article', $data);
        return TRUE;

    }

/*
    $artice_data = array(  'id' =>'2',
                'title' => '不要啦!',
                'type' => '一般',
                'date' => date("Y/m/d H:i:sa"),
                'author' => '翰',
                'content' => 'A討厭B',  );

        echo $this->cmrdb_article->modify_article($artice_data);
*/
    public function modify_article($artice_data)
    {
        $id = $this->session->userdata('id'); //user id
        $user_data = $this->db->get_where('member', array('id' => $id)); //取得資料庫 member 裡 id 資料

        $result = $this->db->where('id', $artice_data['id']); // 取得編輯文章 ID

        $artice_data['author'] = $user_data->row()->name; //取得使用者 name
        $artice_data['hide'] = 1; //預設顯示文章

        $old_data = $this->db->query('SELECT * FROM `article` WHERE `id`= "'.$artice_data['id'].'"' );//複製舊資料

        //取出舊資料
        foreach ($old_data->row() as $data ) {
            $array_data[] = $data;
        }
        
        // 取出欄位資料
        foreach ($old_data->list_fields() as $table) {
            $table_fields[] = $table;
        }
        
        $table = array(); // 集合陣列宣告
        for ($i=0; $i < count($table_fields) ; $i++) { 
            $table[$table_fields[$i]] =  urlencode($array_data[$i]);
        }
        $json_data =  json_encode($table);


        if( $artice_data['title'] != $old_data->row()->title OR
            $artice_data['type'] != $old_data->row()->type OR
            $artice_data['active_time'] != $old_data->row()->active_time OR
            $artice_data['location'] != $old_data->row()->location OR
            $artice_data['introduction'] != $old_data->row()->introduction OR
            $artice_data['content'] != $old_data->row()->content)
        {

            $data = array(

            'article_id' => $old_data->row()->id,
            'old_data' => $json_data );

            $query = $this->db->get_where('modify_article_data', array('article_id'=>$artice_data['id']));  // 一開始筆數
            $this->db->insert('modify_article_data', $data);
            $query2 = $this->db->get_where('modify_article_data', array('article_id'=>$artice_data['id'])); // 新增後筆數
            // 新增筆數後 > 一開始筆數 確定有複製成功 再覆蓋新資料
            if( $query2->num_rows() > $query->num_rows() )
            {
                $this->db->where('id', $artice_data['id']);
                $this->db->update('article', $artice_data);
                return TRUE;
            } else{
                return FALSE;
            }
        } else{
            return FALSE;
        }        

    }

    /** 
    * delete_article
    *
    * 刪除文章 
    *
    * 文章 id
    * $article_id = array( 'id' => $  ) ;
    *
    * @access public 
    * @param $member_in_grp
    * @return TRUE FALSE
    */

/*
    $article_id = array(  'id' =>'4',);
     echo $this->cmrdb_article->delete_article($article_id);
*/
    public function delete_article($article_id)
    {
        $result = $this->db->get_where('article',array('id'=>$article_id['id']));
        if($result->num_rows() == 1){
            $result = $this ->db->delete('article', $article_id);
            return TRUE;
        } else{
            return FALSE;
        }
        
    }

    /** 
    * search_article
    *
    * 搜尋文章欄位
    *
    * 搜尋資料
    * $search_data 預設為NULL, 即拿取所有資料
    *
    * @access public 
    * @param $member_in_grp
    * @return TRUE FALSE
    */

/*
$search_data = '翰'; 
foreach ( $this->cmrdb_article->search_article($search_data)->result() as $key => $value) {
                echo $value->id;
}
*/
    public function search_article($search_data = NULL)
    {
        //若$id為NULL, 則拿取所有會員資料
        if ($search_data == NULL) { 
            $this->db->order_by("id", "desc"); 
            $result = $this->db->get('article');
        } else {
            $result = $this->db->query( "SELECT * FROM `article` WHERE `title` LIKE binary '%".$search_data."%' 
                                                                    OR `type` LIKE binary '%".$search_data."%'
                                                                    OR `author`LIKE binary '%".$search_data."%'
                                                                    OR `content` LIKE binary '%".$search_data."%'
                                                                    ORDER BY `id` desc" ); 
        }

        if($result->num_rows() > 0){
            return $result;
        } else {
            return FALSE;
        }

    }

    /** 
    * hide
    *
    * 隱藏文章 
    *
    * 文章 id
    * $article_id = array( 'id' => $  ) ;
    *
    * $hide 預設為 0
    *
    * @access public 
    * @param $member_in_grp
    * @return TRUE FALSE
    */

/*
echo $this->cmrdb_article->hide(2, 1);
*/
    public function hide($article_id, $hide = 0)
    {
        //將文章顯示

        if($hide == 1){
            $data = array('id' => $article_id, 'hide' => 1 );
            $result = $this->db->get_where('article', array('id' => $article_id));
            if ($result->num_rows() > 0) {
                $this->db->where('id', $article_id);
                $this->db->update('article', $data);
                return TRUE;
            } else {
                 return FALSE;
            }
           
        //將文章隱藏
        } else if($hide == 0){
            $data = array('id' => $article_id, 'hide' => 0);
            $result = $this->db->get_where('article', array('id' => $article_id));
            if ($result->num_rows() > 0) {
                $this->db->where('id', $article_id);
                $this->db->update('article', $data);
                return TRUE;
            } else {
                 return FALSE;
            }
        //hide值只能是 0 或 1
        } else{
               echo "Please enter number 0 or 1";
               return FALSE;
        }
    }
}

?>
