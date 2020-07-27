<!-- This is a template for navigation bar present on most pages -->
<div class='fixed-top'>
    <noscript>
        <!-- JavaScript disabled shows red stripe above the navbar to inform the user -->
        <div class='error nojs'>
            <p>This website runs best with JavaScript enabled!</p>
        </div>
    </noscript>
    <div class='error'>
    </div>

    <!-- navbar starts in the "scroll" opaque version, and with JavaScript enabled is animated to be dynamic and reacts to scroll (updateNavbar function below) -->
    <div id="navbar" class="navbar navbar-scroll">
        <nav class="navbar-menu">
            <a href="<?php echo $WEB_ROOT; ?>">
                <img class="navbar-logo" src="<?php echo $WEB_ROOT; ?>assets/oneplusnoone_white.png" alt="The website's logo.">
            </a>
            <div class="navbar-right">
                <!-- fixed relative adresses based on $WEB_ROOT const defined in config.php -->
                <a href="<?php echo $WEB_ROOT; ?>manual.html">manual</a>
                <a href="<?php echo $WEB_ROOT; ?>documentation.html">documentation</a>
                <a href="<?php echo $WEB_ROOT; ?>" class="current">blog</a>
            </div>
        </nav>
        <nav class="navbar-login">
            <?php
            // users that are logged in will see different options based on their permission level
            if (!$user->isLoggedIn()) {
            ?>
                <!-- new users will only see the option sign in -->
                <a href="<?php echo $WEB_ROOT; ?>login.php">sign in</a>
            <?php
            } else {
            ?>
                <span class="login-dropdown-button">
                    <a><?php echo $_SESSION['username']; ?>&#x25BC</a>
                    <div id="login-dropdown-content" class="dropdown-content">
                        <?php if ($_SESSION['perms'] > 0) {
                            echo '<a href="' . $WEB_ROOT . 'user/editor/posts.php">posts</a>';
                        }
                        ?>
                        <?php if ($_SESSION['perms'] > 1) {
                            echo '<a href="' . $WEB_ROOT . 'user/editor/admin/users.php">users</a>';
                        }
                        ?>
                        <a href="<?php echo $WEB_ROOT; ?>user/logout.php">log out</a>
                    </div>
                </span>
            <?php
            }
            ?>
        </nav>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
</script>

<script>
    function updateNavbar() {
        var scroll = $(window).scrollTop();
        if (scroll > 100) {
            $('#navbar').addClass('navbar-scroll');
            $('#login-dropdown-content').addClass('dropdown-content-scroll');
        } else if (scroll < 100) {
            $('#navbar').removeClass('navbar-scroll');
            $('#login-dropdown-content').removeClass('dropdown-content-scroll');
        }
    }

    updateNavbar();
    $(document).ready(function() {
        updateNavbar();
        $(window).scroll(function() {
            updateNavbar();
        });
    });
</script>