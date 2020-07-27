<?php
// This file fetches post using postID and displays it the the user
require('includes/config.php');

// get post from database
$post = $db->getPost($_GET['id']);
if (!isset($post)) {
	// redirect if post wasn't retrieved for some reason
	header('Location: ' . $WEB_ROOT);
	exit;
}

// validate comment
if (isset($_POST['submit'])) {
	// strip & extract into variables
	$_POST = array_map('stripslashes', $_POST);
	extract($_POST);

	// prevent empty and long message
	if ($commentCont == '') {
		$err_msgs[] = 'Please enter a message.';
	}
	if (strlen($commentCont) > 100) {
		$err_msgs[] = 'The message can only have 100 characters!';
	}

	if (!isset($err_msgs)) {
		// tell wrapper to add post
		if ($db->addPostComment($_GET['id'], $_SESSION['memberID'], $commentCont)) {
			header('Location: viewpost.php?id=' . $_GET['id'] . '#comments');
			exit;
		}
	}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title><?php echo htmlspecialchars($post['postTitle']) ?> | 1+NO1
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
	<link rel="stylesheet" href="style/post.css">
	<link rel="stylesheet" href="style/error.css">
</head>

<body>
	<?php require_once('includes/navbar.php'); ?>
	<main>
		<!-- display retrieved post -->
		<div class="post">
			<!-- post-head background image is dynamic from PHP, so html style is the only option i could think of -->
			<div class="post-head" style="background-image: linear-gradient(to bottom left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 1)), url('<?php echo $post['postThumb']; ?>');">
				<h1><?php echo htmlspecialchars($post['postTitle']); ?></h1>
				<p>Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo date('jS M Y', strtotime($post['postDate'])); ?></p>
			</div>
			<div class="post-content">
				<p><?php echo htmlspecialchars($post['postCont']); ?></p>
			</div>
		</div>

		<!-- comments display -->
		<div id="comments" class="comments">
			<h2>comments</h2>
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

			<?php
			// query comments from DB using postID	
			$comments = $db->getPostComments($_GET['id']);
			foreach ($comments as $comment) {
			?>
				<table class="comment">
					<tr>
						<td class="comment-meta">
							<?php echo htmlspecialchars($comment['username']); ?> commented on <?php echo date('jS M Y', strtotime($comment['commentDate'])); ?>:
						</td>
						<td class="comment-cont">
							<?php echo htmlspecialchars($comment['commentCont']); ?>
						</td>
					</tr>
				</table>
			<?php
			}
			?>

			<?php
			// only allow users that are signed in to comment on the post
			if ($user->isLoggedIn()) {
			?>
				<!-- comment in -->
				<form id="commentForm" action='' method='post'>
					<label>
						write a comment
						<br>
						<textarea id="comContent" name='commentCont' cols='40' posts='6'></textarea>
						<input class="button-submit" type='submit' name='submit' value='comment' key="enter">
					</label>
				</form>
			<?php
				// for users who didn't sign in show simple message
			} else {
				echo 'Please sign in to comment.';
			}
			?>
		</div>
	</main>
	<?php require_once('includes/footer.php'); ?>
</body>

<!-- simple JS form validation -->
<script>
	var form = document.querySelector('#commentForm');
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
		var content = document.querySelector('#comContent');

		clearMsgs(); // clears errors 'live'
		var errElems = [];
		if (content.value.length < 1) {
			errElems.push(content);
			showErrMsg('&#9888; Comment is emtpy!');
		} else if (content.value.length > 100) {
			errElems.push(content);
			showErrMsg('&#9888; Comment content is too long!');
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