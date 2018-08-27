<?php

# ============================================================================
#                        JOIN
# ============================================================================

$templates['wcchat.join'] = '
<div id="wc_join">
    {JOIN}
</div>';

$templates['wcchat.join.inner'] = '
<div id="wc_pass_input">{PASSWORD_REQUIRED}</div>
<input type="submit" id="wc_join_bt" value="{MODE} as {USER_NAME}" onclick="wc_join_chat(\'{CALLER}\', \'join\', {REFRESH_DELAY});">{RECOVER}
{CUSER_LINK}';

$templates['wcchat.join.inner.mode.join'] = 'Join Chat';

$templates['wcchat.join.inner.mode.login'] = 'Login';

$templates['wcchat.join.cuser_link'] = '<div><a href="{CALLER}cuser=1&ret={RETURN_URL}">[Change User]</a></div>';

$templates['wcchat.join.password_required'] = '
<div id="wc_pass_req">
    <div id="wc_pass_err" class="error_msg"></div>
    <b>{USER_NAME}</b>: Password required: <input type="password" id="wc_login_pass" onkeypress="if(event.which == 13 || event.keyCode == 13) { document.getElementById(\'wc_join_bt\').click(); }">
</div>';

$templates['wcchat.join.recover'] = ' <input type="submit" id="wc_join_rec" value="Recover Account" onclick="wc_acc_rec(\'{CALLER}\');">';

?>