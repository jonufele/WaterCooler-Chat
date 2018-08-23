<?php

# ============================================================================
#               INDEX OF CONTENTS
# ============================================================================
#                  INDEX
#                  WCCHAT MAIN
#                  TOPIC
#                  STATIC MSG
#                  TOOLBAR
#                  TEXT INPUT
#                  USER PROFILE SETTINGS
#                  JOIN
#                  LOGIN
#                  USER LIST
#                  POST MESSAGES
#                  ROOMS
#                  THEMES
#                  GLOBAL SETTINGS
#                  INFORMATION
# ============================================================================

# ============================================================================
#                  INDEX
# ============================================================================

$templates['index'] = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
        <LINK rel="stylesheet" id="style" href="{INCLUDE_DIR_THEME}style.css?{STYLE_LASTMOD}" type="text/css">
        <script type="text/javascript" src="{INCLUDE_DIR}script.js?{SCRIPT_LASTMOD}"></script>
        <title>{TITLE}</title>
    </head>
    <body>
        {CONTENTS}
    </body>
</html>';

$templates['index_embedded'] = '{CONTENTS}';

# ============================================================================
#                  WCCHAT MAIN
# ============================================================================

$templates['wcchat'] = '
<noscript>-- Javascript must be enabled! --</noscript>
<div class="closed" id="wc_loader_img_c">
    <div class="center">
        <img src="{INCLUDE_DIR_THEME}images/loader.gif" id="wc_loader_img">
    </div>
</div>
<div id="wcchat">
        <div class="right_col">
            {ROOM_LIST}
            {USER_LIST}
            {THEMES}
            <div class="copyright_note">
                Powered by: <a href="https://github.com/jonufele/WaterCooler-Chat" target="_blank">WaterCooler Chat 1.4</a>
            </div>
        </div>
        <div class="left_col">
            {TOPIC}
            {STATIC_MSG}
            {POSTS}{GSETTINGS}{INFO}
            {TOOLBAR}
            {TEXT_INPUT}
            {SETTINGS}
            {JOIN}            
        </div>
</div>';

$templates['wcchat.critical_error'] = '
<noscript>-- Javascript must be enabled! --</noscript>
<div id="wcchat">
    <fieldset class="critical_error">
        <legend>Critical Error</legend>
        {ERROR}
    </fieldset>
</div>';

$templates['wcchat.botNoAccessNote'] = '403: Forbidden; Powered by: <a href="https://github.com/jonufele/WaterCooler-Chat" target="_blank">WaterCooler Chat 1.4</a>';

# ============================================================================
#                  TOPIC
# ============================================================================

$templates['wcchat.topic'] = '
<fieldset id="wc_topic">
    {TOPIC}
</fieldset>';

$templates['wcchat.topic.inner'] = '
<legend>{CURRENT_ROOM}</legend>
<div id="wc_topic_box">{TOPIC}</div>';

$templates['wcchat.topic.box'] = '
<div id="wc_topic_con">
    {TOPIC_CON}{TOPIC_EDIT_BT}
</div>
<div id="wc_topic_editbox" class="closed">
    <div id="bbcode">{BBCODE}</div>
    <textarea id="wc_topic_txt" rows="5" cols="50">{TOPIC_TXT}</textarea><br>
    <div class="info">
        To hide part of the content by default, add the "[**]" string to generate a "Show more/less" link.
    </div>
    <input type="submit" name="submit" value="Update Topic" onclick="wc_upd_topic(\'{CALLER}\')"> 
    <input type="submit" name="cancel" value="Cancel" onclick="wc_unlock_topic(\'{CALLER}\');">
</div>';

$templates['wcchat.topic.box.partial'] = '
<div id="wc_topic_partial">
    {TOPIC_PARTIAL} <a href="#" onclick="wc_toggle(\'wc_topic_partial\'); wc_toggle(\'wc_topic_full\'); return false" class="more">[Show More]</a>
</div>
<div id="wc_topic_full" class="closed">
    {TOPIC_FULL} <a href="#" onclick="wc_toggle(\'wc_topic_partial\'); wc_toggle(\'wc_topic_full\'); return false" class="less">[Show Less]</a>
</div>';

$templates['wcchat.topic.edit_bt'] = ' <a href="#" class="edit" onclick="wc_lock_topic(\'{CALLER}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="edit_bt{OFF}"></a>';

$templates['wcchat.topic.pm_room'] = '
<div class="pm">
    <b>Private Conversation</b><br>
    <img src="{AVATAR}" class="avatar"> {NAME}&nbsp;&nbsp;&nbsp;
    <img src="{AVATAR2}" class="avatar"> {NAME2}
</div>';

# ============================================================================
#                   STATIC MSG
# ============================================================================

$templates['wcchat.static_msg'] = '<div class="static_msg">You are seeing a static version of the chat contents, login for a dynamic version.</div>';

# ============================================================================
#                   TOOLBAR
# ============================================================================

$templates['wcchat.toolbar'] = '
<div id="wc_toolbar">
    <img src="{INCLUDE_DIR_THEME}images/pixel.gif?{TIME}" class="closed" {ONLOAD}>
    {COMMANDS}
    <span id="wc_settings_icon" class="{ICON_STATE}">
        <a href="#wc_settings_input" onclick="wc_toggle(\'wc_settings_input\'); if(document.getElementById(\'wc_join\').className == \'closed\') { wc_toggle(\'wc_text_input\'); }">
            <img src="{INCLUDE_DIR_THEME}images/settings_icon.png">
        </a>
    </span>
    {USER_NAME}{JOINED_STATUS} | {BBCODE}
    <span id="wc_smiley_box" class="closed">
        {SMILIES}
        <a href="#" onclick="wc_toggle(\'wc_smiley_icon\'); wc_toggle(\'wc_smiley_box\');">[x]</a>
    </span> 
    <img id="wc_post_loader" src="{INCLUDE_DIR_THEME}images/loader.gif" class="closed">
</div>';

$templates['wcchat.toolbar.joined_status'] = '<a href="#" onclick="wc_toggle_status(\'{CALLER}\'); return false;" id="wc_joined_status_c" class="closed"><img src="{INCLUDE_DIR_THEME}images/joined_{MODE}.png" class="joined_status" title="Toggle Status (Available / Do Not Disturb)"></a>';

$templates['wcchat.toolbar.bbcode'] = '
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'{CONT}\', \'[B]\', \'[/B]\'); return false;">
    <img src="{INCLUDE_DIR_THEME}images/bbcode/b.png" title="bold">
</a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'{CONT}\', \'[I]\', \'[/I]\'); return false;">
    <img src="{INCLUDE_DIR_THEME}images/bbcode/i.png" title="italic">
</a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'{CONT}\', \'[U]\', \'[/U]\'); return false;">
    <img src="{INCLUDE_DIR_THEME}images/bbcode/u.png" title="underlined">
</a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'{CONT}\', \'[URL=&quot;http://&quot;]\', \'[/URL]\'); return false;">
    <img src="{INCLUDE_DIR_THEME}images/bbcode/urlt.png" title="link with description/image">
</a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'{CONT}\', \'[IMG]\', \'[/IMG]\'); return false;">
    <img src="{INCLUDE_DIR_THEME}images/bbcode/img.png" title="Image link not terminating with extension">
</a>
{ATTACHMENT_UPLOADS}
<span id="wc_smiley_icon{FIELD}">
    <a href="#" onclick="wc_toggle_smiley(\'{FIELD}\', \'{CONT}\'); return false;">
        <img src="{INCLUDE_DIR_THEME}images/smilies/sm1.gif">
    </a>
</span>
<span id="wc_smiley_box{FIELD}" class="closed">
    {SMILIES}
    <a href="#" onclick="wc_toggle_smiley(\'{FIELD}\', \'{CONT}\'); return false">[x]</a>
</span>';

$templates['wcchat.toolbar.bbcode.attachment_uploads'] = ' <a href="#" onclick="wc_attach_test({ATTACHMENT_MAX_POST_N}); return false">
    <img src="{INCLUDE_DIR_THEME}images/upl.png" id="wc_attachment_upl_icon"  title="Upload Attachments">
</a> 
<span id="wc_attach_cont" class="closed">
    <input id="wc_attach" type="file" class="closed" onchange="wc_attach_upl(\'{CALLER}\', event)">
</span>';

$templates['wcchat.toolbar.commands'] = '
<div id="wc_commands">
    <a href="#" onclick="wc_toggle(\'wc_msg_container\'); wc_toggle(\'wc_info\'); return false" title="Information">
        <img src="{INCLUDE_DIR_THEME}images/cmd.png">
    </a> 
    <a href="#" onclick="wc_clear_screen(\'{CALLER}\'); return false" title="Clear Screen">
        <img src="{INCLUDE_DIR_THEME}images/clr.png"></a> 
    <a href="#" onclick="wc_toggle_time(\'{CALLER}\'); return false" title="Toggle TimeStamps">
        <img src="{INCLUDE_DIR_THEME}images/ts.png">
    </a>
    <a href="#" onclick="wc_toggle(\'wc_sline\'); wc_toggle(\'wc_mline\'); return false" title="Toggle Input Single/Multi Line Mode (To post you need to go back to single line mode)">
        <img id="wc_sline" src="{INCLUDE_DIR_THEME}images/sline.png">
        <img id="wc_mline" src="{INCLUDE_DIR_THEME}images/mline.png" class="closed">
    </a>
    {GSETTINGS}
    {EDIT}
</div>';

$templates['wcchat.toolbar.commands.gsettings'] = ' <a href="#" onclick="wc_toggle(\'wc_msg_container\'); wc_toggle(\'wc_global_settings\'); return false" title="Global Settings"><img src="{INCLUDE_DIR_THEME}images/gsett.png"></a>';

$templates['wcchat.toolbar.commands.edit'] = ' <a href="#" onclick="wc_toggle_edit(\'{CALLER}\', \'{PREFIX}\'); return false" title="Toggle Edit Mode On/Off"><img src="{INCLUDE_DIR_THEME}images/edtmode.png" id="wc_toggle_edit_icon"></a>';

$templates['wcchat.toolbar.smiley.item'] = '<a href="#" onclick="wc_bbcode(document.getElementById(\'{field}\'), \'{cont}\', \'{str_pat}\',\'\'); return false"><img src="{INCLUDE_DIR_THEME}images/smilies/{str_rep}" title="{title}"></a> ';

$templates['wcchat.toolbar.smiley.item.parsed'] = '<img src="{INCLUDE_DIR_THEME}images/smilies/sm{key}.gif">';

$templates['wcchat.error_msg'] = '<div class="error_msg">{ERR}</div>';

$templates['wcchat.toolbar.onload'] = 'onload="wc_refresh_msg(\'{CALLER}\', \'ALL\', {REFRESH_DELAY}, {CHAT_DSP_BUFFER}, \'{INCLUDE_DIR_THEME}\', \'{PREFIX}\')"';

$templates['wcchat.toolbar.onload_once'] = 'onload="wc_refresh_msg_once(\'{CALLER}\', \'ALL\', {CHAT_DSP_BUFFER});"';

# ============================================================================
#                    TEXT INPUT
# ============================================================================

$templates['wcchat.text_input'] = '
<div id="wc_text_input" class="closed">
    <textarea type="text" rows="1" id="wc_text_input_field" onkeydown="return wc_post(event, \'{CALLER}\', {REFRESH_DELAY}, {CHAT_DSP_BUFFER});" autocomplete="off"></textarea>
</div>';

# ============================================================================
#                  USER PROFILE SETTINGS
# ============================================================================

$templates['wcchat.settings'] = '
<fieldset id="wc_settings_input" class="closed">
    <legend>
        Settings <a href="#" onclick="wc_toggle(\'wc_settings_input\'); if(document.getElementById(\'wc_join\').className == \'closed\') { wc_toggle(\'wc_text_input\'); document.getElementById(\'wc_text_input_field\').focus(); } return false;">[Close]</a>
    </legend>
    <div>
        <form id="wc_avatar_form" action="?mode=upl_avatar" enctype="multipart/form-data" method="POST" onsubmit="wc_upl_avatar(\'{CALLER}\', event); return false; ">
            Avatar: <input name="avatar" id="wc_avatar" type="file">
            <input type="submit" name="Upload"> <a href="#" id="wc_av_reset" onclick="wc_reset_av(\'{CALLER}\'); return false;" class="{AV_RESET}">[Reset]</a>
        </form>
    </div>
    <form action="?mode=upd_settings" onsubmit="wc_upd_settings(\'{CALLER}\', event);" method="POST">
        <div>
            Recover E-mail: <input type="text" id="wc_email" value="{USER_EMAIL}" autocomplete="off">
        </div>
        <div>
            Web: <input type="text" name="web" id="wc_web" value="{USER_LINK}" autocomplete="off">
        </div>
        <div>
            Timezone: <select id="wc_timezone">{TIMEZONE_OPTIONS}</select>
        </div>
        <div>
            Hour Format: <select id="wc_hformat">
                <option value="0"{HFORMAT_SEL0}>12h'.'</option>
                <option value="1"{HFORMAT_SEL1}>24h</option>
            </select>
        </div>
        <div>
            Password: <input type="password" id="wc_pass" value="" autocomplete="off"> 
            <span>
                (empty = Unchanged)
            </span> 
            <span id="wc_resetp_elem" class="{RESETP_ELEM_CLOSED}">
                <input type="checkbox" id="wc_resetp" value="1"> Reset
            </span>
        </div>
        <input type="submit" id="wc_submit_settings" value="Update">
    </form>
</fieldset>';

$templates['wcchat.settings.timezone_options'] = '
<option value="-12">(GMT-12:00) Eniwetok</option>
<option value="-11">(GMT-11:00) Midway Island, Samoa</option>
<option value="-10">(GMT-10:00) Hawaii</option>
<option value="-9">(GMT-9:00) Alaska</option>
<option value="-8">(GMT-8:00) Pacific Time (US & Canada)</option>
<option value="-7">(GMT-7:00) Mountain Time (US & Canada)</option>
<option value="-6">(GMT-6:00) Central Time (US & Canada), Mexico City</option>
<option value="-5">(GMT-5:00) Eastern Time (US & Canada), Bogota, Lima, Quito</option>
<option value="-4">(GMT-4:00) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-3.5">(GMT-3:30) Newfoundland</option>
<option value="-3">(GMT-3:00) Brazil, Buenos Aires, Georgetown</option>
<option value="-2">(GMT-2:00) Mid-Atlantic</option>
<option value="-1">(GMT-1:00) Azores, Cape Verde Islands</option>
<option value="0">(GMT) Western Europe Time, London, Lisbon</option>
<option value="1">(GMT+1:00) Central Europe Time, Copenhagen, Madrid, Paris</option>
<option value="2">(GMT+2:00) Eastern Europe Time, Kaliningrad, South Africa</option>
<option value="3">(GMT+3:00) Baghdad, Kuwait, Moscow, Nairobi</option>
<option value="3.5">(GMT+3:30) Tehran</option>
<option value="4">(GMT+4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value="4.5">(GMT+4:30) Kabul</option>
<option value="5">(GMT+5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="5.5">(GMT+5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="6">(GMT+6:00) Almaty, Dhaka, Colombo</option>
<option value="7">(GMT+7:00) Bangkok, Hanoi, Jakarta</option>
<option value="8">(GMT+8:00) Beijing, Perth, Singapore, Hong Kong, Taipei</option>
<option value="9">(GMT+9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="9.5">(GMT+9:30) Adelaide, Darwin</option>
<option value="10">(GMT+10:00) East Australian Standard, Guam, Papua New Guinea</option>
<option value="11">(GMT+11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="12">(GMT+12:00) Auckland, Wellington, Fiji, Marshall Island</option>';

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

# ============================================================================
#                  POST MESSAGES
# ============================================================================

$templates['wcchat.posts'] = '<div id="wc_msg_container"></div>';

$templates['wcchat.post_container'] = '
<div class="msg_item{SKIP_ON_OLDER_LOAD}" id="js_{ID}">
    {CONTENT}
</div>';

$templates['wcchat.posts.self'] = '
<div class="msg">
    {UPDATED_NOTE}
    {EDIT_TAG}
    <div class="self">
        {HIDE_ICON}
        <span class="timestamp" style="{STYLE}">
            {TIMESTAMP}<br>
        </span> 
        <i><b>{USER}</b> <span id="{ID}">{MSG}</span></i>
        {EDIT_CONTAINER}
   </div>
</div>';

$templates['wcchat.posts.normal'] = '
<div class="msg{PM_SUFIX}">
    {UPDATED_NOTE}
    {EDIT_TAG}{PM_TAG}
    <img src="{AVATAR}" class="avatar_thumb" {WIDTH} onload="wc_scroll(\'{ALL}\')">
    <div class="msg_right_col">
        {HIDE_ICON}
        [<span class="user">{POPULATE_START}{USER}{POPULATE_END}{PM_TARGET}</span>]
        <span class="timestamp" style="{STYLE}">
            {TIMESTAMP}
        </span> 
        <div id="{ID}" class="msg_body">{MSG}</div>
        {EDIT_CONTAINER}
    </div>
    <div style="clear: both"></div>
</div>';

$templates['wcchat.posts.partial_content'] = '
<div id="wc_msg_partial_{ID}">
    {PARTIAL}.. <a href="#" onclick="wc_toggle(\'wc_msg_partial_{ID}\'); wc_toggle(\'wc_msg_full_{ID}\'); return false" class="more">[Show More]</a>
</div>
<div id="wc_msg_full_{ID}" class="closed">
    {FULL} <a href="#js_{ID}" onclick="wc_toggle(\'wc_msg_full_{ID}\'); wc_toggle(\'wc_msg_partial_{ID}\');" class="less">[Show Less]</a>
</div>';

$templates['wcchat.posts.edit_container'] = '
<div id="edit_{ID}" class="closed">
</div>';

$templates['wcchat.posts.edit_box'] = '
<div class="bbcode">{BBCODE}</div>
<textarea id="editbox_cont_{ID}" class="editbox" onkeydown="return wc_poste(event, \'{CALLER}\', \'{ID}\', \'{TAG}\');">{CONTENT}</textarea>
{NOTE}';

$templates['wcchat.posts.edit_box.note'] = '<br><span class="note">Note: Messages can be edited up to {TIMEOUT} after post time.</span>';

$templates['wcchat.posts.updated_note'] = '<div id="updated_note_{ID}" class="updated_msg_container"></div>';

$templates['wcchat.posts.normal.width'] = 'style="width: {WIDTH}px"';

$templates['wcchat.posts.normal.pm_tag'] = '<span class="private">private</span>';

$templates['wcchat.posts.normal.edit_tag'] = '<span class="edit_tag"><span id="edited_{ID}">{EDITED}</span>{BT}</span>';

$templates['wcchat.posts.normal.edit_tag.edited'] = 'edited{TARGET}';

$templates['wcchat.posts.normal.edit_tag.edited.target'] = ' by {NAME}';

$templates['wcchat.posts.normal.edit_tag.bt'] = ' <a href="#" onclick="wc_toggle_post_edit(\'{CALLER}\', \'{ID}\', \'{TAG}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="{CLASS}"></a>';

$templates['wcchat.posts.normal.populate_start'] = '<a href="#wc_text_input" onclick="wc_pop_input(\'/pm \'+this.innerHTML+\' \'); return false;">';

$templates['wcchat.posts.normal.populate_end'] = '</a>';

$templates['wcchat.posts.normal.pm_target'] = ' <img src="{INCLUDE_DIR_THEME}images/arrow.png"> <a href="#wc_text_input" onclick="wc_pop_input(\'/pm \'+this.innerHTML+\' \'); return false; ">{TITLE}</a>';

$templates['wcchat.posts.normal.pm_target.self'] = ' <img src="{INCLUDE_DIR_THEME}images/arrow.png"> {TITLE}';

$templates['wcchat.posts.hide_icon'] = '<a href="#" onclick="wc_toggle_msg(\'{CALLER}\', \'{ID}\', \'{ID_SOURCE}\', {PRIVATE}, \'{PREFIX}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/arrow{REVERSE}.png" id="wc_icon_{ID}" title="Hide/Unhide Message" class="hide_icon{OFF}"></a> ';

$templates['wcchat.posts.hidden'] = '<span id="hidden_{ID}"><i>This message is hidden.</i></span>';

$templates['wcchat.posts.hidden_mod'] = '<i><img src="{INCLUDE_DIR_THEME}images/mod.png" class="mod_icon" id="hidden_all_{ID}"> This message is hidden for all users.</i>';

$templates['wcchat.posts.event'] = '
<div class="msg">
    <div class="event">
       <span class="timestamp" style="{STYLE}">
              {TIMESTAMP}<br>
       </span> 
       <i>{MSG}</i>
    </div>
</div>';

$templates['wcchat.posts.older'] = '
<div class="load_older_controls">
    <a href="#" onclick="wc_show_older_msg(\'{CALLER}\', 0); return false">Load Older</a> | 
    <a href="#" onclick="wc_show_older_msg(\'{CALLER}\', 1); return false">Reset</a>
</div>
<div id="wc_older"></div>';

$templates['wcchat.posts.older.block_separator'] = '<hr>';

$templates['wcchat.posts.new_msg_separator'] = '<div class="new_msg"><img src="{INCLUDE_DIR_THEME}images/new_msg_separator.png" onload="wc_scroll(\'ALL\')"></div>';

$templates['wcchat.posts.undo_clear_screen'] = 'Screen cleanup (<a href="#" onclick="wc_undo_clear_screen(\'{CALLER}\'); return false;">Undo</a>)';

$templates['wcchat.posts.global_clear_screen'] = 'New Chat Visit';

$templates['wcchat.posts.curr_user_ref'] = '<span class="curr_user_ref">{NAME}</span>';

# ============================================================================
#                         ROOMS
# ============================================================================

$templates['wcchat.rooms'] = '
<div id="wc_rooms">
    <div class="separator">ROOMS</div>
    <div id="wc_room_list">
        {RLIST}
    </div>
</div>';

$templates['wcchat.rooms.inner'] = '{ROOMS}{CREATE}';

$templates['wcchat.rooms.create'] = '
<div id="wc_room_create">
    <a href="#" onclick="wc_toggle(\'wc_croom_box\'); return false;" class="create_link{OFF}" id="wc_create_link">Create New Room</a>
    <div id="wc_croom_box" class="closed">
        <div class="form_box">
            Room Name: <input type="text" id="wc_room_name" value=""> <input type="submit" value="create" onclick="wc_create_room(\'{CALLER}\')">
        </div>
    </div>
</div>';

$templates['wcchat.rooms.current_room'] = '
<div class="current room_item">
    {NEW_MSG} {TITLE}{EDIT_BT}
</div>
{FORM}';

$templates['wcchat.rooms.room'] = '
<div class="room_item">
    {NEW_MSG} <a href="#" onclick="wc_change_room(\'{CALLER}\', this.innerHTML, 0); return false;">{TITLE}</a>
    {EDIT_BT}
</div>
{FORM}';

$templates['wcchat.rooms.new_msg.on'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg.png" title="Room has new messages">';

$templates['wcchat.rooms.new_msg.off'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg_off.png" title="No new messages">';

$templates['wcchat.rooms.edit_form'] = '
<div id="wc_edt_{ID}" class="closed">
    <div class="form_box">
        <div>
            Name:<br>
            <input type="text" id="wc_nname_{ID}" value="{ROOM_NAME}">
            <input type="hidden" id="wc_oname_{ID}" value="{ROOM_NAME}">
        </div>
        <div>
            Post Permission:<br>
            <select id="wc_perm_{ID}">
                <option value="0">All Users</option>
                <option value="1"{SEL1}>Master Moderator</option>
                <option value="2"{SEL2}>Moderators</option>
                <option value="3"{SEL3}>Certified User</option>
            </select>
        </div>
        <div>
            Read Permission:<br>
            <select id="wc_rperm_{ID}">
                <option value="0">All Users</option>
                <option value="1"{SEL21}>Master Moderator</option>
                <option value="2"{SEL22}>Moderators</option>
                <option value="3"{SEL23}>Certified User</option>
                <option value="4"{SEL24}>User</option>
            </select>
        </div>
        <input type="submit" value="Update" onclick="wc_upd_room(\'{CALLER}\', \'{ID}\')"> {DELETE_BT}
    </div>
</div>';

$templates['wcchat.rooms.edit_form.delete_bt'] = '<input type="submit" value="Delete" onclick="wc_del_room(\'{CALLER}\', \'{ID}\')">';

$templates['wcchat.rooms.edit_form.edit_icon'] = ' <a href="#" onclick="wc_toggle(\'wc_edt_{ID}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="edit_bt{OFF}"></a>';

# ============================================================================
#                        THEMES
# ============================================================================

$templates['wcchat.themes'] = '<div class="themes"><select onchange="wc_apply_theme(this.value, \'{PREFIX}\', {COOKIE_EXPIRE})">{OPTIONS}</select></div>';

$templates['wcchat.themes.option'] = '<option value="{VALUE}"{SELECTED}>{TITLE}</option>';

# ============================================================================
#                     GLOBAL SETTINGS
# ============================================================================

$templates['wcchat.global_settings'] = '
<div id="wc_global_settings" class="closed">
    <div class="header">GLOBAL SETTINGS <span><a href="#" onclick="wc_toggle(\'wc_msg_container\'); wc_toggle(\'wc_global_settings\'); return false">[Close]</a></span></div>
    <form id="wc_global_settings_form" action="?mode=upd_gsettings" method="POST" onsubmit="wc_upd_gsettings(\'{CALLER}\', event); return false;">
        <fieldset>
            <legend>TITLE</legend>
            <div>
                <input type="text" id="gs_title" value="{GS_TITLE}" class="gsett"><br>
                <span>Main Chat Title</span>
            </div>
        </fieldset>
        <fieldset>
            <legend>INCLUDE DIRECTORY</legend>
            <div>
                <input type="text" id="gs_include_dir" value="{GS_INCLUDE_DIR}" class="gsett"><br>
                <span>Relative Path from web root to chat system (Empty if on the root)</span>
            </div>
        </fieldset>
        <fieldset>
            <legend>CHAT PARAMETERS</legend>
            <div>
                Refresh Delay: <input type="text" id="gs_refresh_delay" value="{GS_REFRESH_DELAY}" class="gsett"> ms<br>
                <span>Message Refresh Delay (do not set this too low to avoid server overload)</span>
            </div>
            <div>
                Refresh Delay (Idle): <input type="text" id="gs_refresh_delay_idle" value="{GS_REFRESH_DELAY_IDLE}" class="gsett"> ms<br>
                <span>0 = Disabled; Message Refresh Delay While Idling, if on, catch window will be wider (events will stay longer, logouts will be slower)</span>
            </div>
            <div>
                Idle Start: <input type="text" id="gs_idle_start" value="{GS_IDLE_START}" class="gsett"> s<br>
                <span>Minimum period of time to be considered idle</span>
            </div>
            <div>
                Offline Ping: <input type="text" id="gs_offline_ping" value="{GS_OFFLINE_PING}" class="gsett"> s<br>
                <span>Minimum period of time to be considered offline after last successful ping</span>
            </div>
            <div>
                Load Existing Messages: <input type="checkbox" class="gsett" id="gs_load_ex_msg" value="1"{GS_LOAD_EX_MSG}><br>
                <span>Show existing messages at the beggining of the visit</span>
            </div>
            <div>
                Chat display buffer: <input type="text" id="gs_chat_dsp_buffer" value="{GS_CHAT_DSP_BUFFER}" class="gsett"> posts<br>
                <span>Maximum number of chat posts to display</span>
            </div>
            <div>
                Chat store buffer: <input type="text" id="gs_chat_store_buffer" value="{GS_CHAT_STORE_BUFFER}" class="gsett"> posts<br>
                <span>Maximum number of chat posts to store / Available to load</span>
            </div>
            <div>
                Older Message load step: <input type="text" id="gs_chat_older_msg_step" value="{GS_CHAT_OLDER_MSG_STEP}" class="gsett"> posts<br>
                <span>Maximum number of old chat lines to retrieve at once</span>
            </div>
            <div>
                Archive messages: <input type="checkbox" id="gs_archive_msg" class="gsett" value="1"{GS_ARCHIVE_MSG}><br>
                <span>Archive messages that drop from Store Buffer (available for users to load)</span>
            </div>
            <div>
                List Guests: <input type="checkbox" id="gs_list_guests" class="gsett" value="1"{GS_LIST_GUESTS}><br>
                <span>List guests (Users that never joined chat) on the user list</span>
            </div>
            <div>
                Anti-SPAM: <input type="text" id="gs_anti_spam" value="{GS_ANTI_SPAM}" class="gsett"> s<br>
                <span>Minimum ammount of time between one user\'s posted messages (seconds, 0 = Disabled)</span>
            </div>
            <div>
                Post edit timeout: <input type="text" id="gs_post_edit_timeout" value="{GS_POST_EDIT_TIMEOUT}" class="gsett"> s<br>
                <span>Amount of time a post is available for edition (seconds, 0 = No limit)</span>
            </div>
            <div>
                Maximum Message Length: <input type="text" id="gs_max_data_len" value="{GS_MAX_DATA_LEN}" class="gsett"><br>
                <span>Maximum number of characters to display by default (0 = No limit)</span>
            </div>
            <div>
                Account Recovery Sender E-mail: <input type="text" id="gs_acc_rec_email" value="{GS_ACC_REC_EMAIL}" class="gsett"><br>
                <span>Default: {ACC_REC_EM_DEFAULT}; The email must exist in the webserver in order for the email function to work.</span>
            </div>
            <div>
                Bot Main Page Access: <input type="checkbox" id="gs_bot_main_page_access" class="gsett" value="1"{GS_BOT_MAIN_PAGE_ACCESS}><br>
                <span>Allow search engines / automated bots access to the chat\'s main page</span>
            </div>
        </fieldset>
        <fieldset>
            <legend>MULTIMEDIA</legend>
            <div>
                Images: Max. Dimension To Display: <input type="text" id="gs_image_max_dsp_dim" value="{GS_IMAGE_MAX_DSP_DIM}" class="gsett"> pixels<br>
                <span>Maximum displayed dimension of images within messages</span>
            </div>
            <div>
                Images: Resize Unknown dimensions: <input type="text" id="gs_image_auto_resize_unkn" value="{GS_IMAGE_AUTO_RESIZE_UNKN}" class="gsett"> pixels<br>
                <span>Resize Images with unknown dimension within messages</span>
            </div>
            <div>
                Video Dimensions: <input type="text" id="gs_video_width" value="{GS_VIDEO_WIDTH}" class="gsett"> X <input type="text" id="gs_video_height" value="{GS_VIDEO_HEIGHT}" class="gsett"> Pixels<br>
                <span>Video container dimensions</span>
            </div>
            <div>
                Avatar Size: <input type="text" id="gs_avatar_size" value="{GS_AVATAR_SIZE}" class="gsett"> pixels<br>
                <span>Default = 25 (square avatar)</span>
            </div>
            <div>
                Default Avatar: <input type="text" id="gs_default_avatar" value="{GS_DEFAULT_AVATAR}" class="gsett">
            </div>
            <div>
                Generate Thumbnails For Remote Images: <input type="checkbox" id="gs_gen_rem_thumb" class="gsett" value="1"{GS_GEN_REM_THUMB}><br>
                <span>Generate thumbnails for remote images within posts.</span>
            </div>
            <div>
                Allow upload of Attachments in posts: <input type="checkbox" id="gs_attachment_uploads" class="gsett" value="1"{GS_ATTACHMENT_UPLOADS}><br>
                <span>Allow upload of attachments in posts.</span>
            </div>
            <div>
                Allowed Attachment Types: <input type="text" id="gs_attachment_types" value="{GS_ATTACHMENT_TYPES}" class="gsett"><br>
                <span>Allowed file types (separated by spaces).</span>
            </div>
            <div>
                Attachment Maximum Filezise: <input type="text" id="gs_attachment_max_fsize" value="{GS_ATTACHMENT_MAX_FSIZE}" class="gsett">KB<br>
                <span>Maximum filesize of an attachment file.</span>
            </div>
            <div>
                Attachment Maximum Post Count: <input type="text" id="gs_attachment_max_post_n" value="{GS_ATTACHMENT_MAX_POST_N}" class="gsett"><br>
                <span>Maximum number of attachments allowed per post, 0 = No limit.</span>
            </div>
        </fieldset>
        <fieldset>
            <legend>DEFAULT ROOM AND THEME</legend>
            <div>
                Default Room: <input type="text" id="gs_default_room" value="{GS_DEFAULT_ROOM}" class="gsett">
            </div>
            <div>
                Default Theme: <input type="text" id="gs_default_theme" value="{GS_DEFAULT_THEME}" class="gsett">
            </div>
        </fieldset>
        <fieldset>
            <legend>PERMISSIONS</legend>
            <table>
                <tr>
                    <th class="first_col"></td>
                    <th>Master<br>Moderator</td>
                    <th>Moderator</td>
                    <th>
                        Certified User<br>
                        <span>(Password Protected)</span>
                    </td>
                    <th>User</td>
                    <th>
                        Guest<br>
                        <span>(Not Logged-in)</span>
                    </td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Edit Global Settings</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_gsettings"{GS_MMOD_GSETTINGS} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_gsettings"{GS_MOD_GSETTINGS} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_gsettings"{GS_CUSER_GSETTINGS} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_gsettings"{GS_USER_GSETTINGS} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_gsettings"{GS_GUEST_GSETTINGS} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Room: Create</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_room_c"{GS_MMOD_ROOM_C} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_room_c"{GS_MOD_ROOM_C} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_room_c"{GS_CUSER_ROOM_C} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_room_c"{GS_USER_ROOM_C} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_room_c"{GS_GUEST_ROOM_C} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Room: Edit</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_room_e"{GS_MMOD_ROOM_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_room_e"{GS_MOD_ROOM_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_room_e"{GS_CUSER_ROOM_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_room_e"{GS_USER_ROOM_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_room_e"{GS_GUEST_ROOM_E} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Room: Delete</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_room_d"{GS_MMOD_ROOM_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_room_d"{GS_MOD_ROOM_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_room_d"{GS_CUSER_ROOM_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_room_d"{GS_USER_ROOM_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_room_d"{GS_GUEST_ROOM_D} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Moderators: Assign</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_mod"{GS_MMOD_MOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_mod"{GS_MOD_MOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_mod"{GS_CUSER_MOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_mod"{GS_USER_MOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_mod"{GS_GUEST_MOD} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Moderators: Un-Assign</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_unmod"{GS_MMOD_UNMOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_unmod"{GS_MOD_UNMOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_unmod"{GS_CUSER_UNMOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_unmod"{GS_USER_UNMOD} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_unmod"{GS_GUEST_UNMOD} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: Edit</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_user_e"{GS_MMOD_USER_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_user_e"{GS_MOD_USER_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_user_e"{GS_CUSER_USER_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_user_e"{GS_USER_USER_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_user_e"{GS_GUEST_USER_E} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: Delete</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_user_d"{GS_MMOD_USER_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_user_d"{GS_MOD_USER_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_user_d"{GS_CUSER_USER_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_user_d"{GS_USER_USER_D} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_user_d"{GS_GUEST_USER_D} class="gsett"></td>
                </tr>
                <tr>
                    <th class="first_col"></td>
                    <th>Master<br>Moderator</td>
                    <th>Moderator</td>
                    <th>
                        Certified User<br>
                        <span>(Password Protected)</span>
                    </td>
                    <th>User</td>
                    <th>
                        Guest<br>
                        <span>(Not Logged-in)</span>
                    </td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Topic: Edit</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_topic_e"{GS_MMOD_TOPIC_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_topic_e"{GS_MOD_TOPIC_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_topic_e"{GS_CUSER_TOPIC_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_topic_e"{GS_USER_TOPIC_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_topic_e"{GS_GUEST_TOPIC_E} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: Ban</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_ban"{GS_MMOD_BAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_ban"{GS_MOD_BAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_ban"{GS_CUSER_BAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_ban"{GS_USER_BAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_ban"{GS_GUEST_BAN} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: UnBan</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_unban"{GS_MMOD_UNBAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_unban"{GS_MOD_UNBAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_unban"{GS_CUSER_UNBAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_unban"{GS_USER_UNBAN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_unban"{GS_GUEST_UNBAN} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: Mute</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_mute"{GS_MMOD_MUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_mute"{GS_MOD_MUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_mute"{GS_CUSER_MUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_mute"{GS_USER_MUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_mute"{GS_GUEST_MUTE} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: UnMute</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_unmute"{GS_MMOD_UNMUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_unmute"{GS_MOD_UNMUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_unmute"{GS_CUSER_UNMUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_unmute"{GS_USER_UNMUTE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_unmute"{GS_GUEST_UNMUTE} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: Hide</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_msg_hide"{GS_MMOD_MSG_HIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_msg_hide"{GS_MOD_MSG_HIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_msg_hide"{GS_CUSER_MSG_HIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_msg_hide"{GS_USER_MSG_HIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_msg_hide"{GS_GUEST_MSG_HIDE} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: UnHide</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_msg_unhide"{GS_MMOD_MSG_UNHIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_msg_unhide"{GS_MOD_MSG_UNHIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_msg_unhide"{GS_CUSER_MSG_UNHIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_msg_unhide"{GS_USER_MSG_UNHIDE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_msg_unhide"{GS_GUEST_MSG_UNHIDE} class="gsett"></td>
                </tr>
                <tr>
                    <th class="first_col"></td>
                    <th>Master<br>Moderator</td>
                    <th>Moderator</td>
                    <th>
                        Certified User<br>
                        <span>(Password Protected)</span>
                    </td>
                    <th>User</td>
                    <th>
                        Guest<br>
                        <span>(Not Logged-in)</span>
                    </td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: Add</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_post"{GS_MMOD_POST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_post"{GS_MOD_POST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_post"{GS_CUSER_POST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_post"{GS_USER_POST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_post"{GS_GUEST_POST} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: Edit</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_post_e"{GS_MMOD_POST_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_post_e"{GS_MOD_POST_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_post_e"{GS_CUSER_POST_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_post_e"{GS_USER_POST_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_post_e"{GS_GUEST_POST_E} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: Upload Attachments</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_attach_upl"{GS_MMOD_ATTACH_UPL} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_attach_upl"{GS_MOD_ATTACH_UPL} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_attach_upl"{GS_CUSER_ATTACH_UPL} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_attach_upl"{GS_USER_ATTACH_UPL} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_attach_upl"{GS_GUEST_ATTACH_UPL} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: Download Attachments / Full Size Images</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_attach_down"{GS_MMOD_ATTACH_DOWN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_attach_down"{GS_MOD_ATTACH_DOWN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_attach_down"{GS_CUSER_ATTACH_DOWN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_attach_down"{GS_USER_ATTACH_DOWN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_attach_down"{GS_GUEST_ATTACH_DOWN} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Own Profile: Edit</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_profile_e"{GS_MMOD_PROFILE_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_profile_e"{GS_MOD_PROFILE_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_profile_e"{GS_CUSER_PROFILE_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_profile_e"{GS_USER_PROFILE_E} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_profile_e"{GS_GUEST_PROFILE_E} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Users: Ignore</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_ignore"{GS_MMOD_IGNORE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_ignore"{GS_MOD_IGNORE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_ignore"{GS_CUSER_IGNORE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_ignore"{GS_USER_IGNORE} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_ignore"{GS_GUEST_IGNORE} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Private Message: Send</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_pm_send"{GS_MMOD_PM_SEND} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_pm_send"{GS_MOD_PM_SEND} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_pm_send"{GS_CUSER_PM_SEND} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_pm_send"{GS_USER_PM_SEND} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_pm_send"{GS_GUEST_PM_SEND} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Private Message Room: Start</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_pm_room"{GS_MMOD_PM_ROOM} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_pm_room"{GS_MOD_PM_ROOM} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_pm_room"{GS_CUSER_PM_ROOM} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_pm_room"{GS_USER_PM_ROOM} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_pm_room"{GS_GUEST_PM_ROOM} class="gsett"></td>
                </tr>
                <tr>
                    <th class="first_col"></td>
                    <th>Master<br>Moderator</td>
                    <th>Moderator</td>
                    <th>
                        Certified User<br>
                        <span>(Password Protected)</span>
                    </td>
                    <th>User</td>
                    <th>
                        Guest<br>
                        <span>(Not Logged-in)</span>
                    </td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Login</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_login"{GS_MMOD_LOGIN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_login"{GS_MOD_LOGIN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_login"{GS_CUSER_LOGIN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_login"{GS_USER_LOGIN} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_login"{GS_GUEST_LOGIN} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Account Recovery</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_acc_rec"{GS_MMOD_ACC_REC} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_acc_rec"{GS_MOD_ACC_REC} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_acc_rec"{GS_CUSER_ACC_REC} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_acc_rec"{GS_USER_ACC_REC} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_acc_rec"{GS_GUEST_ACC_REC} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Read Posts</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_read_msg"{GS_MMOD_READ_MSG} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_read_msg"{GS_MOD_READ_MSG} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_read_msg"{GS_CUSER_READ_MSG} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_read_msg"{GS_USER_READ_MSG} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_read_msg"{GS_GUEST_READ_MSG} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> View Room List</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_room_list"{GS_MMOD_ROOM_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_room_list"{GS_MOD_ROOM_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_room_list"{GS_CUSER_ROOM_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_room_list"{GS_USER_ROOM_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_room_list"{GS_GUEST_ROOM_LIST} class="gsett"></td>
                </tr>
                <tr>
                    <td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> View User List</td>
                    <td class="check"><input type="checkbox" value="1" id="mmod_user_list"{GS_MMOD_USER_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="mod_user_list"{GS_MOD_USER_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="cuser_user_list"{GS_CUSER_USER_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="user_user_list"{GS_USER_USER_LIST} class="gsett"></td>
                    <td class="check"><input type="checkbox" value="1" id="guest_user_list"{GS_GUEST_USER_LIST} class="gsett"></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>INVITE LINK CODE</legend>
            <div>
                {URL}?invite= <input type="text" id="gs_invite_link_code" value="{GS_INVITE_LINK_CODE}" class="gsett"><br>
                <span>Set a random code (letters and numbers) to restrict first login access to a referral link.</span>
            </div>
        </fieldset>
        <div>
            <input type="submit" value="Update Settings"> <input type="submit" name="cancel" value="Cancel" onclick="wc_toggle(\'wc_msg_container\'); wc_toggle(\'wc_global_settings\'); return false">
        </div>
    </form>
</div>';

# ============================================================================
#                           INFORMATION
# ============================================================================

$templates['wcchat.info'] = '
<div id="wc_info" class="closed">
    <div class="header">
        Information <span>
            <a href="#" onclick="wc_toggle(\'wc_msg_container\'); wc_toggle(\'wc_info\'); return false">[Close]</a>
        </span>
    </div>
    <div class="header2">Commands</div>
    <div>
        <ul>
            <li>/me <i>message</i> - <span>self message</span></li>
            <li>/ignore <i>user</i> - <span>Ignores a user</span></li>
            <li>/unignore <i>user</i> - <span>Unignores user</span></li>
            <li>/pm <i>user</i> <i>message</i> - <span>Sends a private message to user</span></li>
        </ul>
        <span>(Replace "<i>user</i>" by the name of the user)</span>
    </div>
    <div class="header2">Input Auto Completers</div>
    <div>
        <ul>
            <li>TAB - <span>Hit tab while writing a user name to auto-complete</span></li>
            <li>PM - <span>Click a user name in posts to auto complete the private message command</span></li>
        </ul>
    </div>
    <div class="header2">User References</div>
    <div>
        Add the prefix "@" to a user name to create a reference, the respective user will see it highlighted in the message.<br>
        <span>Example: @Mary -> <span class="curr_user_ref">Mary</span> (Names are case sensitive)</span>
    </div>
    <div class="header2">Event Messages</div>
    <div>
        <ul>
            <li>Event messages are <b>room independent</b> <span>(Users get them, no matter where they are)</span></li>
            <li>Event messages are temporary, after some seconds, the event buffer is reset.</li>
            <li>Changing the topic on a private conversation room only sends an event message to the other participant</li>
            <li>Changing the topic on a public room sends the event to all users</li>
            <li>Changing the topic on a private room sends the event to all users with read access to that room</li>
        </ul>
    </div>
    <div class="header2">Conversations</div>
    <div>
        <ul>
            <li>To initiate a conversation, simply click the user name (joining the chat is mandatory). <span>(You will be asked for a confirmation, after that, the conversation starts automatically)</span></li>
            <li>Users are notified of new conversations through a new message icon above the avatar.</li>
            <li>To respond to a conversation, simple click the icon above the avatar or the user name.</li>
            <li>Users which have initiated conversations will always display the message icon above the avatar <span>(Unless they are currently in conversation)</span></li>
            <li>Conversations work like normal rooms, the only difference is the privacy.</li>
        </ul>
    </div>
    <div class="header2">Rooms</div>
    <div>
        <ul>
            <li>Rooms are containers to hold messages of a certain topic, not user containers.</li>
            <li>The user list is <b>room independent</b>.</li>
            <li>Rooms which read permission is higher than the current user\'s group are invisible (not listed).</li>
            <li>When a room is deleted/renamed, all users visiting it are moved to the default room (except the moderator who deleted/renamed).</li>
            <li>The default room cannot be deleted.</li>
            <li>When a user visits a room, his/her arrival is not announced, only new chat visits are announced.</li>
        </ul>
    </div>
    <div class="header2">Chat Messages</div>
    <div>
        <ul>
            <li>To post a message, a user needs to be logged in and joined</li>
            <li>Posted messages can be hidden either by users/moderators (self only: cookie) or moderators under edit mode (global hide)</li>
            <li>A message that is hidden (either locally or globally) cannot be edited</li>
            <li>Globally hiding a message takes immediate effect on other users, while un-hiding only takes effect on user\'s next visit to the room</li>
            <li>When a message is edited, the other users receive a note on top of the message with a reload link.</li>
            <li>To write a multi-line message, turn on multi-line mode (Toolbar), write the message, go back to single line mode and submit.</li>
            <li>If you need a bigger input box to write your message, simply drag its right corner down.</li>
        </ul>
    </div>
    <div class="header2">Archival; Read/Write Buffers</div>
    <div>
        <ul>
            <li>By Default, all messages that drop from <b>store buffer</b> are stored in separate archives.</li>
            <li>The archived messages are available for users to load.</li>
            <li>The <b>read buffer</b> prevents the screen from going over 100 messages by default <span>(When loading older messages there\'s no limit for the number of messages displayed on the screen)</span></li>
            <li>The store buffer is used to store the most recent messages <span>(Up to 500 messages by default, if archival is not enabled, all messages that drop from this buffer will be lost)</span></li>
            <li>By cleaning the screen, a new initial read point is created <span>(and loading will always start from there, unless you undo the operation)</span></li>
        </ul>
    </div>

    <div class="header2">User Types</div>
    <div>
        <ul>
            <li>
                <b>Guest</b>
                <ul>
                    <li>In terms of listing - <span>Users listed as guests are users that chose a name, but never joined the chat.</span></li>
                    <li>In terms of UserGroup - <span>Users that didn\'t choose a name, or are yet to provide a valid password.</span></li>
                </ul>
            </li>
            <li><b>User</b> - <span>All users that chose a name and have access to that profile (password protected or not).</span></li>
            <li><b>Certified User</b> - <span>Users that have a password protected profile.</span></li>
            <li><b>Moderator</b> - <span>User that have access to moderator tools, manually set by the master moderator.</span></li>
            <li><b>Master Moderator</b> - <span>First user to login with a password. (founder)</span></li>
        </ul>
    </div>
    <div class="header2">Symbols</div>
    <div>
        <ul>
            <li><img src="{INCLUDE_DIR_THEME}images/arrow.png"> - Hides a message <span>(Hides for all users if user is a moderator under edit mode)</span></li>
            <li><img src="{INCLUDE_DIR_THEME}images/arrow_r.png"> - Un-Hides a message <span>(Won\'t work if the message was globally hidden by a moderator)</span></li>
            <li><img src="{INCLUDE_DIR_THEME}images/attach.png"> - Item is an uploaded attachment</li>
            <li><img src="{INCLUDE_DIR_THEME}images/ignored.png"> - User is ignored (cookie)</li>
            <li><img src="{INCLUDE_DIR_THEME}images/muted.png"> - User has been muted by a moderator</li>
            <li><img src="{INCLUDE_DIR_THEME}images/nmsg.png"> - Room/Conversation has new messages</li>
            <li><img src="{INCLUDE_DIR_THEME}images/nmsg_off.png"> - Room/Conversation does not have new messages</li>
            <li><img src="{INCLUDE_DIR_THEME}images/mod.png"> - User is a moderator</li>
            <li><img src="{INCLUDE_DIR_THEME}images/cmd.png"> - Toolbar: Information</li>
            <li><img src="{INCLUDE_DIR_THEME}images/clr.png"> - Toolbar: Clear Screen</li>
            <li><img src="{INCLUDE_DIR_THEME}images/ts.png"> - Toolbar: Toggle Timestamps</li>
            <li><img src="{INCLUDE_DIR_THEME}images/gsett.png"> - Toolbar: Global Settings</li>
            <li><img src="{INCLUDE_DIR_THEME}images/edtmode.png"> - ToolBar: Edit Mode</li>
            <li><img src="{INCLUDE_DIR_THEME}images/sline.png"> - ToolBar: Single Line Input Mode <span>(<b>Enter = Submit</b>, default, to write <b>short posts</b>)</span></li>
            <li><img src="{INCLUDE_DIR_THEME}images/mline.png"> - ToolBar: Multi Line Input Mode <span>(<b>Enter = New line</b>, to write <b>long posts</b> with line breaks, after writing, return to single line mode in order to submit, affects both main input and message edit boxes)</span></li>
            <li><img src="{INCLUDE_DIR_THEME}images/web.png"> - User webpage link</li>
            <li><img src="{INCLUDE_DIR_THEME}images/settings_icon.png" style="width: 20px"> - Profile Tools: Settings</li>
            <li>
                <img src="{INCLUDE_DIR_THEME}images/bbcode/b.png">
                <img src="{INCLUDE_DIR_THEME}images/bbcode/i.png">
                <img src="{INCLUDE_DIR_THEME}images/bbcode/u.png"> - BBcode: Text style
            </li>
            <li><img src="{INCLUDE_DIR_THEME}images/bbcode/img.png"> - BBcode: Images without extention</li>
            <li><img src="{INCLUDE_DIR_THEME}images/bbcode/urlt.png"> - BBcode: Url with description/linked image</li>
            <li><img src="{INCLUDE_DIR_THEME}images/upl.png" style="width: 20px"> - BBcode: Attachment Uploads</li>
            <li><img src="{INCLUDE_DIR_THEME}images/joined_on.png" style="width: 10px"> - Profile "available" status <span>(green bar above user avatar)</span></li>
            <li><img src="{INCLUDE_DIR_THEME}images/joined_off.png" style="width: 10px"> - Profile "do not disturb" status <span>(red bar above user avatar; Under this mode user will not accept conversations / private messages)</span></li>
        </ul>
    </div>
    <div class="header2">Notes</div>
    <div>
        <ul>
            <li>While <b>loading older messages</b>, one block contains less messages than the others, this is normal, it means the end of the archive has been reached, if more archives exist, the loading can continue.
            <li><b>No new messages on an updated room</b>: the activity was from a user being ignored
            <li>Types of <b>Private objects</b>:
                <ol>
                    <li><i>/pm</i>: <span>Quick messages sent to a specific user on a specific room.</span></li>
                    <li><i>Private Conversation room</i>: <span>Private room dedicated to a conversation between two users (just like the normal rooms, but with privacy, both participants can change the topic).</span></li>
                    <li><i>Private Room</i>: <span>Room restrict to a certain user group (Read/Write)</span></li>
                </ol>
            </li>
            <li>Actions such as <b>profile edition</b>, <b>user moderation</b>, <b>topic update</b>, <b>room management</b>, <b>global settings edition</b>, <b>room switch</b> and <b>message reading</b> do not require the user to join the chat.</li>
            <li>By joining, a user is granted access to <b>text message box</b>, <b>uploads</b> and can <b>initiate private conversations</b>.</li>
            <li>When a user logs in to a password protected profile (not in use), the profile statistics do not update unless a correct password is supplied.</li>
            <li>Uploading attachments generates a BBcode tag which that can be used in the input text box next to other text</li>
            <li>Offline users idle time refers to the last known chat ping.</li>
        </ul>
    </div>
</div>';

?>