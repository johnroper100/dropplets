<?php
class User {

	private $logged_in = false;

	public function __construct($pass = false){
		if($pass){
			$this->log_in($pass);
		}else{
			$this->check_user_cookie();
		}
	}

	public function log_in( $pass ){
		global $password;
		if($password == self::hash_password($pass)){
			$this->logged_in = true;
			$this->set_user_cookie();
		}
		return $this->logged_in;
	}

	public function log_out(){
		$logged_in = false;
		setcookie('dropplets-auth-cookie', '', time() - 3600);
		header('Location: '.URL);
	}

	public static function hash_password($password, $hash_string = false){
		if( false === $hash_string)
			return crypt($password, '$2a$13$' . HASH_STRING . '$');
		else
			return crypt($password, '$2a$13$' . $hash_string . '$');
	}

	public function is_logged_in(){
		return $this->logged_in;
	}

	public function set_user_cookie(){
		setcookie('dropplets-auth-cookie', true);
	}

	public function check_user_cookie(){

		if(!isset($_COOKIE['dropplets-auth-cookie']))
			return false;
		$this->logged_in = true;

	}

}