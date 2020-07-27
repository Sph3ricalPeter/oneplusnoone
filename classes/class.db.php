<?php

/**
 * Simple database wrapper
 */
class Db
{
    /* database connection */
    private static $_con;

    /* constructor initiates database connection via PDO, port 3306, PDO::ERRMODE, PDO::ERRMODE_EXCEPTION */
    function __construct($dbHost, $dbUser, $dbPass, $dbName)
    {
        if (!isset(self::$_con)) {
            self::$_con = new PDO("mysql:host=" . DBHOST . ";port=3306;dbname=" . DBNAME, DBUSER, DBPASS);
            self::$_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * @return array with user info - memberID, username, memberPerms, hashedpassword
     */
    public static function getUserInfo($username)
    {
        try {
            $user_st = self::$_con->prepare('SELECT memberID, username, memberPerms, password FROM blog_members WHERE username = ?');
            $user_st->execute(array($username));

            return $user_st->fetch();
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    /**
     * checks if given username is already present in the database
     * @return true if username exists
     * @return false if username doesn't exist
     */
    public static function userExists($username)
    {
        try {
            $user_st = self::$_con->prepare('SELECT username FROM blog_members WHERE username = ?');
            $user_st->execute(array($username));

            return $user_st->fetch();
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    /* adds a user to the DB, storing email, username and hashed password */
    public static function addUser($email, $username, $hashedpassword)
    {
        try {
            $user_st = self::$_con->prepare('INSERT INTO blog_members (email,username,password) VALUES (:email, :username, :password)');
            return $user_st->execute(array(
                ':email' => $email,
                ':username' => $username,
                ':password' => $hashedpassword
            ));
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /* retrieves all users in an array of arrays */
    public static function getUsers()
    {
        try {
            $user_st = self::$_con->query('SELECT memberID, username, email, memberPerms FROM blog_members ORDER BY memberID DESC');

            $users = [];
            while ($user = $user_st->fetch()) {
                $users[] = $user;
            }

            return $users;
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function removeUser($id)
    {
        try {
            $post_st = self::$_con->prepare('DELETE FROM blog_members WHERE memberID = ?');
            return $post_st->execute(array($id));
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function updateUserPerms($id, $perms)
    {
        try {
            $user_st = self::$_con->prepare('UPDATE blog_members SET memberPerms = :memberPerms WHERE memberID = :id');
            return $user_st->execute(array(
                ':memberPerms' => $perms,
                ':id' => $id
            ));
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function getPosts()
    {
        try {
            $post_st = self::$_con->query('SELECT postID, postTitle, postThumb, postDate, postCont, IFNULL(username, "deleted user") as username FROM blog_posts LEFT OUTER JOIN blog_members ON blog_posts.authorID=blog_members.memberID ORDER BY postID DESC');

            $posts = [];
            while ($post = $post_st->fetch()) {
                $posts[] = $post;
            }

            return $posts;
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function getPost($id)
    {
        try {
            $post_st = self::$_con->prepare('SELECT postID, postTitle, postThumb, postDate, postCont, IFNULL(username, "deleted user") as username FROM blog_posts LEFT OUTER JOIN blog_members ON blog_posts.authorID=blog_members.memberID WHERE postID = ?');
            $post_st->execute(array($id));

            return $post_st->fetch();
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function removePost($id)
    {
        try {
            $post_st = self::$_con->prepare('DELETE FROM blog_posts WHERE postID = ?');
            return $post_st->execute(array($id));
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function addPost($postTitle, $memberID, $thumbUrl, $postCont)
    {
        try {
            $post_st = self::$_con->prepare('INSERT INTO blog_posts (postTitle,authorID,postThumb,postCont,postDate) VALUES (:postTitle, :authorID, :thumbUrl, :postCont, :postDate)');
            return $post_st->execute(array(
                ':postTitle' => $postTitle,
                ':authorID' => $memberID,
                ':thumbUrl' => $thumbUrl,
                ':postCont' => $postCont,
                ':postDate' => date('Y-m-d H:i:s')
            ));
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function updatePost($postTitle, $memberID, $thumbUrl, $postCont, $postID)
    {
        try {
            $post_st = self::$_con->prepare('UPDATE blog_posts SET postTitle = :postTitle, authorID = :authorID, postCont = :postCont, postThumb = :postThumb WHERE postID = :postID');
            return $post_st->execute(array(
                ':postTitle' => $postTitle,
                ':authorID' => $memberID,
                ':postCont' => $postCont,
                ':postThumb' => $thumbUrl,
                ':postID' => $postID
            ));
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function addPostComment($postId, $authorID, $commentCont)
    {
        try {
            $post_st = self::$_con->prepare('INSERT INTO blog_comments (postID, authorID, commentDate, commentCont) VALUES (:postID, :authorID, :commentDate, :commentCont)');
            return $post_st->execute(array(
                ':postID' => $postId,
                ':authorID' => $authorID,
                ':commentDate' => date('Y-m-d H:i:s'),
                ':commentCont' => $commentCont,
            ));
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }

    public static function getPostComments($postId)
    {
        try {
            $comment_st = self::$_con->prepare('SELECT commentID, postID, authorID, IFNULL(username, "deleted user") as username, commentDate, commentCont FROM blog_comments LEFT OUTER JOIN blog_members ON blog_comments.authorID=blog_members.memberID WHERE postID = ? ORDER BY commentID');
            $comment_st->execute(array($postId));

            $comments = [];
            while ($comment = $comment_st->fetch()) {
                $comments[] = $comment;
            }

            return $comments;
        } catch (PDOException $e) {
            echo '<p class="error">' . $e->getMessage() . '</p>';
        }
    }
}
