<?php
require_once('../../includes/config.php');

if (!$user->isLoggedIn()) {
    header('Location: ' .  $WEB_ROOT . 'login.php');
}

// tell db to remove post using key=postID
if (isset($_GET['key'])) {
    $db->removePost($_GET['key']);
    header('Location: posts.php');
}
