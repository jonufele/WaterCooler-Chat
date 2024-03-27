<?php

# ============================================================================
#                  TOPIC
# ============================================================================

$templates['wcchat.topic'] = '
<fieldset id="wc_topic">
    {TOPIC}
</fieldset>';

$templates['wcchat.topic.inner'] = '
<legend>{PATH}{CURRENT_ROOM} <a href="#" onclick="wc_toggle_msg_cont(\'wc_mobrooms\'); return false" id="mobscreen_icon">
	<img src="{INCLUDE_DIR_THEME}images/croom.png" style="width: 18px; height: auto" alt="Get Room List" title="Get Room List">
</a></legend>
<div id="subroom_icon_c">{SUBROOM_ICON}</div>
<div id="wc_topic_box">{TOPIC}</div>';

$templates['wcchat.topic.inner.subroom_icon'] = '
<div style="float: right; margin: 0 0 0 10px" title="{TITLE}">
	<a href="#" onclick="wc_toggle_msg_cont(\'wc_subrooms\'); return false"><img src="{INCLUDE_DIR_THEME}images/sroom{ICON}.png" style="width: 18px; height: auto" id="subroom_icon"></a><span style="font-size: 10px; vertical-align: top">{N}</span>
</div>';

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

?>
