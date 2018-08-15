<?php 
header('Content-Type: text/html; charset=utf-8');
class Page {
	
	public $mH1 = "Страница обучения";
	public $mTitle = "PHP базовый курс - выполняем задание";
	public $mCss = '<link href="/css/main.css" rel="stylesheet">';
	public $mJs = '<script type="text/javascript" src="/js/main.js"></script>';
	public $mJsAfter = "";
	
	public $mName = "index";
	public $mVars = [];
	
	public $mHeader = "Header with our beautiful logo";
	public $mMenu = "Меню";
	public $mContent = "Контент";
	public $mFooter = "Футер";
	
	public function __construct() {
		
		$url_array = explode("/", $_SERVER['REQUEST_URI']);
		
		if ($url_array[1] != "")
			$this->mName = $url_array[1];
			
		switch ($this->mName){
			case "news":

				$this->mVars["newsfeed"] = getNews();
				$this->mVars["test"] = "������!";
				$this->mH1 = '�������� ��������';
				$this->mTitle = '�������� ��������';
				break;
			case "newspage":

				$content = getNewsContent($_GET['id_news']);
				$this->mVars["page_title"] = $content["news_title"];
				$this->mVars["page_h1"] = $content["news_title"];
				
				$this->mVars["news_content"] = $content["news_content"];
				
				break;
				
			case "delete":

				$idx=$_GET["id_news"];
				delNews($idx);
				header("location: /news/");
				
				break;
		}
		
	}
	
	function render()
	{
		$page = new Template("site");
		$content = new Template($this->mName);
		
		$vars["header"] = $this->mHeader;
		$vars["title"] = $this->mTitle;
		$vars["h1"] = $this->mH1;
		$vars["year"] = date("Y", time());
		
		$vars["css"] = $this->mCss;
		$vars["js"] = $this->mJs;
		
		$vars["content"] = $content->render($this->mVars);
		
		$vars["menu"] = $this->mMenu;
		
		$template = new Template("site");
		
		return $template->render($vars);
	}
	
	
} 
?>