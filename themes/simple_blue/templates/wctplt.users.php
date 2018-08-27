<?php

# ============================================================================
#                     USER LIST
# ============================================================================

$templates['wcchat.users'] = '
<div id="wc_ulist">
    {ULIST}
</div>';

$templates['wcchat.users.inner'] = '
<div class="separator">JOINED / ONLINE</div>
{JOINED}
{OFFLINE}
{GUESTS}';

$templates['wcchat.users.joined'] = '{USERS}';


$templates['wcchat.users.item'] = '
<div class="user_item{PM_CLASS}" {NAME_STYLE} id="wc_user_{ID}">
        {NEW_MSG}<img src="{AVATAR}" {WIDTH} class="{JOINED_CLASS}" title="{STATUS_TITLE}">
        {PM_S}{NAME}{PM_E}{MOD_ICON}{MUTED_ICON}{LINK}{IDLE}{EDIT_BT}
</div>
{EDIT_FORM}';

$templates['wcchat.users.item.new_msg.on'] = '{PM_S}<img src="{INCLUDE_DIR_THEME}images/nmsg.png" title="Private conversation has new messages" class="new_msg_icon">{PM_E}';

$templates['wcchat.users.item.new_msg.off'] = '{PM_S}<img src="{INCLUDE_DIR_THEME}images/nmsg_off_small.png" title="Private conversation has no new messages" class="new_msg_icon_small">{PM_E}';

$templates['wcchat.users.item.pm_s'] = '<a href="#" onclick="wc_change_room(\'{CALLER}\', \'{ROOM_NAME}\', {NEW}); return false;">';

$templates['wcchat.users.item.pm_e'] = '</a>';

$templates['wcchat.users.item.edit_bt'] = ' <a href="#wc_user_{ID}" onclick="wc_toggle(\'wc_uedt_{ID}\');"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="edit_bt{OFF}"></a>';

$templates['wcchat.users.item.edit_form'] = '
<div id="wc_uedt_{ID}" class="closed">
    <div class="form_box">
    <form action="?mode=upd_user" method="POST" onsubmit="wc_upd_user(\'{CALLER}\', \'{ID}\', event)">
        <div id="wc_box1_{ID}">
            <div class="menu"><b>Moderation</b> | <a href="#" onclick="wc_toggle(\'wc_box1_{ID}\'); wc_toggle(\'wc_box2_{ID}\'); return false">Profile Data</a></div>
            {MODERATOR}
            {BANNED}
            {MUTED}
            {MOD_NOPERM}
        </div>
        <div id="wc_box2_{ID}" class="closed">
            <div class="menu"><a href="#" onclick="wc_toggle(\'wc_box1_{ID}\'); wc_toggle(\'wc_box2_{ID}\'); return false">Moderation</a> | Profile Data</div>
            {PROFILE_DATA}
        </div>
        <div>
            <input type="submit" value="Update"> {DELETE_BT}
        </div>
        <input type="hidden" name="oname" value="{NAME}" class="usett_{ID}">
    </form>
    </div>
</div>';

$templates['wcchat.users.item.edit_form.delete_bt'] = '<input type="submit" name="delete" value="Delete" onclick="wc_del_user(\'{CALLER}\', \'{ID}\', event)">';

$templates['wcchat.users.item.edit_form.moderator'] = '
<div>
    <input type="checkbox" value="1" name="moderator" id="wc_mod_{ID}" class="usett_{ID}" {MOD_CHECKED}> Moderator
</div>';

$templates['wcchat.users.item.edit_form.banned'] = '
<div>
    <input type="checkbox" value="1" name="banned" class="usett_{ID}" {BANNED_CHECKED}> Banned for <input type="text" value="{BANNED_TIME}" name="banned_time" class="usett_{ID}"> m<br>
    <span>(empty = forever)</span>
</div>';

$templates['wcchat.users.item.edit_form.muted'] = '
<div>
    <input type="checkbox" value="1" name="muted" class="usett_{ID}" {MUTED_CHECKED}> Muted for <input type="text" value="{MUTED_TIME}" name="muted_time" class="usett_{ID}"> m<br><span>(empty = forever)</span>
</div>';

$templates['wcchat.users.item.edit_form.profile_data'] = '
<div>
    Name:<br>
    <input type="text" name="name" value="{NAME}" class="usett_{ID}">
</div>
<div>
    Recovery E-Mail:<br>
    <input type="text" name="email" value="{EMAIL}" class="usett_{ID}">
</div>
<div>
    Web:<br>
    <input type="text" name="web" value="{WEB}" class="usett_{ID}">
</div>
<div>
    <input type="checkbox" value="1" name="reset_avatar" class="usett_{ID}" {DIS_AV}> Reset Avatar</li><br>
    <input type="checkbox" value="1" name="reset_pass" class="usett_{ID}" {DIS_PASS}> Reset Password</li><br>
    <input type="checkbox" value="1" name="regen_pass" class="usett_{ID}"> Re-generate password</li>
</div>';

$templates['wcchat.users.item.icon_moderator'] = ' <img src="{INCLUDE_DIR_THEME}images/mod.png" class="moderator_icon" title="Certified Moderator">';

$templates['wcchat.users.item.icon_muted'] = ' <img src="{INCLUDE_DIR_THEME}images/muted.png" class="muted_icon" title="Muted">';

$templates['wcchat.users.item.icon_ignored'] = ' <img src="{INCLUDE_DIR_THEME}images/ignored.png" class="muted_icon" title="Ignored">';

$templates['wcchat.users.item.width'] = 'style="width: {WIDTH}px; vertical-align: middle"';

$templates['wcchat.users.item.link'] = ' <a href="{LINK}" target="_blank"><img class="web" src="{INCLUDE_DIR_THEME}images/web.png"></a>';

$templates['wcchat.users.item.idle'] = ' <span>(Idle)</span>';

$templates['wcchat.users.item.idle_var'] = ' <span>({IDLE})</span>';

$templates['wcchat.users.joined.void'] = 'There are no joined users.';

$templates['wcchat.users.offline'] = '<div class="separator">OFFLINE</div>{USERS}';

$templates['wcchat.users.guests'] = '<div class="separator">GUESTS</div>{USERS}';

$templates['wcchat.users.guests.item'] = '<div class="guest"><img src="{INCLUDE_DIR_THEME}images/guest.png"> {USER} <span>({IDLE})</span></div>';

?>