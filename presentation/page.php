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
				$this->mVars["test"] = "Тест!";
				$this->mH1 = 'Страница самых последних новостей';
				$this->mTitle = 'Новости - самые актуальные';
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
			case "calc":
				$this->mH1 = 'Калькулятор';
				$this->mTitle = 'Онлайн калькулятор';
		}
		
	}
	
	function render()
	{
		
		$vars["header"] = $this->mHeader;
		$vars["title"] = $this->mTitle;
		$vars["h1"] = $this->mH1;
		$vars["year"] = date("Y", time());
		
		$vars["css"] = $this->mCss;
		$vars["js"] = $this->mJs;
		
		$vars["content"] = Template::render($this->mName, $this->mVars);
		
		
		$menu_vars["menucontent"] = [
				["MENU_ACTIVE" => '', "MENU_LINK" => Link::Build("") , "MENU_NAME" => "Главная"],
				["MENU_ACTIVE" => '', "MENU_LINK" => Link::ToNews() , "MENU_NAME" => "Новости"],
				["MENU_ACTIVE" => '', "MENU_LINK" => Link::ToCalc() , "MENU_NAME" => "Калькулятор"],
		];
		
		$vars["menu"] = Template::render("menu", $menu_vars);
		
		return Template::render("site", $vars);
	}
	
	
} 
?>