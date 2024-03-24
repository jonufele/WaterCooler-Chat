<?php

# ============================================================================
#                         ROOMS
# ============================================================================

$templates['wcchat.rooms'] = '
<div id="wc_rooms">
    <div class="separator">ROOMS</div>
    <div id="wc_room_list{MOB}">
        {RLIST}
    </div>
</div>';

$templates['wcchat.rooms.inner'] = '{ROOMS}<div style="margin-top: 10px">{CREATE}{TOGGLE_DEAD}{SEARCH}</div>';

$templates['wcchat.rooms.toggle_dead'] = '
<a href="#" onclick="wc_toggle_drooms(\'{CALLER}\', \'{PREFIX}\'); return false" ><img src="{INCLUDE_DIR_THEME}images/room.png" style="width: 18px; margin-left: 5px" title="Toggle inactive rooms"></a>';

$templates['wcchat.rooms.search'] = '
<a href="#" onclick="wc_toggle_msg_cont(\'wc_search\'); return false"><img src="{INCLUDE_DIR_THEME}images/search.gif" style="vertical-align: top; position: relative; top: 2px; margin-left: 3px" title="Search"></a>';

$templates['wcchat.rooms.create'] = '
<div id="wc_room_create{MOB}" style="position: absolute">
    <div id="wc_croom_box{MOB}" class="closed">
        <div class="form_box">
			<div style="float:right; font-size: 10px"><a href="#" onclick="wc_toggle(\'wc_croom_box{MOB}\'); return false">Close</a></div>
            Room Name: <input type="text" id="wc_room_name{MOB}" value=""> <input type="submit" value="create" onclick="wc_create_room(\'{CALLER}\', \'{MOB}\')">
			<div style="float:right; font-size: 10px"><input type="checkbox" value="1" id="is_subroom{MOB}"> Sub-room</div>
        </div>
    </div>
</div>
<a href="#" onclick="wc_toggle(\'wc_croom_box{MOB}\'); return false;" class="create_link{OFF}" id="wc_create_link{MOB}"><img src="{INCLUDE_DIR_THEME}images/nroom.png" style="width: 18px" title="Create new room"></a>';

$templates['wcchat.rooms.current_room'] = '
<div class="current room_item" style="{STYLE}">
    {ARROW}{NEW_MSG} {TITLE}{STICKY}{EDIT_BT}
</div>
{FORM}';

$templates['wcchat.rooms.room'] = '
<div class="room_item" style="{STYLE}">
    {ARROW}{NEW_MSG} <a href="#" onclick="wc_change_room(\'{CALLER}\', this.innerHTML, 0); return false;">{TITLE}</a>{STICKY}
    {EDIT_BT}
</div>
{FORM}';

$templates['wcchat.rooms.sticky'] = '<img src="{INCLUDE_DIR_THEME}images/sticky.png" title="Room has new messages" style="width: 12px; height: auto; margin: 0 0 0 2px">';

$templates['wcchat.rooms.new_msg.on'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg.png" title="Room has new messages">';

$templates['wcchat.rooms.new_msg.off'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg_off.png" title="No new messages">';

$templates['wcchat.rooms.new_msg.ons'] = '<img src="{INCLUDE_DIR_THEME}images/nmsgs.png" title="Room has new messages">';

$templates['wcchat.rooms.new_msg.offs'] = '<img src="{INCLUDE_DIR_THEME}images/nmsg_offs.png" title="No new messages">';

$templates['wcchat.rooms.edit_form'] = '
<div id="wc_edt_{ID}{MOB}" class="closed">
    <div class="form_box">
		<a href="#" style="float: right; font-size: 10px" onclick="wc_toggle(\'wc_edt_{ID}{MOB}\'); return false;">Close</a>
        <div>
            Name:<br>
            <input type="text" id="wc_nname_{ID}{MOB}" value="{ROOM_NAME}">
            <input type="hidden" id="wc_oname_{ID}{MOB}" value="{ROOM_NAME}">
        </div>
        <div>
            Post Permission:<br>
            <select id="wc_perm_{ID}{MOB}">
                <option value="0">All Users</option>
                <option value="1"{SEL1}>Master Moderator</option>
                <option value="2"{SEL2}>Moderators</option>
                <option value="3"{SEL3}>Certified User</option>
            </select>
        </div>
        <div>
            Read Permission:<br>
            <select id="wc_rperm_{ID}{MOB}">
                <option value="0">All Users</option>
                <option value="1"{SEL21}>Master Moderator</option>
                <option value="2"{SEL22}>Moderators</option>
                <option value="3"{SEL23}>Certified User</option>
                <option value="4"{SEL24}>User</option>
            </select>
        </div>
        <div><input type="checkbox" id="wc_sticky_{ID}{MOB}" value="1" {CKD}> Sticky</div>
        <input type="submit" value="Update" onclick="wc_upd_room(\'{CALLER}\', \'{ID}{MOB}\')"> {DELETE_BT}
    </div>
</div>';

$templates['wcchat.rooms.edit_form.delete_bt'] = '<input type="submit" value="Delete" onclick="wc_del_room(\'{CALLER}\', \'{ID}{MOB}\')">';

$templates['wcchat.rooms.edit_form.edit_icon'] = ' <a href="#" onclick="wc_toggle(\'wc_edt_{ID}{MOB}\'); return false;"><img src="{INCLUDE_DIR_THEME}images/edit.gif" class="edit_bt{OFF}"></a>';

?>
