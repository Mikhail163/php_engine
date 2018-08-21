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
  	elseif (isset($_POST['auth_registration'])) {
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
