<?php

class Password
{

	// Retrieves the list of products on catalog page
	public static function hash($password)
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
	public static function check($password, $hash){
	    return crypt($password, $hash) === $hash;
	}
}