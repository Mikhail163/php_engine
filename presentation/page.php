<?php 
header('Content-Type: text/html; charset=utf-8');

require_once(PRESENTATION_DIR. '/adminproducts.php');
require_once(PRESENTATION_DIR. '/adminproduct.php');
require_once(PRESENTATION_DIR. '/authorization.php');

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
		
			$auth = new Authorisation;
			
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
				
				break;
				
			case "adminproducts":
				
				$ap = new AdminProducts;
				$error = $ap->init();
				$this->mVars["error"] = empty($error)?" ":$error;
				$this->mH1 = 'Администрирование товаров на сайте';
				$this->mTitle = 'Администрирование товаров на сайте';
				$this->mVars["product"] = Catalog::GetProductsForAdmin();
					
				break;
			case "initdb":

				$this->mH1 = 'Страница инициализации базы данных';
				$this->mTitle = 'Страница инициализации базы данных';
				
				init_db();
				
				break;
			case "adminproduct":
				
				
				$ap = new AdminProduct();
				$error = $ap->init();
				$this->mVars["error"] = empty($error)?" ":$error;
				$this->mVars["link_to_admin_products"] = Link::ToAdminProducts();
				
				$pr_info = $ap->getInfo();
				foreach ($pr_info as $key => $value) {
					$this->mVars[$key] = empty($value)?" ":$value;
				}
				
				$this->mH1 = 'Страница редактирования товаров';
				$this->mTitle = 'Страница редактирования товаров';
				
				
				break;
			case "login":

				$this->mH1 = 'Вход в личный кабинет';
				$this->mTitle = 'Страница входа в личный кабинет';
				
				// если уже залогинен, то выбрасываем на главную
				if(Customer::alreadyLoggedIn()){
					header("Location: /");
				}
				
				// если есть куки, то авторизуем сразу
				if(Customer::checkAuthWithCookie()){
					header("Location: /");
				}
				// иначе пробуем авторизовать по логину и паролю
				else{
					$vars["autherror"] = "";
					if ($_SERVER['REQUEST_METHOD'] == 'POST'){
						if(!Customer::authWithCredentials()){
							$vars["autherror"] = "Неправильный логин/пароль";
						}
						else{
							header("Location: /");
						}
					}
					
					
					$vars["username"] = "admin";
					$vars["password"] = Customer::hashPassword("12345");
				}
				
				
				break;
			case "account":
				
				$this->mH1 = 'Личный кабинет';
				$this->mTitle = 'Личный кабинет';
				
				
				break;
			case "registration":
				
				$this->mH1 = 'Регистрация нового пользователя';
				$this->mTitle = 'Регистрация нового пользователя';
				
				
				
				$reg_info = $auth->getRegistrationVars();
				foreach ($reg_info as $key => $value) {
					$this->mVars[$key] = empty($value)?" ":$value;
				}
				
				break;
		}
		
	}
	
	function render()
	{
		$auth = new Authorisation;
		$vars["header"] = Template::render("header", ['customer_login' => $auth->render()]);
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
				["MENU_ACTIVE" => '', "MENU_LINK" => Link::ToAdminProducts() , "MENU_NAME" => "Редактор товаров (CRUD блок)"],
				["MENU_ACTIVE" => '', "MENU_LINK" => Link::ToInitDb() , "MENU_NAME" => "Инициализация базы данных"]
		];
		
		$vars["menu"] = Template::render("menu", $menu_vars);
		
		return Template::render("site", $vars);
	}
	
	
} 
?>