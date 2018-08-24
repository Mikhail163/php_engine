<?php
header("Content-tupe: text/html; charset=utf-8");

class Authorisation
{
  public $mAuthLevel = 0;
  public $mUser;
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
  		Customer::logout();
  		header('Location: ' . Link::Build());
  		exit();
  		
  	}

  	if (Customer::checkAuth()) {
  				
  		$this->mUser = Customer::getInfo();
  		$this->mAuthLevel = 1;
  		$this->mSubmitNames[1]['auth_submit1_value'] = $this->mUser['name'];		
  	}

  	
  }
  
  
  public function getLoginVars() {
  	
  	$vars['LOGINERROR'] = "";
  	$vars['LOGINCLASSERROR'] = " ";
  	$vars['PASSWORDCLASSERROR'] = " ";
  	$vars['LOGIN'] = '';
  	$vars['PASSWORD'] = '';
  	
  	
  	// Проверяем, нажали ли кнопку
  	if (isset($_POST['submitLogin'])) {
  		
  		$name = trim($_POST['name']);
  		$password = trim($_POST['password']);
  		
  		$remember = (isset($_POST['rememberme']) && $_POST['rememberme'] == 'on');
  		
  		$vars['LOGIN'] = $login;
  		
  		$vars['PASSWORD'] = $password;
  		
  		
  		if (empty($login)) {
  			$vars['LOGINERROR'] .= "укажите логин; ";
  			$vars['LOGINCLASSERROR'] = "input_form_error";
  		}
  		
  		if (empty($password)) {
  			$vars['LOGINERROR'] .= "укажите логин; ";
  			$vars['PASSWORDCLASSERROR'] = "input_form_error";
  		}

  		if (empty($vars['LOGINERROR'])) {
  			
  			// Проходим аутентификацию
  			$is_auth = Customer::authLoginPasswordRemember($login, $password, $remember);
  			
  			if ($is_auth == 1) {
  				// Перебрасываем на главную страницу
  				header('Location: ' . Link::Build());
  				exit();
  			}
  			else {
  				$vars['LOGINERROR'] .= "Логин или пароль не верный!";
  			}
  		}
  		
  	}
  	
  	return $vars;
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
  			$hash = Password::hash($password);
  			
  			// Регистрируем нашего пользователя
  			Customer::register($name, $login, $hash);
  			
  			// Проходим аутентификацию
  			Customer::authLoginPasswordRemember($login, $password);
  			
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
}
?>
