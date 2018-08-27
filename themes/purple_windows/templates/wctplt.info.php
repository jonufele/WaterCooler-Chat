<?php

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
            <li>Archived messages are read-only.</li>
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
            <li><img src="{INCLUDE_DIR_THEME}images/archived.png"> - Archived Message (Read-only)</li>
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