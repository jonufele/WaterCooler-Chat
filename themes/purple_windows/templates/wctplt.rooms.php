<?php

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

?>