<?php

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
    <a href="#" onclick="wc_toggle_msg_cont(\'wc_info\'); return false" title="Information">
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

$templates['wcchat.toolbar.commands.gsettings'] = ' <a href="#" onclick="wc_toggle_msg_cont(\'wc_global_settings\'); return false" title="Global Settings"><img src="{INCLUDE_DIR_THEME}images/gsett.png"></a>';

$templates['wcchat.toolbar.commands.edit'] = ' <a href="#" onclick="wc_toggle_edit(\'{CALLER}\', \'{PREFIX}\'); return false" title="Toggle Edit Mode On/Off"><img src="{INCLUDE_DIR_THEME}images/edtmode.png" id="wc_toggle_edit_icon"></a>';

$templates['wcchat.toolbar.smiley.item'] = '<a href="#" onclick="wc_bbcode(document.getElementById(\'{field}\'), \'{cont}\', \'{str_pat}\',\'\'); return false"><img src="{INCLUDE_DIR_THEME}images/smilies/{str_rep}" title="{title}"></a> ';

$templates['wcchat.toolbar.smiley.item.parsed'] = '<img src="{INCLUDE_DIR_THEME}images/smilies/sm{key}.gif">';

$templates['wcchat.error_msg'] = '<div class="error_msg">{ERR}</div>';

$templates['wcchat.toolbar.onload'] = 'onload="wc_refresh_msg(\'{CALLER}\', \'ALL\', {REFRESH_DELAY}, {CHAT_DSP_BUFFER}, \'{INCLUDE_DIR_THEME}\', \'{PREFIX}\')"';

$templates['wcchat.toolbar.onload_once'] = 'onload="wc_refresh_msg_once(\'{CALLER}\', \'ALL\', {CHAT_DSP_BUFFER});"';

?>