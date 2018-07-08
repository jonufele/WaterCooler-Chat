<?php

/**
 * Settings
 * 
 * @package WaterCooler Chat
 * @author João Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, João Ferreira
 * @since 1.1
 */

# NOTE: It's prefrerrable to use the chat interface to edit the settings below.
#      The data directory path can only be edited manually.

# ============================================================================
#                  MAIN TITLE
# ============================================================================

define('TITLE', 'My Chat'); // Main chat title

# ============================================================================
#                  INCLUDE DIRECTORY
# ============================================================================

define('INCLUDE_DIR', ''); // Relative Path from caller page to chat directory (Empty if on the same directory)

# ============================================================================
#                  CHAT SETTINGS
# ============================================================================

define('REFRESH_DELAY', 5000); // Message Refresh Delay (miliseconds, do not set this too low to avoid server overload)
define('IDLE_START', 300); // Minimum period of time (seconds) to be considered idle
define('OFFLINE_PING', 10); // Minimum period of time (seconds) to be considered offline after last successful ping
define('LOAD_EX_MSG', TRUE); // TRUE - When someone joins chat, the existent messages are displayed; FALSE - Starts with empty screen
define('CHAT_DSP_BUFFER', 100); // Maximum number of chat posts to display
define('CHAT_STORE_BUFFER', 500); // Maximum number of chat posts to store / Available to load
define('CHAT_OLDER_MSG_STEP', 20); // Maximum number of old chat posts to retrieve at once
define('LIST_GUESTS', TRUE); // List guests (Users that never joined chat) on user list
define('ANTI_SPAM', 3); // Minimum ammount of time (seconds) between one user's posted messages (seconds, 0 = disabled)
define('ACC_REC_EMAIL', ''); // Account recovey sender email; default = no-reply@<server_address>; The email account must exist in the webserver.


# ============================================================================
#                  MULTIMEDIA SETTINGS
# ============================================================================

define('IMAGE_MAX_DSP_DIM', 300); // Maximum displayed dimension of images within messages (pixels)
define('IMAGE_AUTO_RESIZE_UNKN', 100); // Resize Images with unknown dimension within messages (pixels)
define('VIDEO_WIDTH', 400); // Video container width
define('VIDEO_HEIGHT', 250); // Video container height
define('AVATAR_SIZE', 0); // width in pixels; Default = 25 (square avatar)
define('DEFAULT_AVATAR', 'images/noav.gif');
define('GEN_REM_THUMB', TRUE); // Generate thumbnails for remote images
define('ATTACHMENT_UPLOADS', TRUE); // Allow upload of attachments
define('ATTACHMENT_TYPES', 'jpg jpeg gif png txt pdf zip'); // Allowed file types in attachments (separated by spaces)
define('ATTACHMENT_MAX_FSIZE', 1024); // KB, Maximum filesize of an attached file
define('ATTACHMENT_MAX_POST_N', 2); // Maximum number of attachments allowed per post


# ============================================================================
#                  DEFAULT ROOM AND THEME
# ============================================================================

define('DEFAULT_ROOM', 'General');
define('DEFAULT_THEME' , 'simple_blue');


# ============================================================================
#                  PERMISSIONS
# ============================================================================
# Letters after underscore: C - Create; E - Edit; D - Delete
# MOD - Moderator; MMOD - Master Moderator; CUSER - Certified User; USER - Non certified user; GUEST - Guest
# Higher levels inherit lower level permissions, so be sure to add the permission 
# to higher levels when you remove it from lower levels
# ============================================================================

define('PERM_GSETTINGS', 'MMOD');
define('PERM_ROOM_C', 'MMOD');
define('PERM_ROOM_E', 'MMOD');
define('PERM_ROOM_D', 'MMOD');
define('PERM_MOD', 'MMOD');
define('PERM_UNMOD', 'MMOD');
define('PERM_USER_E', 'MMOD');

define('PERM_TOPIC_E', 'MMOD MOD');
define('PERM_BAN', 'MMOD MOD');
define('PERM_UNBAN', 'MMOD MOD');
define('PERM_MUTE', 'MMOD MOD');
define('PERM_UNMUTE', 'MMOD MOD');
define('PERM_MSG_HIDE', 'MMOD MOD');
define('PERM_MSG_UNHIDE', 'MMOD MOD');

define('PERM_POST', 'MMOD MOD CUSER USER');
define('PERM_ATTACH_UPL', 'MMOD MOD CUSER USER');
define('PERM_ATTACH_DOWN', 'MMOD MOD CUSER USER GUEST');
define('PERM_PROFILE_E', 'MMOD MOD CUSER USER');
define('PERM_IGNORE', 'MMOD MOD CUSER USER');
define('PERM_PM_SEND', 'MMOD MOD CUSER USER');

define('PERM_ACC_REC', 'GUEST');
define('PERM_LOGIN', 'GUEST');
define('PERM_READ_MSG', 'MMOD MOD CUSER USER GUEST');
define('PERM_ROOM_LIST', 'MMOD MOD CUSER USER GUEST');
define('PERM_USER_LIST', 'MMOD MOD CUSER USER GUEST');


# ============================================================================
#                  INVITE LINK
# ============================================================================
#  Set a random code (letters and numbers) here to restrict first login access to a referral link.
#  Invite link model: <chat_page_url>?invite=<code>
# ============================================================================

define('INVITE_LINK_CODE', '');


# ============================================================================
#                  DATA DIRECTORY (SERVER FILESYSTEM)
# ============================================================================

define('DATA_DIR', __DIR__ . '/data/');

?>