<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article extends MY_Controller {
	public function author($author = null,$offset = 0)
	{
		if($author == null){
			show_404("Author not found !");
			return true;
		}
		//�ޤJ model
		$this->load->model("UserModel");
		$this->load->model("ArticleModel");
	 	//���d�ߨϥΪ̬O�_�s�b
		$user = $this->UserModel->getUserByAccount($author);
		if($user == null){
			show_404("Author not found !");
		}
		$pageSize = 2;
	    $this->load->library('pagination');
	    $config['uri_segment'] = 4;
	    $config['base_url'] = site_url('/article/author/'.$author.'/');
	    //���o�`�ƶq
	    $config['total_rows'] = $this->ArticleModel->countArticlesByUserID($user->UserID);
	    $config['per_page'] = $pageSize;
		$this->load->library('pagination');
	    $this->pagination->initialize($config);
			
	    $results = $this->ArticleModel->getArticlesByUserID($user->UserID,$offset,$pageSize);
		$this->load->view('article_author',
			Array(
				"pageTitle" => "�o��t�� - ".$user->Account." ���峹�C��",
				"results" => $results,
				"user" => $user,
				"pageLinks" => $this->pagination->create_links()
			)
		);
	}
	public function post(){
		if (!isset($_SESSION["user"])){//�|���n�J�����n�J��
			redirect(site_url("/user/login")); //��^�n�J��
			return true;
		}
		$this->load->view('article_post',Array(
			"pageTitle" => "�o��t�� - �o��峹"
		));	
	}
	public function posting(){
		if (!isset($_SESSION["user"])){//�|���n�J�����n�J��
			redirect(site_url("/user/login")); //��^�n�J��
			return true;
		}
		$title = trim($this->input->post("title"));
		$content= trim($this->input->post("content"));
		
		if( $title =="" || $content =="" ){
			$this->load->view('article_post',Array(
				"pageTitle" => "�o��t�� - �o��峹",
				"errorMessage" => "Title or Content shouldn't be empty,please check!" ,
				"title" => $title,
				"content" => $content
			));
			return false;
		}
		$this->load->model("ArticleModel");
		$insertID = $this->ArticleModel->insert($_SESSION["user"]->UserID,$title,$content);  //�����s�W�ʧ@
		redirect(site_url("article/postSuccess/".$insertID));
	}	
	public function postSuccess($articleID){
		$this->load->view('article_success',Array(
				"pageTitle" => "�o��t�� - �峹�o���\",
				"articleID" => $articleID
		));
	}
	public function view($articleID = null){
		if($articleID == null){
			show_404("Article not found !");
			return true;
		}
		$this->load->model("ArticleModel");
		//��������ưʧ@
		$article = $this->ArticleModel->get($articleID);  
		if($article == null){
			show_404("Article not found !");
			return true;	
		}
		$this->load->view('article_view',Array(
			//�]�w�������D
			"pageTitle" => "�o��t�� - �峹 [".$article->Title."] ", 
			"article" => $article
		));
	}
	public function edit(){
		$this->load->view('article_edit');	
	}
}