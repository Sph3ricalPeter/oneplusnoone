<?php

class User
{
	private static $_db;
	private $_displayName;

	function __construct($db)
	{
		self::$_db = $db;
		$this->_displayName = '';
	}

	public function isLoggedIn()
	{
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
			return true;
		}
	}

	public function getDisplayName()
	{
		return $this->_displayName;
	}

	public function login($username, $password)
	{
		$userInfo = self::$_db->getUserInfo($username);

		$loginSuccessful = false;
		if (password_verify($password, $userInfo['password']) == 1) {
			$this->display_name = $username;
			$_SESSION['loggedin'] = true;
			$_SESSION['memberID'] = $userInfo['memberID'];
			$_SESSION['username'] = $userInfo['username'];
			$_SESSION['perms'] = $userInfo['memberPerms'];
			$loginSuccessful = true;
		}

		return $loginSuccessful;
	}


	public function logout()
	{
		session_destroy();
	}
}
