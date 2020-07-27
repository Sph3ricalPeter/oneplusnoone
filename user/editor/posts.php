<?php
require('../../includes/config.php');

if (!$user->isLoggedIn()) {
    header('Location: ' .  $WEB_ROOT . 'login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>posts | 1+NO1
    </title>
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/normalize.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/main.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/navbar.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/management.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/error.css">
    <?php
    if (isset($_COOKIE['theme'])) {
        if ($_COOKIE['theme'] == 1) {
            echo '<link rel="stylesheet" href="' . $WEB_ROOT . 'style/theme.css">';
        }
    }
    ?>
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
</script>

<script>
    // simple AJAX GET request to "delpost.php" with key=id as parameters, reloads the page on success
    function delpost(id, title) {
        if (confirm("Are you sure you want to delete '" + title + "'")) {
            $.ajax({
                type: 'GET',
                url: 'delpost.php',
                data: {
                    key: id
                },
                success: function() {
                    window.location.href = 'posts.php';
                }
            });
        }
    }
</script>

<body>
    <?php require_once($FS_ROOT . '/includes/navbar.php'); ?>
    <main>
        <div class="management-section">
            <h2>manage posts</h2>
            <?php
            // similar to index.php, get posts from wrapper in a pre-made array and just display them
            $posts = $db->getPosts();
            foreach ($posts as $post) {
            ?>
                <div class="row">
                    <div class="thumbnail">
                        <img src="<?php echo $WEB_ROOT . $post['postThumb']; ?>" alt="Post thumbnail.">
                    </div>
                    <div class="title">
                        <a href="<?php echo $WEB_ROOT; ?>viewpost.php?id=<?php echo $post['postID']; ?>">
                            <!-- cut the title so it doesnt go everywhere -->
                            <h3><?php echo htmlspecialchars(substr($post['postTitle'], 0, 20)) . '...'; ?></h3>
                        </a>
                    </div>
                    <div class="details">
                        <p><?php echo date('jS M Y', strtotime($post['postDate'])) . ' by ' . $post['username']; ?></p>
                    </div>
                    <div class="action">
                        <a href="editpost.php?id=<?php echo $post['postID']; ?>">Edit</a> |
                        <a class="js" href="javascript:delpost('<?php echo $post['postID']; ?>','<?php echo htmlspecialchars($post['postTitle']); ?>')">Delete JS</a> |
                        <a href="delpost.php?key=<?php echo $post['postID']; ?>">Delete</a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="center-content">
            <a class="main-a" href='addpost.php'>new post</a>
        </div>
    </main>
    <?php require_once($FS_ROOT . '/includes/footer.php'); ?>
</body>

</html>