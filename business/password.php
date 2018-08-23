<?php

class Password
{

	// Retrieves the list of products on catalog page
	public static function hash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	/**
	 * Сверяем введённый пароль и хэш
	 * @param $password
	 * @param $hash
	 * @return bool
	 */
	public static function check($password, $hash){
		return password_verify($password, $hash);
	}
}