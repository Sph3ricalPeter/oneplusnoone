<!-- This is a template for page footer used in most pages -->
<div class="footer">
    <table>
        <tr>
            <td>
                <p id="footer-copy">Made by <a href="https://github.com/Sph3ricalPeter">Peter J.</a> in 2019</p>
            </td>
        </tr>
        <tr>
            <td>
                <!-- theme stored in cookies, set in external theme.php file, operated by this select form -->
                <form action="<?php echo $WEB_ROOT; ?>theme.php" method="post">
                    <select name="theme" onchange="this.form.submit()">
                        <option value="0" <?php if ($_COOKIE['theme'] == '0') {
                                                echo 'selected';
                                            } ?>>no theme</option>
                        <option value="1" <?php if ($_COOKIE['theme'] == '1') {
                                                echo 'selected';
                                            } ?>>theme</option>
                    </select>
                    <input type="submit" value="update">
                </form>
            </td>
        </tr>
    </table>
</div>