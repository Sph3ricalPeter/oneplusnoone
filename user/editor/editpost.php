<?php require('../../includes/config.php');

if (!$user->isLoggedIn()) {
    header('Location: ' .  $WEB_ROOT . 'login.php');
    exit;
}

// add post
if (isset($_POST['submit'])) {
    // strip all fields & extract into variables
    $_POST = array_map('stripslashes', $_POST);
    extract($_POST);

    // create thumbnail path
    $postThumb = $_FILES['postThumb'];
    $thumbName = $postThumb['name'];
    $thumbNameEnc = rawurlencode($thumbName); // encode to prevent whitespaces mainly
    $thumbUrl = $UPLOADS_URL . $thumbNameEnc;
    $thumbPath = realpath($FS_ROOT) . '/images/' . $thumbNameEnc;

    // validate fields
    if ($postTitle == '') {
        $err_msgs[] = 'Please enter the title.';
    } else if (strlen($postTitle) > 50) {
        $err_msgs[] = 'Post title is too long.';
    }
    if ($thumbName == '') {
        $err_msgs[] = 'Please select a thumbnail.';
    }
    if ($postCont == '') {
        $err_msgs[] = 'Please enter the content.';
    } else if (strlen($postCont) > 400) {
        $err_msgs[] = 'Post content is too long.';
    }

    if (!isset($err_msgs)) {
        // upload thumbnail to server (actually move it from tmp to the server directory)
        $file_uploaded = move_uploaded_file($_FILES["postThumb"]["tmp_name"], $thumbPath);

        // only make a database record if file was uploaded succesfully
        if ($file_uploaded) {
            if ($db->updatePost($postTitle, $_SESSION['memberID'], $thumbUrl, $postCont, $_GET['id'])) {
                header('Location: ' . $WEB_ROOT);
                exit;
            } else {
                // delete thumbnail if DB error
                unlink($thumbPath);
                $err_msgs[] = 'Database error when updating post.';
            }
        } else {
            $err_msgs[] = 'Failed to upload file.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>edit post | 1+NO1
    </title>
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
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/wideform.css">
    <link rel="stylesheet" href="<?php echo $WEB_ROOT; ?>style/error.css">
</head>

<body>
    <?php require_once($FS_ROOT . '/includes/navbar.php'); ?>
    <main>
        <div class="wideform-section">
            <h2>edit existing post</h2>
            <?php
            if (!$post = $db->getPost($_GET['id'])) {
                $err_msgs[] = 'Database error when fetching post data.';
            }
            ?>
            <div id="msg-box">
                <!-- print errors formatted -->
                <?php
                if (isset($err_msgs)) {
                    foreach ($err_msgs as $err_msg) {
                        echo '<p class="embed-err">&#9888; ' . $err_msg . '</p>';
                    }
                }
                ?>
            </div>
            <form id="postForm" class="wideform" action='' method='post' enctype="multipart/form-data">
                <label>
                    Title
                    <br>
                    <!-- if no errors, display data from database, if errors, display data from form submit -->
                    <input id="title" type='text' name='postTitle' value='<?php if (isset($err_msgs) && isset($_POST['postTitle'])) {
                                                                                echo htmlspecialchars($_POST['postTitle']);
                                                                            } else {
                                                                                if (isset($post)) {
                                                                                    echo htmlspecialchars($post['postTitle']);
                                                                                }
                                                                            } ?>'>
                </label>

                <label>
                    Thumbnail
                    <br>
                    <input id="thumb" type="file" name="postThumb">
                </label>
                <label>
                    Content
                    <br>
                    <textarea id="content" name='postCont'><?php if (isset($err_msgs) && isset($_POST['postCont'])) {
                                                                echo htmlspecialchars($_POST['postCont']);
                                                            } else {
                                                                if (isset($post)) {
                                                                    echo htmlspecialchars($post['postCont']);
                                                                }
                                                            } ?></textarea>
                </label>
                <input class="button-submit" type='submit' name='submit' value='post'>
            </form>
        </div>
    </main>
    <?php require_once($FS_ROOT . '/includes/footer.php'); ?>
</body>

<!-- simple JS form validation -->
<script>
    var form = document.querySelector('#postForm');
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
        var title = document.querySelector('#title');
        var thumb = document.querySelector('#thumb');
        var content = document.querySelector('#content');

        clearMsgs(); // clears errors 'live'
        var errElems = [];
        if (title.value.length < 1) {
            errElems.push(title);
            showErrMsg('&#9888;  Please fill in title!');
        } else if (title.value.length > 50) {
            errElems.push(title);
            showErrMsg('&#9888;  Title is too long!');
        }
        if (thumb.value.length < 1) {
            errElems.push(thumb);
            showErrMsg('&#9888;  Please select a thumbnail!');
        }
        if (content.value.length > 400) {
            errElems.push(content);
            showErrMsg('&#9888; Content is too long!');
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