<?php

class Customer
{
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
	public static function checkAuthWithCookie(){
		$result = false;
		
		if(isset($_COOKIE['user_id']) && isset($_COOKIE['cookie_hash'])){
			// получаем данные пользователя по id
			$link = getConnection();
			$sql = "SELECT user_id, user_name, user_password FROM user WHERE user_id = '".mysqli_real_escape_string($link, $_COOKIE['user_id'])."'";
			$user_data = getRowResult($sql, $link);
			
			if(($user_data['user_password'] !== $_COOKIE['user_hash']) || ($user_data['user_id'] !== $_COOKIE['user_id'])){
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
	public static function authWithCredentials(){
		$username = $_POST['login'];
		$password = $_POST['password'];
		
		$user_data = self::getInfoByName($username);
		
		$isAuth = 0;
		
		// проверяем соответствие логина и пароля
		if ($user_data) {
			if(checkPassword($password, $user_data['user_password'])){
				$isAuth = 1;
			}
		}
		
		// если стояла галка, то запоминаем пользователя на сутки
		if(isset($_POST['rememberme']) && $_POST['rememberme'] == 'on'){
			setcookie("user_id", $user_data['user_id'], time()+86400);
			setcookie("cookie_hash", $user_data['user_password'], time()+86400);
		}
		
		// сохраним данные в сессию
		$_SESSION['user'] = $user_data;
		
		return $isAuth;
	}
	
	public static function hashPassword($password)
	{
		$salt = md5(uniqid(SALT2, true));
		$salt = substr(strtr(base64_encode($salt), '+', '.'), 0, 22);
		return crypt($password, '$2a$08$' . $salt);
	}
	
	/**
	 * Сверяем введённый пароль и хэш
	 * @param $password
	 * @param $hash
	 * @return bool
	 */
	public static function checkPassword($password, $hash){
		return crypt($password, $hash) === $hash;
	}
	
	public static function alreadyLoggedIn(){
		return isset($_SESSION['user']);
	}
	
	
	public static function getInfoByName($user_name) {
		
		$sql = Sql::user_get_info_by_name();
		
		// Build the parameters array
		$params = array (':name' => $user_name);
		
		// Execute the query
		DatabaseHandler::GetRow($sql, $params);
	}
	
	public static function getIdByLogin($login) {
		
		$sql = Sql::user_get_id_by_name();
		
		$params = array (':login' => $login);
		
		// Execute the query
		DatabaseHandler::GetOne($sql, $params);
	}
	
	
	public static function register($name, $login, $password) {
		$sql = Sql::user_register();
		
		$params = array (':login' => $login, ':name' => $name, ':password' => $password);
		
		DatabaseHandler::Execute($sql, $params);
	}

		 
}
?>
