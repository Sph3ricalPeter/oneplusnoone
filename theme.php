<?php
// theme switcher, operated by footer <select> element, sets cookie with theme setting (1 for theme, 0 for no theme) for 60 minutes
if (isset($_POST['theme'])) {
    if ($_POST['theme'] == 1) {
        setcookie('theme', '1', time() + 3600);
    } else {
        setcookie('theme', '0', time() + 3600);
    }
    header('Location: index.php');
}
