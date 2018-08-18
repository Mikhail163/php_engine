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
}
?>
