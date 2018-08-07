<?php

/**
 * WaterCooler Chat (Main Class file)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */

/*============================================== 
 Edition/Syntax notes:
------------------------------------------------

 1 Tab = 4 spaces

------------------------------------------------

 - POST/GET/COOKIE getters:
 $this->myPost($id)
 $this->myGet($id) / $this->myCookie($id)
 
 - SESSION/SERVER getters:
 $this->mySession($id)
 $this->myServer($id)

 - COOKIE/SESSION setters:
 $this->wcSetCookie($id, $value)
 $this->wcSetSession($id, $value)

 - COOKIE/SESSION un-setters:
 $this->wcUnsetCookie($id)
 $this->wcUnsetSession($id)

 - filemtime / microtime parsers:
 $this->parseFileMTime($file_path)
 $this->parseMicroTime()

------------------------------------------------

 Template populator:

   $this->popTemplate(
      $id,
      $array = NULL,
      $display_condition = NULL,
      $no_condition_content = NULL
   );

   Raw Template with token:
      'horse color is: {TOKEN}'

   Template source:
      themes/<theme_name>/templates.php

   Usage Example:
      $this->popTemplate(
         'wcchat.tmplt_name', 
         array('TOKEN' => 'white'), 
         $horses === TRUE, 
         'Horse fail!'
      );

   Displays:
      "horse color is: white" or "Horse fail!"
      (depending of $horses value)

================================================*/
    
class WcChat {

    /**
     * Logged In State, only specifies the user has chosen a name to use in chat, may or may not have access to the profile
     *
     * @since 1.4
     * @var bool
     */
    private $isLoggedIn = FALSE;

    /**
     * User Name
     *
     * @since 1.2
     * @var string
     */
    private $name;

    /**
     * Include Directory
     *
     * @since 1.1
     * @var string
     */
    private $includeDir;

    /**
     * Ajax Caller
     *
     * @since 1.1
     * @var string
     */
    private $ajaxCaller;

    /**
     * Avatar Source
     *
     * @since 1.1
     * @var string
     */
    private $uAvatar;

    /**
     * Avatar Display Width
     *
     * @since 1.4
     * @var int
     */
    private $uAvatarW;

    /**
     * User Link (Web)
     *
     * @since 1.1
     * @var string
     */
    private $uWeb;

    /**
     * User Email
     *
     * @since 1.1
     * @var string
     */
    private $uEmail;

    /**
     * User Timezone
     *
     * @since 1.1
     * @var decimal
     */
    private $uTimezone;

    /**
     * User Hour Format (am/pm)
     *
     * @since 1.1
     * @var integer
     */
    private $uHourFormat;

    /**
     * Join Message
     *
     * @since 1.1
     * @var string
     */
    private $joinMsg;

    /**
     * User Data String
     *
     * @since 1.1
     * @var string
     */
    private $userDataString;

    /**
     * User Data
     *
     * @since 1.1
     * @var array
     */
    private $uData;

    /**
     * templates
     *
     * @since 1.1
     * @var array
     */
    private $templates;

    /**
     * Moderator List (Raw)
     *
     * @since 1.1
     * @var string
     */
    private $modList;

    /**
     * Muted List (Raw)
     *
     * @since 1.1
     * @var string
     */
    private $mutedList;

    /**
     * Message List (Raw)
     *
     * @since 1.1
     * @var string
     */
    private $msgList;

    /**
     * Hidden Message List (Raw)
     *
     * @since 1.2
     * @var string
     */
    private $hiddenMsgList;
    
    /**
     * Room Definitions (Raw)
     *
     * @since 1.4
     * @var string
     */
    private $roomDef;

    /**
     * Room Definition (write permission)
     *
     * @since 1.4
     * @var string
     */
    private $roomWPerm;

    /**
     * Room Definition (read permission)
     *
     * @since 1.4
     * @var string
     */
    private $roomRPerm;

    /**
     * Room Definition (Last Archive Volume)
     *
     * @since 1.4
     * @var string
     */
    private $roomLArchVol;

    /**
     * Room Definition (Last Archive Volume Message Count)
     *
     * @since 1.4
     * @var string
     */
    private $roomLArchVolMsgN;

    /**
     * Room Definition (Last Modified)
     *
     * @since 1.4
     * @var string
     */
    private $roomLastMod;

    /**
     * User List (Raw)
     *
     * @since 1.1
     * @var string
     */
    private $userList;

    /**
     * Banned List (Raw)
     *
     * @since 1.1
     * @var string
     */
    private $bannedList;

    /**
     * Event List (Raw)
     *
     * @since 1.1
     * @var string
     */
    private $eventList;

    /**
     * Room Topic
     *
     * @since 1.1
     * @var string
     */
    private $topic;

    /**
     * Moderator Status
     *
     * @since 1.1
     * @var bool
     */
    private $isMod = FALSE;

    /**
     * Master Moderator Status
     *
     * @since 1.2
     * @var bool
     */
    private $isMasterMod = FALSE;

    /**
     * Banned Status
     *
     * @since 1.1
     * @var bool
     */
    private $isBanned = FALSE;

    /**
     * Certified User Status
     *
     * @since 1.1
     * @var bool
     */
    private $isCertifiedUser = FALSE;

    /**
     * Profile Access Status
     *
     * @since 1.3
     * @var bool
     */
    private $hasProfileAccess = FALSE;

    /**
     * Stop Message (Critical Error)
     *
     * @since 1.3
     * @var string
     */
    private $stopMsg;

    /**
     * Data Directory Path
     *
     * @since 1.2
     * @var string
     */
    private $dataDir;

    /**
     * Rooms Directory Path
     *
     * @since 1.2
     * @var string
     */
    private $roomDir;

    /**
     * Window of time to catch events/pings
     *
     * @since 1.4
     * @var int
     */
    private $catchWindow;

    /**
     * Unique prefix to be used in cookies/sessions
     *
     * @since 1.4
     * @var string
     */
    private $wcPrefix;

    /**
     * Include Dir (full Server path)
     *
     * @since 1.4
     * @var string
     */
    private $includeDirServer;

    /**
     * Cookie expire period (days)
     *
     * @since 1.4
     * @var int
     */
    private $cookieExpire = 365;

    /**
     * Construct
     * 
     */
    public function __construct() {

        if(session_id() == '') { session_start(); }

        // Initialize Includes / Paths
        $this->initIncPath();
        
        // Halt if user is automated, apply restriction if access is disabled or login attempt
        $this->botAccess();
        
        // Initialize Current Room
        $this->initCurrRoom();

        // Initialize Data files, print stop message if non writable folders exist
        $this->initDataFiles();

        // Handles main form requests
        $this->handleMainForm();

        // Initializes user variables
        $this->initUser();
    }

      /*===============================================
       |       METHOD CATEGORIES                      |
       ================================================
       |  INITIALIZATION (6)                          |
       |  USER DATA HANDLING (3)                      |
       |  ACCESS SECURITY (3)                         |
       |  FILE WRITE/READ (5)                         |
       |  IMAGE HANDLING (4)                          |
       |  PGC/SESSION/SERVER HANDLING (8)             |
       |  SETTERS/GETTERS (10)                        |
       |  PARSER / INTERFACER (13)                    |
       |  AJAX COMPONENTS (6)                         |
       |  AJAX PROCESSOR (1)                          |
       |  OTHER METHODS (3)                           |
       ===============================================*/

      /*===========================================
       |       INITIALIZATION METHODS             |
       ============================================
       |  initIncPath                             |
       |  initUser                                |
       |  hasNonWritableFolders                   |
       |  initDataFiles                           |
       |  initCurrRoom                            |
       |  initCurrRoomDef                         |
       ===========================================*/

    /**
     * Initializes Includes / Paths
     * 
     * @since 1.4
     * @return void
     */
    private function initIncPath() {

        $this->includeDirServer = __DIR__ . '/';
    
        include $this->includeDirServer . 'settings.php';
        $this->wcPrefix = strtoupper(dechex(crc32($this->includeDirServer)));
        
        // Halt if settings has errors
        $settings_has_errors = $this->settingsHasErrors();
        if($settings_has_errors !== FALSE) {
            echo '
                <b>Settings configuration has errors</b>
                <br>(Use the interface editor in the future to avoid this)<br><br> 
                please fix the following errors in settings.php:<br><br>' . 
                $settings_has_errors
            ;
            die(); 
        }
        
        $this->dataDir = (DATA_DIR ? rtrim(DATA_DIR, '/') . '/' : '');
        $this->roomDir = DATA_DIR . 'rooms/';
        $this->includeDir = (
            trim(INCLUDE_DIR, '/') ? 
            '/' . trim(INCLUDE_DIR, '/') . '/' : 
            '/'
        );
        
        // Attempt to set the include dir on the very first run
        if(
            !file_exists($this->roomDir . base64_encode(DEFAULT_ROOM) . '.txt') && 
            $this->myServer('REQUEST_URI') != '/' && 
            is_writable($this->includeDirServer . 'settings.php')
        ) {
            $this->includeDir = '/' . trim($this->myServer('REQUEST_URI'), '/') . '/';
            $this->updateConf(
                array('INCLUDE_DIR' => trim($this->myServer('REQUEST_URI'), '/'))
            );
        }
        
        define('THEME', (
            (
                $this->myCookie('wc_theme') && 
                file_exists($this->includeDirServer . 'themes/' . $this->myCookie('wc_theme') . '/')
            ) ? 
            $this->myCookie('wc_theme') : 
            DEFAULT_THEME
        ));
        
        define('INCLUDE_DIR_THEME', $this->includeDir . 'themes/' . THEME . '/');
        include $this->includeDirServer . 'themes/' . THEME . '/templates.php';
        $this->templates = $templates;
        $this->ajaxCaller = $this->includeDir . 'ajax.php?';
    }

    /**
     * Initializes User's variables
     * 
     * @since 1.4
     * @return void
     */
    private function initUser() {

        if(!$this->mySession('cname')) {
            // Try use cookie value to resume session
            if($this->myCookie('cname') && !$this->mySession('skip_cookie')) {
                if($this->userMatch($this->myCookie('cname')) !== FALSE) {
                    $this->wcSetSession('cname', $this->myCookie('cname'));
                    $this->name = $this->myCookie('cname');
                    $this->isLoggedIn = TRUE;
                } else {
                    // Cookie user does not exist / outdated, fail to resume session
                    // Inform user via login error message
                    $this->wcSetSession('login_err', 
                        'Username <i>' . $this->myCookie('cname') . 
                        '</i> does not exist, cannot be resumed!<br>
                        (Maybe it was renamed by a moderator?)'
                    );
                    $this->wcUnsetCookie('cname');
                }
            }
        } else {
                $this->name = $this->mySession('cname');
                $this->isLoggedIn = TRUE;
        }

        if(!$this->name && !$this->isLoggedIn) {
            $this->name = 'Guest';
        }

        if($this->name && $this->name != 'Guest') {
            $this->isBanned = $this->getBanned($this->name);
        }

        $this->uData = $this->userData();

        // Check if user credentials are outdated, issue an alert message to inform user
        if(
            (
                $this->uData[7] == 0 || 
                (
                    $this->myCookie('chatpass') != $this->uData[5] && 
                    $this->myCookie('chatpass') && 
                    $this->uData[5]
                )
            ) && 
            $this->mySession('cname') && 
            $this->myGet('mode')
        ) {
            // If dummy profile, it means the previous name was changed, clear SESSION value
            if($this->uData[7] == 0) { $this->wcUnsetSession('cname'); }

            // Clear password cookie, user must supply it again
            $this->wcUnsetCookie('chatpass');
            $this->isLoggedIn = FALSE;

            // Issue alert
            $this->wcSetSession('alert_msg',
                'Your login credentials are outdated!' . "\n" .
                'Refresh page (or use the form below) and login again!' . "\n" .
                '(Possible cause: You (or a moderator) edited your profile.)'
            );
        }

        $this->uAvatar      = $this->uData[0];
        $this->uEmail       = $this->uData[1];
        $this->uWeb         = $this->uData[2];
        $this->uTimezone    = $this->uData[3];
        $this->uHourFormat  = $this->uData[4];
        $this->uPass        = $this->uData[5]; 
        $this->uAvatarW     = (AVATAR_SIZE ? AVATAR_SIZE : 25);

        $this->userDataString = base64_encode(
            base64_encode($this->uAvatar) . '|' .
            base64_encode($this->uEmail) . '|' .
            base64_encode($this->uWeb) . '|' .
            $this->uTimezone . '|' .
            $this->uHourFormat . '|' .
            $this->uPass
        );
    
        // Set certified User status
        if(
            $this->hasData($this->uPass) && 
            $this->myCookie('chatpass') && 
            $this->myCookie('chatpass') == $this->uPass
        ) {
            $this->isCertifiedUser = TRUE;
        }

        // Set has perofile access status
        if(
            $this->isLoggedIn && 
            ($this->isCertifiedUser || !$this->uPass)
        ) {
            $this->hasProfileAccess = TRUE;
        }

        // Assign the first moderator if does not exist and current user is certified
        if(strlen(trim($this->modList)) == 0 && $this->isCertifiedUser) {
            $this->writeFile(MODL, "\n" . base64_encode($this->name), 'w');
            touch(ROOMS_LASTMOD);
            $this->wcSetSession(
                'alert_msg', 
                'You have been assigned Master Moderator, reload page in order to get all moderator tools.'
            );
        }

        // Set Master Moderator and Moderator status
        if($this->name && $this->isCertifiedUser) {
            if($this->getMod($this->name) !== FALSE) { $this->isMod = TRUE; }
            $mods = explode("\n", trim($this->modList));
            if($mods[0] == base64_encode($this->name)) {
                $this->isMasterMod = TRUE;
            }
        }

        // Set catch window according to user idle status
        $this->catchWindow = $this->getCatchWindow();
    }

    /**
     * Checks if non writable folders exist
     * 
     * @since 1.4
     * @return bool|string directory list
     */
    private function hasNonWritableFolders() {

        $output = '';
        $tmp1 = is_writable($this->roomDir);
        $tmp2 = is_writable($this->dataDir);
        $tmp3 = is_writable($this->dataDir . 'tmp/');
        $tmp4 = is_writable($this->includeDirServer . 'files/');
        $tmp5 = is_writable($this->includeDirServer . 'files/attachments/');
        $tmp6 = is_writable($this->includeDirServer . 'files/avatars/');
        $tmp7 = is_writable($this->includeDirServer . 'files/thumb/');

        if(!$tmp1 || !$tmp2 || !$tmp3 || !$tmp4 || !$tmp5 || !$tmp6 || !$tmp7) {

            if(!$tmp1 || !$tmp2 || !$tmp3) {
                $output .= '<h2>Data Directories (Absolute Server Path)</h2>';
            }

            if(!$tmp1) { $output .= addslashes($this->roomDir) . "\n"; }
            if(!$tmp2) { $output .= addslashes($this->dataDir) . "\n"; }
            if(!$tmp3) { $output .= addslashes($this->dataDir . 'tmp/') . "\n"; }
            if(!$tmp4 || !$tmp5 || !$tmp6 || !$tmp7) {
                $output .= '<h2>File Directories (Relative Web Path)</h2>';
            }
            if(!$tmp4) { $output .= addslashes($this->includeDir . 'files/') . "\n"; }
            if(!$tmp5) { $output .= addslashes($this->includeDir . 'files/attachments/') . "\n"; }
            if(!$tmp6) { $output .= addslashes($this->includeDir . 'files/avatars/') . "\n"; }
            if(!$tmp7) { $output .= addslashes($this->includeDir . 'files/thumb') . "\n"; }

            return nl2br(trim($output));
        } else {
            return FALSE;
        }
    }

    /**
     * Checks if settings errors exist
     * 
     * @since 1.4
     * @return bool|string error list
     */    
    private function settingsHasErrors() {
    
        // Halt if errors exist
        $errors = '';
        if(
            !is_numeric(REFRESH_DELAY) || 
            intval(REFRESH_DELAY) < 1
        ) {
            $errors = '- REFRESH_DELAY must be an integer >= 1' . "\n";
        }
        
        if(
            !is_numeric(REFRESH_DELAY_IDLE) || 
            intval(REFRESH_DELAY_IDLE) < 0
        ) {
            $errors .= '- REFRESH_DELAY_IDLE must be an integer >= 0' . "\n";
        }
        
        if(
            !is_numeric(IDLE_START) || 
            intval(IDLE_START) < 60
        ) {
            $errors .= '- IDLE_START must be an integer >= 60' . "\n";
        }
        
        if(
            !is_numeric(OFFLINE_PING) || 
            intval(OFFLINE_PING) < 1
        ) {
            $errors .= '- OFFLINE_PING must be an integer >= 1' . "\n";
        }
                
        if(
            !is_numeric(ANTI_SPAM) || 
            intval(ANTI_SPAM) < 0
        ) {
            $errors .= '- ANTI_SPAM must be an integer >= 0' . "\n";
        }
        
        if(
            !is_numeric(CHAT_OLDER_MSG_STEP) || 
            intval(CHAT_OLDER_MSG_STEP) < 1
        ) {
            $errors .= '- CHAT_OLDER_MSG_STEP must be an integer >= 1' . "\n";
        }
        
        if(
            intval(CHAT_DSP_BUFFER) > intval(CHAT_STORE_BUFFER) || 
            !is_numeric(CHAT_DSP_BUFFER) || 
            !is_numeric(CHAT_STORE_BUFFER)
        ) {
            $errors .= '- CHAT_STORE_BUFFER must be >= CHAT_DSP_BUFFER' . "\n";
        }
        
        if(ACC_REC_EMAIL) {
            if(!filter_var(ACC_REC_EMAIL, FILTER_VALIDATE_EMAIL)) {
                $errors .= '- ACC_REC_EMAIL is invalid!' . "\n";
            }
        }
        
        if(
            !file_exists($this->roomDir . base64_encode(DEFAULT_ROOM) . '.txt') && 
            $this->hasData($this->roomDir)
        ) {
            $errors .= '- DEFAULT_ROOM is invalid' . "\n";
        }
        
        if(!file_exists($this->includeDirServer . 'themes/' . DEFAULT_THEME)) {
            $errors .= '- DEFAULT_THEME is invalid' . "\n";
        }
        
        return ($errors ? nl2br(trim($errors)) : FALSE);
    }

    /**
     * Initializes Data Files
     * 
     * @since 1.2
     * @return void|string Html template
     */
    private function initDataFiles() {

        // Halt if non writable folders exist
        $has_non_writable_folders = $this->hasNonWritableFolders();
        if($has_non_writable_folders) {
            echo $this->popTemplate(
                'wcchat.critical_error', 
                array(
                    'ERROR' => 'Non Writable Data / File directories exist, 
                        please set write permissions to the following directories:<br><br>' . 
                        $has_non_writable_folders
                )
            );
            die(); 
        }

        define('MESSAGES_LOC', 
            $this->roomDir . base64_encode($this->mySession('current_room')) . '.txt'
        );
        define('TOPICL', 
            $this->roomDir . 'topic_' . base64_encode($this->mySession('current_room')) . '.txt'
        );
        define('ROOM_DEF_LOC', 
            $this->roomDir . 'def_' . base64_encode($this->mySession('current_room')) . '.txt'
        );
        define('MESSAGES_HIDDEN', 
            $this->roomDir . 'hidden_' . base64_encode($this->mySession('current_room')) . '.txt'
        );
        define('USERL', $this->dataDir . 'users.txt');
        define('MODL', $this->dataDir . 'mods.txt');
        define('MUTEDL', $this->dataDir . 'muted.txt');
        define('BANNEDL', $this->dataDir . 'bans.txt');
        define('EVENTL', $this->dataDir . 'events.txt');
        define('ROOMS_LASTMOD', $this->dataDir . 'rooms_lastmod.txt');

        if(!file_exists(USERL)) { file_put_contents(USERL, ''); }
        if(!file_exists(MODL)) { file_put_contents(MODL, ''); }
        if(!file_exists(MUTEDL)) { file_put_contents(MUTEDL, ''); }
        if(!file_exists(BANNEDL)) { file_put_contents(BANNEDL, ''); }
        if(!file_exists(EVENTL)) { file_put_contents(EVENTL, ''); }
        
        // Initialize messages file with the creation note
        if(!file_exists(MESSAGES_LOC)) {
            file_put_contents(
                MESSAGES_LOC,
                    time() . '|*' . base64_encode('Room') . '| has been created.' . "\n"
            );
        }
        
        if(!file_exists(TOPICL)) { file_put_contents(TOPICL, ''); }
        if(!file_exists(ROOM_DEF_LOC)) {
            file_put_contents(
                ROOM_DEF_LOC, 
                '0|0|0|0|' . $this->parseMicroTime()
            );
        }
        if(!file_exists(ROOMS_LASTMOD)) { file_put_contents(ROOMS_LASTMOD, ''); }
        if(!file_exists(MESSAGES_HIDDEN)) { file_put_contents(MESSAGES_HIDDEN, ''); }

        $this->modList          = $this->readFile(MODL);
        $this->mutedList        = $this->readFile(MUTEDL);
        $this->msgList          = $this->readFile(MESSAGES_LOC);
        $this->roomDef          = $this->readFile(ROOM_DEF_LOC);
        $this->hiddenMsgList    = $this->readFile(MESSAGES_HIDDEN);
        $this->userList         = $this->readFile(USERL);
        $this->topic            = $this->readFile(TOPICL);
        $this->bannedList       = $this->readFile(BANNEDL);
        $this->eventList        = $this->readFile(EVENTL);
        
        // Write the first message to the room (creation note) if no messages exist
        if(strlen($this->msgList) == 0) {
            $towrite = $this->parseMicroTime() . '|*' . base64_encode('room') . '|has been created.' . "\n";
            $this->writeFile(MESSAGES_LOC, $towrite, 'w');
            $this->updateCurrRoomLastMod();
            $this->msgList = $this->readFile(MESSAGES_LOC);
        }
        
        // Initialize Current Room Definitions
        $this->initCurrRoomDef();
    }

    /**
     * Initializes current room variables
     * 
     * @since 1.4
     * @return void
     */
    private function initCurrRoom() {
 
        // Initialize Current Room Session / Cookie
        if(!$this->mySession('current_room')) {
            $this->wcSetSession('current_room', (
                (
                    $this->myCookie('current_room') && 
                    file_exists($this->roomDir . base64_encode($this->myCookie('current_room')) . '.txt')
                ) ? 
                $this->myCookie('current_room') : 
                DEFAULT_ROOM
            ));
        } elseif(
            !file_exists($this->roomDir . base64_encode($this->mySession('current_room')) . '.txt')
        ) {
            $this->wcSetSession('current_room', DEFAULT_ROOM);
            $this->wcSetSession('reset_msg', '1');
            $this->wcSetCookie('current_room', DEFAULT_ROOM);
        }
    }
    
    /**
     * Initiates all definitions for the current room
     * 
     * @since 1.4
     * @return void
     */    
    private function initCurrRoomDef() {
    
        list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $last_mod) = explode('|', $this->roomDef);
        $this->roomWPerm        = intval($wperm);
        $this->roomRPerm        = intval($rperm);
        $this->roomLArchVol     = intval($larch_vol);
        $this->roomLArchVolMsgN = intval($larch_vol_msg_n);
        $this->roomLastMod      = $last_mod;
    }

      /*===========================================
       |       USER DATA HANDLING METHODS         |
       ============================================
       |  userMatch                               |
       |  userData                                |
       |  userLAct                                |
       |  getCatchWindow                          |
       ===========================================*/

    /**
     * Checks if a user exists in the user list, if requested, returns match as array
     * 
     * @since 1.1
     * @param string $name
     * @param string|null $v Supplied Data Row
     * @param string|null $return_match
     * @return bool|array
     */
    private function userMatch($name, $v = NULL, $return_match = NULL) {
    
         // Check if the row has been supplied
        if($v !== NULL) {
            list($tmp, $tmp2) = explode('|', $v, 2);
            if($tmp == base64_encode($name) && $this->hasData(trim($name, ' '))) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            // Row has not been supplied, try to find a match on the raw user list
            if(
                strpos(
                    "\n" . trim($this->userList), 
                    "\n" . base64_encode($name) . '|'
                ) !== FALSE && 
                $this->hasData(trim($name, ' '))
            ) {
                if($return_match !== NULL) {
                    list($tmp, $v2) = explode(
                        "\n" . base64_encode($name) . '|', "\n" . trim($this->userList), 
                        2
                    );
                    if(strpos($v2, "\n") !== FALSE) {
                        list($v3, $tmp1) = explode("\n", $v2, 2);
                    } else {
                        $v3 = $v2;
                    }
                    $par = explode('|', $v3);
                    return array(
                        (base64_encode($name)),
                        (isset($par[0]) ? $par[0] : ''),
                        (isset($par[1]) ? $par[1] : 0),
                        (isset($par[2]) ? $par[2] : 0),
                        (isset($par[3]) ? $par[3] : 0)
                    );
                } else {
                    return TRUE;
                }
            } else {
                if($return_match === NULL) {
                    return FALSE;
                } else {
                    return array('', '', 0, 0, 0);
                }
            }
        }
    }

    /**
     * Retrieves all data for a user (With the exception of the name)
     * 
     * @since 1.1
     * @param string $forced_name
     * @return array
     */
    private function userData($forced_name = NULL) {
    
        $name = (isset($forced_name) ? $forced_name : $this->name);

        // Get user's row
        list($name, $data, $time, $time2, $status) = $this->userMatch($name, NULL, '1');

        // Break data into an array
        if(isset($data)) {
            if(strlen($data) > 0 && strpos(base64_decode($data), '|') !== FALSE) {
                list($av, $email, $lnk, $tmz, $ampm, $pass) = explode('|', base64_decode($data));
                return array(
                    base64_decode($av), 
                    base64_decode($email), 
                    base64_decode($lnk), 
                    $tmz, 
                    $ampm, 
                    $pass, 
                    $time, 
                    $time2, 
                    $status
                );
            }
        }

        return array('', '', '', '0', '0', '', 0, 0, 0);
    }

    /**
     * Retrieves user's last activity (Posts, Logins, Joins)
     * 
     * @since 1.1
     * @param string $forced_name
     * @return int
     */
    private function userLAct($forced_name = NULL) {
    
        $time2 = 0;

        if($forced_name != NULL) {
            list($name, $data, $time, $time2, $status) = 
                $this->userMatch($forced_name, NULL, '1');
        } else {
            $time2 = $this->uData[7];
        }
        return $time2;
    }
    
    /**
     * Retrieves default catchWindow
     * 
     * @since 1.1
     * @param string $forced_idle
     * @return int
     */
    private function getCatchWindow() {         
    
        return 
        (
            REFRESH_DELAY_IDLE != 0 ? 
            intval(REFRESH_DELAY_IDLE/1000) : 
            intval(REFRESH_DELAY/1000)
        ) + 
        OFFLINE_PING;   
    }

      /*===========================================
       |       ACCESS SECURITY METHODS            |
       ============================================
       |  hasPermission                           |
       |  hasRoomPermission                       |
       |  botAccess                               |
       ===========================================*/

    /**
     * Checks if current user has permission to perform/access the $id action
     * 
     * @since 1.2
     * @param string $id
     * @param string|null $skip_msg
     * @return bool
     */
    private function hasPermission($id, $skip_msg = NULL) {
    
        $level = '';
        $tags = array();

        // Attribute a tag to the user
        if($this->isLoggedIn) {
            if($this->isMasterMod === TRUE) {
                $level = 'MMOD';
            } elseif($this->isMod === TRUE) {
                $level = 'MOD';
            } elseif($this->isCertifiedUser) {
                $level = 'CUSER';
            } elseif(!$this->uPass) {
                $level = 'USER';
            } else {
                $level = 'GUEST';
            }
        } else {
            $level = 'GUEST';
        }
        
        // Try to find a match within the permission constant, no match = no permission
        $tags = explode(' ', constant('PERM_' . $id));

        if(in_array($level, $tags)) {
            return TRUE;
        } else {
            if($skip_msg == NULL) {
                echo 'You do not have permission to perform this action!';
            }
            return FALSE;
        }
    }

    /**
     * Checks if user has permission to read/write from/to a room 
     * 
     * @since 1.2
     * @param string $room_name
     * @param string $mode Write(W)/Read(R)
     * @return bool
     */
    private function hasRoomPermission($room_name, $mode) {

        // $tmp = Unused slots in v1.4.10
        list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $tmp) = 
            explode(
                '|', 
                $this->readFile(
                    $this->roomDir . 
                    'def_' . 
                    base64_encode($room_name) . 
                    '.txt'
                )
            );
        $permission = FALSE;

        // Compare the number with the respective user group
        $target = (($mode == 'W') ? $wperm : $rperm);
        switch($target) {
            case '1':
                if($this->isLoggedIn && $this->isMasterMod === TRUE) { $permission = TRUE; }
            break;
            case '2':
                if($this->isLoggedIn && $this->isMod === TRUE) { $permission = TRUE; }
            break;
            case '3':
                if($this->isLoggedIn && $this->isCertifiedUser === TRUE) { $permission = TRUE; }
            break;
            case '4':
                if($this->isLoggedIn && !$this->uPass) {
                    $permission = TRUE;
                }
            break;
            default: $permission = TRUE;
        }
        return $permission;
    }

    /**
     * Checks bot access / Prevents bot login
     * 
     * @since 1.4
     * @return void
     */
    private function botAccess() {
    
        if(BOT_MAIN_PAGE_ACCESS === FALSE || $this->myPost('cname')) {
            require_once $this->includeDirServer . "includes/bots.php";
            if (preg_match($wc_botcheck_regex, $this->myServer('HTTP_USER_AGENT'))) {
                header("HTTP/1.0 403 Forbidden");
                echo $this->popTemplate('wcchat.botNoAccessNote');
                exit;
            }
        }
    }

      /*===========================================
       |       FILE WRITE/READ METHODS            |
       ============================================
       |  writeFile                               |
       |  writeEvent                              |
       |  readFile                                |
       |  updateConf                              |
       |  updateCurrRoomLastMod                   |
       ===========================================*/

    /**
     * Writes to a file and locks write access
     * 
     * @since 1.1
     * @param string $file
     * @param string $content
     * @param string $mode Write Mode
     * @param string|null $allow_empty_content
     * @return void
     */
    private function writeFile($file, $content, $mode, $allow_empty_content = NULL) {
    
        if(strlen(trim($content)) > 0 || $allow_empty_content) {
            $handle = fopen($file, $mode);
            while (!flock($handle, LOCK_EX | LOCK_NB)) {
                sleep(1);
            }
            fwrite($handle, $content);
            fflush($handle);
            flock($handle, LOCK_UN);

            fclose($handle);
        }
    }

    /**
     * Writes an event to the event messages file (temporary messages)
     * 
     * @since 1.1
     * @param string $omode
     * @param string $target User Name
     * @return void|string Html template
     */
    private function writeEvent($omode, $target, $return_raw_line = NULL) {

        // Overwrite content if last modified time is higher than Idle catchWindow
        // (Enough time for all online users to get the update (Idle or not))
        if((time()-$this->parseFileMTime(EVENTL)) > $this->catchWindow) { 
            $mode = 'w'; 
        } else { 
            $mode = 'a';
        }

        // Write event according to mode value
        switch($omode) {
            case 'ignore':
                $towrite = $this->parseMicroTime() . '|' . base64_encode($this->name) . '|' . 
                    base64_encode($target) . '|<b>' . $this->name . 
                    '</b> is ignoring you.' . "\n";
            break;
            
            case 'unignore':
                $towrite = $this->parseMicroTime() . '|' . base64_encode($this->name) . '|' . 
                    base64_encode($target) . '|<b>' . $this->name . 
                    '</b> is no longer ignoring you.' . "\n";
            break;
            
            case 'join':
                $towrite = $this->parseMicroTime() . '|' . base64_encode($this->name) . '|<b>' . 
                $this->name . '</b> has joined chat.' . "\n";
            break;
            
            case 'topic_update':
                $towrite = $this->parseMicroTime() . '|' . base64_encode($this->name) . '|<b>' . 
                $this->name . '</b> updated the topic (' . $this->mySession('current_room') . 
                ').' . "\n";
            break;
        }
        $this->writeFile(EVENTL, $towrite, $mode);

        if($return_raw_line !== NULL) {
            return $towrite;
        }
    }

    /**
     * Reads a file while stripping troublesome characters
     * 
     * @since 1.4
     * @param string $source file path
     * @return string
     */
    private function readFile($source) {
    
        return
            str_replace(
                array(
                    "\r",
                    "\t",
                    "\013",
                    "\014"
                ),
                '',
                file_get_contents($source)
            );
    }

    /**
     * Updates settings.php file
     * 
     * @since 1.3
     * @param array $array
     * @return bool
     */
    private function updateConf($array) {

        $conf = $this->readFile($this->includeDirServer . 'settings.php');
        $conf_bak = $conf;

        foreach($array as $key => $value) {
            $constant_value = constant($key);
            if($constant_value === TRUE) { $constant_value = 'TRUE'; }
            if($constant_value === FALSE) { $constant_value = 'FALSE'; }
            if(ctype_digit($value) || $value == 'TRUE' || $value == 'FALSE') {
                $conf = str_replace(
                    "define('{$key}', {$constant_value})", 
                    "define('{$key}', {$value})", 
                    $conf
                );
            } else {
                $conf = str_replace(
                    "define('{$key}', '{$constant_value}')", 
                    "define('{$key}', '{$value}')", 
                    $conf
                );
            }
        }
        $handle = fopen($this->includeDirServer . 'settings.php', 'w');
        fwrite($handle, $conf);
        fclose($handle);

        if($this->readFile($this->includeDirServer . 'settings.php') != $conf_bak) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Updates Current Room Last Modified Bit
     * 
     * @since 1.4
     * @return void
     */    
    private function updateCurrRoomLastMod() {
    
        list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $last_mod) = 
            explode('|', $this->roomDef);
        
        $this->writeFile(
            ROOM_DEF_LOC,
            $wperm . '|' .
            $rperm . '|' .
            $larch_vol . '|' .
            $larch_vol_msg_n . '|' .
            $this->parseMicroTime(),
            'w'
        );
    }

      /*===========================================
       |       IMAGE HANDLING METHODS             |
       ============================================
       |  initImg                                 |
       |  parseImg                                |
       |  thumbnailCreateMed                      |
       |  thumbnailCreateCr                       |
       ===========================================*/

    /**
     * Initializes an image resource
     * 
     * @since 1.4
     * @param string $original_image Image URL Address
     * @return resource|bool
     */
    private function initImg($original_image) {

        $source_image = '';
        if(!function_exists('gd_info')) { return FALSE; }

        // Try to use the faster exif_imagetype function if it exists
        if(function_exists('exif_imagetype')) {
            $type = exif_imagetype($original_image);
        } else {
            $tmp = @getimagesize($original_image);
            $type = $tmp[2];
        }

        // Create the image resource
        if($type == 1) {
            $source_image = @ImageCreateFromGIF($original_image);
        }

        if($type == 2) {
            $source_image = @ImageCreateFromJPEG($original_image);
        }

        if($type == 3) {
            $source_image = @ImageCreateFromPNG($original_image);
        }

        if($source_image) {
            return $source_image;
        } else {
            return FALSE;
        }
    }

    /**
     * Corverts simple BBCODE image tags to enhanced tags with the image details, generates thumbnails if necessary/possible
     * 
     * @since 1.1
     * @param string $s User Post
     * @param string|null $attach Specifies If It Must Be Treated As An Attachment
     * @return string
     */
    private function parseImg($s, $attach = NULL) {

        $source = (is_array($s) ? $s[1] : $s);
        $source_tag = (
            ($attach === NULL) ? 
            $source : 
            str_replace($this->includeDirServer . 'files/attachments/', '', $source)
        );

        // Use http instead of https (in case https wrapper is not installed)
        $iname = str_replace('https://', 'http://', $source);
        $image = $this->initImg($iname);

        if($image) {
            $w = imagesx($image);
            $h = imagesy($image);
        } else {
            $w = $h = 0;
        }

        $nw = $nh = 0;

        // Generate image tags containing the dimensions
        if($w && $h) {
            if($h > IMAGE_MAX_DSP_DIM || $w > IMAGE_MAX_DSP_DIM) {
                if($w >= $h) {
                    $nh = intval(($h * IMAGE_MAX_DSP_DIM) / $w);
                    $nw = IMAGE_MAX_DSP_DIM;
                } else {
                    $nw = intval(($w * IMAGE_MAX_DSP_DIM) / $h);
                    $nh = IMAGE_MAX_DSP_DIM;
                }

                $target = 'files/thumb/tn_' . strtoupper(dechex(crc32($iname))) . '.jpg';
                if(GEN_REM_THUMB) {
                    if(
                        $this->thumbnailCreateMed(
                            $image,
                            $w,
                            $h, 
                            $this->includeDirServer . $target,
                            IMAGE_MAX_DSP_DIM
                        )
                    ) {
                        return 
                            '[IMG' . 
                                ($attach != NULL ? 'A' : '') . '|' . 
                                $w . 'x' . $h . '|' . $nw . 'x' . $nh . 
                                '|tn_' . strtoupper(dechex(crc32($iname))) . 
                            ']' . 
                                $source_tag . 
                            '[/IMG]'
                        ;
                    } else {
                        return 
                            '[IMG' . 
                                ($attach != NULL ? 'A' : '') . '|' . 
                                $w . 'x' . $h . '|' . $nw . 'x' . $nh . 
                            ']' . 
                                $source_tag . 
                            '[/IMG]';
                    }
                } else {
                    return 
                        '[IMG' . 
                            ($attach != NULL ? 'A' : '') . '|' . 
                            $w . 'x' . $h . '|' . $nw . 'x' . $nh . 
                        ']' . 
                            $source_tag . 
                        '[/IMG]';
                }
            } else {
                return '[IMG]' . $source . '[/IMG]';
            }
        } else {
                return '[IMG|' . IMAGE_AUTO_RESIZE_UNKN . ']' . $source . '[/IMG]';
        }
    }

    /**
     * Generates a normal thumbnail
     * 
     * @since 1.3
     * @param string $original_image Path
     * @param resource $source_image
     * @param int $w Image Width
     * @param int $h Image Height
     * @param string $target_image
     * @param int $thumbsize
     * @return bool
     */
    private function thumbnailCreateMed (
        $source_image, $w, $h, $target_image,
        $thumbsize
    )
    {
        if(!$source_image || !function_exists('gd_info')) { return FALSE; }

        // If at least one dimension is bigger than thumbnail, calculate resized dimensions
        if($w > $thumbsize OR $h > $thumbsize) {
            if($w >= $h) {

                $sizey = $thumbsize * $h;
                $thumbsizey = intval($sizey / $w);
                $temp_image = @ImageCreateTrueColor($thumbsize, $thumbsizey);
                $thw = $thumbsize; $thy = $thumbsizey;
            } else {
                $sizew = $thumbsize * $w;
                $thumbsizew = intval($sizew / $h);
                $temp_image = @ImageCreateTrueColor($thumbsizew, $thumbsize);
                $thw = $thumbsizew; $thy = $thumbsize;
            }
        } else {
            $thw = $w; $thy = $h;
            $temp_image = @ImageCreateTrueColor($thw, $thy);
        }

        @ImageCopyResampled(
            $temp_image, $source_image, 
            0, 0, 0, 0, 
            $thw, $thy, $w, $h
        );

        @ImageJPEG($temp_image, $target_image, 80);

        @ImageDestroy($temp_image);

        if (!file_exists($target_image)) { return false; } else { return true; }
    }

    /**
     * Generates a cropped square thumbnail
     * 
     * @since 1.1
     * @param string $original_image Path
     * @param string $target_image
     * @param int $thumbsize
     * @return bool
     */
    private function thumbnailCreateCr ($original_image, $target_image, $thumbsize) {

        $source_image = $this->initImg($original_image);

        if(!$source_image || !function_exists('gd_info')) { return FALSE; }

        $w = imagesx($source_image);
        $h = imagesx($source_image);

        // Set offset for the picture cropping (depending on orientation)
        if ($w > $h) {
            $smallestdimension = $h;
            $widthoffset = ceil(($w - $h) / 2);
            $heightoffset = 0;
        } else {
            $smallestdimension = $w;
            $widthoffset = 0;
            $heightoffset = ceil(($h - $w) / 2);
        }

        // Create a temporary image for cropping original (using smallest side for dimensions)
        $temp_image1 = @ImageCreateTrueColor($smallestdimension, $smallestdimension);
        // Resize the image to smallest dimension (centered using offset values from above)
        @ImageCopyResampled(
            $temp_image1, $source_image, 0, 0, 
            $widthoffset, $heightoffset, $smallestdimension, $smallestdimension, 
            $smallestdimension, $smallestdimension
        );
        // Create thumbnail and save

        @ImageJPEG($temp_image1, $target_image, 90);

        // Create a temporary new image for the final thumbnail
        $temp_image2 = @ImageCreateTrueColor($thumbsize, $thumbsize);
        // Resize this image to the given thumbnail size
        @ImageCopyResampled(
            $temp_image2, $temp_image1, 0, 0, 0, 0, 
            $thumbsize, $thumbsize, $smallestdimension, $smallestdimension
        );
        // Create thumbnail and save

        @ImageJPEG($temp_image2, $target_image, 90);

        // Delete temporary images
        @ImageDestroy($temp_image1);
        @ImageDestroy($temp_image2);
        // Return status (check for file existance)
        if (!file_exists($target_image)) { return false; } else { return true; }
    }

      /*====================================================
       |      PGC/SESSION/SERVER HANDLING METHODS          |
       =====================================================
       |  handleMainForm                                   |
       |  myPost                                           |
       |  myGet                                            |
       |  myCookie                                         |
       |  mySession                                        |
       |  myServer                                         |
       |  wcSetCookie                                      |
       |  wcUnsetCookie                                    |
       ====================================================*/

    /**
     * Handles main form requests
     * 
     * @since 1.4
     * @return void
     */
    private function handleMainForm() {

        // Process "Change User" Request
        if($this->myGet('cuser')) {
            $this->wcUnsetSession('cname');
            $this->wcUnsetSession('login_err');
            $this->wcUnsetCookie('cname');
            $this->wcUnsetCookie('chatpass');
            $this->wcSetSession('skip_cookie', 1);
            header('location: ' . $this->myGet('ret') . '#wc_topic');
            die();
        }

        // Process Recover Request
        $u = $this->myGet('u');
        if($this->myGet('recover') && $u && file_exists($this->dataDir . 'tmp/rec_' . $u)) {
            $par = $this->userData(base64_decode($u));
            $par2 = $this->userMatch(base64_decode($u), NULL, 'return_match');
            if($par[5] == $this->myGet('recover')) {
                $npass = $this->randNumb(8);
                $npasse = md5(md5($npass));
                $ndata = base64_encode(
                    base64_encode($par[0]) . '|' . 
                    base64_encode($par[1]) . '|' . 
                    base64_encode($par[2]) . '|' . 
                    $par[3] . '|' . 
                    $par[4] . '|' . 
                    $npasse
                );
                
                $request_uri = explode('?', $this->myServer('REQUEST_URI'));
                if(mail(
                    $par[1],
                    'Account Recovery',
                    "Your new password is: " . $npass . "\n\n\n" . 
                    (
                        (
                            (
                                $this->hasData($this->myServer('HTTPS')) && 
                                $this->myServer('HTTPS') !== 'off'
                            ) || 
                            $this->myServer('SERVER_PORT') == 443
                        ) ? 
                        'https://' : 
                        'http://'
                    ) . $this->myServer('SERVER_NAME') . $request_uri[0],
                    $this->mailHeaders(
                        (
                            trim(ACC_REC_EMAIL) ? 
                            trim(ACC_REC_EMAIL) : 
                            'no-reply@' . $this->myServer('SERVER_NAME')
                        ),
                        TITLE,
                        $par[1],
                        base64_decode($u)
                    )
                )) {
                    $this->writeFile(
                        USERL, 
                        str_replace(
                            $u . '|' . $par2[1] . '|',
                            $u . '|' . $ndata . '|',
                            $this->userList
                        ),
                        'w'
                    );
                    $this->stopMsg = 'A message was sent to the account email with the new password.';
                    unlink($this->dataDir . 'tmp/rec_' . $u);
                } else {
                    $this->stopMsg = 'Failed to send E-mail!';
                }
            }
        } elseif(
            $this->myGet('recover') && 
            $u && 
            !file_exists($this->dataDir . 'tmp/rec_' . $u)
        ) {
            $this->stopMsg = 'This recovery link has expired.';
        }

        // Process Login Request
        if($this->myPost('cname')) {
                
            if(
                !$this->hasPermission('LOGIN', 'skip_msg') || 
                $this->getBanned($this->myPost('cname')) !== FALSE
            ) {
                $this->wcSetSession('login_err', 'Cannot login! Access Denied!');
                header('location: ' . $this->myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            if(
                INVITE_LINK_CODE && 
                $this->myGet('invite') != INVITE_LINK_CODE && 
                $this->userMatch($this->myPost('cname')) === FALSE
            ) {
                $this->wcSetSession(
                    'login_err', 
                    'Cannot login! You must follow an invite link to login the first time!'
                );
                header('location: ' . $this->myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            if(
                strlen(trim($this->myPost('cname'), ' ')) == 0 || 
                strlen(trim($this->myPost('cname'), ' ')) > 30 || 
                preg_match("/[\?<>\$\{\}\"\:\|,; ]/i", $this->myPost('cname'))
            ) {
                $this->wcSetSession(
                    'login_err', 
                    'Invalid Nickname, too long (max = 30) or<br>
                    with invalid characters (<b>? < > $ { } " : | , ; space</b>)!'
                );
                header('location: ' . $this->myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            if(trim(strtolower($this->myPost('cname'))) == 'guest')
            {
                $this->wcSetSession(
                    'login_err', 
                    'Username "Guest" is reserved AND cannot be used!'
                );
                header('location: ' . $this->myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            $last_seen = $this->getPing($this->myPost('cname'));

            // Check if chosen name is online
            if(!$this->isLoggedIn && ((time()-$last_seen) <= $this->catchWindow)) {
                
                $tmp = $this->userData($this->myPost('cname'));
                if(
                    $this->myCookie('chatpass') == $tmp[5] && 
                    $this->hasData($tmp[5]) && 
                    $this->hasData($this->myCookie('chatpass'))
                ) {
                    $passok = TRUE;
                } else {
                    $passok = FALSE;
                }
                
                if($passok === FALSE) {
                    $this->wcSetSession(
                        'login_err', 
                        'Nickname <b>' . $this->myPost('cname') . '</b> is currenly in use!'
                    );
                    header('location: ' . $this->myServer('REQUEST_URI') . '#wc_join');
                    die();
                }
            }
            
            // Clear idle_refresh id any (you're not idle if you're logging in)
            if($this->myCookie('idle_refresh')) { $this->wcUnsetCookie('idle_refresh'); }
            
            $this->wcSetCookie('cname', trim($this->myPost('cname'), ' '));
            $this->wcSetSession(
                'cname', 
                trim($this->myPost('cname'), ' ')
            );

            // Clear the session that was used to change user
            $this->wcUnsetSession('skip_cookie');

            header('location: ' . $this->myServer('REQUEST_URI') . '#wc_topic');
            die();
        }
    }

    /**
     * Handles POST requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    private function myPost($index, $bool = NULL) {

        if (isset($_POST[$index])) {
            if($bool !== NULL) { return TRUE; }
            if ($this->hasData($_POST[$index])) {
                return $this->parseName($_POST[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles GET requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    private function myGet($index, $bool = NULL) {

        if (isset($_GET[$index])) {
            if($bool !== NULL) { return TRUE; }
            if ($this->hasData($_GET[$index])) {
                return $this->parseName($_GET[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles COOKIE requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    private function myCookie($index, $bool = NULL) {

        $index = $this->wcPrefix . '_' . $this->parseCookieName($index);
        
        if (isset($_COOKIE[$index])) {
            if($bool !== NULL) { return TRUE; }
            if ($this->hasData($_COOKIE[$index])) {
                return $this->parseName($_COOKIE[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles SESSION requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    private function mySession($index, $bool = NULL) {
    
        $index = $this->wcPrefix . '_' . $index;

        if (isset($_SESSION[$index])) {
            if($bool !== NULL) { return TRUE; }
            if ($this->hasData($_SESSION[$index])) {
                return $this->parseName($_SESSION[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles SERVER requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    private function myServer($index, $bool = NULL) {

        if (isset($_SERVER[$index])) {
            if($bool !== NULL) { return TRUE; }
            if ($this->hasData($_SERVER[$index])) {
                return $this->parseName($_SERVER[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }
    
    /**
     * Sets a Cookie
     * 
     * @since 1.4
     * @param string $name
     * @param string $value     
     * @return void
     */
    private function wcSetCookie($name, $value) {
    
        setcookie(
            $this->parseCookieName($this->wcPrefix . '_' . $name), 
            $value,
            time() + (86400 * $this->cookieExpire), 
            '/'
        );
    }
    
    /**
     * Unsets a Cookie
     * 
     * @since 1.4
     * @param string $name   
     * @return void
     */
    private function wcUnsetCookie($name) {
    
        setcookie(
            $this->parseCookieName($this->wcPrefix . '_' . $name), 
            '',
            time() - 3600, 
            '/'
        );
    }
    
    /**
     * Sets a Session variable
     * 
     * @since 1.4
     * @param string $name
     * @param string $value        
     * @return void
     */
    private function wcSetSession($name, $value) {
    
        $_SESSION[$this->wcPrefix . '_' . $name] = $value;
    
    }
    
    /**
     * Unsets a Cookie
     * 
     * @since 1.4
     * @param string $name   
     * @return void
     */
    private function wcUnsetSession($name) {
    
        unset($_SESSION[$this->wcPrefix . '_' . $name]);
    }

      /*===========================================
       |       GETTER/SETTER METHODS              |
       ============================================
       |  getFileExt                              |
       |  getPing                                 |
       |  setPing                                 |
       |  getMuted                                |
       |  getBanned                               |
       |  getMod                                  |
       |  handleLastRead                          |
       |  updateUser                              |
       |  parseMicroTime                          |
       |  parseFileMtime                          |
       |  parseRoomLastMod                        |
       ===========================================*/

    /**
     * Gets The File Extention
     * 
     * @since 1.3
     * @param string $file_path
     * @return string|bool
     */
    private function getFileExt($file_path) {
    
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        if($this->hasData($ext)) {
            return $ext;
        } else {
            return FALSE;
        }
    }

    /**
     * Gets User Last Known Ping
     * 
     * @since 1.3
     * @param string $name
     * @return int
     */
    private function getPing($name) {
    
        $src = $this->dataDir . 'tmp/' . base64_encode($name);
        if(file_exists($src)) {
            return $this->parseFileMTime($src);
        } else {
            return 0;
        }
    }

    /**
     * Sets User Ping
     * 
     * @since 1.4
     * @return void
     */
    private function setPing() {
    
        if($this->hasProfileAccess) {
            touch($this->dataDir . 'tmp/' . base64_encode($this->name));
        }
    }

    /**
     * Checks if a user is muted, retrieves mute period if exists
     * 
     * @since 1.1
     * @param string $name
     * @return bool|int
     */
    private function getMuted($name) {
    
        preg_match_all(
            '/^' . base64_encode($name) . ' ([0-9]+)$/im', 
            $this->mutedList, 
            $matches
        );
        
        if(isset($matches[1][0]) && $this->hasData(trim($name, ' '))) {
            if(time() < $matches[1][0] || $matches[1][0] == 0) {
                return $matches[1][0];
            } else {
                return FALSE;
            }
        }
        else {
            return FALSE;
        }
    }

    /**
     * Checks if a user is banned, retrieves ban period if exists
     * 
     * @since 1.1
     * @param string $name
     * @return bool|int
     */
    private function getBanned($name) {
           
        preg_match_all(
            '/^' . base64_encode($name) . ' ([0-9]+)$/im', 
            $this->bannedList, 
            $matches
        );
        
        if(isset($matches[1][0]) && $this->hasData(trim($name, ' '))) {
            if(time() < $matches[1][0] || $matches[1][0] == 0) {
                return $matches[1][0];
            } else {
                return FALSE;
            }
        }
        else {
            return FALSE;
        }
    }

    /**
     * Checks if a user is a moderator
     * 
     * @since 1.2
     * @param string $name
     * @return bool
     */    
    private function getMod($name) {
    
        preg_match_all(
            '/^(' . base64_encode($name) . ')$/im', 
            $this->modList, 
            $matches
        );
        if(isset($matches[1][0]) && $this->hasData(trim($name, ' ')))
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Stores/Retrieves a Last Read point
     * 
     * @since 1.1
     * @param string $mode
     * @param string|null $sufix Tied to the current room if not specified
     * @return void|int
     */
    private function handleLastRead($mode, $sufix = NULL) {

        $id = 'lastread' . 
            (
                $sufix !== NULL ? 
                '_' . $sufix : 
                '_' . $this->mySession('current_room')
            );
            
        switch($mode) {
            case 'store':
                $this->wcSetSession($id, $this->parseMicroTime());
                $this->wcSetCookie($id, $this->parseMicroTime());
            break;
            case 'read':
                if($this->myCookie($id) && !$this->mySession($id)) {
                    $this->wcSetSession($id, $this->myCookie($id));
                }
                return $this->mySession($id);
            break;
        }
    }

    /**
     * Updates user's last activity/status tags
     * 
     * @since 1.1
     * @param array $user_row User data
     * @param string $mode
     * @param string|null $value Only Applies To "update_status" Mode
     * @return string
     */
    private function updateUser($user_row, $mode, $value = NULL) {
    
        // If user row not provided, get row from users list
        $list_replace = FALSE;
        if($user_row === NULL) {
            $user_row = $original_row = 
                $this->userMatch($this->name, NULL, 'RETURN_MATCH');
            $list_replace = TRUE;
        }

        // Update user parameters according to request
        if($user_row !== FALSE && $this->name) {
            
            $f = $user_row[2];
    
            switch($mode) {
                case 'update_status':                            
                    $user_row[4] = $value;
                break;
                case 'user_join':                            
                    $user_row[2] = (($f == '0') ? time() : $f);
                    $user_row[3] = time();
                    $user_row[4] = 1;
                    $this->wcUnsetCookie('idle_refresh');
                break;
                case 'new_message':
                    $user_row[3] = time();
                    $this->wcUnsetCookie('idle_refresh');
                break;
                case 'user_visit':
                    if(
                        $f == '0' || 
                        ($f != '0' && $this->hasProfileAccess)
                    ) {
                        $user_row[3] = time();
                        $this->wcUnsetCookie('idle_refresh');
                    }
                break;
            }
        }
        
        if($list_replace === FALSE) {
            // Return the updated row
            return $user_row;
        } elseif($user_row !== FALSE) {
            // Return the updated list
            return str_replace(
                trim(implode('|', $original_row)),
                trim(implode('|', $user_row)),
                $this->userList
            );
        }
    }
    
    /**
     * Gets microtime (If not available uses time() instead)
     * 
     * @since 1.4
     * @return int
     */
    private function parseMicroTime() {

        if (function_exists('microtime')) {
            list($ms, $s) = explode(' ', microtime());
		return ($s+$ms);
        } else {
            return time();
        }
    }
    
    /**
     * Gets filemtime with clean cache
     * 
     * @since 1.4
     * @return int
     */
    private function parseFileMTime($file) {
        clearstatcache();
        return filemtime($file);
    }

    /**
     * Gets Room's Last Modified Time (Either stored or file's)
     * 
     * @since 1.4
     * @return int
     */
    private function parseRoomLastMod($target = NULL) {
        if($target === NULL) {
            if($this->roomLastMod) {
                return $this->roomLastMod;
            } else {
        	   return $this->parseFileMTime(MESSAGES_LOC);
            }
        } else {       
            $room_def = explode(
                '|', 
                $this->readFile(
                    $this->roomDir . 'def_' . base64_encode($target) . '.txt'
                )
            );
            
            if($room_def[4]) {
                return $room_def[4];
            } else {
        	    return $this->parseFileMTime(
                    $this->roomDir . base64_encode($target) . '.txt'
                );
            }
        }
    }

      /*===========================================
       |       PARSER / INTERFACER METHODS        |
       ============================================
       |  parseName                               |
       |  printIndex                              |
       |  popTemplate                             |
       |  parseUsers                              |
       |  parseRooms                              |
       |  iSmiley                                 |
       |  parseThemes                             |
       |  parseError                              |
       |  parseTopicContainer                     |
       |  parseMsg                                |
       |  parseMsgE                               |
       |  parseBbcode                             |
       |  parseIdle                               |
       |  parseCookieName                         |
       ===========================================*/

    /**
     * Strips a name of troublesome characters
     * 
     * @since 1.4
     * @param string $name
     * @return string
     */
    private function parseName($name) {
    
        return
            str_replace(
                array(
                    "\r",
                    "\t",
                    "\013",
                    "\014"
                ),
                '',
                $name
            );
    }

    /**
     * Prints the populated index template
     * 
     * @since 1.1
     * @return void
     */
    public function printIndex($embedded = NULL) {

        $onload = $contents = '';
        
        if($this->isLoggedIn) {
            $JOIN = $this->popTemplate(
                'wcchat.join.inner',
                array(
                    'PASSWORD_REQUIRED' => 
                        $this->popTemplate(
                            'wcchat.join.password_required', 
                            array(
                                'USER_NAME' => $this->name
                            ), 
                            $this->uPass && !$this->isCertifiedUser
                        ),
                    'MODE' => (($this->uPass && !$this->isCertifiedUser) ? 
                        $this->popTemplate('wcchat.join.inner.mode.login', '') : 
                        $this->popTemplate('wcchat.join.inner.mode.join', '')
                    ),
                    'CUSER_LINK' => $this->popTemplate(
                        'wcchat.join.cuser_link', 
                        array(
                            'RETURN_URL' => urlencode($this->myServer('REQUEST_URI'))
                        )
                    ),
                    'USER_NAME' => $this->name,
                    'RECOVER' => $this->popTemplate(
                        'wcchat.join.recover',
                        '',
                        $this->uEmail && 
                        $this->uPass && 
                        !$this->isCertifiedUser && 
                        $this->hasPermission('ACC_REC', 'skip_msg')
                    )
                )
            );
        } else {
            $err = '';
            if($this->mySession('login_err')) {
                $err = $this->mySession('login_err');
                $this->wcUnsetSession('login_err');
            }

            $JOIN = $this->popTemplate(
                'wcchat.login_screen',
                array(
                    'USER_NAME_COOKIE' => $this->myCookie('cname'),
                    'CALLER_NO_PAR' => trim($this->ajaxCaller, '?'),
                    'ERR' => $this->parseError($err)
                )
            );

        }

        $BBCODE = (
            (
                !$this->isLoggedIn && 
                $this->myCookie('cname') && 
                !$this->uPass
            ) ? 
            '<i>Hint: Set-up a password in settings to skip the login screen on your next visit.</i>' :
            $this->popTemplate(
                'wcchat.toolbar.bbcode',
                array(
                    'SMILIES' => $this->iSmiley('wc_text_input_field'),
                    'FIELD' => 'wc_text_input_field',
                    'ATTACHMENT_UPLOADS' => $this->popTemplate(
                        'wcchat.toolbar.bbcode.attachment_uploads',
                        array(
                            'ATTACHMENT_MAX_POST_N' => ATTACHMENT_MAX_POST_N
                        ),
                        ATTACHMENT_UPLOADS && $this->hasPermission('ATTACH_UPL', 'skip_msg')
                    )
                ),
                $this->isLoggedIn
            )
        );

        // Populate Global Settings Form Fields
        $gsettings_par = array(
            'TITLE', 'INCLUDE_DIR', 'REFRESH_DELAY', 'REFRESH_DELAY_IDLE', 
            'IDLE_START', 'OFFLINE_PING', 'CHAT_DSP_BUFFER', 'CHAT_STORE_BUFFER', 
            'CHAT_OLDER_MSG_STEP', 'ARCHIVE_MSG', 'BOT_MAIN_PAGE_ACCESS', 'ANTI_SPAM', 
            'IMAGE_MAX_DSP_DIM',  'IMAGE_AUTO_RESIZE_UNKN', 'VIDEO_WIDTH', 'VIDEO_HEIGHT', 
            'AVATAR_SIZE', 'DEFAULT_AVATAR', 'DEFAULT_ROOM', 'DEFAULT_THEME', 
            'INVITE_LINK_CODE', 'ACC_REC_EMAIL', 'ATTACHMENT_TYPES', 'ATTACHMENT_MAX_FSIZE', 
            'ATTACHMENT_MAX_POST_N'
        );

        $gsettings_par_v = array();

        foreach($gsettings_par as $key => $value) {
            $gsettings_par_v['GS_' . $value] = constant($value);
        }
        $gsettings_par_v['GS_LOAD_EX_MSG']          = (LOAD_EX_MSG === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_LIST_GUESTS']          = (LIST_GUESTS === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_ARCHIVE_MSG']          = (ARCHIVE_MSG === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_BOT_MAIN_PAGE_ACCESS'] = (BOT_MAIN_PAGE_ACCESS === TRUE ? ' CHECKED' : '');        
        $gsettings_par_v['GS_GEN_REM_THUMB']        = (GEN_REM_THUMB === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_ATTACHMENT_UPLOADS']   = (ATTACHMENT_UPLOADS === TRUE ? ' CHECKED' : '');

        $gsettings_perm = array(
            'GSETTINGS', 'ROOM_C', 'ROOM_E', 'ROOM_D', 'MOD', 
            'UNMOD', 'USER_E', 'USER_D', 'TOPIC_E', 'BAN', 'UNBAN', 'MUTE', 
            'UNMUTE', 'MSG_HIDE', 'MSG_UNHIDE', 'POST', 'PROFILE_E', 
            'IGNORE', 'PM_SEND', 'LOGIN', 'ACC_REC', 'READ_MSG', 
            'ROOM_LIST', 'USER_LIST', 'ATTACH_UPL', 'ATTACH_DOWN');

        $gsettings_perm2 = array('MMOD', 'MOD', 'CUSER', 'USER', 'GUEST');

        foreach($gsettings_perm as $key => $value) {
            $perm_data = constant('PERM_' . $value);
            $perm_fields = explode(' ', $perm_data);
            foreach($gsettings_perm2 as $key2 => $value2) {
                $gsettings_par_v['GS_' . $value2 . '_' . $value] = 
                    (in_array($value2, $perm_fields) ? ' CHECKED' : '');
            }
        }

        $contents = $this->popTemplate(
            'wcchat',
            array(
                'TITLE' => TITLE,
                'TOPIC' => $this->popTemplate(
                    'wcchat.topic', 
                    array(
                        'TOPIC' => $this->parseTopicContainer()
                    )
                ),
                'STATIC_MSG' => (!$this->isLoggedIn ? $this->popTemplate('wcchat.static_msg') : ''),
                'POSTS' => $this->popTemplate('wcchat.posts'),
                'GSETTINGS' => ($this->hasPermission('GSETTINGS', 'skip_msg') ? 
                    $this->popTemplate(
                        'wcchat.global_settings',
                        array_merge(
                            array(
                                'URL' => 
                                    (
                                        (
                                            (
                                                $this->hasData($this->myServer('HTTPS')) && 
                                                $this->myServer('HTTPS') !== 'off'
                                            ) || 
                                            $this->myServer('SERVER_PORT') == 443
                                        ) ? 
                                        'https://' : 
                                        'http://'
                                    ) . $this->myServer('SERVER_NAME') . $this->myServer('REQUEST_URI'),
                                'ACC_REC_EM_DEFAULT' => 'no-reply@' . $this->myServer('SERVER_NAME')
                            ),
                            $gsettings_par_v
                        )
                    ) : 
                    ''
                ),
                'TOOLBAR' => $this->popTemplate(
                    'wcchat.toolbar',
                    array(
                        'BBCODE' => ($BBCODE ? $BBCODE : 'Choose a name to use in chat.'),
                        'USER_NAME' => $this->name ? 
                            str_replace("'", "\'", $this->name) : 
                            'Guest',
                        'TIME' => time(),
                        'COMMANDS' => $this->popTemplate(
                            'wcchat.toolbar.commands',
                            array(
                                'GSETTINGS' => $this->popTemplate(
                                    'wcchat.toolbar.commands.gsettings', 
                                    '', 
                                    $this->hasPermission('GSETTINGS', 'skip_msg')
                                ),
                                'EDIT' => $this->popTemplate(
                                    'wcchat.toolbar.commands.edit', 
                                    '', 
                                    $this->hasPermission('ROOM_C', 'skip_msg') || 
                                    $this->hasPermission('ROOM_E', 'skip_msg') || 
                                    $this->hasPermission('ROOM_D', 'skip_msg') || 
                                    $this->hasPermission('MOD', 'skip_msg') || 
                                    $this->hasPermission('UNMOD', 'skip_msg') || 
                                    $this->hasPermission('USER_E', 'skip_msg') || 
                                    $this->hasPermission('USER_D', 'skip_msg') || 
                                    $this->hasPermission('TOPIC_E', 'skip_msg') || 
                                    $this->hasPermission('BAN', 'skip_msg') || 
                                    $this->hasPermission('UNBAN', 'skip_msg') || 
                                    $this->hasPermission('MUTE', 'skip_msg') || 
                                    $this->hasPermission('UNMUTE', 'skip_msg') || 
                                    $this->hasPermission('MSG_HIDE', 'skip_msg') || 
                                    $this->hasPermission('MSG_UNHIDE', 'skip_msg')  
                                )
                            )
                        ),
                        'ICON_STATE' => ($this->hasProfileAccess ? '' : 'closed'),
                        'JOINED_STATUS' => $this->popTemplate(
                            'wcchat.toolbar.joined_status', 
                            array('MODE' => 'on')
                        )
                    )
                ),
                'TEXT_INPUT' => $this->popTemplate(
                    'wcchat.text_input', 
                    array('CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER)
                ),
                'SETTINGS' => $this->popTemplate(
                    'wcchat.settings',
                    array(
                        'AV_RESET' => ($this->uAvatar ? '' : 'closed'),
                        'TIMEZONE_OPTIONS' => str_replace(
                            'value="' . $this->uTimezone . '"', 
                            'value="' . $this->uTimezone . '" SELECTED', 
                            $this->popTemplate('wcchat.settings.timezone_options')
                        ),
                        'HFORMAT_SEL0' => ($this->uHourFormat == '0' ? ' SELECTED' : ''),
                        'HFORMAT_SEL1' => ($this->uHourFormat == '1' ? ' SELECTED' : ''),
                        'USER_LINK' => $this->uWeb,
                        'USER_EMAIL' => $this->uEmail,
                        'RESETP_ELEM_CLOSED' => ($this->uPass ? '' : 'closed')
                    )
                ),
                'JOIN' => $this->popTemplate('wcchat.join', array('JOIN' => $JOIN)),
                'ROOM_LIST' => $this->popTemplate(
                    'wcchat.rooms', 
                    array('RLIST' => $this->parseRooms())
                ),
                'USER_LIST' => $this->popTemplate(
                    'wcchat.users', 
                    array('ULIST' => $this->refreshUsers('VISIT'))
                ),
                'THEMES' => $this->parseThemes()
            )
        );

        $onload = (
            $this->isLoggedIn ? 
            $this->popTemplate(
                'wcchat.toolbar.onload', 
                array(
                    'CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER, 
                    'EDIT_BT_STATUS' => intval($this->myCookie('hide_edit'))
                )
            ) : 
            $this->popTemplate(
                'wcchat.toolbar.onload_once', 
                array(
                    'CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER
                )
            )
        );

        // Set a ban tag
        $tag = '';
        if($this->isBanned !== FALSE) {
            $tag = (
                (intval($this->isBanned) == 0) ? 
                ' (Permanently)' : 
                ' (' . $this->parseIdle($this->isBanned, 1) . ' remaining)'
            );
        }

        // Output the index template
        return $this->popTemplate(
            ($embedded === NULL ? 'index' : 'index_embedded'),
            array(
                'TITLE' => TITLE,
                'CONTENTS' => (
            ($this->isBanned !== FALSE) ? 
                $this->popTemplate(
                    'wcchat.critical_error', 
                    array('ERROR' => 'You are banned!' . $tag)
                ) : 
                ($this->stopMsg ? 
                    $this->popTemplate(
                        'wcchat.critical_error', 
                        array('ERROR' => $this->stopMsg)
                    ) : 
                    $contents
                )
            ),
                'ONLOAD' => (($this->isBanned !== FALSE || $this->stopMsg) ? '' : $onload),
                'REFRESH_DELAY' => REFRESH_DELAY,
                'STYLE_LASTMOD' => $this->parseFileMTime($this->includeDirServer . 'themes/'.THEME.'/style.css'),
                'SCRIPT_LASTMOD' => $this->parseFileMTime($this->includeDirServer . 'script.js'),
                'DEFAULT_THEME' => DEFAULT_THEME
            )
        );

    }

    /**
     * Populates Templates with data from an array
     * 
     * @since 1.1
     * @param string $model Template Model
     * @param array|null $data
     * @param bool|null $cond Condition to display
     * @param string $no_cond_content Content to display if condition is not met
     * @return string|void Html Template
     */
    private function popTemplate(
        $model, $data = NULL, 
        $cond = NULL, $no_cond_content = NULL
    ) {
    
        $out = '';
        if (!isset($cond) || (isset($cond) && $cond == TRUE)) {
            // Import template model
            $out = $this->templates[$model];

            // Replace provided tokens
            if (isset($data)) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        $out = str_replace(
                            "{" . $key . "}", 
                            stripslashes($data[$key]), 
                            $out
                        );
                    }
                }
            }
            $out = trim($out, "\n\r");

            // Replace system tokens
            return str_replace(
                array(
                    '{CALLER}',
                    '{INCLUDE_DIR}',
                    '{INCLUDE_DIR_THEME}',
                    '{PREFIX}'
                ),
                array(
                    $this->ajaxCaller,
                    $this->includeDir,
                    (defined('INCLUDE_DIR_THEME') ? INCLUDE_DIR_THEME : ''),
                    $this->wcPrefix
                ),
                $out
            );
        } else {
            if($no_cond_content == NULL) {
                return '';
            } else {
                return $no_cond_content;
            }
        }
    }

    /**
     * Generates the user list (Ajax Component)
     * 
     * @since 1.2
     * @param array $contents pre-processed user data
     * @param bool mod_perm
     * @param bool edit_perm
     * @param bool del_perm                    
     * @param int|null $visit Specifies a new user visit
     * @return string Html Template
     */
    private function parseUsers($contents, $mod_perm, $edit_perm, $del_perm, $visit = NULL) {
       
        $_on = $_off = $_lurker = array();
        $uid = $this->name;
        $autocomplete = '';

        // Scan users raw file
        if(trim($contents)) {
            $lines = explode("\n", trim($contents));
            foreach($lines as $k => $v) {
                if(trim($v)) {
                    list($usr, $udata, $f, $l, $s) = explode('|', trim($v));
                    $usr = ($usr ? base64_decode($usr) : '');

                    // Store the name autocomplete list (to be used in input)
                    if($visit !== NULL) {
                        $autocomplete .= $usr . ',';
                    }

                    // Generate status icons
                    $mod_icon = $this->popTemplate(
                        'wcchat.users.item.icon_moderator', 
                        '',
                        ($this->getMod($usr) !== FALSE && strlen(trim($usr)) > 0)
                    );

                    $muted_icon = '';
                    $ismuted = $this->getMuted($usr);
                    if($ismuted !== FALSE && strlen(trim($usr)) > 0) {
                        $muted_icon = $this->popTemplate('wcchat.users.item.icon_muted');
                    }
                    if($this->myCookie('ign_'.$usr)) {
                        $muted_icon = $this->popTemplate('wcchat.users.item.icon_ignored');
                    }

                    // Parse Web link and avatar
                    if(strlen($udata) > 0 && strpos(base64_decode($udata), '|') !== FALSE) {
                        list($tmp1, $tmp2, $tmp3, $tmp4, $tmp5, $tmp6) = 
                            explode('|', base64_decode($udata));
                            
                        $av = ($tmp1 ? 
                            $this->includeDir . 'files/avatars/' . base64_decode($tmp1) : 
                            INCLUDE_DIR_THEME . DEFAULT_AVATAR
                        );
                        $ulink = ($tmp3 ? 
                            (
                                preg_match('#^(http|ftp)#i', base64_decode($tmp3)) ? 
                                base64_decode($tmp3) : 
                                'http://' . base64_decode($tmp3)
                            ) :
                            ''
                        );
                    } else {
                        $ulink = '';
                        $av = (DEFAULT_AVATAR ? 
                            INCLUDE_DIR_THEME . DEFAULT_AVATAR : 
                            ''
                        );
                    }

                    // Sets a style for banned users
                    $name_style = '';
                    $isbanned = $this->getBanned($usr);
                    if($isbanned !== FALSE) {
                        $ulink = '';
                        $av = (DEFAULT_AVATAR ? 
                            INCLUDE_DIR_THEME . DEFAULT_AVATAR : 
                            ''
                        );
                        $name_style = ' style="text-decoration: line-through;"';
                    }

                    // Parse joined status
                    $status_title = '';
                    $last_ping = $this->getPing($usr);
                    switch($s) {
                        case '1':
                            if((time()-$l) >= IDLE_START) {
                                $status_title = 'Idle';
                            } else {
                                $status_title = 'Available';
                            }
                        break;
                        case '2': $status_title = 'Do Not Disturb'; break;
                        default: $status_title = 'Not Joined';
                    }

                    // Is user a guest?
                    if($f != '0') {
                        // No guest, is online?
                        if((time() - $last_ping) <= $this->catchWindow) {
                            // Yes, it's an online user
                            $target_array = '_on'; 

                            // Style for current user
                            if(strpos($v, base64_encode($uid).'|') === FALSE) {
                                $boldi = $bolde = ''; 
                            } else {
                                $boldi = '<b>'; 
                                $bolde = '</b>'; 
                            }

                            // Parse Joined Status class
                            $joined_status_class = 'joined_on';
                    
                            if(((time()-$l) >= IDLE_START)) {
                                $joined_status_class = 'joined_idle';
                            }
                            if($s == 2) {
                                $joined_status_class = 'joined_na';
                            }
                        } else {
                            // User is offline
                            $target_array = '_off';
                        }        

                        ${$target_array}[$usr] = $this->popTemplate(
                            'wcchat.users.item',
                            array(
                                'ID' => base64_encode($usr),
                                'WIDTH' => $this->popTemplate(
                                    'wcchat.users.item.width', 
                                    array('WIDTH' => $this->uAvatarW)
                                ),
                                'AVATAR' => $av,
                                'NAME_STYLE' => $name_style,
                                'NAME' => ($target_array == '_on' ? $boldi.$usr.$bolde : $usr),
                                'MOD_ICON' => $mod_icon,
                                'MUTED_ICON' =>$muted_icon,
                                'LINK' => $this->popTemplate(
                                    'wcchat.users.item.link',
                                    array('LINK' => $ulink),
                                    $ulink
                                ),
                                'IDLE' => $this->popTemplate(
                                    'wcchat.users.item.idle_var', 
                                    array('IDLE' => $this->parseIdle($l)), 
                                    ((time()-$l) >= IDLE_START && $s != 2) || 
                                    $target_array == '_off'
                                ),
                                'JOINED_CLASS' => (
                                    (intval($s) && $target_array == '_on') ? 
                                    $joined_status_class : 
                                    ''
                                ),
                                'STATUS_TITLE' => ($target_array == '_on' ? $status_title : ''),
                                'EDIT_BT' => $this->popTemplate(
                                    'wcchat.users.item.edit_bt',
                                    array(
                                        'ID' => base64_encode($usr), 
                                        'OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')
                                    ),
                                    ($edit_perm || $mod_perm)
                                ),
                                'EDIT_FORM' => $this->popTemplate(
                                    'wcchat.users.item.edit_form',
                                    array(
                                        'ID' => base64_encode($usr),
                                        'MODERATOR' => $this->popTemplate(
                                            'wcchat.users.item.edit_form.moderator',
                                            array(
                                                'MOD_CHECKED' => ($mod_icon ? 'CHECKED' : ''),
                                                'ID' => base64_encode($usr)
                                            ),
                                            $this->hasPermission('MOD', 'skip_msg') || 
                                            $this->hasPermission('UNMOD', 'skip_msg')
                                        ),
                                        'MUTED' => $this->popTemplate(
                                            'wcchat.users.item.edit_form.muted',
                                            array(
                                                'MUTED_CHECKED' => ($ismuted !== FALSE ? 'CHECKED' : ''),
                                                'MUTED_TIME' => (
                                                    ($ismuted !== FALSE && $ismuted != 0) ? 
                                                    intval(abs((time() - $ismuted) / 60)) : 
                                                    ''
                                                ),
                                                'ID' => base64_encode($usr)
                                            ),
                                            $this->hasPermission('MUTE', 'skip_msg') || 
                                            $this->hasPermission('UNMUTE', 'skip_msg')
                                        ),
                                        'BANNED' => $this->popTemplate(
                                            'wcchat.users.item.edit_form.banned',
                                            array(
                                                'BANNED_CHECKED' => ($isbanned !== FALSE ? 'CHECKED' : ''),
                                                'BANNED_TIME' => (
                                                    ($isbanned !== FALSE && $isbanned != 0) ? 
                                                    intval(abs((time() - $isbanned) / 60)) : 
                                                    ''
                                                ),
                                                'ID' => base64_encode($usr)
                                            ),
                                            $this->hasPermission('BAN', 'skip_msg') || 
                                            $this->hasPermission('UNBAN', 'skip_msg')
                                        ),
                                        'MOD_NOPERM' => (!$mod_perm ? 'No Permission!' : ''),
                                        'PROFILE_DATA' => $this->popTemplate(
                                            'wcchat.users.item.edit_form.profile_data',
                                            array(
                                                'NAME' => $usr,
                                                'EMAIL' => base64_decode($tmp2),
                                                'WEB' => base64_decode($tmp3),
                                                'DIS_AV' => (trim($tmp1) ? '' : 'DISABLED'),
                                                'DIS_PASS' => (trim($tmp6) ? '' : 'DISABLED'),
                                                'ID' => base64_encode($usr)
                                            ),
                                            $edit_perm,
                                            'No permission!'
                                        ),
                                        'NAME' => $usr,
                                        'DELETE_BT' => $this->popTemplate(
                                            'wcchat.users.item.edit_form.delete_bt',
                                            array(
                                                'ID' => base64_encode($usr)
                                            ),
                                            $del_perm && 
                                            $this->name != $usr
                                        )
                                    ),
                                    $edit_perm || $mod_perm
                                )
                            )
                        );
                    } elseif(LIST_GUESTS === TRUE) {
                        // User is a guest (never joined)
                        $_lurker[$usr] = $this->popTemplate(
                            'wcchat.users.guests.item', 
                            array(
                                'USER' => $usr, 
                                'IDLE' => $this->parseIdle($l)
                            )
                        );
                    }
                }
            }
        }

        // Sort lists alphabetically
        $on = $off = $lurker = '';
        ksort($_on); ksort($_off); ksort($_lurker);
        foreach($_on as $k => $v) { $on .= $v; }
        foreach($_off as $k => $v) { $off .= $v; }
        foreach($_lurker as $k => $v) { $lurker .= $v; }

        // Store autocomplete list in SESSION
        if($autocomplete) {
            $this->wcSetSession('autocomplete', ',' . $autocomplete);
        }

        $output = 
            $this->popTemplate(
                'wcchat.users.inner',
                array(
                    'JOINED' => (
                        $on ? 
                        $this->popTemplate('wcchat.users.joined', array('USERS' => $on)) : 
                        $this->popTemplate('wcchat.users.joined.void')
                    ),
                    'OFFLINE' => $this->popTemplate(
                        'wcchat.users.offline', 
                        array('USERS' => $off), 
                        $off
                    ),
                    'GUESTS' => $this->popTemplate('wcchat.users.guests', 
                        array('USERS' => $lurker), 
                        $lurker
                    ),
                )
            ); 

         return $output;
    }

    /**
     * Generates the room list, rooms are topic containers, not user containers
     * 
     * @since 1.2
     * @return string Html Template
     */
    private function parseRooms() {

        $rooms = $create_room = '';

        // Scan rooms folder
        foreach(glob($this->roomDir . '*.txt') as $file) {
            $room_name = base64_decode(
                str_replace(
                    array($this->roomDir, '.txt'), 
                    '', 
                    $file
                )
            );
            
            // Skip any special room related files (definitions, topic, hidden msg)
            if(
                strpos($file, 'def_') === FALSE && 
                strpos($file, 'topic_') === FALSE && 
                strpos($file, 'hidden_') === FALSE
            ) {

                // Parse the edit form if user has permission
                $edit_form = $edit_icon = '';
                if($this->hasPermission('ROOM_E', 'skip_msg')) {
                    list($perm,$t1,$t2,$t3,$t4) = explode(
                        '|', 
                        $this->readFile($this->roomDir . 'def_' . base64_encode($room_name) . '.txt')
                    );
                    $enc = base64_encode($file);
                    $edit_form = 
                        $this->popTemplate(
                            'wcchat.rooms.edit_form',
                            array(
                                'ID' => $enc,
                                'ROOM_NAME' => $room_name,
                                'SEL1' => ($perm == '1' ? ' SELECTED' : ''),
                                'SEL2' => ($perm == '2' ? ' SELECTED' : ''),
                                'SEL3' => ($perm == '3' ? ' SELECTED' : ''),
                                'SEL21' => ($t1 == '1' ? ' SELECTED' : ''),
                                'SEL22' => ($t1 == '2' ? ' SELECTED' : ''),
                                'SEL23' => ($t1 == '3' ? ' SELECTED' : ''),
                                'SEL24' => ($t1 == '4' ? ' SELECTED' : ''),
                                'DELETE_BT' => $this->popTemplate(
                                    'wcchat.rooms.edit_form.delete_bt', 
                                    array('ID' => $enc), 
                                    $room_name != DEFAULT_ROOM
                                )                        
                            )
                        );
                    $edit_icon = $this->popTemplate(
                        'wcchat.rooms.edit_form.edit_icon', 
                        array(
                            'ID' => base64_encode($file),
                            'OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')
                        )
                    );
                }

                // Parse room if user has read permission for this specific room
                if($this->hasRoomPermission($room_name, 'R')) {
                    if($room_name == $this->mySession('current_room')) {
                        $rooms .=
                            $this->popTemplate(
                                'wcchat.rooms.current_room',
                                array(
                                    'TITLE' => $room_name,
                                    'EDIT_BT' => $edit_icon,
                                    'FORM' => $edit_form,
                                    'NEW_MSG' => $this->popTemplate('wcchat.rooms.new_msg.off')
                                )
                            );
                    } else {
                        $lastread = $this->handleLastRead('read', $room_name);
                        $lastmod = $this->parseRoomLastMod($room_name);

                        $rooms .= 
                            $this->popTemplate(
                                'wcchat.rooms.room',
                                array(
                                    'TITLE' => $room_name,
                                    'EDIT_BT' => $edit_icon,
                                    'FORM' => $edit_form,
                                    'NEW_MSG' => $this->popTemplate(
                                        'wcchat.rooms.new_msg.' . 
                                        (($lastread < $lastmod) ? 'on' : 'off')
                                    )
                                )
                            );
                    }
                }
            }
        }

        // Check if the edit buttons/links are supposed to be hidden
        $create_room = '';
        if($this->hasPermission('ROOM_C', 'skip_msg')) {
            $create_room =
                $this->popTemplate(
                    'wcchat.rooms.create', 
                    array(
                        'OFF' => (
                            ($this->myCookie('hide_edit') == 1) ? '_off' : ''
                        )
                    )
                );
        }

        // Replace list with message if no permission to read the list
        if(!$this->hasPermission('ROOM_LIST', 'skip_msg')) {
            $rooms = 'Can\'t display rooms.';
        }

        return $this->popTemplate(
            'wcchat.rooms.inner', 
            array('ROOMS' => $rooms, 'CREATE' => $create_room)
        );
    }

    /**
     * Generates the Smiley interface
     * 
     * @since 1.1
     * @param string $field Target Html Id
     * @return string Html Template
     */
    private function iSmiley($field) {
    
        $out = '';
        $s1 =
            array(
                '1' => ':)',
                '3' => ':o',
                '2' => ':(',
                '4' => ':|',
                '5' => ':frust:',
                '6' => ':D',
                '7' => ':p',
                '8' => '-_-',
                '10' => ':E',
                '11' => ':mad:',
                '12' => '^_^',
                '13' => ':cry:',
                '14' => ':inoc:',
                '15' => ':z',
                '16' => ':love:',
                '17' => '@_@',
                '18' => ':sweat:',
                '19' => ':ann:',
                '20' => ':susp:',
                '9' => '>_<'
            );

        // Parse smiley codes to html
        foreach($s1 as $key => $value) {
            $out .=
                $this->popTemplate('wcchat.toolbar.smiley.item',
                    array(
                        'title' => $value,
                        'field' => $field,
                        'str_pat' => $value,
                        'str_rep' => 'sm' . $key . '.gif'
                    )
                );
        }

        return($out);

    }

    /**
     * Generates the theme List
     * 
     * @since 1.2
     * @return string Html Template
     */
    private function parseThemes() {
    
        $options = '';
        foreach(glob($this->includeDirServer . 'themes/*') as $file) {
            $title = str_replace(
                $this->includeDirServer . 'themes/', 
                '', 
                $file
            );
            if($title != '.' AND $title != '..') {
                $options .= $this->popTemplate(
                    'wcchat.themes.option',
                    array(
                        'TITLE' => $title,
                        'VALUE' => $title,
                        'SELECTED' => (($title == THEME) ? ' SELECTED' : '')
                    )
                );
            }
        }
        
        return $this->popTemplate(
            'wcchat.themes', 
            array(
                'OPTIONS' => $options, 
                'COOKIE_EXPIRE' => $this->cookieExpire
            )
        );
    }

    /**
     * Parses an error message if it exists
     * 
     * @since 1.1
     * @param string|void $error
     * @return string|void Html Template
     */
    private function parseError($error) {
    
        if($this->hasData($error)) {
            return $this->popTemplate('wcchat.error_msg', array('ERR' => $error));
        } else {
            return '';
        }
    }

    /**
     * Parses The Topic Container Template/Contents
     * 
     * @since 1.2
     * @return string Html Template
     */
    private function parseTopicContainer() {
    
        $this->topic = $this->readFile(TOPICL);

        $topic_con = nl2br($this->parseBbcode($this->topic));
        if(strpos($this->topic, '[**]') !== FALSE) {
            list($v1, $v2) = explode('[**]', $this->topic);
            $topic_con = $this->popTemplate(
                'wcchat.topic.box.partial',
                array(
                    'TOPIC_PARTIAL' => nl2br($this->parseBbcode($v1)),
                    'TOPIC_FULL' => nl2br(
                        $this->parseBbcode(str_replace('[**]', '', $this->topic))
                    )
                )
            );
        }

        return $this->popTemplate(
            'wcchat.topic.inner',
            array(
                'CURRENT_ROOM' => $this->mySession('current_room'),
                'TOPIC' =>
                    $this->popTemplate(
                        'wcchat.topic.box',
                        array(
                            'TOPIC_CON' => ($this->topic ? $topic_con : 'No topic set.'),
                            'TOPIC_TXT' => ($this->topic ? stripslashes($this->topic) : ''),
                            'TOPIC_EDIT_BT' => $this->popTemplate(
                                'wcchat.topic.edit_bt',
                                array('OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')),
                                $this->hasPermission('TOPIC_E', 'skip_msg')
                            ),
                            'BBCODE' => $this->popTemplate(
                                'wcchat.toolbar.bbcode',
                                array(
                                    
                                    'SMILIES' => $this->iSmiley('wc_topic_txt'),
                                    'FIELD' => 'wc_topic_txt',
                                    'ATTACHMENT_UPLOADS' => ''
                                )
                            )
                        )
                    )
            )
        );
    }

    /**
     * Parses post message(s) 
     * 
     * @since 1.2
     * @param array $lines
     * @param int $lastread
     * @param string $action Specifies if the user is sending or retrieving message(s)
     * @param int|null $older_index Sequencial id of the first old (hidden) message
     * @return array
     */
    private function parseMsg($lines, $lastread, $action, $older_index = NULL) {

        // Initialize the displayed messages index
        $index = 0;
        $output = $new = $first_elem = $first_elem_name = '';
        $previous = $prev_unique_id = $skip_new = $first_elem_time = 0;
        $puser = '';
        $old_start = FALSE;        
        $today_date = gmdate('d-M', time() + ($this->uTimezone * 3600));
        if($older_index != NULL) { $older_index = str_replace('js_', '', $older_index); }
        
        // Build an array with avatars to be displayed in posts
        $avatar_array = array();
        if($action != 'SEND') {
            if(trim($this->userList)) {
                $av_lines = explode("\n", trim($this->userList));
                foreach($av_lines as $k => $v) {
                    list($tmp1, $tmp2, $tmp3, $tmp4) = explode('|', trim($v));
                    list($tmp5, $tmp6) = explode('|', base64_decode($tmp2), 2);
                    $avatar_array[base64_decode($tmp1)] = base64_decode($tmp5);
                }
            }
        } else {
            // If user is simply sending a message, only the user avatar is needed
            $avatar_array[$this->name] = $this->uAvatar;
        }

        // Halt if no permission to read
        if(!$this->hasRoomPermission($this->mySession('current_room'), 'R')) {
            echo 'You do not have permission to access this room!';
            die();
        }
        
        // Get the first lastread at room visit if any (will be useful while loading older messages)
        $lastread_new = (
            (
                $this->mySession('lastread_arch_' . $this->mySession('current_room')) && 
                $older_index !== NULL
            ) ? 
            $this->mySession('lastread_arch_' . $this->mySession('current_room')) : 
            $lastread
        );
 
        // Scan the available post lines and populates the respective templates

        // Invert order in order to scan the newest first    
        krsort($lines);
        foreach($lines as $k => $v) {
            list($time, $user, $msg) = explode('|', trim($v), 3);
            $pm_target = FALSE;
            
            // Check if user contains a target parameter (Private Message)
            if(strpos($user, '-') !== FALSE) {
                list($pm_target, $nuser) = explode('-', $user);
                $user = $nuser;
            }

            $self = FALSE; $hidden = FALSE;

            if(strpos($time, '*') !== FALSE) {
                $time = str_replace('*', '', $time);
                $hidden = TRUE; 
            }

            if(preg_match('/^\*/', $user)) {
                $self = TRUE;
                $user = trim($user, '*');
            }
            $time_date = gmdate('d-M', $time + ($this->uTimezone * 3600));

            // Halt scan if no batch retrieval and current message is no longer new
            if(
                $this->myGet('all') != 'ALL' && 
                $time <= $lastread && 
                $older_index === NULL
            ) {
                break;
            }
            
            // Generate an unique id for the post message
            $unique_id = (
                !$self ? 
                ($time . '|' . $user) : 
                ($time . '|*' . $user)
            );
            $hidden_cookie = 'hide_' . $unique_id;
            
            // Start counting older messages
            if($older_index !== NULL) {
                if(
                    (
                        ($prev_unique_id == $older_index && $prev_unique_id) || 
                        $older_index == 'beginning'
                    ) && 
                    $old_start === FALSE
                ) {
                    $old_start = TRUE;
                } elseif($old_start === FALSE) {
                    $prev_unique_id = $unique_id;
                }
            }
           
            // Retrieve messages within the display buffer or older message batch limits
            if(
                (
                    (
                        $time > $lastread || 
                        $this->myGet('all') == 'ALL'
                    ) && 
                    $index < CHAT_DSP_BUFFER
                ) || 
                (
                    $older_index !== NULL && 
                    $old_start === TRUE && 
                    $index < CHAT_OLDER_MSG_STEP
                )
            ) {
                
                // Sets a breakpoint for the "new messages" tag (If previous nessage is new)
                $new = '';
                if(($this->myGet('all') == 'ALL' || $older_index !== NULL) && !$skip_new) {
                    if($previous) {
                        if(
                            $time <= $lastread_new && 
                            $previous > $lastread_new && 
                            $puser != base64_encode($this->name)
                        ) {
                            $new = $this->popTemplate('wcchat.posts.new_msg_separator');
                            $skip_new = 1;
                        } else { $previous = $time; $puser = $user; }
                    } else {
                        $previous = $time;
                        $puser = $user;
                    }
                }
     
                // Halt scan if the custom/global start point is reached
                if(
                    (
                        $time < $this->myCookie(
                            'start_point_' . 
                            $this->mySession('current_room')
                        ) && 
                        $older_index === NULL &&
                        LOAD_EX_MSG !== FALSE
                    ) || 
                    (
                        ($time < $this->mySession('global_start_point')) && 
                        LOAD_EX_MSG === FALSE && 
                        $this->mySession('global_start_point')
                    )
                ) {
                    $start_point = (
                        LOAD_EX_MSG !== FALSE ? 
                        $this->myCookie(
                            'start_point_' . 
                            $this->mySession('current_room')
                        ) : 
                        $this->mySession('global_start_point')
                    );
                    
                    // Clear Screen Tag
                    $time_date2 = gmdate('d-M', $start_point + ($this->uTimezone * 3600));
                    $output = $this->popTemplate(
                        'wcchat.posts.self',
                        array(
                            'SKIP_ON_OLDER_LOAD' => '_skip',
                            'STYLE' => 'dislay:inline',
                            'TIMESTAMP' => 
                                gmdate(
                                    (($this->uHourFormat == '1') ? 'H:i': 'g:i a'), 
                                    $start_point + ($this->uTimezone * 3600)
                                ).
                                ($time_date2 != $today_date ? ' ' . $time_date2 : ''),
                            'USER' => $this->name,
                            'MSG' => (
                                LOAD_EX_MSG !== FALSE ? 
                                $this->popTemplate('wcchat.posts.undo_clear_screen') : 
                                $this->popTemplate('wcchat.posts.global_clear_screen')
                            ),
                            'ID' => $unique_id,
                            'HIDE_ICON' => ''
                        )
                    ) . $new . $output;
                    break;
                }

                // Skip current line if the post belongs to the current user and the user is not sending a message nor performing a batch retrieval (Older posts, new room visit)
                if(
                    $this->myGet('all') != 'ALL' && 
                    $older_index === NULL && 
                    base64_decode($user) == $this->name && 
                    $action != 'SEND'
                ) {
                    $index++;
                    continue;
                }

                // Process the post if not part of an ignore
                if(!$this->myCookie('ign_'.$user)) {
                    if(!$self) {
                        if(
                            $pm_target === FALSE || 
                            (
                                (
                                    base64_decode($pm_target) == $this->name || 
                                    base64_decode($user) == $this->name
                                ) && 
                                $this->hasProfileAccess && 
                                $pm_target !== FALSE
                            )
                        ) {
                            $output = $this->popTemplate(
                                'wcchat.posts.normal',
                                array(
                                    'PM_SUFIX' => ($pm_target !== FALSE ? '_pm' : ''),
                                    'PM_TAG' => $this->popTemplate(
                                        'wcchat.posts.normal.pm_tag', 
                                        '', 
                                        $pm_target !== FALSE
                                    ),
                                    'STYLE' => (
                                        $this->myCookie('hide_time') ? 
                                        'display:none' : 
                                        'display:inline'
                                    ),
                                    'TIMESTAMP' => 
                                        gmdate(
                                            (($this->uHourFormat == '1') ? 'H:i' : 'g:i a'), 
                                            $time + ($this->uTimezone * 3600)
                                        ) . 
                                        ($time_date != $today_date ? ' ' . $time_date : '')
                                    ,
                                    'POPULATE_START' => $this->popTemplate(
                                        'wcchat.posts.normal.populate_start', 
                                        array('USER' => addslashes(base64_decode($user))), 
                                        base64_decode($user) != $this->name
                                    ),
                                    'USER' => base64_decode($user),
                                    'POPULATE_END' => $this->popTemplate(
                                        'wcchat.posts.normal.populate_end', 
                                        '', 
                                        base64_decode($user) != $this->name
                                    ),
                                    'PM_TARGET' => (
                                            (base64_decode($pm_target) != $this->name) ?
                                            $this->popTemplate(
                                                'wcchat.posts.normal.pm_target', 
                                                array('TITLE' => base64_decode($pm_target)), 
                                                $pm_target !== FALSE
                                            ) :
                                            $this->popTemplate(
                                                'wcchat.posts.normal.pm_target.self', 
                                                array('TITLE' => base64_decode($pm_target)), 
                                                $pm_target !== FALSE
                                            )
                                        ),
                                    'MSG' => $this->popTemplate(
                                        'wcchat.posts.hidden' . ($hidden ? '_mod' : ''), 
                                        '', 
                                        $hidden || $this->myCookie($hidden_cookie), 
                                        $this->parseBbcode($msg)
                                    ),
                                    'ID' => $unique_id,
                                    'HIDE_ICON' => $this->popTemplate(
                                        'wcchat.posts.hide_icon', 
                                        array(
                                            'REVERSE' => (
                                                ($hidden || $this->myCookie($hidden_cookie)) ? 
                                                '_r' : 
                                                ''
                                            ), 
                                            'ID' => $unique_id, 
                                            'OFF' => ''
                                        ),
                                        !$this->mySession('archive')
                                    ),
                                    'AVATAR' => (
                                        isset($avatar_array[base64_decode($user)]) ? 
                                        (
                                            $this->hasData($avatar_array[base64_decode($user)]) ? 
                                            $this->includeDir . 'files/avatars/' . $avatar_array[base64_decode($user)] : 
                                            INCLUDE_DIR_THEME . DEFAULT_AVATAR
                                        ) : 
                                        INCLUDE_DIR_THEME . DEFAULT_AVATAR
                                    ),
                                    'WIDTH' => $this->popTemplate(
                                        'wcchat.posts.normal.width', 
                                        array('WIDTH' => $this->uAvatarW)
                                    )
                                )
                            ). $new . $output;
                            
                            $index++;
                            
                            if($this->myGet('all') == 'ALL' || $older_index !== NULL) {
                                $first_elem = ($hidden ? 
                                    str_replace('|', '*|', $unique_id) : 
                                    $unique_id
                                );
                                $first_elem_time = $time;
                                $first_elem_user = $user;
                            }
                        }
                    } else {
                        $output = $this->popTemplate(
                            'wcchat.posts.self',
                            array(
                                'SKIP_ON_OLDER_LOAD' => '',
                                'STYLE' => ($this->myCookie('hide_time') ? 'display: none' : 'dislay:inline'),
                                'TIMESTAMP' => 
                                    gmdate(
                                        (($this->uHourFormat == '1') ? 'H:i': 'g:i a'), 
                                        $time + ($this->uTimezone * 3600)
                                    ).
                                    ($time_date != $today_date ? ' '.$time_date : ''),
                                'USER' => base64_decode($user),
                                'MSG' => $this->popTemplate(
                                    'wcchat.posts.hidden', 
                                    '', 
                                    $hidden || $this->myCookie($hidden_cookie), 
                                    $this->parseBbcode($msg)
                                ),
                                'ID' => $unique_id,
                                'HIDE_ICON' => $this->popTemplate(
                                    'wcchat.posts.hide_icon', 
                                    array(
                                        'REVERSE' => (
                                            ($hidden || $this->myCookie($hidden_cookie)) ? 
                                            '_r' : 
                                            ''
                                        ), 
                                        'ID' => $unique_id, 
                                        'OFF' => ''
                                    ),
                                    !$this->mySession('archive')
                                )
                            )
                        ) . $new . $output;
                        
                        $index++;
                        if($this->myGet('all') == 'ALL' || $older_index !== NULL) {
                            $first_elem = ($hidden ? 
                                str_replace('|', '*|', $unique_id) : 
                                $unique_id
                            );
                            $first_elem_time = $time;
                            $first_elem_user = $user;
                        }
                    }
                }
            }
            
            // Halt if next index increment does not obey display buffer/older msg batch limits
            if(
                ($index >= CHAT_DSP_BUFFER && $older_index === NULL) || 
                ($older_index !== NULL && $index >= CHAT_OLDER_MSG_STEP)
            ) {
                break;
            }
        }

        // check if the very first element needs a "new messages" tag

        if(
            $first_elem_time > $lastread_new && 
            $first_elem_name != base64_encode($this->name) && 
            !$skip_new && 
            $output
        ) {
            $output = $this->popTemplate('wcchat.posts.new_msg_separator') . $output;
        }
        
        return array($output, $index, $first_elem);
    }

    /**
     * Parses Event Message(s), event messages are room independent, users get them no matter the room they're in
     * 
     * @since 1.2
     * @param string $lines
     * @param int $lastread
     * @param string $action Specifies if the user is sending or receiving messages
     * @return string|void Html template
     */
    private function parseMsgE($lines, $lastread, $action) {
    
        $output = '';
        
        // Store today's date to strip of today's events
        $today_date = gmdate('d-M', time() + ($this->uTimezone * 3600));

        krsort($lines);
        foreach($lines as $k => $v) {
        
            // Count the number of parameters (3 = normal event, 4 = event with a target user)
            $tar_user = '';
            if(substr_count($v, '|') == 3) {
                list($time, $user, $tar_user, $msg) = explode('|', trim($v), 4);
                $tar_user = base64_decode($tar_user);
            } else {
                      list($time, $user, $msg) = explode('|', trim($v), 3);
            }
            
            // Process event if target is current user or no target
            if(($tar_user && $tar_user == $this->mySession('cname')) || !$tar_user) {
            
                // Event's date to compare with today's
                $time_date = gmdate('d-M', $time + ($this->uTimezone * 3600));
                
                // Halt if: event is not new or user doesn't have a read point and not sending or user already has the event listed
                if(
                    ($time <= $lastread) || 
                    (!$lastread && $action != 'SEND') || 
                    (time() - $time) > $this->catchWindow
                ) {
                    break;
                }

                // Skip current line if event's user is the current user and is not sending
                if(base64_decode($user) == $this->name && $action != 'SEND') { continue; }

                // Parse event if not outdated
                if($time > $lastread) { 
                    $output = $this->popTemplate(
                        'wcchat.posts.event',
                        array(
                            'STYLE' => ($this->myCookie('hide_time') ? 'display: none' : 'dislay:inline'),
                            'TIMESTAMP' => 
                                gmdate(
                                    (($this->uHourFormat == '1') ? 'H:i': 'g:i a'), 
                                    $time + ($this->uTimezone * 3600)
                                ).
                                ($time_date != $today_date ? ' '.$time_date : ''),
                            'MSG' => $this->parseBbcode($msg)
                        )
                    ) . $output;
                }
            }
        }
        return $output;
    }

    /**
     * Parses BBCODE/Smilie Tags Into Html codes
     * 
     * @since 1.1
     * @param string $data
     * @return string Parsed Msg
     */
    private function parseBbcode($data) {
    
        $search =
            array(
                '/\[b\](.*?)\[\/b\]/i',
                '/\[i\](.*?)\[\/i\]/i',
                '/\[u\](.*?)\[\/u\]/i',
                '/\[img](.*?)\[\/img\]/i',
                '/\[imga\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)\](.*?)\[\/img\]/i',
                '/\[imga\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)\|tn_([0-9A-Z]+)\](.*?)\[\/img\]/i',
                '/\[img\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)\](.*?)\[\/img\]/i',
                '/\[img\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)\|tn_([0-9A-Z]+)\](.*?)\[\/img\]/i',
                '/\[img\|([0-9]+)\](.*?)\[\/img\]/i',
                '/\[url\="(.*?)"\](.*?)\[\/url\]/i',
                '/\[attach_(.*?)_([0-9a-f]+)_([0-9]+)_([A-Za-z0-9 _\.]+)\]/i',
                '/https:\/\/www\.youtube\.com\/watch\?v=([0-9a-zA-Z]*)/i',
                '/(?<!href=\"|src=\"|\])((http|ftp)+(s)?:\/\/[^<>\s]+)/i'
               );

        // Download permission alert message
        $down_perm = ($this->hasPermission('ATTACH_DOWN', 'skip_msg') ? TRUE : FALSE);
        $down_alert = (
            $this->hasPermission('ATTACH_DOWN', 'skip_msg') ? 
            '' : 
            'onclick="alert(\'Your usergroup does not have permission to download arquives / full size images!\'); return false;"'
        );

        $replace =
            array(
                '<b>\\1</b>',
                '<i>\\1</i>',
                '<u>\\1</u>',
                '<div style="margin: 10px">
                    <img src="\\1" class="thumb" onload="wc_scroll()">
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="\\5" style="width: \\3px; height: \\4px;" class="thumb" onload="wc_scroll()"><br>
                    <img src="' . INCLUDE_DIR_THEME . 'images/attach.png">
                    <a href="' . ($down_perm ? $this->includeDir . 'files/attachments/\\5' : '#') . '" target="_blank" ' . $down_alert . '>\\1 x \\2</a>
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="' . $this->includeDir . 'files/thumb/tn_\\5.jpg" class="thumb" onload="wc_scroll()"><br>
                    <img src="' . INCLUDE_DIR_THEME . 'images/attach.png">
                    <a href="' . ($down_perm ? $this->includeDir . 'files/attachments/\\6' : '#') . '" target="_blank" ' . $down_alert . '>\\1 x \\2</a>
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="\\5" style="width: \\3px; height: \\4px;" class="thumb" onload="wc_scroll()"><br>
                    <a href="' . ($down_perm ? '\\5' : '#') . '" target="_blank" ' . $down_alert . '>\\1 x \\2</a>
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="' . $this->includeDir . 'files/thumb/tn_\\5.jpg" class="thumb" onload="wc_scroll()"><br>
                    <a href="' . ($down_perm ? '\\6' : '#') . '" target="_blank" ' . $down_alert.'>\\1 x \\2</a>
                </div>',
                '<div style="width: \\1px;" class="thumb_container">
                    <img src="\\2" style="width: \\1px;" class="thumb" onload="wc_scroll()"><br>
                    <a href="' . ($down_perm ? '\\2' : '#').'" target="_blank" ' . $down_alert . '>Unknown Dimensions</a>
                </div>',
                '<a href="\\1" target="_blank">\\2</a>',
                '<div style="margin:10px;">
                    <i>
                        <img src="' . INCLUDE_DIR_THEME . 'images/attach.png"> 
                        <a href="' . ($down_perm ? $this->includeDir . 'files/attachments/\\1_\\2_\\4' : '#') . '" target="_blank" ' . $down_alert . '>\\4</a> 
                        <span style="font-size: 10px">(\\3KB)</span>
                    </i>
                </div>',
                '<div id="im_\\1">
                    <a href="#" onclick="wc_pop_vid(\'\\1\', ' . VIDEO_WIDTH . ', ' . VIDEO_HEIGHT . '); return false;">
                    <img src="' . INCLUDE_DIR_THEME . 'images/video_cover.jpg" class="thumb" style="margin: 10px" onload="wc_scroll()"></a>
                </div>
                <div id="wc_video_\\1" class="closed"></div>',
                '<a href="\\0" target="_blank" style="font-size:10px;font-family:tahoma">\\0</a>'
            );

        $output = preg_replace($search, $replace, $data);

        // Process smilies next with the bbcode
        $smilies =
            array(
                '1' => ':)',
                '3' => ':o',
                '2' => ':(',
                '4' => ':|',
                '5' => ':frust:',
                '6' => ':D',
                '7' => ':p',
                '8' => '-_-',
                '10' => ':E',
                '11' => ':mad:',
                '12' => '^_^',
                '13' => ':cry:',
                '14' => ':inoc:',
                '15' => ':z',
                '16' => ':love:',
                '17' => '@_@',
                '18' => ':sweat:',
                '19' => ':ann:',
                '20' => ':susp:',
                '9' => '>_<'
            );


        foreach($smilies as $key => $value) {
            $output =
                str_replace(
                    $value,
                    $this->popTemplate(
                        'wcchat.toolbar.smiley.item.parsed',
                        array('key' => $key)
                    ),
                    $output
                );
        }

        return $output;
    }

    /**
     * Parses the difference between two timestamps into a user-friendly string
     * 
     * @since 1.1
     * @param string $date
     * @param string|null $mode
     * @return string
     */
    private function parseIdle($date, $mode = NULL) {
    
        if($mode == NULL) {
            $it = $timesec = time()-$date;
        } else {
            $it = $timesec = $date-time();
        }

        $str = '';
        $ys = 60 * 60 * 24 * 365;

        $year = intval($timesec/$ys);
        if($year >= 1) {
            $timesec = $timesec%$ys;
        }

        $month = intval($timesec/2628000);
        if($month >= 1) {
            $timesec = $timesec%2628000;
        }
    
        $days = intval($timesec/86400);
        if($days >= 1) {
            $timesec = $timesec%86400;
        }

        $hours = intval($timesec/3600);
        if($hours >= 1) {
            $timesec = $timesec%3600;
        }

        $minutes = intval($timesec/60);
        if($minutes >= 1) {
            $timesec = $timesec%60;
        }

        $par_count = 0;

        if($year > 0) {
            $str = $year . 'Y';
            $par_count++;
        }
        if($month > 0) {
            $str .= ' ' . $month . 'M';
            $par_count++;
        }
        if($days > 0 && $par_count < 2) {
            $str .= ' ' . $days . 'd';
            $par_count++;
        }
        if($hours > 0 && $par_count < 2) {
            $str .= ' ' . $hours . 'h';
            $par_count++;
        }
        if($minutes > 0 && $par_count < 2 && $it < (60*60*2)) {
            $str .= ' ' . $minutes . 'm';
            $par_count++;
        }
        if($it < 60) {
            $str = $it . 's';
        }

        return(trim($str));
    }
    
    /**
     * Parses the cookie name
     * 
     * @since 1.4
     * @param string $name
     * @return string
     */
    private function parseCookieName($name) {
    
        return str_replace(
            array('=', ',', ';', ' ', "\t", "\r", "\013", "\014"), 
            array('_', '_', '_', '_', '', '', '', ''), 
            $name
        );    
    }

      /*===========================================
       |       AJAX COMPONENT METHODS             |
       ============================================
       |  checkTopicChanges                       |
       |  refreshRooms                            |
       |  checkHiddenMsg                          |
       |  refreshMsgE                             |
       |  refreshMsg                              |
       |  refreshUsers                            |
       ===========================================*/

    /**
     * Checks if Topic has Changed, if yes, returns the populated html template (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */
    private function checkTopicChanges() {
    
        $lastmod_t = $this->parseFileMTime(TOPICL);
        $lastread_t = $this->handleLastRead(
            'read', 
            'topic_' . $this->mySession('current_room')
        );

        // If changed or reload request exists, return the parsed html template
        if($lastmod_t > $lastread_t || $this->myGet('reload') == '1') {
            $this->handleLastRead('store', 'topic_' . $this->mySession('current_room'));
            $t = $this->topic;
            return $this->parseTopicContainer();
        }
    }

    /**
     * Refresh Rooms if changes exist (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */
    private function refreshRooms() {
    
        $lastread = $this->handleLastRead('read', 'rooms_lastread');
        $lastmod = $this->parseFileMTime(ROOMS_LASTMOD);

        // If changed, return the parsed html template
        if($lastread < $lastmod) {
            $this->handleLastRead('store', 'rooms_lastread');
            return $this->parseRooms();
        }
    }

    /**
     * Retrieves the list of Hidden Messages (Ajax Component)
     * 
     * @since 1.3
     * @return string|void
     */
    private function checkHiddenMsg() {
    
        return trim($this->hiddenMsgList, ' ');
    }

    /**
     * Parses Event Messages (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */
    private function refreshMsgE() {
    
        if(!$this->hasPermission('READ_MSG', 'skip_msg')) {
            return 'Can\'t display messages.';
        }
        $output_e = '';
        $lastmod = $this->parseFileMTime(EVENTL);
        
        // Read last read point
        $lastread = $this->handleLastRead('read', 'events_');

        // Parse event messages if modified and is no new room visit
        if($lastmod > $lastread && $this->myGet('all') != 'ALL') {
        
            // Store a new read point
            $this->handleLastRead('store', 'events_');

            // Parse/retrieve other user's messages if they exist
            if($this->hasData($this->eventList)) {
                $lines = explode("\n", trim($this->eventList));
                $output_e = $this->parseMsgE($lines, $lastread, 'RECEIVE');
            }
        }
        if($output_e) { return $output_e; }
    }

    /**
     * Updates/Refreshes Screen Post Messages (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */    
    private function refreshMsg() {
    
        if($this->isBanned !== FALSE) {
            return 'You are banned!';
        }
        if(!$this->hasPermission('READ_MSG', 'skip_msg')) {
            return 'Can\'t display messages.';
        }
        
        $output = '';
        $lastmod = $this->parseRoomLastMod();
        $older_index = $this->myGet('n');
        if(!$this->hasData($older_index)) { $older_index = NULL; }

        $lastread = $this->handleLastRead('read');

        // Reload messages if reset_msg tag exists
        if($this->mySession('reset_msg')) { $_GET['all'] = 'ALL'; }

        // Halt if is no new room visit and lastread is not set (user might not be accepting cookies)
        if($this->myGet('all') != 'ALL' && !$lastread) { die(); }

        // Store the first lastread to be used on older message loading
        if($this->myGet('all') == 'ALL' && $lastread) {
            $this->wcSetSession(
                'lastread_arch_' . $this->mySession('current_room'), 
                $lastread
            );
        }

        $lines = array();
        $index = 0;
        
        // Reset archive cached volume if any
        if(
            $this->mySession('archive') && 
            $older_index === NULL && 
            !$this->myGet('loop')
        ) {
            $this->wcUnsetSession('archive');
        }

        // Parse post messages if new messages exist or new room visit or older index exists
        if(
            $lastmod > $lastread || 
            $this->myGet('all') == 'ALL' || 
            $older_index !== NULL
        ) {
            
            // If "load existing messages" is disabled, automatically set a global start point 
            if(!$this->mySession('global_start_point') && LOAD_EX_MSG === FALSE) {
                $this->wcSetSession('global_start_point', time());
            }
            
            $this->handleLastRead('store');

            // Retrieve post messages
            if(strlen($this->msgList) > 0 || $this->mySession('archive')) {
                if(!$this->mySession('archive')) {
                    $lines = explode("\n", trim($this->msgList));
                } else {
                    // Scan Current Archive volume
                    $archive_vol = intval($this->mySession('archive'));
                    $archive_target = 
                        $this->roomDir . 
                        base64_encode($this->mySession('current_room')) . '.' . 
                        ($this->roomLArchVol + 1 - $archive_vol)
                    ;
                    $lines = explode("\n", trim($this->readFile($archive_target)));
                }
                
                // Scan next archive volume if oldest chat post is the archive's last
                if(
                    $older_index !== NULL && 
                    strpos(
                        str_replace('*|', '|', $lines[0]), 
                        str_replace('js_', '', $older_index)
                    ) !== FALSE
                ) {
                    
                    $next_archive_vol =  (intval($this->mySession('archive')) + 1);
                    $next_archive_target = 
                        $this->roomDir . 
                        base64_encode($this->mySession('current_room')) . '.' . 
                        ($this->roomLArchVol + 1 - $next_archive_vol)
                    ;
                    
                    if(file_exists($next_archive_target)) {
                        $lines = explode(
                            "\n", 
                            trim($this->readFile($next_archive_target))
                        );
                        $older_index = 'beginning';
                        $this->wcSetSession('archive', $next_archive_vol);
                    }  
                }
                list($output, $index, $first_elem) =
                    $this->parseMsg($lines, $lastread, 'RETRIEVE', $older_index);
            }
        }

        // If content exists before the oldest displayed message or archives exist, initiate the controls to load older posts
        $older_controls = '';
        if(count($lines) && $this->myGet('all') == 'ALL' && LOAD_EX_MSG === TRUE) {
            if($first_elem) {
                list($tmp1, $tmp2) = explode($first_elem, $this->msgList, 2);
                if(trim($tmp1) || $this->roomLArchVol > 0) {
                    $older_controls = $this->popTemplate('wcchat.posts.older');
                }
            } else {
                // No first element returned but lines exist? Add controls in case of screen cleanup.
                $older_controls = $this->popTemplate('wcchat.posts.older');
            }
        }

        // Return output, also remove auto-scroll Javascript tags from retrieved older messages
        if(trim($output)) {
            $output = $older_controls.
            (
                ($older_index !== NULL) ? 
                str_replace(
                    'wc_scroll()', 
                    '', 
                    $output . $this->popTemplate('wcchat.posts.older.block_separator')
                ) : 
                $output
            );
        }

        // Process RESET tag
        if($this->mySession('reset_msg')) {
            $output = 'RESET' . $output;
            $this->wcUnsetSession('reset_msg');
        }

        // Delay Older Message/New Room Visit Loading (In order to display the loader image)
        if(($older_index !== NULL) || $this->myGet('all') == 'ALL') { sleep(1); }
        
        return $output;
    }

    /**
     * Updates/Refreshes Screen User List (Ajax Component)
     * 
     * @param string|null $visit New user visit tag provided by ajax caller     
     * @since 1.4
     * @return string|void Html Template
     */
    private function refreshUsers($visit = NULL) {

        // Store user ping
        $this->setPing();

        if($this->isBanned !== FALSE) { return 'You are banned!'; }
        if(!$this->hasPermission('USER_LIST', 'skip_msg')) {
            return 'Can\'t display users.';
        }

        $contents = $this->userList;
        $user_row = $original_row = $this->userMatch($this->name, NULL, 'RETURN_MATCH');
        $changes = FALSE;

        // Reset user status on a new visit or update guest user listing if enabled
        if($visit != NULL) {
            if($this->hasProfileAccess) {
                $user_row = $this->updateUser($user_row, 'update_status', 0);
                $changes = TRUE;
            }

            // If LIST_GUESTS is disabled, only process user if he/she joined the chat at least once in the past
            // Note: A user is not considered part of the guest group if he/she is logged to an unprotected name, but
            // to be listed, needs to join the chat (unless LIST_GUESTS is enabled)
            if(
                $this->isLoggedIn && 
                (
                    LIST_GUESTS === TRUE || 
                    (
                        LIST_GUESTS === FALSE && 
                        $this->uData[6] != '0'
                    )
                )
            ) {
                if($this->userMatch($this->name) === FALSE) {
                    if($this->name != 'Guest') {
                        $contents .= "\n" . 
                            base64_encode($this->name) . '|' . 
                            $this->userDataString . 
                            '|0|' . 
                            time() . 
                            '|0'
                        ;
                    }
                } else {
                    $user_row = $this->updateUser($user_row, 'user_visit');
                }
                $changes = TRUE;
            }
        }

        // Creates New User/Update user last activity on join, also update status 
        if($this->myGet('join') == '1') {
            if($this->userMatch($this->name) === FALSE) {
                if($this->name != 'Guest') {
                    $contents .= "\n" . 
                        base64_encode($this->name) . '|' . 
                        $this->userDataString . '|' . 
                        time(). '|' . 
                        time(). '|1'
                    ;
                }
            } else {
                $user_row = $this->updateUser($user_row, 'user_join');
            }
            $user_row = $this->updateUser($user_row, 'update_status', 1);
            $changes = TRUE;
        }

        // Update user last activity on new message
        if($this->myGet('new') == '1') {
            $user_row = $this->updateUser($user_row, 'new_message');
            $changes = TRUE;
        }

        // Write changes
        if($changes) {
            if($user_row !== FALSE) {
                $contents = str_replace(
                    trim(implode('|', $original_row)),
                    trim(implode('|', $user_row)),
                    trim($contents)
                );
            }
            $this->writeFile(USERL, trim($contents), 'w');
        }

        // Parses permissions
        $edit_perm = FALSE;
        if($this->hasPermission('USER_E', 'skip_msg')) {
            $edit_perm = TRUE;
        }

        $mod_perm = FALSE;
        if(
            $this->hasPermission('MOD', 'skip_msg') || 
            $this->hasPermission('UNMOD', 'skip_msg') || 
            $this->hasPermission('BAN', 'skip_msg') || 
            $this->hasPermission('UNBAN', 'skip_msg') || 
            $this->hasPermission('MUTE', 'skip_msg') || 
            $this->hasPermission('UNMUTE', 'skip_msg')
        ) {
            $mod_perm = TRUE;
        }

        $del_perm = FALSE;
        if($this->hasPermission('USER_D', 'skip_msg')) {
            $del_perm = TRUE;
        }

        $output = $this->parseUsers(
            $contents, $mod_perm, $edit_perm, $del_perm, $visit
        );

        // Sets a cookie to enlarge refresh delay if user is idling
        if(
            !$this->myCookie('idle_refresh') && 
            $visit === NULL && 
            (time()-$this->uData[7]) > IDLE_START && 
            REFRESH_DELAY_IDLE != 0
        ) {
            $this->wcSetCookie('idle_refresh', REFRESH_DELAY_IDLE);
        }

        // Return user list if changes exist
        $sfv = strtoupper(dechex(crc32(trim($output))));
        if(
            ($sfv != $this->mySession('user_list_sfv')) || 
            !$this->mySession('user_list_sfv') || 
            $visit !== NULL || 
            $this->myGet('ilmod')
        ) {
            $this->wcSetSession('user_list_sfv', $sfv);
            return $output;
        }
    }

      /*===========================================
       |       AJAX PROCESSOR METHOD              |
       ============================================
       |  ajax                                    |
       ===========================================*/

    /**
     * Processes Ajax Requests
     * 
     * @since 1.1
     * @param string $mode
     * @return void
     */
    public function ajax() {

        if($this->myGet('mode')) {
        
		if(!preg_match('/[^A-Za-z0-9_]/i', $this->myGet('mode'))) {
			include($this->includeDirServer . 'includes/wcajax.' . $this->myGet('mode') . '.php');
            }
            
        }   
    }

      /*===========================================
       |       OTHER METHODS                      |
       ============================================
       |  mailHeaders                             |
       |  randNumb                                |
       |  hasData                                 |
       ===========================================*/

    /**
     * Generates Mail Headers
     * 
     * @since 1.3
     * @param string $from
     * @param string $fname
     * @param string $to
     * @param string $toname
     * @return string
     */
    private function mailHeaders($from, $fname, $to, $toname) {
    
        $headers = "MIME-Version: 1.0\n";
        $headers.= "From: ".$fname." <".$from.">\n";
        $headers .= "To: ".$toname."  <".$to.">\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";
        $headers .= "Return-Path: <" . $from . ">\n";
        $headers .= "Errors-To: <" . $from . ">\n"; 
        $headers .= "Content-Type: text/plain; charset=iso-8859-1\n";

        return($headers);
    }

    /**
     * Generates A Random Number
     * 
     * @since 1.2
     * @param string $n Length
     * @return string
     */
    private function randNumb($n) {
    
        $output = '';
          $salt = '0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
          srand((double)microtime()*1000000);
          $i = 0;
          while ($i < $n) {
            $num = rand() % 33;
            $tmp = substr($salt, $num, 1);
            $output = $output . $tmp;
            $i++;
        }
        return $output;
    }

    /**
     * Checks if a variable contains data
     * 
     * @since 1.1
     * @param string $var
     * @return bool
     */
    private function hasData($var) {

        if (strlen($var) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>
