<?php require('../../includes/config.php');

if (!$user->isLoggedIn()) {
    header('Location: ' .  $WEB_ROOT . 'login.php');
    exit;
}

// add post
if (isset($_POST['submit'])) {
    // strip & extract into variables
    $_POST = array_map('stripslashes', $_POST);
    extract($_POST);

    // thumbnail path construct
    $postThumb = $_FILES['postThumb'];
    $thumbName = $postThumb['name'];
    $thumbNameEnc = rawurlencode($thumbName); // url encode to prevent whitespaces and such slipping through
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
    if (strlen($postCont) > 400) {
        $err_msgs[] = 'Post content is too long.';
    }

    // move thumbnail and create db record only if move was successful
    if (!isset($err_msgs)) {
        $file_uploaded = move_uploaded_file($_FILES["postThumb"]["tmp_name"], $thumbPath);

        if ($file_uploaded) {
            if ($db->addPost($postTitle, $_SESSION['memberID'], $thumbUrl, $postCont)) {
                header('Location: ' . $WEB_ROOT);
                exit;
            } else {
                $err_msgs[] = 'Database error.';
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
    <title>new post | 1+NO1
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
            <h2>write new post</h2>
            <div id="msg-box">
                <!-- print errors formatted  -->
                <?php
                if (isset($err_msgs)) {
                    foreach ($err_msgs as $err_msg) {
                        echo '<p class="embed-err">&#9888; ' . $err_msg . '</p>';
                    }
                }
                ?>
            </div>
            <!-- form remembers data if submit validation fails -->
            <form id="postForm" class="wideform" action='' method='post' enctype="multipart/form-data">
                <label>
                    Title *
                    <br>
                    <input id="title" type='text' name='postTitle' value='<?php if (isset($err_msgs)) {
                                                                                echo $_POST['postTitle'];
                                                                            } ?>'>
                </label>

                <label>
                    Thumbnail *
                    <br>
                    <input id="thumb" type="file" name="postThumb">
                </label>
                <label>
                    <!-- optional field, won't be validated on submit - YES its a FEATURE -->
                    Content (optional)
                    <br>
                    <textarea id="content" name='postCont'><?php if (isset($err_msgs)) {
                                                                echo $_POST['postCont'];
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