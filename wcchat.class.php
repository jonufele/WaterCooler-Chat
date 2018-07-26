<?php

/**
 * WaterCooler Chat (Main Class file)
 * 
 * @version 1.4
 * @author João Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, João Ferreira
 */
    
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
    private $udata;

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
     * Construct
     * 
     */
    public function __construct() {

        // Halt if user is automated
        require_once(__DIR__ . "/bots.php");
        if (preg_match($wc_botcheck_regex, $this->myServer('HTTP_USER_AGENT'))) { 
	    header("HTTP/1.0 403 Forbidden");
	    exit;
	}

        if(session_id() == '') { session_start(); }

        // Initialize Includes / Paths
        $this->initIncPath();

        // Initialize Current Room
        $this->initCurrRoom();

        // Initialize Data files, print stop message if non writable folders exist
        $this->initDataFiles();

        // Handles main form requests
        $this->handleMainForm();

        // Initializes user variables
        $this->initUser();

        // Print Index / Process Ajax
        if($this->myGet('mode')) {
            $this->ajax($this->myGet('mode'));
        }
    }

      /*===============================================
       |       METHOD CATEGORIES                      |
       ================================================
       |  INITIALIZATION (5)                          |
       |  USER DATA HANDLING (3)                      |
       |  ACCESS SECURITY (2)                         |
       |  FILE WRITE/READ (4)                         |
       |  IMAGE HANDLING (4)                          |
       |  PGC/SESSION/SERVER HANDLING (6)             |
       |  SETTERS/GETTERS (8)                         |
       |  PARSER / INTERFACER (13)                    |
       |  AJAX COMPONENTS (4)                         |
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
       ===========================================*/

    /**
     * Initializes Includes / Paths
     * 
     * @since 1.4
     * @return void
     */
    private function initIncPath() {

        include(__DIR__ . '/settings.php');
        $this->includeDir = (INCLUDE_DIR ? '/' . trim(INCLUDE_DIR, '/') . '/' : '');
        define('THEME', (
            ($this->myCookie('wc_theme') && file_exists($this->includeDir . 'themes/' . $this->myCookie('wc_theme') . '/')) ? 
            $this->myCookie('wc_theme') : 
            DEFAULT_THEME
        ));
        define('INCLUDE_DIR_THEME', $this->includeDir . 'themes/' . THEME . '/');
        include(__DIR__ . '/themes/' . THEME . '/templates.php');
        $this->templates = $templates;
        $this->ajaxCaller = $this->includeDir.'ajax.php?';
        $this->dataDir = (DATA_DIR ? rtrim(DATA_DIR, '/') . '/' : '');
        $this->roomDir = DATA_DIR . 'rooms/';
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
            if($this->myCookie('cname')) {
                if($this->userMatch($this->myCookie('cname')) !== FALSE) {
                    $_SESSION['cname'] = $this->name = $this->myCookie('cname');
                    $this->isLoggedIn = TRUE;
                } else {
                    // Cookie user does not exist / outdated, fail to resume session
                    // Inform user via login error message
                    $_SESSION['login_err'] = 'Username <i>'.$this->myCookie('cname').'</i> does not exist, cannot be resumed!<br>(Maybe it was renamed by a moderator?)';
                    setcookie('cname', '', time()-3600, '/');
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
        if(($this->uData[7] == 0 || ($this->myCookie('chatpass') != $this->uData[5] && $this->myCookie('chatpass') && $this->uData[5])) && $this->mySession('cname') && $this->myGet('mode')) {
            // If dummy profile, it means the previous name was changed, clear SESSION value
            if($this->uData[7] == 0) { unset($_SESSION['cname']); }

            // Clear password cookie, user must supply it again
            setcookie('chatpass', '', time()-3600, '/');
            $this->isLoggedIn = FALSE;

            // Issue alert
            $_SESSION['alert_msg'] = 
                'Your login credentials are outdated!'."\n".
                'Refresh page (or use the form below) and login again!'."\n".
                '(Possible cause: You (or a moderator) edited your profile.)';
        }

        $this->uAvatar = $this->uData[0];
        $this->uEmail = $this->uData[1];
        $this->uWeb = $this->uData[2];
        $this->uTimezone = $this->uData[3];
        $this->uHourFormat = $this->uData[4];
        $this->uPass = $this->uData[5]; 
        $this->uAvatarW = (AVATAR_SIZE ? AVATAR_SIZE : 25);

        $this->userDataString = base64_encode(
            base64_encode($this->uAvatar).'|'.
            base64_encode($this->uEmail).'|'.
            base64_encode($this->uWeb).'|'.
            $this->uTimezone.'|'.
            $this->uHourFormat.'|'.
            $this->uPass
        );
    
        // Set certified User status
        if($this->hasData($this->uPass) && $this->myCookie('chatpass') && $this->myCookie('chatpass') == $this->uPass) {
            $this->isCertifiedUser = TRUE;
        }

        // Set has perofile access status
        if($this->isLoggedIn && ($this->isCertifiedUser || !$this->uPass)) {
            $this->hasProfileAccess = TRUE;
        }

        // Assign the first moderator if does not exist and current user is certified
        if(strlen(trim($this->modList)) == 0 && $this->isCertifiedUser) {
            $this->writeFile(MODL, "\n".base64_encode($this->name), 'w');
            touch(ROOMS_LASTMOD);
            $_SESSION['alert_msg'] = 'You have been assigned Master Moderator, reload page in order to get all moderator tools.';
        }

        // Set Master Moderator and Moderator status
        if($this->name && $this->isCertifiedUser) {
            if($this->getMod($this->name) !== FALSE) { $this->isMod = TRUE; }
            $mods = explode("\n", trim($this->modList));
            if($mods[0] == base64_encode($this->name)) {
                $this->isMasterMod = TRUE;
            }
        }
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
        $tmp4 = is_writable(__DIR__ . '/files/');
        $tmp5 = is_writable(__DIR__ . '/files/attachments/');
        $tmp6 = is_writable(__DIR__ . '/files/avatars/');
        $tmp7 = is_writable(__DIR__ . '/files/thumb/');
        if(!$tmp1 || !$tmp2 || !$tmp3 || !$tmp4 || !$tmp5 || !$tmp6 || !$tmp7) {
            if(!$tmp1 || !$tmp2 || !$tmp3) {
                $output .= '<h2>Data Directories (Absolute Server Path)</h2>';
            }
            if(!$tmp1) { $output .= addslashes($this->roomDir)."\n"; }
            if(!$tmp2) { $output .= addslashes($this->dataDir)."\n"; }
            if(!$tmp3) { $output .= addslashes($this->dataDir . 'tmp/')."\n"; }
            if(!$tmp4 || !$tmp5 || !$tmp6 || !$tmp7) {
                $output .= '<h2>File Directories (Relative Web Path)</h2>';
            }
            if(!$tmp4) { $output .= addslashes($this->includeDir . 'files/')."\n"; }
            if(!$tmp5) { $output .= addslashes($this->includeDir . 'files/attachments/')."\n"; }
            if(!$tmp6) { $output .= addslashes($this->includeDir . 'files/avatars/')."\n"; }
            if(!$tmp7) { $output .= addslashes($this->includeDir . 'files/thumb')."\n"; }
            return nl2br(trim($output));
        } else {
            return FALSE;
        }
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
                    'ERROR' => 'Non Writable Data / File directories exist, please set write permissions to the following directories:<br><br>'.$has_non_writable_folders
                )
            );
            die(); 
        }

        define('MESSAGES_LOC', $this->roomDir . base64_encode($this->mySession('current_room')).'.txt');
        define('TOPICL', $this->roomDir . 'topic_'.base64_encode($this->mySession('current_room')).'.txt');
        define('ROOM_DEF', $this->roomDir . 'def_'.base64_encode($this->mySession('current_room')).'.txt');
        define('MESSAGES_HIDDEN', $this->roomDir . 'hidden_'.base64_encode($this->mySession('current_room')).'.txt');
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
        if(!file_exists(MESSAGES_LOC)) { file_put_contents(MESSAGES_LOC, time().'|*'.base64_encode('Room').'| has been created.'."\n"); }
        if(!file_exists(TOPICL)) { file_put_contents(TOPICL, ''); }
        if(!file_exists(ROOM_DEF)) { file_put_contents(ROOM_DEF, '0|0|0|0|'); }
        if(!file_exists(ROOMS_LASTMOD)) { file_put_contents(ROOMS_LASTMOD, ''); }
        if(!file_exists(MESSAGES_HIDDEN)) { file_put_contents(MESSAGES_HIDDEN, ''); }

        $this->modList = $this->readFile(MODL);
        $this->mutedList = $this->readFile(MUTEDL);
        $this->msgList = $this->readFile(MESSAGES_LOC);
        $this->roomDef = $this->readFile(ROOM_DEF);
        $this->hiddenMsgList = $this->readFile(MESSAGES_HIDDEN);
        $this->userList = $this->readFile(USERL);
        $this->topic = $this->readFile(TOPICL);
        $this->bannedList = $this->readFile(BANNEDL);
        $this->eventList = $this->readFile(EVENTL);

       // Write the first message to the room (creation note) if no messages exist
        if($this->mySession('current_room') == DEFAULT_ROOM && strlen($this->msgList) == 0) {
            $towrite = time().'|*'.base64_encode('room').'|has been created.'."\n";
            $this->writeFile(MESSAGES_LOC, $towrite, 'w');
            $this->msgList = $this->readFile(MESSAGES_LOC);
        }
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
            $_SESSION['current_room'] = (($this->myCookie('current_room') && file_exists($this->roomDir . base64_encode($this->myCookie('current_room')).'.txt')) ? $this->myCookie('current_room') : DEFAULT_ROOM);
        } elseif(!file_exists($this->roomDir . base64_encode($this->mySession('current_room')).'.txt')) {
            $_SESSION['current_room'] = DEFAULT_ROOM;
            $_SESSION['reset_msg'] = '1';
            setcookie('current_room', DEFAULT_ROOM, time()+(86400*365), '/');
        }
    }

      /*===========================================
       |       USER DATA HANDLING METHODS         |
       ============================================
       |  userMatch                               |
       |  userData                                |
       |  userLAct                                |
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
            if($tmp == base64_encode($name)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            // Row has not been supplied, try to find a match on the raw user list
            if(strpos(trim($this->userList), base64_encode($name).'|') !== FALSE) {
                if($return_match !== NULL) {
                    list($tmp, $v2) = explode(base64_encode($name).'|', trim($this->userList), 2);
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
                return array(base64_decode($av), base64_decode($email), base64_decode($lnk), $tmz, $ampm, $pass, $time, $time2, $status);
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
            list($name, $data, $time, $time2, $status) = $this->userMatch($forced_name, NULL, '1');
        } else {
            $time2 = $this->uData[7];
        }
        return $time2;
    }

      /*===========================================
       |       ACCESS SECURITY METHODS            |
       ============================================
       |  hasPermission                           |
       |  hasRoomPermission                       |
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
        $tags = explode(' ', constant('PERM_'.$id));

        if(in_array($level, $tags)) {
            return TRUE;
        } else {
            if($skip_msg == NULL) { echo 'You do not have permission to perform this action!'; }
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

        // $tmp4 = Unused slots in v1.4.10
        list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $tmp4) = 
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

      /*===========================================
       |       FILE WRITE/READ METHODS            |
       ============================================
       |  writeFile                               |
       |  writeEvent                              |
       |  readFile                                |
       |  updateConf                              |
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

        // Overwrite content if last modified time is higher than 60s (Enough time for all online users to get the update)
        if((time()-filemtime(EVENTL)) > 60) { $mode = 'w'; } else { $mode = 'a'; }

        // Write event according to mode value
        switch($omode) {
            case 'ignore':
                $towrite = time().'|'.base64_encode($this->name).'|'.base64_encode($target).'|<b>'.$this->name.'</b> is ignoring you.'."\n";
            break;
                case 'unignore':
                $towrite = time().'|'.base64_encode($this->name).'|'.base64_encode($target).'|<b>'.$this->name.'</b> is no longer ignoring you.'."\n";
            break;
            case 'join':
                $towrite = time().'|'.base64_encode($this->name).'|<b>'.$this->name.'</b> has joined chat.'."\n";
            break;
            case 'topic_update':
                $towrite = time().'|'.base64_encode($this->name).'|<b>'.$this->name.'</b> updated the topic ('.$this->mySession('current_room').').'."\n";
            break;
        }
        $this->writeFile(EVENTL, $towrite, $mode);

        if($return_raw_line != NULL) {
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

        $conf = $this->readFile(__DIR__ . '/settings.php');
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
        $handle = fopen(__DIR__ . '/settings.php', 'w');
        fwrite($handle, $conf);
        fclose($handle);

        if($this->readFile(__DIR__ . '/settings.php') != $conf_bak) {
            return TRUE;
        } else
            return FALSE;    
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
        $source_tag = (($attach === NULL) ? $source : str_replace($this->includeDir . 'files/attachments/', '', $source));

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
                    if($this->thumbnailCreateMed($iname, $image, $w, $h, __DIR__ . '/' .$target, IMAGE_MAX_DSP_DIM)) {
                        return '[IMG'.($attach != NULL ? 'A' : '').'|'.$w.'x'.$h.'|'.$nw.'x'.$nh.'|tn_'.strtoupper(dechex(crc32($iname))).']' . $source_tag . '[/IMG]';
                    } else {
                        return '[IMG'.($attach != NULL ? 'A' : '').'|'.$w.'x'.$h.'|'.$nw.'x'.$nh.']'.$source_tag.'[/IMG]';
                    }
                } else {
                    return '[IMG'.($attach != NULL ? 'A' : '').'|'.$w.'x'.$h.'|'.$nw.'x'.$nh.']'.$source_tag.'[/IMG]';
                }
            } else {
                return '[IMG]'.$source.'[/IMG]';
            }
        } else {
                return '[IMG|'.IMAGE_AUTO_RESIZE_UNKN.']'.$source.'[/IMG]';
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
     * @param string $target_file
     * @param int $thumbsize
     * @return bool
     */
    private function thumbnailCreateMed ($original_image, $source_image, $w, $h, $target_image, $thumbsize)
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

        @ImageCopyResampled($temp_image, $source_image, 0, 0, 0, 0, $thw, $thy, $w, $h);

        @ImageJPEG($temp_image, $target_image, 80);

        @ImageDestroy($temp_image);

        if (!file_exists($target_image)) { return false; } else { return true; }
    }

    /**
     * Generates a cropped square thumbnail
     * 
     * @since 1.1
     * @param string $original_image Path
     * @param string $target_file
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
        @ImageCopyResampled($temp_image1, $source_image, 0, 0, $widthoffset, $heightoffset, $smallestdimension, $smallestdimension, $smallestdimension, $smallestdimension);
        // Create thumbnail and save

        @ImageJPEG($temp_image1, $target_image, 90);

        // Create a temporary new image for the final thumbnail
        $temp_image2 = @ImageCreateTrueColor($thumbsize, $thumbsize);
        // Resize this image to the given thumbnail size
        @ImageCopyResampled($temp_image2, $temp_image1, 0, 0, 0, 0, $thumbsize, $thumbsize, $smallestdimension, $smallestdimension);
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
            unset($_SESSION['cname']);
            unset($_SESSION['login_err']);
            setcookie('cname', '', time()-3600, '/');
            setcookie('chatpass', '', time()-3600, '/');
            header('location: '.$this->myGet('ret').'#wc_topic');
            die();
        }

        // Process Recover Request
        $u = $this->myGet('u');
        if($this->myGet('recover') && $u && file_exists($this->dataDir . 'tmp/rec_'.$u)) {
            $par = $this->userData(base64_decode($u));
            $par2 = $this->userMatch(base64_decode($u), NULL, 'return_match');
            if($par[5] == $this->myGet('recover')) {
                $npass = $this->randNumb(8);
                $npasse = md5(md5($npass));
                $ndata = base64_encode(base64_encode($par[0]).'|'.base64_encode($par[1]).'|'.base64_encode($par[2]).'|'.$par[3].'|'.$par[4].'|'.$npasse);
                $request_uri = explode('?', $_SERVER['REQUEST_URI']);
                if(mail(
                    $par[1],
                    'Account Recovery',
                    "Your new password is: ".$npass."\n\n\n".((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].$request_uri[0],
                    $this->mailHeaders(
                        (trim(ACC_REC_EMAIL) ? trim(ACC_REC_EMAIL) : 'no-reply@'.$_SERVER['SERVER_NAME']),
                        TITLE,
                        $par[1],
                        base64_decode($u)
                    )
                )) {
                    $this->writeFile(USERL, str_replace($u.'|'.$par2[1].'|', $u.'|'.$ndata.'|', $this->userList), 'w');
                    $this->stopMsg = 'A message was sent to the account email with the new password.';
                    unlink($this->dataDir . 'tmp/rec_'.$u);
                } else {
                    $this->stopMsg = 'Failed to send E-mail!';
                };
            }
        } elseif($this->myGet('recover') && $u && !file_exists($this->dataDir . 'tmp/rec_'.$u)) {
            $this->stopMsg = 'This recovery link has expired.';
        }

        // Process Login Request
        if($this->myPost('cname')) {        
            if(!$this->hasPermission('LOGIN', 'skip_msg') || $this->getBanned($this->myPost('cname')) !== FALSE) {
                $_SESSION['login_err'] = 'Cannot login! Access Denied!';
                header('location: '.$this->myServer('REQUEST_URI').'#wc_join'); die();
            }
            if(INVITE_LINK_CODE && $this->myGet('invite') != INVITE_LINK_CODE && $this->userMatch($_POST['cname']) === FALSE) {
                $_SESSION['login_err'] = 'Cannot login! You must follow an invite link to login the first time!';
                header('location: '.$this->myServer('REQUEST_URI').'#wc_join'); die();
            }
            if(strlen(trim($this->myPost('cname'), ' ')) == 0 || strlen(trim($this->myPost('cname'), ' ')) > 30 || preg_match("/[\?<>\$\{\}\"\:\|,; ]/i", $this->myPost('cname'))) {
                $_SESSION['login_err'] = 'Invalid Nickname, too long (max = 30) or<br>with invalid characters (<b>? < > $ { } " : | , ; space</b>)!';
                header('location: '.$this->myServer('REQUEST_URI').'#wc_join'); die();
            }
            if(trim(strtolower($this->myPost('cname'))) == 'guest')
            {
                $_SESSION['login_err'] = 'Username "Guest" is reserved AND cannot be used!';
                header('location: '.$this->myServer('REQUEST_URI').'#wc_join'); die();
            }
            $last_seen = $this->getPing($this->myPost('cname'));

            if(!$this->isLoggedIn && ((time()-$last_seen) < OFFLINE_PING)) {
                $tmp = $this->userData($this->myPost('cname'));
                if($this->myCookie('chatpass') == $tmp[5] && $this->hasData($tmp[5]) && $this->hasData($this->myCookie('chatpass'))) { $passok = TRUE; } else { $passok = FALSE; }
                if($passok === FALSE) {
                    $_SESSION['login_err'] = 'Nickname <b>'.$this->myPost('cname').'</b> is currenly in use!';
                    header('location: '.$this->myServer('REQUEST_URI').'#wc_join'); die();
                }
            }
            setcookie('cname', trim($this->myPost('cname'), ' '), time()+(86400*365), '/');
            $_SESSION['cname'] = trim($this->myPost('cname'), ' ');
            header('location: '.$this->myServer('REQUEST_URI').'#wc_topic');
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
    private function myPost($index) {

        if (isset($_POST[$index])) {
            if ($this->hasData($_POST[$index])) {
                return $this->parseName($_POST[$index]);
            }
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
    private function myGet($index) {

        if (isset($_GET[$index])) {
            if ($this->hasData($_GET[$index])) {
                return $this->parseName($_GET[$index]);
            }
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
    private function myCookie($index) {

        if (isset($_COOKIE[$index])) {
            if ($this->hasData($_COOKIE[$index])) {
                return $this->parseName($_COOKIE[$index]);
            }
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
    private function mySession($index) {

        if (isset($_SESSION[$index])) {
            if ($this->hasData($_SESSION[$index])) {
                return $this->parseName($_SESSION[$index]);
            }
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
    private function myServer($index) {

        if (isset($_SERVER[$index])) {
            if ($this->hasData($_SERVER[$index])) {
                return $this->parseName($_SERVER[$index]);
            }
        }
        return '';
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
            return filemtime($src);
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
        preg_match_all('/^'.base64_encode($name).' ([0-9]+)$/im', $this->mutedList, $matches);
        if(isset($matches[1][0]) && $this->hasData(trim($name))) {
            if(time() < $matches[1][0] || $matches[1][0] == 0) {
                return $matches[1][0];
            } else {
                return FALSE;
            }
        }
        else
            return FALSE;
    }

    /**
     * Checks if a user is banned, retrieves ban period if exists
     * 
     * @since 1.1
     * @param string $name
     * @return bool|int
     */
    private function getBanned($name) {
        preg_match_all('/^'.base64_encode($name).' ([0-9]+)$/im', $this->bannedList, $matches);
        if(isset($matches[1][0]) && $this->hasData(trim($name))) {
            if(time() < $matches[1][0] || $matches[1][0] == 0) {
                return $matches[1][0];
            } else {
                return FALSE;
            }
        }
        else
            return FALSE;
    }

    /**
     * Checks if a user is a moderator
     * 
     * @since 1.2
     * @param string $name
     * @return bool
     */    
    private function getMod($name) {
        preg_match_all('/^('.base64_encode($name).')$/im', $this->modList, $matches);
        if(isset($matches[1][0]) && $this->hasData(trim($name)))
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

        $id = 'lastread'.($sufix !== NULL ? '_'.$sufix : '_'.$this->mySession('current_room'));
        $enc =  $this->parseCookieName($id);
        switch($mode) {
            case 'store':
                $_SESSION[$id] = time();
                setcookie($enc, time(), time()+(86400*365), '/');
            break;
            case 'read':
                if($this->myCookie($enc) && !$this->mySession($id)) {
                    $_SESSION[$id] = $this->myCookie($enc);
                }
                return $this->mySession($id);
            break;
        }
    }

    /**
     * Updates user's last activity/status tags
     * 
     * @since 1.1
     * @param string $contents Raw User List
     * @param string $mode
     * @param string|null $value Only Applies To "update_status" Mode
     * @return string
     */
    private function updateUser($user_row, $mode, $value = NULL) {
    
        // If user row not provided, get row from users list
        $list_replace = FALSE;
        if($user_row === NULL) {
            $user_row = $original_row = $this->userMatch($this->name, NULL, 'RETURN_MATCH');
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
                break;
                case 'new_message':
                    $user_row[3] = time();
                break;
                case 'user_visit':
                    if($f == '0' || ($f != '0' && $this->hasProfileAccess)) {
                        $user_row[3] = time();
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
                    'MODE' => (($this->uPass && !$this->isCertifiedUser) ? $this->popTemplate('wcchat.join.inner.mode.login', '') : $this->popTemplate('wcchat.join.inner.mode.join', '')),
                    'CUSER_LINK' => $this->popTemplate(
                        'wcchat.join.cuser_link', 
                        array(
                            'RETURN_URL' => urlencode($_SERVER['REQUEST_URI'])
                        )
                    ),
                    'USER_NAME' => $this->name,
                    'RECOVER' => $this->popTemplate(
                        'wcchat.join.recover',
                        '',
                        $this->uEmail && $this->uPass && !$this->isCertifiedUser && $this->hasPermission('ACC_REC', 'skip_msg')
                    )
                )
            );
        } else {
            $err = '';
            if($this->mySession('login_err')) {
                $err = $this->mySession('login_err');
                unset($_SESSION['login_err']);
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

        $BBCODE = ((!$this->isLoggedIn && $this->myCookie('cname') && !$this->uPass) ? 
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
        $gsettings_par = array('TITLE', 'INCLUDE_DIR', 'REFRESH_DELAY', 'IDLE_START', 'OFFLINE_PING', 'CHAT_DSP_BUFFER', 'CHAT_STORE_BUFFER', 'CHAT_OLDER_MSG_STEP', 'ARCHIVE_MSG', 'ANTI_SPAM', 'IMAGE_MAX_DSP_DIM',  'IMAGE_AUTO_RESIZE_UNKN', 'VIDEO_WIDTH', 'VIDEO_HEIGHT', 'AVATAR_SIZE', 'DEFAULT_AVATAR', 'DEFAULT_ROOM', 'DEFAULT_THEME', 'INVITE_LINK_CODE', 'ACC_REC_EMAIL', 'ATTACHMENT_TYPES', 'ATTACHMENT_MAX_FSIZE', 'ATTACHMENT_MAX_POST_N');

        $gsettings_par_v = array();

        foreach($gsettings_par as $key => $value) {
            $gsettings_par_v['GS_'.$value] = constant($value);
        }
        $gsettings_par_v['GS_LOAD_EX_MSG'] = (LOAD_EX_MSG === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_LIST_GUESTS'] = (LIST_GUESTS === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_ARCHIVE_MSG'] = (ARCHIVE_MSG === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_GEN_REM_THUMB'] = (GEN_REM_THUMB === TRUE ? ' CHECKED' : '');
        $gsettings_par_v['GS_ATTACHMENT_UPLOADS'] = (ATTACHMENT_UPLOADS === TRUE ? ' CHECKED' : '');

        $gsettings_perm = array('GSETTINGS', 'ROOM_C', 'ROOM_E', 'ROOM_D', 'MOD', 'UNMOD', 'USER_E', 'TOPIC_E', 'BAN', 'UNBAN', 'MUTE', 'UNMUTE', 'MSG_HIDE', 'MSG_UNHIDE', 'POST', 'PROFILE_E', 'IGNORE', 'PM_SEND', 'LOGIN', 'ACC_REC', 'READ_MSG', 'ROOM_LIST', 'USER_LIST', 'ATTACH_UPL', 'ATTACH_DOWN');

        $gsettings_perm2 = array('MMOD', 'MOD', 'CUSER', 'USER', 'GUEST');

        foreach($gsettings_perm as $key => $value) {
            $perm_data = constant('PERM_'.$value);
            $perm_fields = explode(' ', $perm_data);
            foreach($gsettings_perm2 as $key2 => $value2) {
                $gsettings_par_v['GS_'.$value2.'_'.$value] = (in_array($value2, $perm_fields) ? ' CHECKED' : '');
            }
        }

        $contents = $this->popTemplate(
            'wcchat',
            array(
                'TITLE' => TITLE,
                'TOPIC' => $this->popTemplate('wcchat.topic', array('TOPIC' => $this->parseTopicContainer())),
                'STATIC_MSG' => (!$this->isLoggedIn ? $this->popTemplate('wcchat.static_msg') : ''),
                'POSTS' => $this->popTemplate('wcchat.posts'),
                'GSETTINGS' => ($this->hasPermission('GSETTINGS', 'skip_msg') ? 
                    $this->popTemplate(
                        'wcchat.global_settings',
                        array_merge(
                            array(
                                'URL' => ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
                                'ACC_REC_EM_DEFAULT' => 'no-reply@' . $_SERVER['SERVER_NAME']
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
                        'USER_NAME' => $this->name ? str_replace("'", "\'", $this->name) : 'Guest',
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
                                    $this->hasPermission('ROOM_C', 'skip_msg') || $this->hasPermission('ROOM_E', 'skip_msg') || $this->hasPermission('ROOM_D', 'skip_msg') || $this->hasPermission('MOD', 'skip_msg') || $this->hasPermission('UNMOD', 'skip_msg') || $this->hasPermission('USER_E', 'skip_msg') || $this->hasPermission('TOPIC_E', 'skip_msg') || $this->hasPermission('BAN', 'skip_msg') || $this->hasPermission('UNBAN', 'skip_msg') || $this->hasPermission('MUTE', 'skip_msg') || $this->hasPermission('UNMUTE', 'skip_msg') || $this->hasPermission('MSG_HIDE', 'skip_msg') || $this->hasPermission('MSG_UNHIDE', 'skip_msg')  
                                )
                            )
                        ),
                        'ICON_STATE' => ($this->hasProfileAccess ? '' : 'closed'),
                        'JOINED_STATUS' => $this->popTemplate('wcchat.toolbar.joined_status', array('MODE' => 'on'))
                    )
                ),
                'TEXT_INPUT' => $this->popTemplate('wcchat.text_input', array('CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER)),
                'SETTINGS' => $this->popTemplate(
                    'wcchat.settings',
                    array(
                        'AV_RESET' => ($this->uAvatar ? '' : 'closed'),
                        'TIMEZONE_OPTIONS' => str_replace(
                            'value="'.$this->uTimezone.'"', 
                            'value="'.$this->uTimezone.'" SELECTED', 
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
                'ROOM_LIST' => $this->popTemplate('wcchat.rooms', array('RLIST' => $this->parseRooms())),
                'USER_LIST' => $this->popTemplate('wcchat.users', array('ULIST' => $this->parseUsers('VISIT'))),
                'THEMES' => $this->parseThemes()
            )
        );

        $onload = ($this->isLoggedIn ? $this->popTemplate('wcchat.toolbar.onload', array('CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER, 'EDIT_BT_STATUS' => intval($this->myCookie('hide_edit')))) : $this->popTemplate('wcchat.toolbar.onload_once', array('CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER)));

        // Set a ban tag
        $tag = '';
        if($this->isBanned !== FALSE) {
            $tag = ((intval($this->isBanned) == 0) ? ' (Permanently)' : ' ('.$this->parseIdle($this->isBanned, 1).' remaining)');
        }

        // Output the index template
        return $this->popTemplate(
            ($embedded === NULL ? 'index' : 'index_embedded'),
            array(
                'TITLE' => TITLE,
                'CONTENTS' => (
			($this->isBanned !== FALSE) ? 
				$this->popTemplate('wcchat.critical_error', array('ERROR' => 'You are banned!'.$tag)) : 
				($this->stopMsg ? 
					$this->popTemplate('wcchat.critical_error', array('ERROR' => $this->stopMsg)) : 
					$contents
				)
			),
                'ONLOAD' => (($this->isBanned !== FALSE || $this->stopMsg) ? '' : $onload),
                'REFRESH_DELAY' => REFRESH_DELAY,
                'STYLE_LASTMOD' => filemtime(__DIR__.'/themes/'.THEME.'/style.css'),
                'SCRIPT_LASTMOD' => filemtime(__DIR__.'/script.js'),
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
    private function popTemplate($model, $data = NULL, $cond = NULL, $no_cond_content = NULL)
    {
        $out = '';
        if (!isset($cond) || (isset($cond) && $cond == TRUE))
        {
            // Import template model
            $out = $this->templates[$model];

            // Replace tokens
            if (isset($data)) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        $out = str_replace("{" . $key . "}", stripslashes($data[$key]), $out);
                    }
                }
            }
            $out = trim($out, "\n\r");

            return str_replace(
                array(
                    '{CALLER}',
                    '{INCLUDE_DIR}',
                    '{INCLUDE_DIR_THEME}'
                ),
                array(
                    $this->ajaxCaller,
                    $this->includeDir,
                    INCLUDE_DIR_THEME
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
     * @param int|null $visit Specifies a new user visit (To update user status)
     * @return string Html Template
     */
    private function parseUsers($visit = NULL) {

        // Store user ping
        $this->setPing();

        if($this->isBanned !== FALSE) { return 'You are banned!'; }
        if(!$this->hasPermission('USER_LIST', 'skip_msg')) { return 'Can\'t display users.'; }
 
        // Deprecated condition, try find a way to tell a user has logged out without the need to scan all user pings every time
        if($this->myGET('ilmod') != 'ignore_lastmod') {
            $online_lastmod = filemtime(USERL);
            // if((time()-$online_lastmod) >= IDLE_START) { return; }
        }
        $_on = $_off = $_lurker = array();
        $uid = $this->name;
        $autocomplete = '';

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
            if($this->isLoggedIn && (LIST_GUESTS === TRUE || (LIST_GUESTS === FALSE && $this->uData[6] != '0'))) {
                if($this->userMatch($this->name) === FALSE) {
                    if($this->name != 'Guest') { $contents .= "\n".base64_encode($this->name).'|'.$this->userDataString.'|0|'.time().'|0'; }
                } else {
                    $user_row = $this->updateUser($user_row, 'user_visit');
                }
                $changes = TRUE;
            }
        }

        // Creates New User/Update user last activity on join, also update status 
        if($this->myGet('join') == '1') {
            if($this->userMatch($uid) === FALSE) {
                if($this->name != 'Guest') { $contents .= "\n".base64_encode($uid).'|'.$this->userDataString.'|'.time().'|'.time().'|1'; }
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

        // Scan users raw file
        if(trim($contents)) {
            $lines = explode("\n", trim($contents));
            foreach($lines as $k => $v) {
                if(trim($v)) {
                    list($usr, $udata, $f, $l, $s) = explode('|', trim($v));
                    $usr = ($usr ? base64_decode($usr) : '');

                    // Store the name autocomplete list (to be used in input)
                    if($visit != NULL) {
                        $autocomplete .= $usr.',';
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
                        list($tmp1, $tmp2, $tmp3, $tmp4, $tmp5, $tmp6) = explode('|', base64_decode($udata));
                        $av = ($tmp1 ? $this->includeDir . 'files/avatars/' . base64_decode($tmp1) : INCLUDE_DIR_THEME.DEFAULT_AVATAR);
                        $ulink = ($tmp3 ? (preg_match('#^(http|ftp)#i', base64_decode($tmp3)) ? base64_decode($tmp3) : 'http://'.base64_decode($tmp3)) : '');
                    } else {
                        $ulink = '';
                        $av = (DEFAULT_AVATAR ? INCLUDE_DIR_THEME.DEFAULT_AVATAR : '');
                    }

                    // Sets a style for banned users
                    $name_style = '';
                    $isbanned = $this->getBanned($usr);
                    if($isbanned !== FALSE) {
                        $ulink = ''; $av = (DEFAULT_AVATAR ? INCLUDE_DIR_THEME.DEFAULT_AVATAR : '');
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

                    // Parses permissions
                    $edit_perm = FALSE;
                    if($this->hasPermission('USER_E', 'skip_msg')) {
                        $edit_perm = TRUE;
                    }

                    $mod_perm = FALSE;
                    if($this->hasPermission('MOD', 'skip_msg') || $this->hasPermission('UNMOD', 'skip_msg') || $this->hasPermission('BAN', 'skip_msg') || $this->hasPermission('UNBAN', 'skip_msg') || $this->hasPermission('MUTE', 'skip_msg') || $this->hasPermission('UNMUTE', 'skip_msg')) {
                        $mod_perm = TRUE;
                    }

                    // Is user a guest?
                    if($f != '0') {
                        // No guest, is online?
                        if((time()-$last_ping) < OFFLINE_PING) {
                            // Yes, it's an online user 

                            // Style for current user
                            if(strpos($v, base64_encode($uid).'|') === FALSE) { $boldi = $bolde = ''; } else { $boldi = '<b>'; $bolde = '</b>'; }

                            // Parse Joined Status class
                            $joined_status_class = 'joined_on';
                    
                            if(((time()-$l) >= IDLE_START)) { $joined_status_class = 'joined_idle'; }
                            if($s == 2) { $joined_status_class = 'joined_na'; }        

                            $_on[$usr] = $this->popTemplate(
                                'wcchat.users.item',
                                array(
                                    'ID' => base64_encode($usr),
                                    'WIDTH' => $this->popTemplate('wcchat.users.item.width', array('WIDTH' => $this->uAvatarW)),
                                    'AVATAR' => $av,
                                    'NAME_STYLE' => $name_style,
                                    'NAME' => $boldi.$usr.$bolde,
                                    'MOD_ICON' => $mod_icon,
                                    'MUTED_ICON' =>$muted_icon,
                                    'LINK' => $this->popTemplate('wcchat.users.item.link', array('LINK' => $ulink), $ulink),
                                    'IDLE' => $this->popTemplate('wcchat.users.item.idle_var', array('IDLE' => $this->parseIdle($l)), ((time()-$l) >= IDLE_START && $s != 2)),
                                    'JOINED_CLASS' => (intval($s) ? $joined_status_class : ''),
                                    'STATUS_TITLE' => $status_title,
                                    'EDIT_BT' => $this->popTemplate('wcchat.users.item.edit_bt', array('ID' => base64_encode($usr), 'OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')), ($edit_perm || $mod_perm)),
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
                                                $this->hasPermission('MOD', 'skip_msg') || $this->hasPermission('UNMOD', 'skip_msg')
                                            ),
                                            'MUTED' => $this->popTemplate(
                                                'wcchat.users.item.edit_form.muted',
                                                array(
                                                    'MUTED_CHECKED' => ($ismuted !== FALSE ? 'CHECKED' : ''),
                                                    'MUTED_TIME' => (($ismuted !== FALSE && $ismuted != 0) ? intval(abs((time()-$ismuted)/60)) : ''),
                                                    'ID' => base64_encode($usr)
                                                ),
                                                $this->hasPermission('MUTE', 'skip_msg') || $this->hasPermission('UNMUTE', 'skip_msg')
                                            ),
                                            'BANNED' => $this->popTemplate(
                                                'wcchat.users.item.edit_form.banned',
                                                array(
                                                    'BANNED_CHECKED' => ($isbanned !== FALSE ? 'CHECKED' : ''),
                                                    'BANNED_TIME' => (($isbanned !== FALSE && $isbanned != 0) ? intval(abs((time()-$isbanned)/60)) : ''),
                                                    'ID' => base64_encode($usr)
                                                ),
                                                $this->hasPermission('BAN', 'skip_msg') || $this->hasPermission('UNBAN', 'skip_msg')
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
                                            'NAME' => $usr
                                        ),
                                        $edit_perm || $mod_perm
                                    )
                                )
                            );

                        } else {
                            // Not online or guest, treat it as offline
                            $_off[$usr] = $this->popTemplate(
                                'wcchat.users.item',
                                array(
                                    'ID' => base64_encode($usr),
                                    'WIDTH' => $this->popTemplate('wcchat.users.item.width', array('WIDTH' => $this->uAvatarW)),
                                    'AVATAR' => $av,
                                    'NAME_STYLE' => $name_style,
                                    'NAME' => $usr,
                                    'MOD_ICON' => $mod_icon,
                                    'MUTED_ICON' =>$muted_icon,
                                    'LINK' => $this->popTemplate('wcchat.users.item.link', array('LINK' => $ulink), $ulink),
                                    'IDLE' => $this->popTemplate('wcchat.users.item.idle_var', array('IDLE' => $this->parseIdle($l))),
                                    'JOINED_CLASS' => '',
                                    'STATUS_TITLE' => '',
                                    'EDIT_BT' => $this->popTemplate('wcchat.users.item.edit_bt', array('ID' => base64_encode($usr), 'OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')), ($edit_perm || $mod_perm)),
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
                                                $this->hasPermission('MOD', 'skip_msg') || $this->hasPermission('UNMOD', 'skip_msg')
                                            ),
                                            'MUTED' => $this->popTemplate(
                                                'wcchat.users.item.edit_form.muted',
                                                array(
                                                    'MUTED_CHECKED' => ($ismuted !== FALSE ? 'CHECKED' : ''),
                                                    'MUTED_TIME' => (($ismuted !== FALSE && $ismuted != 0) ? intval(abs((time()-$ismuted)/60)) : ''),
                                                    'ID' => base64_encode($usr)
                                                ),
                                                $this->hasPermission('MUTE', 'skip_msg') || $this->hasPermission('UNMUTE', 'skip_msg')
                                            ),
                                            'BANNED' => $this->popTemplate(
                                                'wcchat.users.item.edit_form.banned',
                                                array(
                                                    'BANNED_CHECKED' => ($isbanned !== FALSE ? 'CHECKED' : ''),
                                                    'BANNED_TIME' => (($isbanned !== FALSE && $isbanned != 0) ? intval(abs((time()-$isbanned)/60)) : ''),
                                                    'ID' => base64_encode($usr)
                                                ),
                                                $this->hasPermission('BAN', 'skip_msg') || $this->hasPermission('UNBAN', 'skip_msg')
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
                                            'NAME' => $usr
                                        ),
                                        $edit_perm || $mod_perm
                                    )
                                )
                            );
                        }
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
        foreach($_on as $k => $v) $on .= $v;
        foreach($_off as $k => $v) $off .= $v;
        foreach($_lurker as $k => $v) $lurker .= $v;

        // Store autocomplete list in SESSION
        if($autocomplete) { $_SESSION['autocomplete'] = ','.$autocomplete; }

        return 
            $this->popTemplate(
                'wcchat.users.inner',
                array(
                    'JOINED' => (
                        $on ? 
                        $this->popTemplate('wcchat.users.joined', array('USERS' => $on)) : 
                        $this->popTemplate('wcchat.users.joined.void')
                    ),
                    'OFFLINE' => $this->popTemplate('wcchat.users.offline', array('USERS' => $off), $off),
                    'GUESTS' => $this->popTemplate('wcchat.users.guests', array('USERS' => $lurker), $lurker),
                )
            );
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
            $room_name = base64_decode(str_replace(array($this->roomDir, '.txt'), '', $file));
            
            // Skip any special room related files (definitions, topic, hidden msg)
            if(strpos($file, 'def_') === FALSE && strpos($file, 'topic_') === FALSE && strpos($file, 'hidden_') === FALSE) {

                // Parse the edit form if user has permission
                $edit_form = $edit_icon = '';
                if($this->hasPermission('ROOM_E', 'skip_msg')) {
                    list($perm,$t1,$t2,$t3,$t4) = explode('|', $this->readFile($this->roomDir . 'def_'.base64_encode($room_name).'.txt'));
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
                                'DELETE_BT' => $this->popTemplate('wcchat.rooms.edit_form.delete_bt', array('ID' => $enc), $room_name != DEFAULT_ROOM)                        
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
                        $lastmod = filemtime($this->roomDir . base64_encode($room_name).'.txt');
                        $rooms .= 
                            $this->popTemplate(
                                'wcchat.rooms.room',
                                array(
                                    'TITLE' => $room_name,
                                    'EDIT_BT' => $edit_icon,
                                    'FORM' => $edit_form,
                                    'NEW_MSG' => $this->popTemplate('wcchat.rooms.new_msg.'.(($lastread < $lastmod) ? 'on' : 'off'))
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
                $this->popTemplate('wcchat.rooms.create', array('OFF' => $this->myCookie('hide_edit') == 1 ? '_off' : ''));
        }

        // Replace list with message if no permission to read the list
        if(!$this->hasPermission('ROOM_LIST', 'skip_msg')) { $rooms = 'Can\'t display rooms.'; }

        return $this->popTemplate('wcchat.rooms.inner', array('ROOMS' => $rooms, 'CREATE' => $create_room));
    }

    /**
     * Generates the Smiley interface
     * 
     * @since 1.1
     * @param string $field Target Html Id
     * @return string Html Template
     */
    private function iSmiley($field)
    {
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
        foreach($s1 as $key => $value)
            $out .=
                $this->popTemplate('wcchat.toolbar.smiley.item',
                    array(
                        'title' => $value,
                        'field' => $field,
                        'str_pat' => $value,
                        'str_rep' => 'sm'.$key.'.gif'
                    )
                );

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
        foreach(glob($this->includeDir . 'themes/*') as $file) {
            $title = str_replace($this->includeDir . 'themes/', '', $file);
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
        return $this->popTemplate('wcchat.themes', array('OPTIONS' => $options));
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
                    'TOPIC_FULL' => nl2br($this->parseBbcode(str_replace('[**]', '', $this->topic)))
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
        $output = $new = $first_elem = '';
        $previous = $prev_unique_id = $skip_new = 0;
        $old_start = FALSE;        
        $today_date = gmdate('d-M', time()+($this->uTimezone * 3600));
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
 
        // Scan the available post lines and populates the respective templates

        // Invert order in order to scan the newest first    
        krsort($lines);
        foreach($lines as $k => $v) {
            list($time, $user, $msg) = explode('|', trim($v), 3);
            $pm_target = FALSE;
            // Check if user contains a target parameter (Private Message)
            if(strpos($user, '-') !== FALSE) { list($pm_target, $nuser) = explode('-', $user); $user = $nuser; }

            $self = FALSE; $hidden = FALSE;

            if(strpos($time, '*') !== FALSE) { $time = str_replace('*', '', $time); $hidden = TRUE; }

            if(preg_match('/^\*/', $user)) { $self = TRUE; $user = trim($user, '*'); }
            $time_date = gmdate('d-M', $time+($this->uTimezone * 3600));

            // Halt scan if no batch retrieval and current message is no longer new
            if($this->myGet('all') != 'ALL' && $time <= $lastread && $older_index === NULL) { break; }
            
            // Generate an unique id for the post message
            $unique_id = (!$self ? ($time.'|'.$user) : ($time.'|*'.$user));
            
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
            
                // Sets a breakpoint for the "new messages" tag
                $new = '';
                if($this->myGet('all') == 'ALL' && !$skip_new) {
                    if($previous) {
                        if($time <= $lastread && $previous > $lastread) {
                            $new = $this->popTemplate('wcchat.posts.new_msg_separator');
                            $skip_new = 1;
                        } else { $previous = $time; }
                    } else {
                        $previous = $time;
                    }
                }
     
                // Halt scan if the custom/global start point is reached
                if(
                    (
                        $time < $this->myCookie('start_point_'.$this->parseCookieName($this->mySession('current_room'))) && 
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
                        $this->myCookie('start_point_'.$this->parseCookieName($this->mySession('current_room'))) : 
                        $this->mySession('global_start_point')
                    );
				    $time_date2 = gmdate('d-M', $start_point+($this->uTimezone * 3600));
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
                                    ($time_date2 != $today_date ? ' '.$time_date2 : ''),
                                'USER' => $this->name,
                                'MSG' => (
                                    LOAD_EX_MSG !== FALSE ? 
                                    $this->popTemplate('wcchat.posts.undo_clear_screen') : 
                                    $this->popTemplate('wcchat.posts.global_clear_screen')
                                ),
                                'ID' => $unique_id,
                                'HIDE_ICON' => ''
                            )
                        ).$new.$output;
                    break;
                }

                // Skip current line if the post belongs to the current user and the user is not sending a message nor performing a batch retrieval (Older posts, new room visit)
                if($this->myGet('all') != 'ALL' && $older_index === NULL && base64_decode($user) == $this->name && $action != 'SEND') {
                    $index++; continue;
                }

                // Process the post if not part of an ignore
                if(!$this->myCookie('ign_'.$user)) {
                    if(!$self) {
                        if($pm_target === FALSE || ((base64_decode($pm_target) == $this->name || base64_decode($user) == $this->name) && $this->hasProfileAccess && $pm_target !== FALSE)) {
                            $output = $this->popTemplate(
                                'wcchat.posts.normal',
                                array(
                                    'PM_SUFIX' => ($pm_target !== FALSE ? '_pm' : ''),
                                    'PM_TAG' => $this->popTemplate('wcchat.posts.normal.pm_tag', '', $pm_target !== FALSE),
                                    'STYLE' => ($this->myCookie('hide_time') ? 'display:none' : 'display:inline'),
                                    'TIMESTAMP' => gmdate((($this->uHourFormat == '1') ? 'H:i': 'g:i a'), $time + ($this->uTimezone * 3600)).($time_date != $today_date ? ' '.$time_date : ''),
                                    'POPULATE_START' => $this->popTemplate('wcchat.posts.normal.populate_start', array('USER' => addslashes(base64_decode($user))), base64_decode($user) != $this->name),
                                    'USER' => base64_decode($user),
                                    'POPULATE_END' => $this->popTemplate('wcchat.posts.normal.populate_end', '', base64_decode($user) != $this->name),
                                    'PM_TARGET' => (
                                            (base64_decode($pm_target) != $this->name) ?
                                            $this->popTemplate('wcchat.posts.normal.pm_target', array('TITLE' => base64_decode($pm_target)), $pm_target !== FALSE) :
                                            $this->popTemplate('wcchat.posts.normal.pm_target.self', array('TITLE' => base64_decode($pm_target)), $pm_target !== FALSE)
                                        ),
                                    'MSG' => $this->popTemplate('wcchat.posts.hidden', '', $hidden, $this->parseBbcode($msg)),
                                    'ID' => $unique_id,
                                    'HIDE_ICON' => $this->popTemplate('wcchat.posts.hide_icon', array('REVERSE' => ($hidden ? '_r' : ''), 'ID' => $unique_id, 'OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')), $this->hasPermission(($hidden ? 'MSG_UNHIDE' : 'MSG_HIDE'), 'skip_msg')),
                                    'AVATAR' => (isset($avatar_array[base64_decode($user)]) ? ($this->hasData($avatar_array[base64_decode($user)]) ? $this->includeDir . 'files/avatars/'.$avatar_array[base64_decode($user)] : INCLUDE_DIR_THEME . DEFAULT_AVATAR ) : INCLUDE_DIR_THEME . DEFAULT_AVATAR ),
                                    'WIDTH' => $this->popTemplate('wcchat.posts.normal.width', array('WIDTH' => $this->uAvatarW))
                                )
                            ).$new.$output;
                            $index++;
                            if($this->myGet('all') == 'ALL') { $first_elem = $unique_id; }
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
                                'MSG' => $this->popTemplate('wcchat.posts.hidden', '', $hidden, $this->parseBbcode($msg)),
                                'ID' => $unique_id,
                                'HIDE_ICON' => $this->popTemplate('wcchat.posts.hide_icon', array('REVERSE' => ($hidden ? '_r' : ''), 'ID' => $unique_id, 'OFF' => ($this->myCookie('hide_edit') == 1 ? '_off' : '')), $this->hasPermission(($hidden ? 'MSG_UNHIDE' : 'MSG_HIDE'), 'skip_msg'))
                            )
                        ).$new.$output;
                        $index++;
                        if($this->myGet('all') == 'ALL') { $first_elem = $unique_id; }
                    }
                }
            }
            
            // Halt if next index increment does not obey display buffer/older msg batch limits
            if(($index >= CHAT_DSP_BUFFER && $older_index === NULL) || ($older_index !== NULL && $index >= CHAT_OLDER_MSG_STEP)) { break; }
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
        $today_date = gmdate('d-M', time()+($this->uTimezone * 3600));

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
                $time_date = gmdate('d-M', $time+($this->uTimezone * 3600));
                
                // Halt if: event is not new or user doesn't have a read point and not sending or user already has the event listed
                if($time <= $lastread || (!$lastread && $action != 'SEND') || (time()-$time) > (intval(REFRESH_DELAY/1000)*2)) { break; }

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
                    ).$output;
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
        $down_alert = ($this->hasPermission('ATTACH_DOWN', 'skip_msg') ? '' : 'onclick="alert(\'Your usergroup does not have permission to download arquives / full size images!\'); return false;"');

        $replace =
            array(
                  '<b>\\1</b>',
                  '<i>\\1</i>',
                  '<u>\\1</u>',
                  '<div style="margin: 10px"><img src="\\1" class="thumb" onload="wc_doscroll()"></div>',

                  '<div style="width: \\3px; text-align: center; font-size: 10px; margin: 10px"><img src="\\5" style="width: \\3px; height: \\4px;" class="thumb" onload="wc_doscroll()"><br><img src="'.INCLUDE_DIR_THEME.'images/attach.png"><a href="'.($down_perm ? $this->includeDir . 'files/attachments/\\5' : '#').'" target="_blank" '.$down_alert.'>\\1 x \\2</a></div>',
                '<div style="width: \\3px; text-align: center; font-size: 10px; margin: 10px"><img src="'.$this->includeDir.'files/thumb/tn_\\5.jpg" class="thumb" onload="wc_doscroll()"><br><img src="'.INCLUDE_DIR_THEME.'images/attach.png"><a href="'.($down_perm ? $this->includeDir . 'files/attachments/\\6' : '#').'" target="_blank" '.$down_alert.'>\\1 x \\2</a></div>',

                  '<div style="width: \\3px; text-align: center; font-size: 10px; margin: 10px"><img src="\\5" style="width: \\3px; height: \\4px;" class="thumb" onload="wc_doscroll()"><br><a href="'.($down_perm ? '\\5' : '#').'" target="_blank" '.$down_alert.'>\\1 x \\2</a></div>',
                '<div style="width: \\3px; text-align: center; font-size: 10px; margin: 10px"><img src="'.$this->includeDir . 'files/thumb/tn_\\5.jpg" class="thumb" onload="wc_doscroll()"><br><a href="'.($down_perm ? '\\6' : '#').'" target="_blank" '.$down_alert.'>\\1 x \\2</a></div>',
                '<div style="width: \\1px; text-align: center; font-size: 10px; margin: 10px"><img src="\\2" style="width: \\1px;" class="thumb" onload="wc_doscroll()"><br><a href="'.($down_perm ? '\\2' : '#').'" target="_blank" '.$down_alert.'>Unknown Dimensions</a></div>',
                  '<a href="\\1" target="_blank">\\2</a>',
                '<div style="margin:10px;"><i><img src="'.INCLUDE_DIR_THEME.'images/attach.png"> <a href="'.($down_perm ? $this->includeDir . 'files/attachments/\\1_\\2_\\4' : '#').'" target="_blank" '.$down_alert.'>\\4</a> <span style="font-size: 10px">(\\3KB)</span></i></div>',
                '<div id="im_\\1"><a href="#" onclick="wc_pop_vid(\'\\1\', '.VIDEO_WIDTH.', '.VIDEO_HEIGHT.'); return false;"><img src="'.INCLUDE_DIR_THEME.'images/video_cover.jpg" class="thumb" style="margin: 10px" onload="wc_doscroll()"></a></div><div id="wc_video_\\1" class="closed"></div>',
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


        foreach($smilies as $key => $value)
            $output =
                str_replace(
                    $value,
                    $this->popTemplate('wcchat.toolbar.smiley.item.parsed',
                    array(
                        'key' => $key
                    )
                ),
                $output
            );

        return $output;
    }

    /**
     * Parses the difference of two timestamps into a user-friendly string
     * 
     * @since 1.1
     * @param string $date
     * @param string|null $mode
     * @return string
     */
    private function parseIdle($date, $mode = NULL)
    {
        if($mode == NULL)
            $it = $timesec = time()-$date;
        else
            $it = $timesec = $date-time();

        $str = '';
        $ys = 60*60*24*365;

        $year = intval($timesec/$ys);
        if($year >= 1)
            $timesec = $timesec%$ys;

        $month = intval($timesec/2628000);
        if($month >= 1)
            $timesec = $timesec%2628000;
    
        $days = intval($timesec/86400);
        if($days >= 1)
            $timesec = $timesec%86400;

        $hours = intval($timesec/3600);
        if($hours >= 1)
            $timesec = $timesec%3600;

        $minutes = intval($timesec/60);
        if($minutes >= 1)
            $timesec = $timesec%60;

        $par_count = 0;

        if($year > 0) {
            $str = $year.'Y';
            $par_count++;
        }
        if($month > 0) {
            $str .= " ".$month.'M';
            $par_count++;
        }
        if($days > 0 && $par_count < 2) {
            $str .= " ".$days.'d';
            $par_count++;
        }
        if($hours > 0 && $par_count < 2) {
            $str .= " ".$hours.'h';
            $par_count++;
        }
        if($minutes > 0 && $par_count < 2) {
            $str .= " ".$minutes.'m';
            $par_count++;
        }
        if($it < 60)
            $str = $it."s";

        return(trim($str));
    }
    
    /**
     * Parses the cookie name
     * 
     * @since 1.4
     * @param string $name
     * @return string
     */
    private function parseCookieName($name)
    {
        return str_replace('=', '_', base64_encode($name));
    }

      /*===========================================
       |       AJAX COMPONENT METHODS             |
       ============================================
       |  checkTopicChanges                       |
       |  refreshRooms                            |
       |  checkHiddenMsg                          |
       |  updateMsgOnceE                          |
       ===========================================*/

    /**
     * Checks if Topic has Changed, if yes, returns the populated html template (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */
    private function checkTopicChanges() {
        $lastmod_t = filemtime(TOPICL);
        $lastread_t = $this->handleLastRead('read', 'topic_'.$this->mySession('current_room'));

        // If changed or reload request exists, return the parsed html template
        if($lastmod_t > $lastread_t || $this->myGet('reload') == '1') {
            $this->handleLastRead('store', 'topic_'.$this->mySession('current_room'));
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
        $lastmod = filemtime(ROOMS_LASTMOD);

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
    private function updateMsgOnceE() {
        if(!$this->hasPermission('READ_MSG', 'skip_msg')) { return 'Can\'t display messages.'; }
        $output_e = '';
        $lastmod = filemtime(EVENTL);
        
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
        if($output_e) return $output_e;
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
    private function ajax($mode) {

        switch($mode) {
            case 'reset_archive':
                unset($_SESSION['archive']);
            break;
            // Auto-completes a user name
            case 'name_autocomplete':
                $hint = $this->myGet('hint');
                if($this->hasData($hint)) {
                    $words = explode(' ', trim($hint));
                    $current_word = $words[count($words)-1];
                    $users = $this->mySession('autocomplete');
                    if(strpos(strtolower($users), ','.strtolower($current_word)) !== FALSE) {
                        list($tmp, $tmp2) = explode(','.strtolower($current_word), strtolower($users), 2);
                        list($rem_name, $tmp3) = explode(',', $tmp2);
                        echo $rem_name;   
                    }
                }
            break;
            // Processes User Edition
            case 'upd_user':
                $output = '';
                $oname = $this->myPost('oname');
                
                // Halt if target user is invalid (happens if the user was renamed)
                if($this->userMatch($oname) === FALSE) { echo 'Invalid Target User (If you just renamed the user, close the form and retry after the name update)!'; die(); } 
                $udata = $this->userData($oname);
                
                // Process moderator request if target user is not a moderator already
                if($this->myPost('moderator') && $this->getMod($oname) === FALSE) {
                    if($this->hasPermission('MOD', 'skip_msg')) {
                    
                        // User must have a password in order to be assigned as moderator
                        if($udata[5]) {
                            $this->writeFile(MODL, "\n".base64_encode($oname), 'a');
                            $output .= '- Successfully set '.$oname.' as moderator.'."\n";
                        } else {
                            $output .= '- Cannot set '.$oname.' as moderator: Not using a password.MOD_OFF'."\n";
                        }
                    }
                }
                
                // Process un-moderator request if target user is a moderator
                if(!$this->myPost('moderator') && $this->getMod($oname) !== FALSE) {
                    if($this->hasPermission('UNMOD', 'skip_msg')) {
                        if($oname && $this->getMod($par[1]) !== FALSE) {
                            $this->writeFile(MODL, str_replace("\n".base64_encode($oname), '', $this->modList), 'w', 'allow_empty');
                            $output .= '- Successfully removed '.$oname.' moderator status.'."\n";
                        } else {
                            $output .= '- '.$oname.': invalid moderator!'."\n";
                        }
                    }
                }
                
                // Process mute request if target user is not muted already
                if($this->myPost('muted') && $this->getMuted($oname) === FALSE) {
                    if($this->hasPermission('MUTE', 'skip_msg')) {
                        $xpar = '';
                        
                        // Parse requested mute time if any
                        if($this->myPost('muted_time')) {
                            if(ctype_digit($this->myPost('muted_time'))) {
                                $xpar = ' '.(time()+(60*abs(intval($this->myPost('muted_time')))));
                            }
                        } else { $xpar = ' 0'; }
                        $this->writeFile(MUTEDL, preg_replace('/'."\n".base64_encode($oname).' ([0-9]+)/', '', $this->mutedList)."\n".base64_encode($oname).$xpar, 'w');
                        $output .= '- Successfully muted '.$oname.($this->myPost('muted_time') ? ' for '.abs(intval($this->myPost('muted_time'))).' minute(s)' : '').'.'."\n";
                    }
                }
                
                // Process unmute request if target user is muted
                if(!$this->myPost('muted') && $this->getMuted($oname) !== FALSE) {
                    if($this->hasPermission('UNMUTE', 'skip_msg')) {
                        $this->writeFile(MUTEDL, preg_replace('/'."\n".base64_encode(trim($oname)).' ([0-9]+)/', '', $this->mutedList), 'w', 'allow_empty');

                        $output .= '- Successfully unmuted '.$oname."\n";
                    }
                }
              
                // Process ban request if target user is not banned already
                if($this->myPost('banned') && $this->getBanned($oname) === FALSE) {
                    if($this->hasPermission('BAN', 'skip_msg')) {
                        
                        // Parse requested ban time if any
                        $xpar = '';
                        if($this->myPost('banned_time')) {
                                    if(ctype_digit($this->myPost('banned_time'))) {
                                   $xpar = ' '.(time()+(60*abs(intval($this->myPost('banned_time')))));
                                    }
                        } else { $xpar = ' 0'; }
                        $this->writeFile(BANNEDL, preg_replace('/'."\n".base64_encode($oname).' ([0-9]+)/', '', $this->bannedList)."\n".base64_encode($oname).$xpar, 'w');
                        $output .= '- Successfully banned '.$oname.($this->myPost('banned_time') ? ' for '.abs(intval($this->myPost('banned_time'))).' minute(s)' : '').'.'."\n";
                    }
                }
                
                // Process unban request if target user is banned
                if(!$this->myPost('banned') && $this->getBanned($oname) !== FALSE) {
                    if($this->hasPermission('UNBAN', 'skip_msg')) {
                        $this->writeFile(BANNEDL, preg_replace('/'."\n".base64_encode($oname).' ([0-9]+)/', '', $this->bannedList), 'w', 'allow_empty');
                        $output .= '- Successfully unbanned '.$oname."\n";
                    }
                }

                $changes = 0; $name_err = FALSE;
                if($this->hasPermission('USER_E', 'skip_msg')) {
                    if($this->myPost('name') != $oname) {
                    
                        // Check if new name already exists
                        if($this->userMatch($this->myPost('name')) !== FALSE) {
                            $output .= '- Cannot rename: '.$this->myPost('name').' already exists!';
                            $name_err = TRUE;
                        // Check if name is valid
                        } elseif(strlen(trim($this->myPost('name'), ' ')) == 0 || strlen(trim($this->myPost('name'))) > 30 || preg_match("/[\?<>\$\{\}\"\: ]/i", $this->myPost('name'))) {
                            $output .= '- Invalid Nickname, too long (max = 30) OR containing invalid characters: ? < > $ { } " : space'."\n";
                            $name_err = TRUE;
                        // all ok, name will be renamed when the file is written
                        } else {
                            $changes = 1;
                            $output .= '- Name Renamed Successfully!'."\n";
                        }
                    }
                    
                    // Process web address request
                    if($this->myPost('web') != $udata[2]) {
                        if(filter_var($this->myPost('web'), FILTER_VALIDATE_URL)) {
                            $udata[2] = $this->myPost('web');
                            $changes = 1;
                            $output .= '- Web Address Successfully set!'."\n";
                        } else {
                            $output .= '- Invalid Web Address!';
                        }                        
                    }

                    // Process email address request
                    if($this->myPost('email') != $udata[1]) {
                        if(filter_var($this->myPost('email'), FILTER_VALIDATE_EMAIL)) {
                            $udata[1] = $this->myPost('email');
                            $changes = 1;
                            $output .= '- Email Address Successfully set!'."\n";
                        } else {
                            $output .= '- Invalid Web Address!';
                        }
                    }

                    // Process reset avatar request
                    if($this->myPost('reset_avatar') && $udata[0]) {
                        $udata[0] = '';
                        $changes = 1;
                        $output .= '- Avatar Successfully Reset!'."\n";
                    }

                    // Process reset password request
                    if($this->myPost('reset_pass') && $udata[5]) {
                        $udata[5] = '';
                        $changes = 1;
                        $output .= '- Password Successfully Reset!'."\n";
                    }

                    // Process password regenerate request
                    if($this->myPost('regen_pass')) {
                        $npass = $this->randNumb(8);
                        $udata[5] = md5(md5($npass));
                        $changes = 1;
                        $output .= '- Password Successfully Re-generated: '.$npass."\n";
                    }

                    if($changes) {
                        $nstring = base64_encode($udata[0]).'|'.base64_encode($udata[1]).'|'.base64_encode($udata[2]).'|'.$udata[3].'|'.$udata[4].'|'.$udata[5];
                        // If name is the same or name error exists, write with the name unchanged
                        if($this->myPost('name') == $oname || $name_err) {
                            $towrite = preg_replace(
                                '/('.base64_encode($oname).')\|(.*?)\|/', 
                                '\\1|'.base64_encode($nstring).'|', 
                                $this->userList
                            );
                        // If not, write with the new name
                        } elseif(trim($this->myPost('name'), ' ')) {
                            $towrite = preg_replace(
                                '/('.base64_encode($oname).')\|(.*?)\|/', 
                                base64_encode($this->myPost('name')).'|'.base64_encode($nstring).'|', 
                                $this->userList
                            );
                            // Update the moderator, mute and ban lists
                            if($this->getMod($oname) !== FALSE) {
                                $this->writeFile(MODL, preg_replace('/^('.base64_encode($oname).')$/im', base64_encode($this->myPost('name')), $this->modList), 'w');
                            }
                            if($this->getMuted($oname) !== FALSE) {
                                $this->writeFile(MUTEDL, preg_replace('/^'.base64_encode($oname).' ([0-9]+)$/im', base64_encode($this->myPost('name')).' \\1', $this->mutedList), 'w');
                            }
                            if($this->getBanned($oname) !== FALSE) {
                                $this->writeFile(BANNEDL, preg_replace('/^'.base64_encode($oname).' ([0-9]+)$/im', base64_encode($this->myPost('name')).' \\1', $this->bannedList), 'w');
                            }
                        }

                        $this->writeFile(USERL, $towrite, 'w');
                    }
                    echo trim($output);
                }
            break;
            // Changes User Status (Available/Do Not Disturb)
            case 'toggle_status':
                $current = $this->uData[8];

                if($current == 1) {
                    $this->writeFile(USERL, $this->updateUser(NULL, 'update_status', 2), 'w');
                    echo 'Your status has been changed to: Do Not Disturb!';
                }
                if($current == 2) {
                    $this->writeFile(USERL, $this->updateUser(NULL, 'update_status', 1), 'w');
                    echo 'You status has been changed to: Available!';
                }
            break;
            // Uploads an attachment
            case 'attach_upl':
                // Process request if form element exists and has permission to attach and attachmemts are enabled
                if($_FILES['attach']['tmp_name'] && $this->hasPermission('ATTACH_UPL', 'skip_msg') && ATTACHMENT_UPLOADS) {
                    sleep(1);
                    // Strip special characters from name
                    $dest_name = preg_replace('/[^A-Za-z0-9 _\.]/', '', $_FILES['attach']['name']);
                    if(strlen($dest_name) > 13) { $dest_name = substr($dest_name, strlen($dest_name)-13, 13); }
                    
                    // Destination paths
                    $dest = __DIR__ . '/files/attachments/' . base64_encode($this->name) . '_' . dechex(time()). '_' . $dest_name;
                    $dest_web = $this->includeDir . 'files/attachments/' . base64_encode($this->name) . '_' . dechex(time()) . '_' . $dest_name;
                    
                    // Get file type by extention
                    $type = $this->getFileExt($dest_name);
                    $file_ok = TRUE;
                    $allowed_types = explode(' ', trim(ATTACHMENT_TYPES, ' '));
                    $allowed_types_img = array('jpg', 'jpeg', 'gif', 'png');
                    
                    // Meets filesize limit? Is an allowed type? Destination does not exist?
                    if($_FILES['attach']['size'] <= (ATTACHMENT_MAX_FSIZE*1024) && in_array($type, $allowed_types) && !file_exists($dest)) {            
                        copy($_FILES['attach']['tmp_name'], $dest);
                        if(in_array($type, $allowed_types_img)) {
                            echo $this->parseImg($dest_web, 'ATTACH');
                        } else {
                            echo '[attach_' . base64_encode($this->name).'_'. dechex(time()) . '_' . intval($_FILES['attach']['size']/1014).'_'.$dest_name.']';
                        }
                    // Halt, file does not meet requirements
                    } else {
                        if(!file_exists($dest)) {
                            echo 'Error: Invalid file (Allowed: '.trim(implode(', ', $allowed_types), ' ').' up to 1024KB)';
                        } else {
                            echo 'Error: File already exists!';
                        }
                    }
                }
            break;
            // Recovers the account
            case 'acc_rec':
                // Email exists? No pending recovery? Has permission? 
                if($this->uEmail && !file_exists($this->dataDir . 'tmp/rec_'.base64_encode($this->name)) && $this->hasPermission('ACC_REC', 'skip_msg')) {
                    if(mail(
                        $this->uEmail,
                        'Account Recovery',
                        "Someone (probably you) has requested an account recovery.\nClick the following link in order to reset your account password:\n\n".$_SERVER['HTTP_REFERER'].'?recover='.$this->uPass."&u=".urlencode(base64_encode($this->name))."\n\nIf you did not request this, please ignore it.",
                        $this->mailHeaders(
                            (trim(ACC_REC_EMAIL) ? trim(ACC_REC_EMAIL) : 'no-reply@'.$_SERVER['SERVER_NAME']),
                            TITLE,
                            $this->uEmail,
                            $this->name
                        )
                    )) {
                        echo 'A message was sent to the account email with recovery instructions.';
                        touch($this->dataDir . 'tmp/rec_'.base64_encode($this->name));
                    } else {
                        echo 'Failed to send E-mail!';
                    };
                // Does not meet requiments, check pending recovery
                } elseif(file_exists($this->dataDir . 'tmp/rec_'.base64_encode($this->name))) {
                    echo 'A pending recovery already exists for this account.';
                }
            break;
            // Updates Global Settings
            case 'upd_gsettings':

                if(!$this->hasPermission('GSETTINGS')) { die(); }

                // Set arrays with $_POST ids and batch process
                $arr = array();
                $gsettings_par = array('TITLE', 'INCLUDE_DIR', 'REFRESH_DELAY', 'IDLE_START', 'OFFLINE_PING', 'CHAT_DSP_BUFFER', 'CHAT_STORE_BUFFER', 'CHAT_OLDER_MSG_STEP', 'ARCHIVE_MSG', 'ANTI_SPAM', 'IMAGE_MAX_DSP_DIM',  'IMAGE_AUTO_RESIZE_UNKN', 'VIDEO_WIDTH', 'VIDEO_HEIGHT', 'AVATAR_SIZE', 'DEFAULT_AVATAR', 'DEFAULT_ROOM', 'DEFAULT_THEME', 'INVITE_LINK_CODE', 'ACC_REC_EMAIL', 'ATTACHMENT_TYPES', 'ATTACHMENT_MAX_FSIZE', 'ATTACHMENT_MAX_POST_N');

                foreach($gsettings_par as $key => $value) {
                    $arr[$value] = $this->myPost('gs_'.strtolower($value));
                }

                $gsettings_perm = array('GSETTINGS', 'ROOM_C', 'ROOM_E', 'ROOM_D', 'MOD', 'UNMOD', 'USER_E', 'TOPIC_E', 'BAN', 'UNBAN', 'MUTE', 'UNMUTE', 'MSG_HIDE', 'MSG_UNHIDE', 'POST', 'PROFILE_E', 'IGNORE', 'PM_SEND', 'LOGIN', 'ACC_REC', 'READ_MSG', 'ROOM_LIST', 'USER_LIST', 'ATTACH_UPL', 'ATTACH_DOWN');

                $gsettings_perm2 = array('MMOD', 'MOD', 'CUSER', 'USER', 'GUEST');

                foreach($gsettings_perm as $key => $value) {
                    $arr['PERM_'.$value] = '';
                    foreach($gsettings_perm2 as $key2 => $value2) {
                        if($this->myPost(strtolower($value2).'_'.strtolower($value)) == '1') { $arr['PERM_'.$value] .= ' '.$value2; }
                    }
                    $arr['PERM_'.$value] = trim($arr['PERM_'.$value], ' ');
                }

                // Update settings.php
                $res = $this->updateConf(
                    array_merge(
                        $arr,
                        array(
                            'LOAD_EX_MSG' => (($_POST['gs_load_ex_msg'] == '1') ? 'TRUE' : 'FALSE'),
                            'LIST_GUESTS' => (($_POST['gs_list_guests'] == '1') ? 'TRUE' : 'FALSE'),
                            'ARCHIVE_MSG' => (($_POST['gs_archive_msg'] == '1') ? 'TRUE' : 'FALSE'),
                            'GEN_REM_THUMB' => (($_POST['gs_gen_rem_thumb'] == '1') ? 'TRUE' : 'FALSE'),
                            'ATTACHMENT_UPLOADS' => (($_POST['gs_attachment_uploads'] == '1') ? 'TRUE' : 'FALSE')        
                        )
                    )
                );

                // Parse update return value and print
                if($res === TRUE) {
                    echo 'Settings Successfully Updated!';
                } else {
                    echo 'Nothing to update!';
                }
            break;
            // Updates Ajax Components (Chat blocks that need to be refreshed periodically)
            case 'update_components':
                echo $this->parseUsers().'[$]'.$this->checkTopicChanges().'[$]'.$this->refreshRooms().'[$]'.$this->checkHiddenMsg().'[$]'.$this->updateMsgOnceE().'[$]'.$this->mySession('alert_msg');
                if($this->mySession('alert_msg')) { unset($_SESSION['alert_msg']); }
            break;
            // Hides/Unhides a Posted Message
            case 'toggle_msg':
                $id = $this->myGet('id');
                $id_hidden = str_replace('|', '*|', $id);
                $action = '';

                // If message is not hidden, hide it
                if(strpos($this->msgList, $id) !== FALSE) {
                    if(!$this->hasPermission('MSG_HIDE', 'skip_msg')) { echo 'NO_ACCESS'; die(); }
                    $action = 'hide';
                    $this->writeFile(MESSAGES_LOC, str_replace($id, $id_hidden, $this->msgList), 'w');
                    echo $this->popTemplate('wcchat.posts.hidden');
                }

                // If message is hidden, unhide it
                if(strpos($this->msgList, $id_hidden) !== FALSE) {
                    if(!$this->hasPermission('MSG_UNHIDE', 'skip_msg')) { echo 'NO_ACCESS'; die(); }
                    $action = 'unhide';
                    preg_match_all('/'.str_replace(array('*', '|'), array('\*', '\|'), $id_hidden.'|').'(.*)/', $this->msgList, $matches);
                    $this->writeFile(MESSAGES_LOC, str_replace($id_hidden, $id, $this->msgList), 'w');
                    echo $this->parseBbcode($matches[1][0]);
                }

                // Write message id, replace old value if exists
                if(strpos($this->hiddenMsgList, $id) === FALSE && $action == 'hide') {
                    $this->writeFile(MESSAGES_HIDDEN, ' '.$id, (time()-filemtime(MESSAGES_HIDDEN) > intval((REFRESH_DELAY/1000)*2) ? 'w' : 'a'));
                }
                if(strpos($this->hiddenMsgList, $id) !== FALSE && $action == 'unhide') {
                    $this->writeFile(MESSAGES_HIDDEN, str_replace(' '.$id, '', $this->hiddenMsgList), 'w', 'allow_empty');
                }
            break;
            // Retrieves Hidden Messages for the current room
            case 'check_hidden_msg':
                echo $this->checkHiddenMsg();
            break;
            // Refreshes Topic
            case 'refreshtopic':
                echo $this->parseTopicContainer();
            break;
            // Updates Room Name / Definitions
            case 'update_rname':
                // Halt if no room edit permission
                if(!$this->hasPermission('ROOM_E')) { die(); }
                $oname = $this->myPost('oname');
                $nname = $this->myPost('nname');
                $wperm = $this->myPost('perm');
                $rperm = $this->myPost('rperm');
                $enc = base64_encode($oname);
                
                // Get room definitions
                $settings = $this->readFile($this->roomDir . 'def_'.$enc.'.txt');
                
                // $_operm = Write permission;
                // $_rperm = Read permission;
                // $_larch_vol = Last created archive volume;
                // $_larch_vol_msg_n = Last created archive volume message count;
                // $_tmp4 is unused in v1.4.10
                list($_wperm, $_rperm, $_larch_vol, $_larch_vol_msg_n, $_tmp4) = explode('|', $settings, 5);
                $changes = 0;
                
                // Update permissions if changed
                if($_wperm != $wperm || $rperm != $_rperm) {
                    file_put_contents(
                        $this->roomDir . 'def_' . $enc . '.txt',
                        $wperm .'|' . 
                        $rperm . '|' . 
                        $_larch_vol . '|' . 
                        $_larch_vol_msg_n . '|' . 
                        $_tmp4
                    );
                    $changes++;
                }
                
                // Rename room if changed
                if($oname != $nname) {
                    if(
                        file_exists($this->roomDir . base64_encode($nname).'.txt') || 
                        !trim($nname, ' ') || 
                        preg_match("/[\?<>\$\{\}\"\:\|,;]/i", $nname)
                    ) {
                        echo 'Room '.$nname.' already exists OR invalid room name (illegal: ? < > $ { } " : | , ;)';
                        die();
                    }
                    rename($this->roomDir . $enc.'.txt', $this->roomDir . base64_encode($nname).'.txt');
                    rename($this->roomDir . 'def_'.$enc.'.txt', $this->roomDir . 'def_'.base64_encode($nname).'.txt');
                    rename($this->roomDir . 'topic_'.$enc.'.txt', $this->roomDir . 'topic_'.base64_encode($nname).'.txt');
                    if(file_exists($this->roomDir . 'hidden_'.$enc.'.txt')) {
                        rename($this->roomDir . 'hidden_'.$enc.'.txt', $this->roomDir . 'hidden_'.base64_encode($nname).'.txt');
                    }
                    
                    // If the renamed room is the default, rename in settings as well
                    if(trim($oname) == trim(DEFAULT_ROOM)) {
                        file_put_contents(
                            __DIR__.'/settings.php',
                            str_replace(
                                "'DEFAULT_ROOM', '".str_replace("'", "\'", $oname)."'",
                                "'DEFAULT_ROOM', '".str_replace("'", "\'", $nname)."'",
                                $this->readFile(__DIR__.'/settings.php')
                            )
                        );
                    }
                    
                    // If the renamed room is the current room, reset current room client/server variables
                    if($this->mySession('current_room') == $oname) {
                        $_SESSION['current_room'] = $nname;
                        setcookie('current_room', $nname, time()+(86400*365), '/');
                    }
                    $changes++;
                }
                if($changes) {
                    echo 'Room '.$oname.' Successfully updated!';
                    touch(ROOMS_LASTMOD);
                }
            break;
            // Deletes A Room
            case 'delete_rname':
                // Halt if no permission to delete rooms
                if(!$this->hasPermission('ROOM_D')) { die(); }
                $changes = 0;
                $room_move = '';
                $oname = $this->myGet('oname');
                $enc = base64_encode($oname);
                
                // Room Name exists?
                if(trim($oname) && file_exists($this->roomDir . $enc.'.txt')) {
                    // Halt if default room
                    if(trim($oname) == DEFAULT_ROOM) {
                        echo 'The Default Room cannot be deleted!';
                        die();
                    }
                    // Delete room files
                    unlink($this->roomDir . $enc.'.txt');
                    unlink($this->roomDir . 'def_'.$enc.'.txt');
                    unlink($this->roomDir . 'topic_'.$enc.'.txt');
                    if(file_exists($this->roomDir . 'hidden_'.$enc.'.txt')) {
                        unlink($this->roomDir . 'hidden_'.$enc.'.txt');
                    }
                    // If deleted room is current room, move user to the default room
                    if($this->mySession('current_room') == $oname) {
                        $_SESSION['current_room'] = DEFAULT_ROOM;
                        setcookie('current_room', DEFAULT_ROOM, time()+(86400*365), '/');
                        $room_move = 'RMV';
                    }
                    $changes++;
                }
                if($changes) {
                    echo $room_move.'Room '.$oname.' Successfully deleted!';
                    // Rooms list has just changed, update modification time of the control file
                    touch(ROOMS_LASTMOD);
                }
            break;
            // Creates a Room
            case 'croom':
                // Halt if no permission to create rooms
                if(!$this->hasPermission('ROOM_C')) { die(); }
                $room_name = $this->myGet('n');
                // Target room exists? Is room name valid?
                if(!file_exists($this->roomDir . base64_encode($room_name).'.txt') && trim($room_name, ' ') && !preg_match("/[\?<>\$\{\}\"\:\|,;]/i", $room_name)) {
                    file_put_contents($this->roomDir . base64_encode($room_name).'.txt', time().'|*'.base64_encode($this->name).'|created the room.'."\n");
                    file_put_contents($this->roomDir . 'def_'.base64_encode($room_name).'.txt', '0|0|0|0|');
                    file_put_contents($this->roomDir . 'topic_'.base64_encode($room_name).'.txt', '');
                    touch(ROOMS_LASTMOD);
                    echo $this->parseRooms();
                // Room name is invalid
                } else {
                    echo 'Room '.$room_name.' already exists OR invalid room name (illegal: <b>? < > $ { } " : | , ;</b>)!';
                }
            break;
            // Changes to Another Room
            case 'changeroom':
                $room_name = $this->myGet('n');
                $_SESSION['current_room'] = $room_name;
                setcookie('current_room', $room_name, time()+(86400*365), '/');

                echo $this->parseRooms();
            break;
            // Refreshes Room List
            case 'refreshrooms':
                echo $this->refreshRooms();
            break;
            // Resets Avatar
            case 'reset_av':
                $efile = '';
                // If user avatar exists, strip its control timestamp
                if($this->uAvatar) { list($efile, $_time) = explode('?', $this->uAvatar); }
                
                // If avatar exists, reset user value and delete image file
                if(file_exists(__DIR__ . '/files/avatars/' . $efile)) {
                    $nstring = '|'.base64_encode($this->uEmail).'|'.base64_encode($this->uWeb).'|'.$this->uTimezone.'|'.$this->uHourFormat.'|'.$this->uPass;
                    $towrite = preg_replace(
                        '/('.base64_encode($this->name).')\|(.*?)\|/', 
                        '\\1|'.base64_encode($nstring).'|', 
                        $this->userList
                    );

                    $this->writeFile(USERL, $towrite, 'w');
                    echo 'Avatar successfully reset!';
                    unlink(__DIR__ . '/files/avatars/' . $efile);
                } else {
                    echo 'No Avatar to reset!';
                }
            break;
            // Uploads an avatar
            case 'upl_avatar':
                // Process if form field has been supplied
                if(isset($_FILES['avatar']['tmp_name'])) {

                    $type = $this->getFileExt($_FILES['avatar']['name']);
                    $allowed_types = array('jpeg', 'jpg', 'gif', 'png');

                    // Halt if type is not among allowed types
                    if(!in_array($type, $allowed_types)) {
                        echo 'Invalid Image Type (allowed: '.trim(implode(', ', $allowed_types), ' ').')';
                    } else {
                        // Set avatar size to 25px if not set
                        if(!AVATAR_SIZE) { $tn_size = 25; } else { $tn_size = AVATAR_SIZE; }
                        
                        // Set destination paths
                        $dest = __DIR__.'/files/avatars/'.base64_encode($this->name).'.'.str_replace('image/', '', $_FILES['avatar']['type']);
                        $dest_write = base64_encode($this->name).'.'.str_replace('image/', '', $_FILES['avatar']['type']);
                        
                        // Try to create a cropped thumbnail, halt otherwise
                        if($this->thumbnailCreateCr($_FILES['avatar']['tmp_name'], $dest, $tn_size)) {
                            $nstring = base64_encode($dest_write.'?'.time()).'|'.base64_encode($this->uEmail).'|'.base64_encode($this->uWeb).'|'.$this->uTimezone.'|'.$this->uHourFormat.'|'.$this->uPass;

                            // Set user's avatar value
                            $towrite = preg_replace(
                                '/('.base64_encode($this->name).')\|(.*?)\|/', 
                                '\\1|'.base64_encode($nstring).'|', 
                                $this->userList
                            );

                            $this->writeFile(USERL, $towrite, 'w');
                            echo 'Avatar successfully set!';
                        }
                    }
                }
            break;
            // Create a new chat start point (For a Screen Cleanup)
            case 'new_start_point':
                setcookie('start_point_'.$this->parseCookieName($this->mySession('current_room')), time(), time()+(86400*365), '/');
            break;
            case 'undo_start_point':
                setcookie('start_point_'.$this->parseCookieName($this->mySession('current_room')), '', (time()-3600), '/');
            break;
            // Shows/Hides TimeStamps
            case 'toggle_time':
                if($this->myCookie('hide_time')) {
                    setcookie('hide_time', '', time()-3600, '/');
                } else {
                    setcookie('hide_time', '1', time()+(86400*365), '/');
                }
            break;
            // Shows/Hides Edit Buttons/Links
            case 'toggle_edit':
                if($this->myCookie('hide_edit')) {
                    setcookie('hide_edit', '', time()-3600, '/');
                } else {
                    setcookie('hide_edit', '1', time()+(86400*365), '/');
                }
            break;
            // Updates Topic Description
            case 'upd_topic':
                if(!$this->hasPermission('TOPIC_E')) { die(); }
                $t = $this->myGET('t');
                $this->writeFile(TOPICL, $t, 'w', 'allow_empty');

                echo $this->parseTopicContainer();
            break;
            // Checks if topic has changed
            case 'check_topic_changes':
                echo $this->checkTopicChanges();
            break;
            // Retrieves Password Form If Required (profile has a password set and current user doesn't have a match)
            case 'get_pass_input':
                echo $this->popTemplate(
                    'wcchat.join.password_required',
                    array(
                        'USER_NAME' => $this->name
                    )
                );
            break;
            // Compares Two Passwords (Supplied and Stored)
            case 'cmppass':
                $pass = $this->myGet('pass');
                // Halt if password don't match, return a 0 to ajax caller
                if(md5(md5($pass)) != $this->uPass) {
                    echo 0;
                } else {
                    // Password ok, update server/client variables
                    setcookie('chatpass', md5(md5($pass)), time()+(86400*365), '/');
                    $_COOKIE['chatpass'] = md5(md5($pass));

                    echo 1;
                    
                    // Get rid of the pending account recovery file if exists
                    if(file_exists($this->dataDir . 'tmp/rec_'.base64_encode($this->name))) {
                        unlink($this->dataDir . 'tmp/rec_'.$u);
                    }
                }
                
            break;
            // Processes/Writes a post message, also handles ignore action
            case 'smsg':

                // Halt if no permission to post or no profile access
                if(!$this->hasPermission('POST')) { die(); }
                if(!$this->hasProfileAccess) { echo 'Account Access Denied!'; die(); }

                if(strlen($this->myGet('t')) > 0) {

                    // Is ignore request?
                    if(preg_match('#^(/ignore|/unignore)#i', $this->myGet('t'))) {

                        // Yes, is ignore request, does user has permission to ignore?
                        if(!$this->hasPermission('IGNORE')) { die(); }

                        // Yes, has permission, process request
                        $par = explode(' ', trim($this->myGet('t')), 2);
                        switch($par[0]) {
                            case '/ignore':
                                if($this->userMatch($par[1]) !== FALSE) {
                                    $this->writeEvent('ignore', $par[1]);
                                    setcookie('ign_'.$this->parseCookieName($par[1]), '1', time()+(86400*364), '/');
                                    echo 'Successfully ignored '.$par[1];
                                } else {
                                    echo 'User '.$par1.' does not exist.';
                                }
                            break;
                            case '/unignore':
                                if($this->userMatch($par[1]) !== FALSE && $this->myCookie('ign_'.base64_encode($par[1]))) {
                                    $this->writeEvent('unignore', $par[1]);
                                    setcookie('ign_'.$this->parseCookieName($par[1]), '', time()-3600, '/');
                                    echo 'Successfully unignored '.$par[1];
                                } else {
                                    echo 'User '.$par1.' does not exist / Not being ignored.';
                                }
                            break;
                        }
                    } else {

                        // No ignore, normal post

                        // Halt if banned or muted
                        $muted_msg = $banned_msg = '';
                        $muted = $this->getMuted($this->name);
                        if($muted !== FALSE && $this->name) {
                            $muted_msg = 'You have been set as mute'.($muted ? ' ('.$this->parseIdle($muted, 1).' remaining)' : '').', you cannot talk for the time being!';
                        }
                        if($this->isBanned !== FALSE) {
                            $banned_msg = 'You are banned'.(intval($this->isBanned) ? ' ('.intval(($this->isBanned-time())).' seconds remaining)' : '').'!';
                        }
                        if(!$muted_msg && !$banned_msg) {

                            // Not banned or muted, has permission?
                            if(!$this->hasRoomPermission($this->mySession('current_room'), 'W')) {
                                echo 'You don\'t have permission to post messages here!'; die();
                            }

                            // Check if last post is within anti-spam limit
                            if((time()-$this->mySession('lastpost')) < ANTI_SPAM) {
                                echo 'Anti Spam: Please allow '.ANTI_SPAM.'s between posted messages!';
                                die();
                            }
                            $name_prefix = '';
                            $text = $this->myGet('t');

                            // Tag private message
                            if(preg_match('#^(/me )#i', $text)) {
                                $name_prefix = '*';
                                $text = str_replace('/me ', '', $text);
                            }

                            // Pre-process private message
                            if(preg_match('#^(/pm )#i', $text)) {
                                if(!$this->hasPermission('PM_SEND')) { die(); }
                                list($target, $ntext) = explode(' ', str_replace('/pm ', '', $text), 2);
                                if(strlen(trim($ntext)) > 0 && strlen(trim($target)) > 0) {
                                    $target = trim($target, ' ');
                                    
                                    if($this->userMatch($target) === FALSE) {
                                        echo 'User '.$target.' does not exist!'; die();
                                    } else {
                                        $user_status = $this->userData($target);
                                        if((time() - $this->getPing($target)) < OFFLINE_PING && $user_status[8] == 2) {
                                            echo 'User '.$target.' does not want to be disturbed at the moment!'; die();
                                        }
                                    }
                                } else {
                                           echo 'Invalid Private Message Syntax ("/pm <user> <message>")'; die();                                             }
                                $name_prefix = base64_encode($target).'-';
                                $text = trim($ntext, ' ');
                            }

                            // Check if posts contains images
                            if(
                                !preg_match('/((http|ftp)+(s)?:\/\/[^<>\s]+)(.jpg|.jpeg|.png|.gif)([?0-9]*)/i', $text) && 
                                !preg_match('/\[IMG\](.*?)\[\/IMG\]/i', $text)
                            ) {
                                $text = trim($text);
                            } else {
                                // Image(s) detected, try parse to more detailed tags with information about the image(s)
                                $text = preg_replace_callback(
                                    '/(?<!=\"|\[IMG\])((http|ftp)+(s)?:\/\/[^<>\s]+(\.jpg|\.jpeg|\.png|\.gif)([?0-9]*))/i',
                                    array($this, 'parseImg'),
                                    trim($text)
                                );
                                $text = preg_replace_callback(
                                    '/\[IMG\](.*?)\[\/IMG\]/i',
                                    array($this, 'parseImg'),
                                    trim($text)
                                );
                            }

                            // Check if the new post will overflow the store buffer, if yes, delete the oldest line (1st line)
                            $source = $this->msgList;
                            if(substr_count($source, "\n") >= CHAT_STORE_BUFFER) {
                                list($v1, $v2) = explode("\n", $source, 2);
                                $source = $v2;
                                
                                // Arquive discarded message if enabled
                                if(ARCHIVE_MSG === TRUE) {
                                    list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $tmp) = explode('|', $this->roomDef);
                                    $larch_vol = intval($larch_vol);
                                    if($larch_vol == 0) { $larch_vol = 1; }
                                    $larch_vol_msg_n = intval($larch_vol_msg_n);
                                    $archive = str_replace('.txt', '.'.$larch_vol, MESSAGES_LOC);
                                    // Check if current arquive is full, if yes, start a new arquive
                                    if(($larch_vol_msg_n+1) > CHAT_STORE_BUFFER) {
                                        $larch_vol++;
                                        $larch_vol_msg_n = 1;
                                        $archive = str_replace('.txt', '.'.$larch_vol, MESSAGES_LOC);
                                        $this->writeFile(
                                            ROOM_DEF, 
                                            $wperm . '|' . 
                                            $rperm . '|' . 
                                            $larch_vol . '|' . 
                                            $larch_vol_msg_n . '|' . 
                                            $tmp,
                                            'w'
                                        );    
                                    } else {
                                        $larch_vol_msg_n++;
                                        $this->writeFile(
                                            ROOM_DEF, 
                                            $wperm . '|' . 
                                            $rperm . '|' . 
                                            $larch_vol . '|' . 
                                            $larch_vol_msg_n . '|' . 
                                            $tmp,
                                            'w'
                                        );
                                    }
                                    $this->writeFile($archive, $v1."\n", 'a');
                                }
                            }

                            // Write and return the post parsed as html code
                            $towrite = time().'|'.$name_prefix.base64_encode($this->name).'|'.strip_tags($text)."\n";
                            $this->writeFile(MESSAGES_LOC, $source.$towrite, 'w');
                            touch(ROOMS_LASTMOD);
                            $this->handleLastRead('store');
                            list($output, $index, $first_elem) = $this->parseMsg(array(trim($towrite)), 0, 'SEND');
                            $_SESSION['lastpost'] = time();
                            echo $output;
                        } else {
                            echo ($banned_msg ? $banned_msg : $muted_msg);
                        }
                    }
                }
            break;
            // Updates user settings
            case 'updsett':

                // Halt if no profile access or no edit permission
                if(!$this->hasProfileAccess) { echo 'NO_ACCESS'; die(); }
                if(!$this->hasPermission('PROFILE_E', 'skip_msg')) { echo 'NO_ACCESS'; die(); }

                $user_data = $this->userData();
                $email = $this->myPost('email');
                $web = $this->myPost('web');
                $timezone = $this->myPost('timezone');
                $hformat = $this->myPost('hformat');

                // Halt if Email or Web Url are not valid
                if((!filter_var($email, FILTER_VALIDATE_EMAIL) && trim($email)) || (!filter_var($web, FILTER_VALIDATE_URL) && trim($web))) {
                    echo 'INVALID';
                    die();
                }

                if($this->myPost('resetp') == '1') {
                    $pass = '';
                    setcookie('chatpass', '', time()-3600, '/');
                } else {
                    $passe = md5(md5($this->myPost('pass')));
                    $pass = ($this->myPost('pass') ? $passe : $this->uPass);
                    if($this->myPost('pass')) {
                        setcookie('chatpass', '', time()-3600, '/');
                    }
                }

                $nstring = base64_encode($this->uAvatar).'|'.base64_encode($email).'|'.base64_encode($web).'|'.$timezone.'|'.$hformat.'|'.$pass;

                $towrite = preg_replace(
                        '/('.base64_encode($this->name).')\|(.*?)\|/', 
                        '\\1|'.base64_encode($nstring).'|', 
                        $this->userList
                    );

                $this->writeFile(USERL, $towrite, 'w');

                // Generate tags for javascript form manipulation
                if($timezone != $this->uTimezone || $hformat != $this->uHourFormat) { echo 'RELOAD_MSG'; }
                if($pass != '') { echo ' RESETP_CHECKBOX'; }
                if($this->myPost('pass') && $this->myPost('resetp') != '1') { echo ' RELOAD_PASS_FORM'; }

            break;
            // Writes an event message
            case 'smsge':
                if($this->myGet('t')) {
                    $towrite = $this->writeEvent($this->myGet('t'), '', 'RETURN_RAW_LINE');
                    $output = $this->parseMsgE(array(trim($towrite)), 0, 'SEND');
                    if($output) { echo $output; }
                }
            break;
            // Retrieves User List
            case 'updu':
                echo $this->parseUsers();
            break;
            // Parses Event Messages
            case 'updmsg_e':
                echo $this->updateMsgOnceE();
            break;
            // Parses Post Messages
            case 'updmsg':
                if($this->isBanned !== FALSE) { echo 'You are banned!'; die(); }
                if(!$this->hasPermission('READ_MSG', 'skip_msg')) { echo 'Can\'t display messages.'; die(); }
                $output = '';
                $lastmod = filemtime(MESSAGES_LOC);
                $older_index = $this->myGet('n');
                if(!$this->hasData($older_index)) { $older_index = NULL; }

                $lastread = $this->handleLastRead('read');

                // Halt if is no new room visit and lastread is not set (user might not be accepting cookies)
                if($this->myGet('all') != 'ALL' && !$lastread) { die(); }

                $previous = $skip_new = 0;
                $new = '';
                $lines = array();
                $index = 0;
                
                // Reset archive cached volume if any
                if($this->mySession('archive') && $older_index === NULL && !$this->myGet('loop')) {
                    unset($_SESSION['archive']);
                }               

                // Reload messages if reset_msg tag exists
                if($this->mySession('reset_msg')) { $_GET['all'] = 'ALL'; }

                // Parse post messages if new messages exist or new room visit or older index exists
                if($lastmod > $lastread || $this->myGet('all') == 'ALL' || $older_index !== NULL) {
                    
                    // If "load existing messages" is disabled, automatically set a global start point 
                    if(!$this->mySession('global_start_point') && LOAD_EX_MSG === FALSE) {
                        $_SESSION['global_start_point'] = time();
                    }
                    $this->handleLastRead('store');
     
                    // Retrieve post messages
                    if(strlen($this->msgList) > 0 || $this->mySession('archive')) {
                        list($wperm,$rperm,$last_arch_vol,$tmp,$tmp2) = explode('|', $this->roomDef);
                        if(!$this->mySession('archive')) {
                            $lines = explode("\n", trim($this->msgList));
                        } else {
                            // Scan Current Archive volume
                            $archive_vol = intval($this->mySession('archive'));
                            $archive_target = 
                                $this->roomDir . 
                                base64_encode($this->mySession('current_room')) . '.' . 
                                ($last_arch_vol + 1 - $archive_vol)
                            ;
                            $lines = explode("\n", trim($this->readFile($archive_target)));
                        }
                        
                        // Scan next archive volume if oldest chat post is the archive's last
                        if($older_index !== NULL && strpos($lines[0], str_replace('js_', '', $older_index)) !== FALSE) {
                            
                            $next_archive_vol =  (intval($this->mySession('archive'))+1);
                            $next_archive_target = 
                                $this->roomDir . 
                                base64_encode($this->mySession('current_room')) . '.' . 
                                ($last_arch_vol + 1 - $next_archive_vol)
                            ;
                            
                            if(file_exists($next_archive_target)) {
                                $lines = explode("\n", trim($this->readFile($next_archive_target)));
                                $older_index = 'beginning';
                                $_SESSION['archive'] = $next_archive_vol;
                            }  
                        }
                        list($output, $index, $first_elem) = $this->parseMsg($lines, $lastread, 'RETRIEVE', $older_index);
                    }
                }

                // If total post message count > returned index, add the links to load older posts
                $older_controls = '';
                if(count($lines) && $this->myGet('all') == 'ALL' && LOAD_EX_MSG === TRUE) {
                    if($first_elem) {
                        list($tmp1, $tmp2) = explode($first_elem, $this->msgList, 2);
				        if(trim($tmp1)) { $older_controls = $this->popTemplate('wcchat.posts.older'); }
                    } else {
                        $older_controls = $this->popTemplate('wcchat.posts.older');
                    }
                }

                // Process RESET tag
                if($this->mySession('reset_msg')) {
                    $output = 'RESET'.$output;
                    unset($_SESSION['reset_msg']);
                }

                // Return output, also remove auto-scroll Javascript tags from retrieved older messages
                if(trim($output)) {
                    echo $older_controls.
                    (
                        ($older_index !== NULL) ? 
                        str_replace('wc_doscroll()', '', $output.$this->popTemplate('wcchat.posts.older.block_separator')) : 
                        $output
                    );
                }

                // Delay Older Message/New Room Visit Loading (In order to display the loader image)
                if(($older_index !== NULL) || $this->myGet('all') == 'ALL') { sleep(1); }
            break;
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
    private function mailHeaders($from,$fname,$to,$toname) {
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
