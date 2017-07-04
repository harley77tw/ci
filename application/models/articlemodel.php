
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ArticleModel extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    function insert($author,$title,$content){
        $this->db->insert("article", 
            Array(
            "Author" =>  $author,
            "Title" => $title,
            "Content" => $content.".......",
            "Views" => 0,
        ));     
        return $this->db->insert_id() ;
    }    
    function get($articleID){
        $this->db->select("article.*,user.Account");
        $this->db->from('article');
        $this->db->join('user', 'article.author = user.userID', 'left');
        $this->db->where(Array("articleID" => $articleID));
        $query = $this->db->get();
        if ($query->num_rows() <= 0){
            return null; //�L��Ʈɦ^�� null
        }
        return $query->row();  //�^�ǲĤ@��
    }
    

    function countArticlesByUserID($userID){
    $this->db->select("count(articleID) as ArticleCount");
    $this->db->from('article');
    $this->db->where(Array("author" => $userID));
    $query = $this->db->get();

    if ($query->num_rows() <= 0){
    return null; //�L��Ʈɦ^�� null
    }
    return $query->row()->ArticleCount;
    }

    function getArticlesByUserID($userID,$offset = 0,$pageSize = 20){
    $this->db->select("article.*,user.Account");
    $this->db->from('article');
    $this->db->join('user', 'article.author = user.userID', 'left');
    $this->db->where(Array("author" => $userID));
    $this->db->limit($pageSize,$offset);
    $this->db->order_by("ArticleID","desc");//�Ѥj��p�Ƨ�
    $query = $this->db->get();

    return $query->result(); //�L��Ʈɦ^�� null
    }

}