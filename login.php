<?php
require('includes/config.php');

// login query if submit key exists
if (isset($_POST['submit'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // user class compares credentials against database using password_verify()
    if ($user->login($username, $password)) {
        header('Location: index.php');
        exit;
    } else {
        $err_msg = 'Wrong username or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>sign in | 1+NO1</title>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/normalize.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/main.css">
    <?php
    if (isset($_COOKIE['theme'])) {
        if ($_COOKIE['theme'] == 1) {
            echo '<link rel="stylesheet" href="' . $WEB_ROOT .  'style/theme.css">';
        }
    }
    ?>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/navbar.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/error.css">
</head>

<body>
    <?php require_once('includes/navbar.php'); ?>
    <main>
        <form id="login-form" action="" method="post" class="center-form">
            <h2>sign in to your account</h2>
            <a href='register.php'>Don't have an account yet?</a>
            <div id="msg-box">
                <?php
                if (isset($err_msg)) {
                    echo '<p class="embed-err">&#9888; ' . $err_msg . '</p>';
                }
                ?>
            </div>
            <!-- input fields dont lose data if error occurs -->
            <label>
                username *
                <br>
                <input id="username" type="text" name="username" value="<?php if (isset($err_msg)) {
                                                                            echo $_POST['username'];
                                                                        } ?>" autofocus />
            </label>
            <label>
                password *
                <br>
                <input id="password" type="password" name="password" value="" />
            </label>
            <input class="button-submit" type="submit" name="submit" value="sign in" />
        </form>
    </main>
    <?php require_once('includes/footer.php'); ?>
</body>

<!-- simple JS form validation -->
<script>
    var form = document.querySelector('#login-form');
    var msgEl = document.querySelector('#msg-box');

    // listen for submit on the form element
    form.addEventListener('submit', function(event) {
        var formValid = validate();
        if (!formValid) {
            event.preventDefault();
        }
    });

    // validate form fields
    function validate() {
        var username = document.querySelector('#username');
        var password = document.querySelector('#password');

        clearMsgs(); // clears errors 'live'
        var errElems = [];
        // funny regex for email format courtesy of someone on stackoverflow.com
        if (username.value.length < 1) {
            errElems.push(username);
            showErrMsg('&#9888;  Plase enter username!');
        }
        if (password.value.length < 1) {
            errElems.push(password);
            showErrMsg('&#9888;  Please enter password!');
        }

        // if any errors appeared, focus the first error field
        if (errElems.length > 0) {
            errElems[0].focus();
            return false;
        } else {
            return true;
        }
    }

    // print errors without reload
    function showErrMsg(text) {
        var errP = document.createElement('p');
        errP.className = 'embed-err';
        errP.innerHTML = text;
        msgEl.appendChild(errP);
    }

    function clearMsgs() {
        msgEl.innerHTML = '';
    }
</script>

</html>