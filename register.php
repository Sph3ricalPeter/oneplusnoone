<?php
require('includes/config.php');

// data submit from register from
if (isset($_POST['submit'])) {

    // extracts POST array into variables following the key: value -> $key = value rule
    extract($_POST);

    // checks for username duplicity
    if ($db->userExists($username)) {
        $err_msgs[] = 'An account with this username already exists!';
    }

    // simple credentials validation
    if ($username == '') {
        $err_msgs[] = 'Please enter a username.';
    } else if (strlen($username) > 20) {
        $err_msgs[] = 'Username is too long.';
    }
    if (!ctype_alpha($username)) {
        $err_msgs[] = 'Username can only contain letters.';
    }
    if ($password == '') {
        $err_msgs[] = 'Please enter the password.';
    }
    if ($passwordConfirm == '') {
        $err_msgs[] = 'Please confirm the password.';
    }
    if ($password != $passwordConfirm) {
        $err_msgs[] = 'Passwords do not match.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err_msgs[] = 'Please enter the email address.';
    }

    // proceed if no errors
    if (!isset($err_msgs)) {
        // encrypt password using the PHP bcrypt hash algo which includes salt
        $hashedpassword = password_hash($password, PASSWORD_BCRYPT);

        // add user record into database
        if ($db->addUser($email, $username, $hashedpassword)) {
            header('Location: login.php');
            exit;
        } else {
            $err_msgs[] = 'Database error.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>register | 1+NO1</title>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/normalize.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/main.css">
    <!-- add style if theme cookie value is 1 = yes theme -->
    <?php
    if (isset($_COOKIE['theme'])) {
        if ($_COOKIE['theme'] == 1) {
            echo '<link rel="stylesheet" href="' . $WEB_ROOT .  'style/theme.css">';
        }
    }
    ?>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/navbar.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/users.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/error.css">
</head>

<body>
    <?php require_once($FS_ROOT . 'includes/navbar.php'); ?>

    <main>
        <form id="register-form" action='' method='post' class='center-form'>
            <h2>register</h2>
            <a href='login.php'>Have an account already?</a>
            <div id="msg-box">
                <!-- print errors formatted within the form -->
                <?php
                if (isset($err_msgs)) {
                    foreach ($err_msgs as $err_msg) {
                        echo '<p class="embed-err">&#9888; ' . $err_msg . '</p>';
                    }
                }
                ?>
            </div>
            <!-- fields with a star are considered mandatory and cannot be left empty -->
            <label>
                email *
                <br>
                <!-- pattern regular expression -->
                <input pattern='^(([^<>()\[\]\\.,;:\s@" ]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$' id="email" type='text' name='email' value='<?php if (isset($err_msgs)) {
                                                                                                                                                                                                                                            echo $_POST['email'];
                                                                                                                                                                                                                                        } ?>' autofocus>
            </label>
            <label>
                username *
                <br>
                <input id="username" type='text' name='username' value='<?php if (isset($err_msgs)) {
                                                                            echo $_POST['username'];
                                                                        } ?>'></label>

            <label>
                password *
                <br>
                <input id="password" type='password' name='password' value='<?php if (isset($err_msgs)) {
                                                                                echo $_POST['password'];
                                                                            } ?>'></label>

            <label>
                confirm password *
                <br>
                <input id="passConf" type='password' name='passwordConfirm' value='<?php if (isset($err_msgs)) {
                                                                                        echo $_POST['passwordConfirm'];
                                                                                    } ?>'>
            </label>
            <input type='submit' name='submit' value='sign up' class='button-submit'>
        </form>
    </main>
    <?php require_once('includes/footer.php'); ?>
</body>

<!-- simple JS form validation -->
<script>
    var form = document.querySelector('#register-form');
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
        var email = document.querySelector('#email');
        var username = document.querySelector('#username');
        var password = document.querySelector('#password');
        var pswConf = document.querySelector('#passConf');

        clearMsgs(); // clears errors 'live'
        var errElems = [];
        // funny regex for email format courtesy of someone on stackoverflow.com
        var emailFormat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!email.value.match(emailFormat)) {
            errElems.push(email);
            showErrMsg('&#9888;  Email format is invalid!');
        }
        if (username.value.length < 4) {
            errElems.push(username);
            showErrMsg('&#9888;  Username needs to be more than 4 letters!');
        }
        if (password.value.length < 4) {
            errElems.push(password);
            showErrMsg('&#9888;  Password needs to be at least 4 characters!');
        }
        if (password.value != pswConf.value) {
            errElems.push(pswConf);
            showErrMsg('&#9888; Passwords dont match!');
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