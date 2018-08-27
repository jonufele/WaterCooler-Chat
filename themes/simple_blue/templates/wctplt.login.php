<?php

# ============================================================================
#                    LOGIN SCREEN
# ============================================================================

$templates['wcchat.login_screen'] = '
    <fieldset id="wc_login_screen">
    <legend>User Login</legend>
    {ERR}
    <form action="#wc_topic" method="POST">
        Name: <input type="text" name="cname" value="{USER_NAME_COOKIE}"> 
        <input type="submit" name="join" value="Login">
    </form>
    <div class="note">(If you can\'t login, you\'ll have to enable cookies!)</div>
</fieldset>';

?>