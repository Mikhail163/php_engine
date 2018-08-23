<?php

class Customer
{
	public static $mUser;
	public static $mIsAuth;
	/**
	 * Инициализируем наш проект и строим структуру бд
	 */
	
	public static function Init() {
		$sql = Sql::create_user_table();
		
		DatabaseHandler::Execute($sql);
		
		$sql = Sql::create_role_table();
		
		DatabaseHandler::Execute($sql);
		
		$sql = Sql::create_user_role_table();
		
		DatabaseHandler::Execute($sql);
	}
	
	
	// блок функций авторизации
	/**
	 * валидация пользовательского куки
	 * @return bool
	 */
	public static function checkAuth(){
		
		
		if (!isset(self::$mIsAuth)) {
			
			$result = false;
			
			if (isset($_SESSION['user'])) {
				
				debug("Customer::checkAuth() user isset");
				
				$user_data = self::getInfoById($_SESSION['user']['user_id']);
				$user_data_session = $_SESSION['user'];
				
				if(($user_data['password'] === $user_data_session['password']) && ($user_data['user_id'] === $user_data_session['user_id'])){
					$result = true;
					
					debug("Customer::checkAuth() user isset: Пароли из сессии совпали");
					
					// Если куки существуют - то обновляем их, если все корректно
					if(isset($_COOKIE['user_id']) && isset($_COOKIE['cookie_hash']))
						if(($user_data['password'] === $_COOKIE['cookie_hash']) && ($user_data['user_id'] === $_COOKIE['user_id']))
							self::setCookie($user_data['user_id'], $user_data['password']);
				}
				
			}
			elseif(isset($_COOKIE['user_id']) && isset($_COOKIE['cookie_hash'])){

				
				// получаем данные пользователя по id
				$user_data = self::getInfoById($_COOKIE['user_id']);
				
				
				if(($user_data['password'] === $_COOKIE['cookie_hash']) && ($user_data['user_id'] === $_COOKIE['user_id'])){
					$result = true;
					$_SESSION['user'] = $user_data;
					
					//  Обновляем cookies
					self::setCookie($user_data['user_id'], $user_data['password']);
				}
				
				
			}
			
			
			self::$mIsAuth = $result;
		}

		
		
		
		return self::$mIsAuth;
	}
	
	public static function setCookie($user_id, $password) {
		setcookie("user_id", $user_id, time() + 3600*24*30*12, "/");
		setcookie("cookie_hash", $password, time() + 3600*24*30*12, "/");
	}
	
	public static function unsetCookie() {
		setcookie("user_id","",time()-3600,"/");
		setcookie("cookie_hash","",time()-3600,"/");
		setcookie("PHPSESSID","",time()-3600,"/");	
	}
	
	/**
	 * авторизация через логин и пароль
	 */
	public static function authWithCredentials(){
		$username = $_POST['login'];
		$password = $_POST['password'];
		$remember = (isset($_POST['rememberme']) && $_POST['rememberme'] == 'on');
		
		
		return self::authLoginPasswordRemember($username, $password, $remember);
	}
	
	public static function authLoginPasswordRemember($login, $password, $remember=true){
		
		$user_data = self::getInfoBylogin($login);
		
		$isAuth = 0;
		
		// проверяем соответствие логина и пароля
		if ($user_data) {
			if(self::checkPassword($password, $user_data['password'])){
				$isAuth = 1;
				
				// сохраним данные в сессию
				$_SESSION['user'] = $user_data;
				
				// если стояла галка, то запоминаем пользователя на сутки
				if($remember){
					setcookie("user_id", $user_data['user_id'], time()+86400);
					setcookie("cookie_hash", $user_data['password'], time()+86400);	
				}
			}
		}
		
		
		
		
		
		return $isAuth;
	}
	
	public static function hashPassword($password)
	{
		return Password::hash($password);
	}
	
	/**
	 * Сверяем введённый пароль и хэш
	 * @param $password
	 * @param $hash
	 * @return bool
	 */
	public static function checkPassword($password, $hash){
		return Password::check($password, $hash);
	}
	
	public static function alreadyLoggedIn(){
		return isset($_SESSION['user']);
	}
	
	
	public static function getInfoByName($name) {
		
		$sql = Sql::user_get_info_by_name();
		
		// Build the parameters array
		$params = array (':name' => $name);
		
		// Execute the query
		return DatabaseHandler::GetRow($sql, $params);
	}
	
	public static function getInfoById($id) {
		
		$sql = Sql::user_get_info_by_id();
		
		// Build the parameters array
		$params = array (':user_id' => $id);
		
		// Execute the query
		return DatabaseHandler::GetRow($sql, $params);
	}
	
	public static function getInfoByLogin($login) {
		
		$sql = Sql::user_get_info_by_login();
		
		// Build the parameters array
		$params = array (':login' => $login);
		
		// Execute the query
		return DatabaseHandler::GetRow($sql, $params);
	}
	
	public static function getIdByLogin($login) {
		
		$sql = Sql::user_get_id_by_login();
		
		//echo $sql;
		
		$params = array (':login' => $login);
		
		// Execute the query
		return DatabaseHandler::GetOne($sql, $params);
	}
	
	
	public static function register($name, $login, $password) {
		$sql = Sql::user_register();
		
		$params = array (':login' => $login, ':name' => $name, ':password' => $password);
		
		return DatabaseHandler::Execute($sql, $params);
	}

		 
	public static function getInfo() {
		
		if (!isset(self::$mUser)) {
			if (isset($_SESSION['user']))
				self::$mUser = $_SESSION['user'];
		}
		
		
		return self::$mUser;
	}
	
	public static function logout() {
		
		echo '<h1>Удаляем сессию нахрен</h1>';
		
		self::unsetCookie();
		
		
		/*
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
					);
		}*/
		
		$_SESSION = array(); //destroy all of the session variables
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
					);
		}
		
		

		session_unset();
		session_destroy();
		
		
	}
}
?>
