<?php

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

$templates['index.critical_error'] = '
<div class="wc_critical_error">
	<h2>{TITLE}</h2>
	{ERROR}
</div>';

# ============================================================================
#                  WCCHAT MODULES
# ============================================================================

$templates['wcchat'] = '
<noscript>-- Javascript must be enabled! --</noscript>
<img src="{INCLUDE_DIR_THEME}images/loader.gif" class="closed">
<h1>{TITLE}</h1>
<table id="wcchat">
	<tr>
		<td class="wc_left_col">
			{TOPIC}
			{STATIC_MSG}
			{POSTS}{GSETTINGS}
			{TOOLBAR}
			{TEXT_INPUT}
			{SETTINGS}
			{JOIN}			
		</td>
		<td class="wc_right_col">
			{ROOM_LIST}
			{USER_LIST}
			{THEMES}
			<div class="copyright_note">
				Powered by: <a href="https://github.com/jonufele/WaterCooler-Chat" target="_blank">WaterCooler Chat 1.4</a>
			</div>
		</td>
		<td style="width: 30px">
		</td>
	</tr>
</table>';

# ============================================================================
#                  WCCHAT MODULES : TOPIC
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
	<div class="info">To hide part of the content by default, add the "[**]" string to generate a "Show more/less" link.</div>
	<input type="submit" name="submit" value="Update Topic" onclick="wc_upd_topic(\'{CALLER}\')"> <input type="submit" name="cancel" value="Cancel" onclick="wc_toggle(\'wc_topic_con\'); wc_toggle(\'wc_topic_editbox\');">
</div>';

$templates['wcchat.topic.box.partial'] = '
<div id="wc_topic_partial">
	{TOPIC_PARTIAL} <a href="#" onclick="wc_toggle(\'wc_topic_partial\'); wc_toggle(\'wc_topic_full\'); return false" class="more">[Show More]</a>
</div>
<div id="wc_topic_full" class="closed">
	{TOPIC_FULL} <a href="#" onclick="wc_toggle(\'wc_topic_partial\'); wc_toggle(\'wc_topic_full\'); return false" class="less">[Show Less]</a>
</div>';

$templates['wcchat.topic.edit_bt'] = ' <a href="#" class="edit" onclick="wc_toggle(\'wc_topic_con\'); wc_toggle(\'wc_topic_editbox\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="wc_edit_bt{OFF}"></a>';

# ============================================================================
#                  WCCHAT MODULES : STATIC MSG
# ============================================================================

$templates['wcchat.static_msg'] = '<div class="wc_static_msg">You are seeing a static version of the chat contents, login for a dynamic version.</div>';

# ============================================================================
#                  WCCHAT MODULES : TOOLBAR
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
	<span id="wc_smiley_box" class="closed">{SMILIES}<a href="#" onclick="wc_toggle(\'wc_smiley_icon\'); wc_toggle(\'wc_smiley_box\');">[x]</a></span> 
	<img id="wc_post_loader" src="{INCLUDE_DIR_THEME}images/loader.gif" class="closed">
</div>';

$templates['wcchat.toolbar.joined_status'] = '<a href="#" onclick="wc_toggle_status(\'{CALLER}\'); return false;" id="wc_joined_status_c" class="closed"><img src="{INCLUDE_DIR_THEME}images/joined_{MODE}.png" class="joined_status" title="Toggle Status (Available / Do Not Disturb)"></a>';

$templates['wcchat.toolbar.bbcode'] = '
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'[B]\', \'[/B]\'); return false;"><img src="{INCLUDE_DIR_THEME}images/bbcode/b.png" title="bold"></a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'[I]\', \'[/I]\'); return false;"><img src="{INCLUDE_DIR_THEME}images/bbcode/i.png" title="italic"></a> 
<a href="#" class="underline" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'[U]\', \'[/U]\'); return false;"><img src="{INCLUDE_DIR_THEME}images/bbcode/u.png" title="underlined"></a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'[URL=&quot;http://&quot;]\', \'[/URL]\'); return false;"><img src="{INCLUDE_DIR_THEME}images/bbcode/urlt.png" title="link with description/image"></a> 
<a href="#" onclick="wc_bbcode(document.getElementById(\'{FIELD}\'), \'[IMG]\', \'[/IMG]\'); return false;"><img src="{INCLUDE_DIR_THEME}images/bbcode/img.png" title="Image link not terminating with extension"></a>{ATTACHMENT_UPLOADS}
<span id="wc_smiley_icon{FIELD}"><a href="#" onclick="wc_toggle(\'wc_smiley_icon{FIELD}\'); wc_toggle(\'wc_smiley_box{FIELD}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/smilies/sm1.gif"></a></span>
<span id="wc_smiley_box{FIELD}" class="closed">{SMILIES}<a href="#" onclick="wc_toggle(\'wc_smiley_icon{FIELD}\'); wc_toggle(\'wc_smiley_box{FIELD}\'); return false">[x]</a></span>';

$templates['wcchat.toolbar.bbcode.attachment_uploads'] = ' <a href="#" onclick="wc_attach_test({ATTACHMENT_MAX_POST_N}); return false"><img src="{INCLUDE_DIR_THEME}images/upl.png" id="attachment_upl_icon"  title="Upload Attachments"></a> 
<span id="wc_attach_cont" class="closed"><input id="wc_attach" type="file" class="closed" onchange="wc_attach_upl(\'{CALLER}\', event, \'{INCLUDE_DIR_THEME}\')"></span>';

$templates['wcchat.toolbar.commands'] = '<div id="wc_commands"><a href="#" onclick="wc_showcom(); return false" title="Commands">COM</a> | <a href="#" onclick="wc_clear_screen(\'{CALLER}\'); return false" title="Clear Screen">CLR</a> | <a href="#" onclick="wc_toggle_time(\'{CALLER}\'); return false" title="Toggle TimeStamps">TS</a>{GSETTINGS}{EDIT}</div>';

$templates['wcchat.toolbar.commands.gsettings'] = ' | <a href="#" onclick="wc_toggle(\'wc_msg_container\'); wc_toggle(\'wc_global_settings\'); return false" title="Global Settings">GST</a>';

$templates['wcchat.toolbar.commands.edit'] = ' | <a href="#" onclick="wc_toggle_edit(\'{CALLER}\'); return false" title="Toggle Edit Mode On/Off">EDT</a>';

$templates['wcchat.toolbar.smiley.item'] = '<a href="#" OnClick="wc_bbcode(document.getElementById(\'{field}\'),\'{str_pat}\',\'\'); return false"><img src="{INCLUDE_DIR_THEME}images/smilies/{str_rep}" title="{title}"></a> ';

$templates['wcchat.toolbar.smiley.item.parsed'] = '<img src="{INCLUDE_DIR_THEME}images/smilies/sm{key}.gif">';

$templates['wcchat.error_msg'] = '<div class="wc_error_msg">{ERR}</div>';

$templates['wcchat.toolbar.onload'] = 'onload="wc_updmsg(\'{CALLER}\', \'ALL\', {REFRESH_DELAY}, {CHAT_DSP_BUFFER}, \'{INCLUDE_DIR_THEME}\')"';

$templates['wcchat.toolbar.onload_once'] = 'onload="wc_updmsg_once(\'{CALLER}\', \'ALL\', {CHAT_DSP_BUFFER}, \'{INCLUDE_DIR_THEME}\'); wc_updu(0, \'{CALLER}\', 0, \'ignore_lastmod\')"';

# ============================================================================
#                  WCCHAT MODULES : TEXT INPUT
# ============================================================================

$templates['wcchat.text_input'] = '
<div id="wc_text_input" class="closed">
	<input type="text" id="wc_text_input_field" onkeypress="return wc_post(event, \'{CALLER}\', {REFRESH_DELAY}, {CHAT_DSP_BUFFER});" autocomplete="off">
</div>';

# ============================================================================
#                  WCCHAT MODULES : SETTINGS
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
	<form action="?mode=updsett" onsubmit="wc_upd_settings(\'{CALLER}\', event, \'{INCLUDE_DIR_THEME}\');" method="POST">
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
#                  WCCHAT MODULES : JOIN
# ============================================================================

$templates['wcchat.join'] = '
<div id="wc_join">
	{JOIN}
</div>';

$templates['wcchat.join.inner'] = '
<div id="wc_pass_input">{PASSWORD_REQUIRED}</div>
<input type="submit" id="wc_join_bt" value="{MODE} as {USER_NAME}" onclick="wc_joinchat(\'{CALLER}\', \'join\', {REFRESH_DELAY});">{RECOVER}
{CUSER_LINK}';

$templates['wcchat.join.inner.mode.join'] = 'Join Chat';

$templates['wcchat.join.inner.mode.login'] = 'Login';

$templates['wcchat.join.cuser_link'] = '<div><a href="{CALLER}cuser=1&ret={RETURN_URL}">[Change User]</a></div>';

$templates['wcchat.join.password_required'] = '
<div id="wc_pass_req">
	<div id="wc_pass_err" class="wc_error_msg"></div>
	<b>{USER_NAME}</b>: Password required: <input type="password" id="wc_login_pass" onkeypress="if(event.which == 13 || event.keyCode == 13) { document.getElementById(\'wc_join_bt\').click(); }">
</div>';

$templates['wcchat.join.recover'] = ' <input type="submit" id="wc_join_rec" value="Recover Account" onclick="wc_acc_rec(\'{CALLER}\');">';

# ============================================================================
#                  LOGIN SCREEN
# ============================================================================

$templates['wcchat.login_screen'] = '
	<fieldset id="wc_login_screen">
	<legend>User Login</legend>
	{ERR}
	<form action="#wc_topic" method="POST">
		Name: <input type="text" name="cname" value="{USER_NAME_COOKIE}"> 
		<input type="submit" name="join" value="Login">
	</form>
	<div class="wc_note">(If you can\'t login, you\'ll have to enable cookies!)</div>
</fieldset>';

# ============================================================================
#                  USER LIST MODULES
# ============================================================================

$templates['wcchat.users'] = '
<div id="wc_ulist">
	{ULIST}
</div>';

$templates['wcchat.users.inner'] = '
<div class="wc_separator">JOINED / ONLINE</div>
{JOINED}
{OFFLINE}
{GUESTS}';

$templates['wcchat.users.joined'] = '<table>{USERS}</table>';

$templates['wcchat.users.item'] = '
<tr>
	<td class="avatar">
		<img src="{AVATAR}" {WIDTH} class="{JOINED_CLASS}" title="{STATUS_TITLE}">
	</td>
	<td {NAME_STYLE}>
		{PREFIX}{LINK}{IDLE}{EDIT_BT}
	</td>
</tr>
{EDIT_FORM}';

$templates['wcchat.users.item.edit_bt'] = ' <a href="#" onclick="wc_toggle(\'wc_uedt_{ID}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="wc_edit_bt{OFF}"></a>';

$templates['wcchat.users.item.edit_form'] = '
<tr id="wc_uedt_{ID}" class="closed">
	<td class="wc_form_box" colspan="2">
		<form action="?mode=upd_user" method="POST" onsubmit="wc_upd_user(\'{CALLER}\', \'{ID}\', event)">
			<div id="wc_box1_{ID}">
				<div><b>Moderation</b> | <a href="#" onclick="wc_toggle(\'wc_box1_{ID}\'); wc_toggle(\'wc_box2_{ID}\'); return false">Profile Data</a></div>
				{MODERATOR}
				{BANNED}
				{MUTED}
				{MOD_NOPERM}
			</div>
			<div id="wc_box2_{ID}" class="closed">
				<div><a href="#" onclick="wc_toggle(\'wc_box1_{ID}\'); wc_toggle(\'wc_box2_{ID}\'); return false">Moderation</a> | Profile Data</div>
				{PROFILE_DATA}
			</div>
			<div>
				<input type="submit" value="Update">
			</div>
			<input type="hidden" name="oname" value="{NAME}" class="usett_{ID}">
		</form>
	</td>
</tr>';

$templates['wcchat.users.item.edit_form.moderator'] = '
<div>
	<input type="checkbox" value="1" name="moderator" id="mod_{ID}" class="usett_{ID}" {MOD_CHECKED}> Moderator
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

$templates['wcchat.users.item.icon_moderator'] = ' <img src="{INCLUDE_DIR_THEME}images/mod.png" class="wc_moderator_icon" title="Certified Moderator">';

$templates['wcchat.users.item.icon_muted'] = ' <img src="{INCLUDE_DIR_THEME}images/muted.png" class="wc_muted_icon" title="Muted">';

$templates['wcchat.users.item.icon_ignored'] = ' <img src="{INCLUDE_DIR_THEME}images/ignored.png" class="wc_muted_icon" title="Ignored">';

$templates['wcchat.users.item.width'] = 'style="width: {WIDTH}px"';

$templates['wcchat.users.item.link'] = ' <a href="{LINK}" target="_blank"><img class="wc_web" src="{INCLUDE_DIR_THEME}images/web.png"></a>';

$templates['wcchat.users.item.idle'] = ' <span>(Idle)</span>';

$templates['wcchat.users.item.idle_var'] = ' <span>({IDLE})</span>';

$templates['wcchat.users.joined.void'] = 'There are no joined users.';

$templates['wcchat.users.offline'] = '<div class="wc_separator">OFFLINE</div><table>{USERS}</table>';

$templates['wcchat.users.guests'] = '<div class="wc_separator">GUESTS</div>{USERS}';

$templates['wcchat.users.guests.item'] = '<div class="wc_guest"><img src="{INCLUDE_DIR_THEME}images/guest.png"> {USER} <span>({IDLE} ago)</span></div>';

# ============================================================================
#                  POST MESSAGES
# ============================================================================

$templates['wcchat.posts'] = '<div id="wc_msg_container"></div>';

$templates['wcchat.posts.self'] = '
<div class="wc_msg_item">
	<div class="wc_msg">
		{HIDE_ICON}
		<span class="wc_timestamp" style="{STYLE}">
			{TIMESTAMP}
		</span> 
		<i><b>{USER}</b> <span id="{ID}">{MSG}</span></i>
		</span>
	</div>
</div>';

$templates['wcchat.posts.normal'] = '
<div class="wc_msg_item">
	<div class="wc_msg{PM_SUFIX}">
		{PM_TAG}
		<table>
			<tr>
				<td class="avatar_container">
					<img src="{AVATAR}" class="wc_thumb" {WIDTH} onload="wc_doscroll()">
				</td>
				<td>
					{HIDE_ICON}
					[<span class="wc_user">{POPULATE_START}{USER}{POPULATE_END}{PM_TARGET}</span>]
					<span class="wc_timestamp" style="{STYLE}">
						{TIMESTAMP}
					</span> 
					<div id="{ID}" class="msg_body">{MSG}</div>
				</td>
			</tr>
		</table>
	</div>
</div>';

$templates['wcchat.posts.normal.width'] = 'style="width: {WIDTH}px"';

$templates['wcchat.posts.normal.pm_tag'] = '<span class="wc_private">private</span>';

$templates['wcchat.posts.normal.populate_start'] = '<a href="#wc_text_input" onclick="wc_pop_input(\'/pm \'+this.innerHTML+\' \'); return false;">';

$templates['wcchat.posts.normal.populate_end'] = '</a>';

$templates['wcchat.posts.normal.pm_target'] = ' <img src="{INCLUDE_DIR_THEME}images/arrow.png"> <a href="#wc_text_input" onclick="wc_pop_input(\'/pm \'+this.innerHTML+\' \'); return false; ">{TITLE}</a>';

$templates['wcchat.posts.normal.pm_target.self'] = ' <img src="{INCLUDE_DIR_THEME}images/arrow.png"> {TITLE}';

$templates['wcchat.posts.hide_icon'] = '<a href="#" onclick="wc_toggle_msg(\'{CALLER}\', \'{ID}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/arrow{REVERSE}.png" id="wc_icon_{ID}" title="Hide/Unhide Message" class="wc_hide_icon{OFF}"></a> ';

$templates['wcchat.posts.hidden'] = '<i>This message is hidden.</i>';

$templates['wcchat.posts.event'] = '
<div class="wc_msg">
	<span class="wc_timestamp" style="{STYLE}">
		{TIMESTAMP}
	</span> 
	<i>{MSG}</i>
	</span>
</div>';

$templates['wcchat.posts.older'] = '
<div id="wc_load_older">
	<a href="#" onclick="wc_show_older_msg(\'{CALLER}\', 0, \'{INCLUDE_DIR_THEME}\'); return false">Load Older</a> | 
	<a href="#" onclick="wc_show_older_msg(\'{CALLER}\', 1, \'{INCLUDE_DIR_THEME}\'); return false">Reset</a>
</div>
<div id="wc_older"></div>';

$templates['wcchat.posts.new_msg_separator'] = '<div class="wc_new_msg"><img src="{INCLUDE_DIR_THEME}images/new_msg_separator.png" onload="wc_doscroll()"></div>';

# ============================================================================
#                  CHAT ROOMS
# ============================================================================

$templates['wcchat.rooms'] = '
<div id="wc_rooms">
	<div class="wc_separator">ROOMS</div>
	<div id="wc_room_list">
		{RLIST}
	</div>
</div>';

$templates['wcchat.rooms.inner'] = '{ROOMS}{CREATE}';

$templates['wcchat.rooms.create'] = '
<div id="wc_room_create">
	<a href="#" onclick="wc_toggle(\'wc_croom_box\'); return false;" class="wc_create_link{OFF}" id="wc_create_link">Create New Room</a>
	<div id="wc_croom_box" class="closed">
		<div class="wc_form_box">
			Room Name: <input type="text" id="wc_room_name" value=""> <input type="submit" value="create" onclick="wc_create_room(\'{CALLER}\')">
		</div>
	</div>
</div>';

$templates['wcchat.rooms.current_room'] = '
<div id="wc_room_item" class="wc_current">
	{NEW_MSG} {TITLE}{EDIT_BT}
</div>
{FORM}';

$templates['wcchat.rooms.room'] = '
<div id="wc_room_item">
	{NEW_MSG} <a href="#" onclick="wc_changeroom(\'{CALLER}\', this.innerHTML, \'{INCLUDE_DIR_THEME}\'); return false;">{TITLE}</a>
	{EDIT_BT}
</div>
{FORM}';

$templates['wcchat.rooms.new_msg.on'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg.png" title="Room has new messages">';

$templates['wcchat.rooms.new_msg.off'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg_off.png" title="No new messages">';

$templates['wcchat.rooms.edit_form'] = '
<div id="wc_edt_{ID}" class="closed">
	<div class="wc_form_box">
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
		<input type="submit" value="Update" onclick="wc_update_rname(\'{CALLER}\', \'{ID}\')"> {DELETE_BT}
	</div>
</div>';

$templates['wcchat.rooms.edit_form.delete_bt'] = '<input type="submit" value="Delete" onclick="wc_delete_rname(\'{CALLER}\', \'{ID}\', \'{INCLUDE_DIR_THEME}\')">';

$templates['wcchat.rooms.edit_form.edit_icon'] = ' <a href="#" onclick="wc_toggle(\'wc_edt_{ID}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="wc_edit_bt{OFF}"></a>';

# ============================================================================
#                  WCCHAT MODULES : THEMES
# ============================================================================

$templates['wcchat.themes'] = '<div class="themes"><select onchange="apply_theme(this.value)">{OPTIONS}</select></div>';

$templates['wcchat.themes.option'] = '<option value="{VALUE}"{SELECTED}>{TITLE}</option>';

# ============================================================================
#                  WCCHAT MODULES : GLOBAL SETTINGS
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
				<span>Relative Path from caller page to include directory (Empty if on the same directory)</span>
			</div>
		</fieldset>
		<fieldset>
			<legend>CHAT PARAMETERS</legend>
			<div>
				Refresh Delay: <input type="text" id="gs_refresh_delay" value="{GS_REFRESH_DELAY}" class="gsett"> ms<br>
				<span>Message Refresh Delay (do not set this too low to avoid server overload)</span>
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
				List Guests: <input type="checkbox" id="gs_list_guests" class="gsett" value="1"{GS_LIST_GUESTS}><br>
				<span>List guests (Users that never joined chat) on the user list</span>
			</div>
			<div>
				Anti-SPAM: <input type="text" id="gs_anti_spam" value="{GS_ANTI_SPAM}" class="gsett"> s<br>
				<span>Minimum ammount of time between one user\'s posted messages (seconds, 0 = Disabled)</span>
			</div>
			<div>
				Account Recovery Sender E-mail: <input type="text" id="gs_acc_rec_email" value="{GS_ACC_REC_EMAIL}" class="gsett"><br>
				<span>Default: {ACC_REC_EM_DEFAULT}; The email must exist in the webserver in order for the email function to work.</span>
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
				<tr class="l"><td colspan="6" class="separator"></td></tr>
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
				<tr class="l"><td colspan="6" class="separator"></td></tr>
				<tr>
					<td><img src="{INCLUDE_DIR_THEME}images/arrow.png"> Posts: Add</td>
					<td class="check"><input type="checkbox" value="1" id="mmod_post"{GS_MMOD_POST} class="gsett"></td>
					<td class="check"><input type="checkbox" value="1" id="mod_post"{GS_MOD_POST} class="gsett"></td>
					<td class="check"><input type="checkbox" value="1" id="cuser_post"{GS_CUSER_POST} class="gsett"></td>
					<td class="check"><input type="checkbox" value="1" id="user_post"{GS_USER_POST} class="gsett"></td>
					<td class="check"><input type="checkbox" value="1" id="guest_post"{GS_GUEST_POST} class="gsett"></td>
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
				<tr class="l"><td colspan="6" class="separator"></td></tr>
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

?>