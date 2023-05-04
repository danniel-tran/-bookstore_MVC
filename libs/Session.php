<?php
class Session{
	
	//===== INIT ======
	public static function init(){	
		session_start();
	}
	
	//===== SET ======
	public static function set($key, $value){
		$_SESSION[$key] = $value;
	}
	
	//===== GET ======
	public static function get($key){
		if(isset($_SESSION[$key])) return $_SESSION[$key];
	}
	
	//===== DELETE ======
	public static function delete($key){
		if(isset($_SESSION[$key])) unset($_SESSION[$key]);
	}
	
	//===== DESTROY ======
	public static function destroy(){
		session_destroy();
	}
}

