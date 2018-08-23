<?php

/**
 * Settings
 * 
 * @package WaterCooler Chat
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 * @since 1.1
 */

# NOTE: It's prefrerrable to use the chat interface to edit the settings below.
#      The data directory path can only be edited manually here.

# ============================================================================
#                  MAIN TITLE
# ============================================================================

// Main chat title
define('TITLE', 'My Chat');

# ============================================================================
#                  INCLUDE DIRECTORY
# ============================================================================

// Relative Path from web root to chat system (Empty if on the root)
define('INCLUDE_DIR', '');

# ============================================================================
#                  CHAT SETTINGS
# ============================================================================

// Message Refresh Delay (miliseconds, do not set too low to avoid server overload)
define('REFRESH_DELAY', 5000);

// Message Refresh Delay while idling (miliseconds); 0 = Disabled; if on, catch window
// will be wider (event buffer will keep contents longer, logout detection will be slower) 
define('REFRESH_DELAY_IDLE', 30000);

// Minimum period of time (seconds) to be considered idle
define('IDLE_START', 300);

 // Minimum period of time (seconds) to be considered offline after last successful ping
define('OFFLINE_PING', 10);

// Load Existing messages on a new visit
// TRUE = When someone joins chat, the existent messages are displayed;
// FALSE = Starts with empty screen
define('LOAD_EX_MSG', TRUE);

// Maximum number of chat posts to display
define('CHAT_DSP_BUFFER', 100);

// Maximum number of chat posts to store / Available to load
define('CHAT_STORE_BUFFER', 500);

// Maximum number of old chat posts to retrieve at once
define('CHAT_OLDER_MSG_STEP', 20);

// List guests (Users that never joined chat) on user list
define('LIST_GUESTS', TRUE);

// Minimum ammount of time (seconds) between one user's posted messages (seconds, 0 = disabled)
// Note: If microtime function is not available, disabling ANTI_SPAM may generate non-unique message ids
define('ANTI_SPAM', 3);

// Account recovey sender email;
// default = no-reply@<server_address>;
// The email account must exist in the webserver
define('ACC_REC_EMAIL', '');

// Archive messages that drop from Store Buffer (system will keep packing the messages in files the same size as the store buffer, available for users to load)
define('ARCHIVE_MSG', TRUE);

// Allow search engines / automated bots access to the chat's main page
define('BOT_MAIN_PAGE_ACCESS', FALSE);

// Number of seconds a post is available for edition (0 = no limit)
define('POST_EDIT_TIMEOUT', 300);

// Maximum number of characters to display by default in messages (0 = no limit)
define('MAX_DATA_LEN', 500);


# ============================================================================
#                  MULTIMEDIA SETTINGS
# ============================================================================

// Maximum displayed dimension of images within messages (pixels)
define('IMAGE_MAX_DSP_DIM', 300);

// Resize Images with unknown dimension within messages (pixels)
define('IMAGE_AUTO_RESIZE_UNKN', 100);

// Video container width
define('VIDEO_WIDTH', 400);

// Video container height
define('VIDEO_HEIGHT', 250);

// width in pixels; Default = 25 (square avatar)
define('AVATAR_SIZE', 0);

define('DEFAULT_AVATAR', 'images/noav.gif');

// Generate thumbnails for remote images
define('GEN_REM_THUMB', TRUE);

// Allow upload of attachments
define('ATTACHMENT_UPLOADS', TRUE);

// Allowed file types in attachments (separated by spaces)
define('ATTACHMENT_TYPES', 'jpg jpeg gif png txt pdf zip');

// KB, Maximum filesize of an attached file
define('ATTACHMENT_MAX_FSIZE', 1024);

// Maximum number of attachments allowed per post
define('ATTACHMENT_MAX_POST_N', 2);


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
define('PERM_USER_D', 'MMOD');

define('PERM_TOPIC_E', 'MMOD MOD');
define('PERM_BAN', 'MMOD MOD');
define('PERM_UNBAN', 'MMOD MOD');
define('PERM_MUTE', 'MMOD MOD');
define('PERM_UNMUTE', 'MMOD MOD');
define('PERM_MSG_HIDE', 'MMOD MOD');
define('PERM_MSG_UNHIDE', 'MMOD MOD');

define('PERM_POST', 'MMOD MOD CUSER USER');
define('PERM_POST_E', 'MMOD MOD CUSER USER');
define('PERM_ATTACH_UPL', 'MMOD MOD CUSER USER');
define('PERM_ATTACH_DOWN', 'MMOD MOD CUSER USER GUEST');
define('PERM_PROFILE_E', 'MMOD MOD CUSER USER');
define('PERM_IGNORE', 'MMOD MOD CUSER USER');
define('PERM_PM_SEND', 'MMOD MOD CUSER USER');
define('PERM_PM_ROOM', 'MMOD MOD CUSER USER');

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