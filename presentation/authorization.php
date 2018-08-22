<?php
header("Content-tupe: text/html; charset=utf-8");

class Authorisation
{
  public $mAuthLevel = 0;
  public $mSubmitNames = [
  		[
  				'auth_submit1_value' => 'Вход', 
  				'auth_submit1_name' => 'auth_login', 
  				'auth_submit2_value' => 'Регистрация', 
  				'auth_submit2_name' => 'auth_registration'
  		],
  		[
  				'auth_submit1_value' => 'Unknown',
  				'auth_submit1_name' => 'auth_account',
  				'auth_submit2_value' => 'Выход',
  				'auth_submit2_name' => 'auth_logout'
  		]
  ];
  // Class constructor
  public function __construct()
  {
  	
  	if (isset($_POST['auth_login'])) {
  		header('Location: ' . Link::ToLogin()); 		
  		exit();
  	}
  	elseif (isset($_POST['auth_registration'])/* && !isset($_POST['submitRegistration'])*/) {
  		header('Location: ' . Link::ToRegistration());		
  		exit();
  	}
  	elseif (isset($_POST['auth_account'])) {
  		header('Location: ' . Link::ToAccount());		
  		exit();
  	}
  	elseif (isset($_POST['auth_logout'])) {
  		$this->mAuthLevel = 0;
  		$this->mSubmitNames[1]['auth_submit1_value'] = 'Unknown';
  	}
  }
  
  public function getRegistrationVars() {

  	$reg_vars['REGISTRERROR'] = "";
  	$reg_vars['LOGINCLASSERROR'] = " ";
  	$reg_vars['NAMECLASSERROR'] = " ";
  	$reg_vars['PASSWORDCLASSERROR'] = " ";
  	$reg_vars['PASSWORD2CLASSERROR'] = " ";
  	$reg_vars['LOGIN'] = '';
  	$reg_vars['NAME'] = '';
  	$reg_vars['PASSWORD'] = '';
  	$reg_vars['PASSWORD2'] = '';
  	
  	// Проверяем, нажали ли кнопку
  	if (isset($_POST['submitRegistration'])) {

  		$name = trim($_POST['name']);
  		$login = trim($_POST['login']);
  		$password = trim($_POST['password']);
  		$password2 = trim($_POST['password2']);
  		
  		$reg_vars['LOGIN'] = $login;
  		
  		echo "<h1>$login</h1>";
  		$reg_vars['NAME'] = $name;
  		$reg_vars['PASSWORD'] = $password;
  		$reg_vars['PASSWORD2'] = $password2;
  		
  		if (empty($name)) {
  			$reg_vars['REGISTRERROR'] .= "укажите имя; ";
  			$reg_vars['NAMECLASSERROR'] = "input_form_error";
  		}
  		
  		if (empty($login)) {
  			$reg_vars['REGISTRERROR'] .= "укажите логин; ";
  			$reg_vars['LOGINCLASSERROR'] = "input_form_error";
  		}
  		else {
  			$id = Customer::getIdByLogin($login);
  			
  			echo "<h1>$id</h1>";
  			
  			if (!empty($id))
  			{
  				$reg_vars['REGISTRERROR'] .= "логин занят; ";
  				$reg_vars['LOGINCLASSERROR'] = "input_form_error";
  			}
  		}
  		
  		if (empty($password)) {
  			$reg_vars['REGISTRERROR'] .= "укажите пароль; ";
  			$reg_vars['PASSWORDCLASSERROR'] = "input_form_error";
  		}
  		
  		if (empty($password2)) {
  			$reg_vars['REGISTRERROR'] .= "введите пароль повторно; ";
  			$reg_vars['PASSWORD2CLASSERROR'] = "input_form_error";
  			
  		}
  		else {
  			if (strcmp($password, $password2) !== 0) {
  				$reg_vars['PASSWORDCLASSERROR'] = "input_form_error";
  				$reg_vars['PASSWORD2CLASSERROR'] = "input_form_error";
  				$reg_vars['REGISTRERROR'] .= "пароли не совпадают; ";
  			}
  		}
  		
  		if (empty($reg_vars['REGISTRERROR'])) {
  			// Регистрируем нашего пользователя
  			Customer::register($name, $login, Password::hash($password));
  			
  			// Перебрасываем на главную страницу
  			header('Location: ' . Link::Build());
  			exit();
  		}
  			
  	}
  	
  	return $reg_vars;
  }
  
  public function render() {
  	return Template::render("auth", $this->mSubmitNames[$this->mAuthLevel]);
  }
  
  // блок функций авторизации
  /**
   * валидация пользовательского куки
   * @return bool
   */
  public function checkAuthWithCookie(){
  	$result = false;
  	
  	if(isset($_COOKIE['id_user']) && isset($_COOKIE['cookie_hash'])){
  		// получаем данные пользователя по id
  		$link = getConnection();
  		$sql = "SELECT id_user, user_name, user_password FROM user WHERE id_user = '".mysqli_real_escape_string($link, $_COOKIE['user_id'])."'";
  		$user_data = getRowResult($sql, $link);
  		
  		if(($user_data['user_password'] !== $_COOKIE['user_hash']) || ($user_data['id_user'] !== $_COOKIE['id_user'])){
  			setcookie("id", "", time() - 3600*24*30*12, "/");
  			setcookie("hash", "", time() - 3600*24*30*12, "/");
  		}
  		else{
  			header("Location: /");
  		}
  		
  	}
  	
  	return $result;
  }
  
  /**
   * авторизация через логин и пароль
   */
  public function authWithCredentials(){
  	$username = $_POST['login'];
  	$password = $_POST['password'];
  	
  	// получаем данные пользователя по логину
  	$link = getConnection();
  	$sql = "SELECT id_user, user_name, user_password FROM user WHERE user_login = '".mysqli_real_escape_string($link, $username)."'";
  	$user_data = getRowResult($sql, $link);
  	
  	$isAuth = 0;
  	
  	// проверяем соответствие логина и пароля
  	if ($user_data) {
  		if(checkPassword($password, $user_data['user_password'])){
  			$isAuth = 1;
  		}
  	}
  	
  	// если стояла галка, то запоминаем пользователя на сутки
  	if(isset($_POST['rememberme']) && $_POST['rememberme'] == 'on'){
  		setcookie("id_user", $user_data['id_user'], time()+86400);
  		setcookie("cookie_hash", $user_data['user_password'], time()+86400);
  	}
  	
  	// сохраним данные в сессию
  	$_SESSION['user'] = $user_data;
  	
  	return $isAuth;
  }
}
?>
