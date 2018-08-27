<?php

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

?>