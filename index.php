<?php
require('includes/config.php');

// look for theme cookie, if it hasn't been set yet, set it to default 0 (no theme) and reload
// also prevent another redirect if cookie cannot be set for some reason (IO error on w3 workaround)
if (!isset($_COOKIE['theme']) && !isset($_GET['cookieSet'])) {
    setcookie('theme', '0', time() + 3600);
    header('Location: index.php?cookieSet=true');
}

// query posts from database
$posts = $db->getPosts();

// pagination with 5 posts per page, info saved in $_GET
if (isset($_GET['pageNumber'])) {
    $pageNumber = $_GET['pageNumber'];
} else {
    // default page is 1st
    $pageNumber = 1;
}

$nRecordsPerPage = 5;
$offset = ($pageNumber - 1) * $nRecordsPerPage;

$postCount = sizeof($posts);
$pageCount = ceil($postCount / $nRecordsPerPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>blog | 1+NO1
    </title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
    <?php
    if (isset($_COOKIE['theme'])) {
        if ($_COOKIE['theme'] == 1) {
            echo '<link rel="stylesheet" href="' . $WEB_ROOT .  'style/theme.css">';
        }
    }
    ?>
    <link rel="stylesheet" href="style/navbar.css">
    <link rel="stylesheet" href="style/blog.css">
    <link rel="stylesheet" href="style/error.css">
</head>

<body>
    <?php require_once('includes/navbar.php'); ?>
    <main>
        <div class="latest-posts">

            <h2>latest</h2>
            <?php
            // Latest post is going to be first in the array (see class.db.php getPosts() method)
            // links lead to viewpost with the postID
            if (isset($posts[0])) {
            ?>
                <div class="post-large">
                    <a class="post-thumbnail" href="viewpost.php?id=<?php echo $posts[0]['postID']; ?>">
                        <img src="<?php echo $posts[0]['postThumb']; ?>" alt="Post thumbnail.">
                    </a>
                    <div class="post-content">
                        <h1><a href="viewpost.php?id=<?php echo $posts[0]['postID']; ?>"> <?php echo htmlspecialchars($posts[0]['postTitle']); ?></a></h1>
                        <p class="post-meta">Posted by <?php echo htmlspecialchars($posts[0]['username']); ?> on <?php echo date('jS M Y', strtotime($posts[0]['postDate'])); ?></p>
                        <div class="post-desc">
                            <p><?php echo htmlspecialchars(substr($posts[0]['postCont'], 0, 200)) . '...'; ?></p>
                        </div>
                        <a href="viewpost.php?id=<?php echo $posts[0]['postID']; ?>">
                            <div class="button-readmore">read more</div>
                        </a>
                    </div>
                </div>
            <?php
            } else {
                // inform if no posts were retrieved
                echo '<p>there are no posts to be shown...</p>';
            }
            ?>

        </div>

        <div class="all-posts">

            <h2 id="blog-pagination">everything</h2>
            <?php
            // start on page's offset, end after nRecordsPerPage, simple enough
            for ($i = $offset; $i < $offset + $nRecordsPerPage; $i++) {
                // prevent going out of array range
                if (!isset($posts[$i])) break;
            ?>
                <div class="post">
                    <a class="post-thumbnail" href="viewpost.php?id=<?php echo $posts[$i]['postID']; ?>">
                        <img src="<?php echo $posts[$i]['postThumb']; ?>" alt="Post thumbnail.">
                    </a>
                    <div class="post-content">
                        <h1><a href="viewpost.php?id=<?php echo $posts[$i]['postID']; ?>"> <?php echo htmlspecialchars($posts[$i]['postTitle']); ?></a></h1>
                        <p class="post-meta">Posted by <?php echo $posts[$i]['username']; ?> on <?php echo date('jS M Y', strtotime($posts[$i]['postDate'])); ?></p>
                        <div class="post-desc">
                            <p><?php echo htmlspecialchars(substr($posts[$i]['postCont'], 0, 100)) . '...'; ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <nav class="pagination">
                <!-- only show pages section of it makes sense -->
                <?php if ($pageCount > 0) {
                ?>
                    <span>pages:</span>
                <?php
                }
                ?>
                <?php
                // just create a menu with page number, highlight the current one using class .pagination-current
                for ($i = 1; $i <= $pageCount; $i++) {
                    if ($i == $pageNumber) {
                        echo '<a class="pagination-current" href="?pageNumber=' . $i . '#blog-pagination">' . $i . '</a>';
                    } else {
                        echo '<a href="?pageNumber=' . $i . '#blog-pagination">' . $i . '</a>';
                    }
                }
                ?>
            </nav>
        </div>
    </main>
    <?php require_once('includes/footer.php'); ?>
</body>

</html>