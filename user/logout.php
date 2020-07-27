<?php
require_once('../includes/config.php');

// just tell user to log out and redirect to login
$user->logout();
header('Location: ' . $WEB_ROOT . 'login.php');
