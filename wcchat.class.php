<?php

/**
 * WaterCooler Chat (Main Class file)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */

/*============================================== 
 Some Edition/Syntax notes:
------------------------------------------------
 1 Tab = 4 spaces
------------------------------------------------
 - POST/GET/COOKIE getters:
 WcPgc::myPost($id)
 WcPgc::myGet($id) / WcPgc::myCookie($id)
 
 - SESSION/SERVER getters:
 WcPgc::mySession($id)
 WcPgc::myServer($id)

 - COOKIE/SESSION setters:
 WcPgc::wcSetCookie($id, $value)
 WcPgc::wcSetSession($id, $value)

 - COOKIE/SESSION un-setters:
 WcPgc::wcUnsetCookie($id)
 WcPgc::wcUnsetSession($id)

 - filemtime / microtime parsers:
 WcTime::parseFileMTime($file_path)
 WcTime::parseMicroTime()
 
 - Current User / Current Room Arrays
 
 $this->user->data[$id]
 indexes:
    avatar; email; web; timeZone; hourMode
    pass; firstJoin; lastAct; status; raw
    
 $this->room->def[$id]
 indexes:
    wPerm; rPerm; lArchVol; lArchVolMsgN
    lastMod 
------------------------------------------------
 Template populator:

   WcGui::popTemplate(
      $id,
      $array = NULL,
      $display_condition = NULL,
      $condition_fail_content = NULL
   );

   Raw Template with token:
      'horse color is: {TOKEN}'

   Template source:
      themes/<theme_name>/templates.php

   Usage Example:
      WcGui::popTemplate(
         'wcchat.tmplt_name', 
         array('TOKEN' => 'white'), 
         $horses === TRUE, 
         'Horse fail!'
      );

   Displays:
      "horse color is: white" or "Horse fail!"
      (depending of $horses value)      
------------------------------------------------
 Includes:
    Ajax Server Modules (1 file per action)
    WcRoom - Room Class
    WcUser - User Class
    WcUtils - Uncategorized Utilities
 Categorized Utility Classes:
    WcFile / WcGui / WcImg / WcPgc / WcTime
================================================*/
    
class WcChat {

    /**
     * Include Directory
     *
     * @since 1.1
     * @var string
     */
    public static $includeDir;
    
    /**
     * Theme Include Directory
     *
     * @since 1.4
     * @var string
     */
    public static $includeDirTheme;

    /**
     * Ajax Caller
     *
     * @since 1.1
     * @var string
     */
    public static $ajaxCaller;

    /**
     * Join Message
     *
     * @since 1.1
     * @var string
     */
    private $joinMsg;

    /**
     * templates
     *
     * @since 1.1
     * @var array
     */
    public static $templates;

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
    public static $dataDir;
    
    /**
     * Rooms Directory Path
     *
     * @since 1.2
     * @var string
     */
    public static $roomDir;

    /**
     * Window of time to catch events/pings
     *
     * @since 1.4
     * @var int
     */
    public static $catchWindow;

    /**
     * Unique prefix to be used in cookies/sessions
     *
     * @since 1.4
     * @var string
     */
    public static $wcPrefix;

    /**
     * Include Dir (full Server path)
     *
     * @since 1.4
     * @var string
     */
    public static $includeDirServer;

    /**
     * Cookie expire period (days)
     *
     * @since 1.4
     * @var int
     */
    public static $cookieExpire = 365;
    
    /**
     * Room Object
     *
     * @since 1.4
     * @var obj
     */     
    private $room;
    
    /**
     * User Object
     *
     * @since 1.4
     * @var obj
     */
    private $user;

    /**
     * Initializes Chat Environment
     * 
     * @since 1.4
     * @return void
     */
    protected function init() {

        if(session_id() == '') { session_start(); }

        // Initialize Includes / Paths
        $this->initIncPath();
        
        // Halt if user is automated, apply restriction if access is disabled or login attempt
        WcUtils::botAccess();
        
        // Initialize Current Room
        $this->room->initCurr();

        // Initialize Data files, print stop message if non writable folders exist
        $this->initDataFiles();

        // Handles main form requests
        $this->handleMainForm();

        // Initializes user variables
        $this->user->init();
    }

    /**
     * Initializes Includes / Paths
     * 
     * @since 1.4
     * @return void
     */
    private function initIncPath() {

        self::$includeDirServer = __DIR__ . '/';
    
        include self::$includeDirServer . 'settings.php';
        include self::$includeDirServer . 'includes/classes/wcuser.class.php';
        include self::$includeDirServer . 'includes/classes/wcroom.class.php';
        include self::$includeDirServer . 'includes/classes/wcutils.class.php';
        include self::$includeDirServer . 'includes/classes/wcutils.file.class.php';
        include self::$includeDirServer . 'includes/classes/wcutils.gui.class.php';
        include self::$includeDirServer . 'includes/classes/wcutils.img.class.php';
        include self::$includeDirServer . 'includes/classes/wcutils.pgc.class.php';
        include self::$includeDirServer . 'includes/classes/wcutils.time.class.php';
        
        self::$wcPrefix = strtoupper(dechex(crc32(self::$includeDirServer)));
        
        // Halt if settings has errors
        $settings_has_errors = WcUtils::settingsHasErrors();
        if($settings_has_errors !== FALSE) {
            echo '
                <b>Settings configuration has errors</b>
                <br>(Use the interface editor in the future to avoid this)<br><br> 
                please fix the following errors in settings.php:<br><br>' . 
                $settings_has_errors
            ;
            die(); 
        }
        
        self::$dataDir = (DATA_DIR ? rtrim(DATA_DIR, '/') . '/' : '');
        self::$roomDir = DATA_DIR . 'rooms/';
        self::$includeDir = (
            trim(INCLUDE_DIR, '/') ? 
            '/' . trim(INCLUDE_DIR, '/') . '/' : 
            '/'
        );
        
        // Attempt to set the include dir on the very first run
        if(
            !file_exists(self::$roomDir . base64_encode(DEFAULT_ROOM) . '.txt') && 
            WcPgc::myServer('REQUEST_URI') != '/' && 
            is_writable(self::$includeDirServer . 'settings.php')
        ) {
            self::$includeDir = '/' . trim(WcPgc::myServer('REQUEST_URI'), '/') . '/';
            WcFile::updateConf(
                array('INCLUDE_DIR' => trim(WcPgc::myServer('REQUEST_URI'), '/'))
            );
        }
        
        define('THEME', (
            (
                WcPgc::myCookie('wc_theme') && 
                file_exists(self::$includeDirServer . 'themes/' . WcPgc::myCookie('wc_theme') . '/')
            ) ? 
            WcPgc::myCookie('wc_theme') : 
            DEFAULT_THEME
        ));
        
        self::$templates = WcGui::includeTemplates();
        
        self::$includeDirTheme = self::$includeDir . 'themes/' . THEME . '/';

        self::$ajaxCaller = self::$includeDir . 'ajax.php?';
        
        $this->user = new WcUser();        
        $this->room = new WcRoom($this->user);
    }

    /**
     * Initializes Data Files
     * 
     * @since 1.2
     * @return void|string Html template
     */
    private function initDataFiles() {

        // Halt if non writable folders exist
        $has_non_writable_folders = WcFile::hasNonWritableFolders();
        if($has_non_writable_folders) {
            echo WcGui::popTemplate(
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
            self::$roomDir . base64_encode(WcPgc::mySession('current_room')) . '.txt'
        );
        define('TOPICL', 
            self::$roomDir . 'topic_' . base64_encode(WcPgc::mySession('current_room')) . '.txt'
        );
        define('ROOM_DEF_LOC', 
            self::$roomDir . 'def_' . base64_encode(WcPgc::mySession('current_room')) . '.txt'
        );
        define('MESSAGES_UPDATED', 
            self::$roomDir . 'updated_' . base64_encode(WcPgc::mySession('current_room')) . '.txt'
        );
        define('USERL', self::$dataDir . 'users.txt');
        define('MODL', self::$dataDir . 'mods.txt');
        define('MUTEDL', self::$dataDir . 'muted.txt');
        define('BANNEDL', self::$dataDir . 'bans.txt');
        define('EVENTL', self::$dataDir . 'events.txt');
        define('ROOMS_LASTMOD', self::$dataDir . 'rooms_lastmod.txt');

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
                $this->room->parseDefString()
            );
        }
        if(!file_exists(ROOMS_LASTMOD)) { file_put_contents(ROOMS_LASTMOD, ''); }
        if(!file_exists(MESSAGES_UPDATED)) { file_put_contents(MESSAGES_UPDATED, ''); }

        $this->user->modList          = WcFile::readFile(MODL);
        $this->user->mutedList        = WcFile::readFile(MUTEDL);
        $this->room->rawMsgList          = WcFile::readFile(MESSAGES_LOC);
        $this->room->rawUpdatedMsgList    = WcFile::readFile(MESSAGES_UPDATED);
        $this->user->rawList         = WcFile::readFile(USERL);
        $this->room->topic            = WcFile::readFile(TOPICL);
        $this->user->bannedList       = WcFile::readFile(BANNEDL);
        $this->room->rawEventList        = WcFile::readFile(EVENTL);
        
        // Write the first message to the room (creation note) if no messages exist
        if(strlen($this->room->rawMsgList) == 0) {
            $towrite = WcTime::parseMicroTime() . '|*' . base64_encode('room') . '|has been created.' . "\n";
            WcFile::writeFile(MESSAGES_LOC, $towrite, 'w');
            $this->room->updateCurrLastMod();
            $this->room->rawMsgList = WcFile::readFile(MESSAGES_LOC);
        }
        
        // Initialize Current Room Definitions
        $this->room->initCurrDef();
        
        // Set Pm Room Tag
        if(strpos(WcPgc::mySession('current_room'), 'pm_') !== FALSE) {
            $this->room->isConv = TRUE;
        }
    }

    /**
     * Handles main form requests
     * 
     * @since 1.4
     * @return void
     */
    private function handleMainForm() {
    
        // Skip this if it's an ajax call
        if(WcPgc::myGet('mode')) {
            return;
        }

        // Process "Change User" Request
        if(WcPgc::myGet('cuser')) {
            WcPgc::wcUnsetSession('cname');
            WcPgc::wcUnsetSession('login_err');
            WcPgc::wcUnsetCookie('cname');
            WcPgc::wcUnsetCookie('chatpass');
            WcPgc::wcSetSession('skip_cookie', 1);
            if($this->room->isConv) {
                WcPgc::wcUnsetSession('current_room');
                WcPgc::wcUnsetCookie('current_room');
            }
            header('location: ' . WcPgc::myGet('ret') . '#wc_topic');
            die();
        }

        // Process Recover Request
        $u = WcPgc::myGet('u');
        if(WcPgc::myGet('recover') && $u && file_exists(self::$dataDir . 'tmp/rec_' . $u)) {
            $par = $this->user->getData(base64_decode($u));
            if($par['pass'] == WcPgc::myGet('recover')) {
                $npass = WcUtils::randNumb(8);
                $npasse = md5(md5($npass));
                $ndata = $this->user->parseDataString(
                    array('pass' => $npasse),
                    $par
                );
                
                $request_uri = explode('?', WcPgc::myServer('REQUEST_URI'));
                if(mail(
                    $par['email'],
                    'Account Recovery',
                    "Your new password is: " . $npass . "\n\n\n" . 
                    (
                        (
                            (
                                WcUtils::hasData(WcPgc::myServer('HTTPS')) && 
                                WcPgc::myServer('HTTPS') !== 'off'
                            ) || 
                            WcPgc::myServer('SERVER_PORT') == 443
                        ) ? 
                        'https://' : 
                        'http://'
                    ) . WcPgc::myServer('SERVER_NAME') . $request_uri[0],
                    WcUtils::mailHeaders(
                        (
                            trim(ACC_REC_EMAIL) ? 
                            trim(ACC_REC_EMAIL) : 
                            'no-reply@' . WcPgc::myServer('SERVER_NAME')
                        ),
                        TITLE,
                        $par['email'],
                        base64_decode($u)
                    )
                )) {
                    WcFile::writeFile(
                        USERL, 
                        str_replace(
                            $u . '|' . $par['raw'] . '|',
                            $u . '|' . $ndata . '|',
                            $this->user->rawList
                        ),
                        'w'
                    );
                    $this->stopMsg = 'A message was sent to the account email with the new password.';
                    unlink(self::$dataDir . 'tmp/rec_' . $u);
                } else {
                    $this->stopMsg = 'Failed to send E-mail!';
                }
            }
        } elseif(
            WcPgc::myGet('recover') && 
            $u && 
            !file_exists(self::$dataDir . 'tmp/rec_' . $u)
        ) {
            $this->stopMsg = 'This recovery link has expired.';
        }

        // Process Login Request
        if(WcPgc::myPost('cname')) {
                
            if(
                !$this->user->hasPermission('LOGIN', 'skip_msg') || 
                $this->user->getBanned(WcPgc::myPost('cname')) !== FALSE
            ) {
                WcPgc::wcSetSession('login_err', 'Cannot login! Access Denied!');
                header('location: ' . WcPgc::myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            if(
                INVITE_LINK_CODE && 
                WcPgc::myGet('invite') != INVITE_LINK_CODE && 
                $this->user->match(WcPgc::myPost('cname')) === FALSE
            ) {
                WcPgc::wcSetSession(
                    'login_err', 
                    'Cannot login! You must follow an invite link to login the first time!'
                );
                header('location: ' . WcPgc::myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            if(
                strlen(trim(WcPgc::myPost('cname'), ' ')) == 0 || 
                strlen(trim(WcPgc::myPost('cname'), ' ')) > 30 || 
                preg_match("/[\?<>\$\{\}\"\:\|,; ]/i", WcPgc::myPost('cname'))
            ) {
                WcPgc::wcSetSession(
                    'login_err', 
                    'Invalid Nickname, too long (max = 30) or<br>
                    with invalid characters (<b>? < > $ { } " : | , ; space</b>)!'
                );
                header('location: ' . WcPgc::myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            if(trim(strtolower(WcPgc::myPost('cname'))) == 'guest')
            {
                WcPgc::wcSetSession(
                    'login_err', 
                    'Username "Guest" is reserved AND cannot be used!'
                );
                header('location: ' . WcPgc::myServer('REQUEST_URI') . '#wc_join');
                die();
            }
            
            $last_seen = $this->user->getPing(WcPgc::myPost('cname'));

            // Check if chosen name is online
            if(!$this->user->isLoggedIn && ((time()-$last_seen) <= self::$catchWindow)) {
                
                $tmp = $this->user->getData(WcPgc::myPost('cname'));
                if(
                    WcPgc::myCookie('chatpass') == $tmp['pass'] && 
                    WcUtils::hasData($tmp['pass']) && 
                    WcUtils::hasData(WcPgc::myCookie('chatpass'))
                ) {
                    $passok = TRUE;
                } else {
                    $passok = FALSE;
                }
                
                if($passok === FALSE) {
                    WcPgc::wcSetSession(
                        'login_err', 
                        'Nickname <b>' . WcPgc::myPost('cname') . '</b> is currenly in use!'
                    );
                    header('location: ' . WcPgc::myServer('REQUEST_URI') . '#wc_join');
                    die();
                }
            }
            
            // Clear idle_refresh id any (you're not idle if you're logging in)
            if(WcPgc::myCookie('idle_refresh')) { WcPgc::wcUnsetCookie('idle_refresh'); }
            
            WcPgc::wcSetCookie('cname', trim(WcPgc::myPost('cname'), ' '));
            WcPgc::wcSetSession(
                'cname', 
                trim(WcPgc::myPost('cname'), ' ')
            );

            // Clear the session that was used to change user
            WcPgc::wcUnsetSession('skip_cookie');

            header('location: ' . WcPgc::myServer('REQUEST_URI') . '#wc_topic');
            die();
        }
    }

    /**
     * Prints the populated index template
     * 
     * @since 1.1
     * @return void
     */
    public function printIndex($embedded = NULL) {

        $onload = $contents = '';
        $this->init();
        
        if($this->user->isLoggedIn) {
            $JOIN = WcGui::popTemplate(
                'wcchat.join.inner',
                array(
                    'PASSWORD_REQUIRED' => 
                        WcGui::popTemplate(
                            'wcchat.join.password_required', 
                            array(
                                'USER_NAME' => $this->user->name
                            ), 
                            $this->user->data['pass'] && !$this->user->isCertified
                        ),
                    'MODE' => (($this->user->data['pass'] && !$this->user->isCertified) ? 
                        WcGui::popTemplate('wcchat.join.inner.mode.login', '') : 
                        WcGui::popTemplate('wcchat.join.inner.mode.join', '')
                    ),
                    'CUSER_LINK' => WcGui::popTemplate(
                        'wcchat.join.cuser_link', 
                        array(
                            'RETURN_URL' => urlencode(WcPgc::myServer('REQUEST_URI'))
                        )
                    ),
                    'USER_NAME' => $this->user->name,
                    'RECOVER' => WcGui::popTemplate(
                        'wcchat.join.recover',
                        '',
                        $this->user->data['email'] && 
                        $this->user->data['pass'] && 
                        !$this->user->isCertified && 
                        $this->user->hasPermission('ACC_REC', 'skip_msg')
                    )
                )
            );
        } else {
            $err = '';
            if(WcPgc::mySession('login_err')) {
                $err = WcPgc::mySession('login_err');
                WcPgc::wcUnsetSession('login_err');
            }

            $JOIN = WcGui::popTemplate(
                'wcchat.login_screen',
                array(
                    'USER_NAME_COOKIE' => WcPgc::myCookie('cname'),
                    'CALLER_NO_PAR' => trim(self::$ajaxCaller, '?'),
                    'ERR' => WcUtils::parseError($err)
                )
            );

        }

        $BBCODE = (
            (
                !$this->user->isLoggedIn && 
                WcPgc::myCookie('cname') && 
                !$this->user->data['pass']
            ) ? 
            '<i>Hint: Set-up a password in settings to skip the login screen on your next visit.</i>' :
            WcGui::popTemplate(
                'wcchat.toolbar.bbcode',
                array(
                    'SMILIES' => WcGui::iSmiley('wc_text_input_field', 'wc_text_input'),
                    'FIELD' => 'wc_text_input_field',
                    'CONT' => 'wc_text_input',
                    'ATTACHMENT_UPLOADS' => WcGui::popTemplate(
                        'wcchat.toolbar.bbcode.attachment_uploads',
                        array(
                            'ATTACHMENT_MAX_POST_N' => ATTACHMENT_MAX_POST_N
                        ),
                        ATTACHMENT_UPLOADS && $this->user->hasPermission('ATTACH_UPL', 'skip_msg')
                    )
                ),
                $this->user->isLoggedIn
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
            'ATTACHMENT_MAX_POST_N', 'POST_EDIT_TIMEOUT', 'MAX_DATA_LEN'
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
            'UNMUTE', 'MSG_HIDE', 'MSG_UNHIDE', 'POST', 'POST_E', 'PROFILE_E', 
            'IGNORE', 'PM_SEND', 'PM_ROOM', 'LOGIN', 'ACC_REC', 'READ_MSG', 
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

        $contents = WcGui::popTemplate(
            'wcchat',
            array(
                'TITLE' => TITLE,
                'TOPIC' => WcGui::popTemplate(
                    'wcchat.topic', 
                    array(
                        'TOPIC' => $this->room->parseTopicContainer()
                    )
                ),
                'STATIC_MSG' => (!$this->user->isLoggedIn ? 
                    WcGui::popTemplate('wcchat.static_msg') : 
                    ''
                ),
                'POSTS' => WcGui::popTemplate('wcchat.posts'),
                'INFO' => WcGui::popTemplate('wcchat.info'),
                'GSETTINGS' => ($this->user->hasPermission('GSETTINGS', 'skip_msg') ? 
                    WcGui::popTemplate(
                        'wcchat.global_settings',
                        array_merge(
                            array(
                                'URL' => 
                                    (
                                        (
                                            (
                                                WcUtils::hasData(WcPgc::myServer('HTTPS')) && 
                                                WcPgc::myServer('HTTPS') !== 'off'
                                            ) || 
                                            WcPgc::myServer('SERVER_PORT') == 443
                                        ) ? 
                                        'https://' : 
                                        'http://'
                                    ) . WcPgc::myServer('SERVER_NAME') . WcPgc::myServer('REQUEST_URI'),
                                'ACC_REC_EM_DEFAULT' => 'no-reply@' . WcPgc::myServer('SERVER_NAME')
                            ),
                            $gsettings_par_v
                        )
                    ) : 
                    ''
                ),
                'TOOLBAR' => WcGui::popTemplate(
                    'wcchat.toolbar',
                    array(
                        'BBCODE' => ($BBCODE ? $BBCODE : 'Choose a name to use in chat.'),
                        'USER_NAME' => $this->user->name ? 
                            str_replace("'", "\'", $this->user->name) : 
                            'Guest',
                        'TIME' => time(),
                        'COMMANDS' => WcGui::popTemplate(
                            'wcchat.toolbar.commands',
                            array(
                                'GSETTINGS' => WcGui::popTemplate(
                                    'wcchat.toolbar.commands.gsettings', 
                                    '', 
                                    $this->user->hasPermission('GSETTINGS', 'skip_msg')
                                ),
                                'EDIT' => WcGui::popTemplate(
                                    'wcchat.toolbar.commands.edit', 
                                    '', 
                                    $this->user->hasPermission('ROOM_C', 'skip_msg') || 
                                    $this->user->hasPermission('ROOM_E', 'skip_msg') || 
                                    $this->user->hasPermission('ROOM_D', 'skip_msg') || 
                                    $this->user->hasPermission('MOD', 'skip_msg') || 
                                    $this->user->hasPermission('UNMOD', 'skip_msg') || 
                                    $this->user->hasPermission('USER_E', 'skip_msg') || 
                                    $this->user->hasPermission('USER_D', 'skip_msg') || 
                                    $this->user->hasPermission('TOPIC_E', 'skip_msg') || 
                                    $this->user->hasPermission('BAN', 'skip_msg') || 
                                    $this->user->hasPermission('UNBAN', 'skip_msg') || 
                                    $this->user->hasPermission('MUTE', 'skip_msg') || 
                                    $this->user->hasPermission('UNMUTE', 'skip_msg') || 
                                    $this->user->hasPermission('MSG_HIDE', 'skip_msg') || 
                                    $this->user->hasPermission('MSG_UNHIDE', 'skip_msg')  
                                )
                            )
                        ),
                        'ICON_STATE' => ($this->user->hasProfileAccess ? '' : 'closed'),
                        'JOINED_STATUS' => WcGui::popTemplate(
                            'wcchat.toolbar.joined_status', 
                            array('MODE' => 'on')
                        )
                    )
                ),
                'TEXT_INPUT' => WcGui::popTemplate(
                    'wcchat.text_input', 
                    array('CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER)
                ),
                'SETTINGS' => WcGui::popTemplate(
                    'wcchat.settings',
                    array(
                        'AV_RESET' => ($this->user->data['avatar'] ? '' : 'closed'),
                        'TIMEZONE_OPTIONS' => str_replace(
                            'value="' . $this->user->data['timeZone'] . '"', 
                            'value="' . $this->user->data['timeZone'] . '" SELECTED', 
                            WcGui::popTemplate('wcchat.settings.timezone_options')
                        ),
                        'HFORMAT_SEL0' => ($this->user->data['hourMode'] == '0' ? ' SELECTED' : ''),
                        'HFORMAT_SEL1' => ($this->user->data['hourMode'] == '1' ? ' SELECTED' : ''),
                        'USER_LINK' => $this->user->data['web'],
                        'USER_EMAIL' => $this->user->data['email'],
                        'RESETP_ELEM_CLOSED' => ($this->user->data['pass'] ? '' : 'closed')
                    )
                ),
                'JOIN' => WcGui::popTemplate('wcchat.join', array('JOIN' => $JOIN)),
                'ROOM_LIST' => WcGui::popTemplate(
                    'wcchat.rooms', 
                    array('RLIST' => $this->room->getListChanges('VISIT'))
                ),
                'USER_LIST' => WcGui::popTemplate(
                    'wcchat.users', 
                    array('ULIST' => $this->user->getListChanges('VISIT'))
                ),
                'THEMES' => WcGui::parseThemes()
            )
        );

        $onload = (
            $this->user->isLoggedIn ? 
            WcGui::popTemplate(
                'wcchat.toolbar.onload', 
                array(
                    'CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER, 
                    'EDIT_BT_STATUS' => intval(WcPgc::myCookie('hide_edit'))
                )
            ) : 
            WcGui::popTemplate(
                'wcchat.toolbar.onload_once', 
                array(
                    'CHAT_DSP_BUFFER' => CHAT_DSP_BUFFER
                )
            )
        );

        // Set a ban tag
        $tag = '';
        if($this->user->isBanned !== FALSE) {
            $tag = (
                (intval($this->user->isBanned) == 0) ? 
                ' (Permanently)' : 
                ' (' . WcTime::parseIdle($this->user->isBanned, 1) . ' remaining)'
            );
        }

        // Output the index template
        return WcGui::popTemplate(
            ($embedded === NULL ? 'index' : 'index_embedded'),
            array(
                'TITLE' => TITLE,
                'CONTENTS' => (
            ($this->user->isBanned !== FALSE) ? 
                WcGui::popTemplate(
                    'wcchat.critical_error', 
                    array('ERROR' => 'You are banned!' . $tag)
                ) : 
                ($this->stopMsg ? 
                    WcGui::popTemplate(
                        'wcchat.critical_error', 
                        array('ERROR' => $this->stopMsg)
                    ) : 
                    $contents
                )
            ),
                'ONLOAD' => (($this->user->isBanned !== FALSE || $this->stopMsg) ? '' : $onload),
                'REFRESH_DELAY' => REFRESH_DELAY,
                'STYLE_LASTMOD' => WcTime::parseFileMTime(self::$includeDirServer . 'themes/'.THEME.'/style.css'),
                'SCRIPT_LASTMOD' => WcTime::parseFileMTime(self::$includeDirServer . 'script.js'),
                'DEFAULT_THEME' => DEFAULT_THEME
            )
        );

    }

    /**
     * Processes Ajax Requests
     * 
     * @since 1.1
     * @param string $mode
     * @return void
     */
    public function ajax() {
    
        $this->init();

        if(WcPgc::myGet('mode')) {
        
        if(!preg_match('/[^A-Za-z0-9_]/i', WcPgc::myGet('mode'))) {
            include(self::$includeDirServer . 'includes/wcajax.' . WcPgc::myGet('mode') . '.php');
            }
            
        }   
    }
       
}

?>
