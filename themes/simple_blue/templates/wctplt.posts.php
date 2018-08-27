<?php

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
    {PM_TAG}
    <img src="{AVATAR}" class="avatar_thumb" {WIDTH} onload="wc_scroll(\'{ALL}\')">
    <div class="msg_right_col">
        {EDIT_TAG}{HIDE_ICON}
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
    {PARTIAL}.. <a href="#" onclick="wc_toggle(\'wc_msg_partial_{ID}\'); wc_toggle(\'wc_msg_full_{ID}\'); return false;" class="more">[Show More]</a>
</div>
<div id="wc_msg_full_{ID}" class="closed">
    {FULL} <a href="#" onclick="wc_toggle(\'wc_msg_full_{ID}\'); wc_toggle(\'wc_msg_partial_{ID}\'); document.getElementById(\'js_{ID}\').scrollIntoView(); return false;" class="less">[Show Less]</a>
</div>';

$templates['wcchat.posts.edit_container'] = '
<div id="edit_{ID}" class="closed">
</div>';

$templates['wcchat.posts.edit_box'] = '
<span id="edit_loader_{ID}" style="float:right"></span>
<div class="bbcode">{BBCODE}</div>
<textarea id="editbox_cont_{ID}" class="editbox" onkeydown="return wc_poste(event, \'{CALLER}\', \'{ID}\', \'{TAG}\');">{CONTENT}</textarea>
<br><span class="note">Do not edit provided image/video tags, instead, replace with new links.</span>
{NOTE}';

$templates['wcchat.posts.edit_box.note'] = '<br><span class="note">Messages can be edited up to {TIMEOUT} after post time.</span>';

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

$templates['wcchat.posts.new_msg_separator'] = '<div id="wc_new_msg" class="new_msg"><img src="{INCLUDE_DIR_THEME}images/new_msg_separator.png" onload="setTimeout(function() {document.getElementById(\'wc_new_msg\').scrollIntoView();}, 2000);"></div>';

$templates['wcchat.posts.undo_clear_screen'] = 'Screen cleanup (<a href="#" onclick="wc_undo_clear_screen(\'{CALLER}\'); return false;">Undo</a>)';

$templates['wcchat.posts.global_clear_screen'] = 'New Chat Visit';

$templates['wcchat.posts.curr_user_ref'] = '<span class="curr_user_ref">{NAME}</span>';

$templates['wcchat.posts.archived'] = '<img src="{INCLUDE_DIR_THEME}images/archived.png" title="Archived" class="archived">';

?>