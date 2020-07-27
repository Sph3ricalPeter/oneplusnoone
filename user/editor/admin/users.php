<?php
require_once('../../../includes/config.php');

if (!$user->isLoggedIn()) {
    header('Location: ' .  $WEB_ROOT . 'login.php');
    exit;
}

// If permissions select field was submitted, update user permissions using memberID
if (isset($_POST)) {
    if (isset($_POST['perms'])) {
        $db->updateUserPerms($_POST['id'], $_POST['perms']);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>users | 1+NO1</title>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/normalize.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/main.css">
    <?php
    if (isset($_COOKIE['theme'])) {
        if ($_COOKIE['theme'] == 1) {
            echo '<link rel="stylesheet" href="' . $WEB_ROOT . 'style/theme.css">';
        }
    }
    ?>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/navbar.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/management.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/error.css">
</head>

<!-- AJAX import -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
</script>

<!-- AJAX user deletion using memberID as query key, shows prompt before deleting -->
<script>
    function deluser(id, title) {
        if (confirm("Are you sure you want to delete '" + title + "'")) {
            $.ajax({
                type: 'GET',
                url: 'deluser.php',
                data: {
                    key: id
                },
                success: function() {
                    window.location.href = 'users.php?deluser=' + id;
                }
            });
        }
    }
</script>

<body>
    <?php require_once($FS_ROOT . '/includes/navbar.php'); ?>
    <main>
        <div class="management-section">
            <h2>manage users</h2>
            <?php
            // query all users from DB
            $users = $db->getUsers();

            // display each user
            foreach ($users as $user) {
            ?>
                <!-- using form for user permissions change -->
                <form method="POST" action="">
                    <input name="id" type="hidden" value="<?php echo $user['memberID']; ?>">
                    <div class="row">
                        <div class="title">
                            <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                        </div>
                        <div class="details">
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="details">
                            <!-- simple onchange form submit, there's a submit button in 'action' div, in case JS is disabled -->
                            <select name="perms" onchange="this.form.submit()">
                                <option value="0" <?php if ($user['memberPerms'] == 0) {
                                                        echo 'selected';
                                                    } ?>>user</option>
                                <option value="1" <?php if ($user['memberPerms'] == 1) {
                                                        echo 'selected';
                                                    } ?>>editor</option>
                                <option value="2" <?php if ($user['memberPerms'] == 2) {
                                                        echo 'selected';
                                                    } ?>>admin</option>
                            </select>
                        </div>
                        <div class="action">
                            <?php if ($user['memberID'] != 1) { ?>
                                <a href="deluser.php?key=<?php echo $user['memberID']; ?>">Delete</a>
                                | <a href="javascript:deluser('<?php echo $user['memberID']; ?>','<?php echo $user['username']; ?>')">Delete JS</a>
                            <?php } ?>
                            | <input type="submit" value="update" class="button">
                        </div>
                    </div>
                </form>
            <?php
            }
            ?>
            <div class="center-content">
                <a class="main-a" href='<?php echo $WEB_ROOT; ?>register.php'>register another user</a>
            </div>
        </div>
    </main>
    <?php require_once($FS_ROOT . '/includes/footer.php'); ?>
</body>

</html>