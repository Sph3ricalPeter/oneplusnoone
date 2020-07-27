<?php
require_once('../../../includes/config.php');

if (!$user->isLoggedIn()) {
  header('Location: ' .  $WEB_ROOT . 'login.php');
}

// remove user from db using key=memberID
if (isset($_GET['key'])) {
  $db->removeUser($_GET['key']);
  header('Location: users.php');
  exit;
}
