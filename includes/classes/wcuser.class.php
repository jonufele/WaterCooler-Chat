<?php

/**
 * WaterCooler Chat (User Class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcUser {

    /**
     * Logged In State, only specifies the user has chosen a name to use in chat, may or may not have access to the profile
     *
     * @since 1.4
     * @var bool
     */
    public $isLoggedIn = FALSE;

    /**
     * User Name
     *
     * @since 1.2
     * @var string
     */
    public $name;

    /**
     * Default Avatar Display Width
     *
     * @since 1.4
     * @var int
     */
    public $defAvatarSize = 25;

    /**
     * User Data Array
     *
     * @since 1.1
     * @var array
     */
    public $data;

    /**
     * Moderator List (Raw)
     *
     * @since 1.1
     * @var string
     */
    public $modList;

    /**
     * Muted List (Raw)
     *
     * @since 1.1
     * @var string
     */
    public $mutedList;

    /**
     * User List (Raw)
     *
     * @since 1.1
     * @var string
     */
    public $rawList;

    /**
     * Banned List (Raw)
     *
     * @since 1.1
     * @var string
     */
    public $bannedList;

    /**
     * Moderator Status
     *
     * @since 1.1
     * @var bool
     */
    public $isMod = FALSE;

    /**
     * Master Moderator Status
     *
     * @since 1.2
     * @var bool
     */
    public $isMasterMod = FALSE;

    /**
     * Banned Status
     *
     * @since 1.1
     * @var bool
     */
    public $isBanned = FALSE;
    
    /**
     * Muted Status
     *
     * @since 1.4
     * @var bool
     */
    public $isMuted = FALSE;

    /**
     * Certified User Status
     *
     * @since 1.1
     * @var bool
     */
    public $isCertified = FALSE;

    /**
     * Profile Access Status
     *
     * @since 1.3
     * @var bool
     */
    public $hasProfileAccess = FALSE;

    /**
     * Initializes User's variables
     * 
     * @since 1.4
     * @return void
     */
    public function init() {

        if(!WcPgc::mySession('cname')) {
            // Try use cookie value to resume session
            if(WcPgc::myCookie('cname') && !WcPgc::mySession('skip_cookie')) {
                if($this->match(WcPgc::myCookie('cname')) !== FALSE) {
                    WcPgc::wcSetSession('cname', WcPgc::myCookie('cname'));
                    $this->name = WcPgc::myCookie('cname');
                    $this->isLoggedIn = TRUE;
                } else {
                    // Cookie user does not exist / outdated, fail to resume session
                    // Inform user via login error message
                    WcPgc::wcSetSession('login_err', 
                        'Username <i>' . WcPgc::myCookie('cname') . 
                        '</i> does not exist, cannot be resumed!<br>
                        (Maybe it was renamed by a moderator?)'
                    );
                    WcPgc::wcUnsetCookie('cname');
                }
            }
        } else {
                $this->name = WcPgc::mySession('cname');
                $this->isLoggedIn = TRUE;
        }

        if(!$this->name && !$this->isLoggedIn) {
            $this->name = 'Guest';
        }

        if($this->name && $this->name != 'Guest') {
            $this->isBanned = $this->getBanned($this->name);
            $this->isMuted = $this->getMuted($this->name);
        }

        $this->data = $this->getData();

        // Check if user credentials are outdated, issue an alert message to inform user
        if(
            (
                $this->data['lastAct'] == 0 || 
                (
                    WcPgc::myCookie('chatpass') != $this->data['pass'] && 
                    WcPgc::myCookie('chatpass') && 
                    $this->data['pass']
                )
            ) && 
            WcPgc::mySession('cname') && 
            WcPgc::myGet('mode')
        ) {
            // If dummy profile, it means the previous name was changed, clear SESSION value
            if($this->data['lastAct'] == 0) { WcPgc::wcUnsetSession('cname'); }

            // Clear password cookie, user must supply it again
            WcPgc::wcUnsetCookie('chatpass');
            $this->isLoggedIn = FALSE;

            // Issue alert
            WcPgc::wcSetSession('alert_msg',
                'Your login credentials are outdated!' . "\n" .
                'Refresh page (or use the form below) and login again!' . "\n" .
                '(Possible cause: You (or a moderator) edited your profile.)'
            );
        }
    
        // Set certified User status
        if(
            WcUtils::hasData($this->data['pass']) && 
            WcPgc::myCookie('chatpass') && 
            WcPgc::myCookie('chatpass') == $this->data['pass']
        ) {
            $this->isCertified = TRUE;
        }

        // Set has perofile access status
        if(
            $this->isLoggedIn && 
            ($this->isCertified || !$this->data['pass'])
        ) {
            $this->hasProfileAccess = TRUE;
        }

        // Assign the first moderator if does not exist and current user is certified
        if(strlen(trim($this->modList)) == 0 && $this->isCertified) {
            WcFile::writeFile(MODL, "\n" . base64_encode($this->name), 'w');
            touch(ROOMS_LASTMOD);
            WcPgc::wcSetSession(
                'alert_msg', 
                'You have been assigned Master Moderator, reload page in order to get all moderator tools.'
            );
        }

        // Set Master Moderator and Moderator status
        if($this->name && $this->isCertified) {
            if($this->getMod($this->name) !== FALSE) { $this->isMod = TRUE; }
            $mods = explode("\n", trim($this->modList));
            if($mods[0] == base64_encode($this->name)) {
                $this->isMasterMod = TRUE;
            }
        }

        // Set catch window according to user idle status
        WcChat::$catchWindow = WcTime::getCatchWindow();
    }

    /**
     * Checks if a user exists in the user list, if requested, returns match as array
     * 
     * @since 1.1
     * @param string $name
     * @param string|null $v Supplied Data Row
     * @param string|null $return_match
     * @return bool|array
     */
    public function match($name, $v = NULL, $return_match = NULL) {
    
         // Check if the row has been supplied
        if($v !== NULL) {
            list($tmp, $tmp2) = explode('|', $v, 2);
            if($tmp == base64_encode($name) && WcUtils::hasData(trim($name, ' '))) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            // Row has not been supplied, try to find a match on the raw user list
            if(
                strpos(
                    "\n" . trim($this->rawList), 
                    "\n" . base64_encode($name) . '|'
                ) !== FALSE && 
                WcUtils::hasData(trim($name, ' '))
            ) {
                if($return_match !== NULL) {
                    list($tmp, $v2) = explode(
                        "\n" . base64_encode($name) . '|', "\n" . trim($this->rawList), 
                        2
                    );
                    if(strpos($v2, "\n") !== FALSE) {
                        list($v3, $tmp1) = explode("\n", $v2, 2);
                    } else {
                        $v3 = $v2;
                    }
                    $par = explode('|', $v3);
                    
                    return array(
                        'name' => (base64_encode($name)),
                        'data' => (isset($par[0]) ? $par[0] : ''),
                        'firstJoin' => (isset($par[1]) ? $par[1] : 0),
                        'lastAct' => (isset($par[2]) ? $par[2] : 0),
                        'status' => (isset($par[3]) ? $par[3] : 0)
                    );
                } else {
                    return TRUE;
                }
            } else {
                if($return_match === NULL) {
                    return FALSE;
                } else {
                    return array(
                        'name' => '',
                        'data' => '',
                        'firstJoin' => 0,
                        'lastAct' => 0,
                        'status' => 0
                    );
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
    public function getData($forced_name = NULL) {
    
        $name = (isset($forced_name) ? $forced_name : $this->name);

        // Get user's row
        $arr = $this->match($name, NULL, '1');

        // Break data into an array
        if(isset($arr['data'])) {
            if(
                strlen($arr['data']) > 0 && 
                strpos(base64_decode($arr['data']), '|') !== FALSE
            ) {
                list($avatar, $email, $web, $timeZone, $hourMode, $pass) = 
                    explode('|', base64_decode($arr['data']));
                    
                return array(
                    'avatar' => base64_decode($avatar), 
                    'email' => base64_decode($email), 
                    'web' => base64_decode($web), 
                    'timeZone' =>  $timeZone, 
                    'hourMode' => $hourMode, 
                    'pass' => $pass, 
                    'firstJoin' => $arr['firstJoin'], 
                    'lastAct' => $arr['lastAct'], 
                    'status' => $arr['status'],
                    'raw' => $arr['data']
                );
            }
        }

        return array(
            'avatar' => '', 
            'email' => '', 
            'web' => '', 
            'timeZone' => 0, 
            'hourMode' => 0, 
            'pass' => '', 
            'firstJoin' => 0, 
            'lastAct' => 0, 
            'status' => 0,
            'raw' => ''
        );
    }

    /**
     * Checks if current user has permission to perform/access the $id action
     * 
     * @since 1.2
     * @param string $id
     * @param string|null $skip_msg
     * @return bool
     */
    public function hasPermission($id, $skip_msg = NULL) {
    
        $level = '';
        $tags = array();

        // Attribute a tag to the user
        if($this->isLoggedIn) {
            if($this->isMasterMod === TRUE) {
                $level = 'MMOD';
            } elseif($this->isMod === TRUE) {
                $level = 'MOD';
            } elseif($this->isCertified) {
                $level = 'CUSER';
            } elseif(!$this->data['pass']) {
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
     * Gets User Last Known Ping
     * 
     * @since 1.3
     * @param string $name
     * @return int
     */
    public function getPing($name) {
    
        $src = WcChat::$dataDir . 'tmp/' . base64_encode($name);
        if(file_exists($src)) {
            return WcTime::parseFileMTime($src);
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
    public function setPing() {
    
        if($this->hasProfileAccess) {
            touch(WcChat::$dataDir . 'tmp/' . base64_encode($this->name));
        }
    }

    /**
     * Checks if a user is muted, retrieves mute period if exists
     * 
     * @since 1.1
     * @param string $name
     * @return bool|int
     */
    public function getMuted($name) {
    
        preg_match_all(
            '/^' . base64_encode($name) . ' ([0-9]+)$/im', 
            $this->mutedList, 
            $matches
        );
        
        if(isset($matches[1][0]) && WcUtils::hasData(trim($name, ' '))) {
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
    public function getBanned($name) {
           
        preg_match_all(
            '/^' . base64_encode($name) . ' ([0-9]+)$/im', 
            $this->bannedList, 
            $matches
        );
        
        if(isset($matches[1][0]) && WcUtils::hasData(trim($name, ' '))) {
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
    public function getMod($name) {
    
        preg_match_all(
            '/^(' . base64_encode($name) . ')$/im', 
            $this->modList, 
            $matches
        );
        if(isset($matches[1][0]) && WcUtils::hasData(trim($name, ' ')))
            return TRUE;
        else
            return FALSE;
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
    public function update($user_row, $mode, $value = NULL) {
    
        // If user row not provided, get row from users list
        $list_replace = FALSE;
        if($user_row === NULL) {
            $user_row = $original_row = 
                $this->match($this->name, NULL, 'RETURN_MATCH');
            $list_replace = TRUE;
        }

        // Update user parameters according to request
        if($user_row !== FALSE && $this->name) {
            
            $f = $user_row['firstJoin'];
    
            switch($mode) {
                case 'update_status':                            
                    $user_row['status'] = $value;
                break;
                case 'user_join':                            
                    $user_row['firstJoin'] = (($f == '0') ? time() : $f);
                    $user_row['lastAct'] = time();
                    $user_row['status'] = 1;
                    WcPgc::wcUnsetCookie('idle_refresh');
                break;
                case 'new_message':
                    $user_row['lastAct'] = time();
                    WcPgc::wcUnsetCookie('idle_refresh');
                break;
                case 'user_visit':
                    if(
                        $f == '0' || 
                        ($f != '0' && $this->hasProfileAccess)
                    ) {
                        $user_row['lastAct'] = time();
                        WcPgc::wcUnsetCookie('idle_refresh');
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
                $this->rawList
            );
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
    public function parseRawList(
        $contents, $mod_perm, $edit_perm, $del_perm, $visit = NULL
    ) {
       
        $_on = $_off = $_on_new = $_off_new = $_lurker = array();
        $uid = $this->name;
        $autocomplete = '';
        
        // I added this line because I need a function from the room obj
        $new_room = new WcRoom($this);

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
                    $mod_icon = WcGui::popTemplate(
                        'wcchat.users.item.icon_moderator', 
                        '',
                        ($this->getMod($usr) !== FALSE && strlen(trim($usr)) > 0)
                    );

                    $muted_icon = '';
                    $ismuted = $this->getMuted($usr);
                    if($ismuted !== FALSE && strlen(trim($usr)) > 0) {
                        $muted_icon = WcGui::popTemplate('wcchat.users.item.icon_muted');
                    }
                    if(WcPgc::myCookie('ign_' . base64_encode($usr))) {
                        $muted_icon = WcGui::popTemplate('wcchat.users.item.icon_ignored');
                    }

                    // Parse Web link and avatar
                    if(
                        strlen($udata) > 0 && 
                        strpos(base64_decode($udata), '|') !== FALSE
                    ) {
                        list($tmp1, $tmp2, $tmp3, $tmp4, $tmp5, $tmp6) = 
                            explode('|', base64_decode($udata));
                            
                        $av = ($tmp1 ? 
                            WcChat::$includeDir . 'files/avatars/' . base64_decode($tmp1) : 
                            WcChat::$includeDirTheme . DEFAULT_AVATAR
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
                            WcChat::$includeDirTheme . DEFAULT_AVATAR : 
                            ''
                        );
                    }

                    // Sets a style for banned users
                    $name_style = '';
                    $isbanned = $this->getBanned($usr);
                    if($isbanned !== FALSE) {
                        $ulink = '';
                        $av = (DEFAULT_AVATAR ? 
                            WcChat::$includeDirTheme . DEFAULT_AVATAR : 
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

                    $boldi = $bolde = '';
                    // Is user a guest?
                    if($f != '0') {
                        // No guest, is online?
                        if((time() - $last_ping) <= WcChat::$catchWindow) {
                            // Yes, it's an online user
                            $target_array = '_on'; 

                            // Style for current user
                            if(
                                strpos(
                                    "\n" . $v, 
                                    "\n" . base64_encode($uid).'|'
                                ) === FALSE
                            ) {
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
                        
                        // Process user private conversation link
                        $pm_room = $new_room->parseConvName($usr, $this->name);
                        
                        $lastread = $lastmod = 0;
                        $pm_room_icon = '';
                        $pm_is_new = 0;
                        
                        $currently_in_pm = FALSE;

                        if(
                            file_exists(
                                WcChat::$roomDir . 
                                base64_encode($pm_room) . '.txt'
                            )
                        ) {
                            if(
                                $this->name != $usr && 
                                $this->isLoggedIn && 
                                WcPgc::mySession('current_room') != $pm_room
                            ) {
                                $lastread = WcTime::handleLastRead('read', $pm_room);
                                $lastmod = $new_room->parseLastMod($pm_room);
    
                                $pm_room_icon = 
                                    WcGui::popTemplate(
                                        'wcchat.users.item.new_msg.' . 
                                        (($lastread < $lastmod) ? 'on' : 'off'),
                                        array(
                                            'PM_S' => WcGui::popTemplate(
                                                'wcchat.users.item.pm_s',
                                                array(
                                                    'ROOM_NAME' => $pm_room,
                                                    'NEW' => 0
                                                )
                                            ),
                                            'PM_E' => WcGui::popTemplate(
                                                'wcchat.users.item.pm_e',
                                                ''
                                            )
                                        ),
                                        !WcPgc::myCookie('ign_' . base64_encode($usr)) && 
                                        !$this->isMuted && !$this->isBanned && 
                                        $this->hasProfileAccess
                                    );
                                    
                                // Bring unread updated conversations to the top of the list
                                if(WcUtils::hasData($pm_room_icon) && $lastread < $lastmod) {
                                    $target_array .= '_new';
                                }
                            }
    
                            if(WcPgc::mySession('current_room') == $pm_room) {
                                 $currently_in_pm = TRUE;
                            } 
                        } else {
                            $pm_is_new = 1;
                        }      
                        
                        ${$target_array}[$usr] = WcGui::popTemplate(
                            'wcchat.users.item',
                            array(
                                'ID' => base64_encode($usr),
                                'WIDTH' => WcGui::popTemplate(
                                    'wcchat.users.item.width', 
                                    array('WIDTH' => (
                                        AVATAR_SIZE ? AVATAR_SIZE : $this->defAvatarSize
                                    ))
                                ),
                                'AVATAR' => $av,
                                'NAME_STYLE' => $name_style,
                                'NAME' => $boldi.$usr.$bolde,
                                'PM_S' => WcGui::popTemplate(
                                    'wcchat.users.item.pm_s',
                                    array(
                                        'ROOM_NAME' => $pm_room,
                                        'NEW' => $pm_is_new
                                    ),
                                    $this->name != $usr && 
                                    $this->isLoggedIn && 
                                    WcPgc::mySession('current_room') != $pm_room && 
                                    !WcPgc::myCookie('ign_' . base64_encode($usr)) && 
                                    !$this->isMuted && !$this->isBanned && 
                                    $this->hasPermission('PM_ROOM', 'skip_msg')
                                ),
                                'PM_E' => WcGui::popTemplate(
                                    'wcchat.users.item.pm_e',
                                    '',
                                    $this->name != $usr && 
                                    $this->isLoggedIn && 
                                    WcPgc::mySession('current_room') != $pm_room && 
                                    !WcPgc::myCookie('ign_' . base64_encode($usr)) && 
                                    !$this->isMuted && !$this->isBanned && 
                                    $this->hasPermission('PM_ROOM', 'skip_msg')
                                ),
                                'PM_CLASS' => ($currently_in_pm ? '_pm' : ($pm_room_icon ? '_msg_icon' : '')),
                                'NEW_MSG' => $pm_room_icon,
                                'MOD_ICON' => $mod_icon,
                                'MUTED_ICON' =>$muted_icon,
                                'LINK' => WcGui::popTemplate(
                                    'wcchat.users.item.link',
                                    array('LINK' => $ulink),
                                    $ulink
                                ),
                                'IDLE' => WcGui::popTemplate(
                                    'wcchat.users.item.idle_var', 
                                    array(
                                        'IDLE' => (
                                            ($target_array == '_off' && $last_ping) ?
                                            WcTime::parseIdle($last_ping) : 
                                            WcTime::parseIdle($l)
                                        )
                                    ), 
                                    ((time()-$l) >= IDLE_START && $s != 2) || 
                                    $target_array == '_off'
                                ),
                                'JOINED_CLASS' => (
                                    (intval($s) && $target_array == '_on') ? 
                                    $joined_status_class : 
                                    ''
                                ),
                                'STATUS_TITLE' => ($target_array == '_on' ? $status_title : ''),
                                'EDIT_BT' => WcGui::popTemplate(
                                    'wcchat.users.item.edit_bt',
                                    array(
                                        'ID' => base64_encode($usr), 
                                        'OFF' => (WcPgc::myCookie('hide_edit') == 1 ? '_off' : '')
                                    ),
                                    ($edit_perm || $mod_perm)
                                ),
                                'EDIT_FORM' => WcGui::popTemplate(
                                    'wcchat.users.item.edit_form',
                                    array(
                                        'ID' => base64_encode($usr),
                                        'MODERATOR' => WcGui::popTemplate(
                                            'wcchat.users.item.edit_form.moderator',
                                            array(
                                                'MOD_CHECKED' => ($mod_icon ? 'CHECKED' : ''),
                                                'ID' => base64_encode($usr)
                                            ),
                                            $this->hasPermission('MOD', 'skip_msg') || 
                                            $this->hasPermission('UNMOD', 'skip_msg')
                                        ),
                                        'MUTED' => WcGui::popTemplate(
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
                                        'BANNED' => WcGui::popTemplate(
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
                                        'PROFILE_DATA' => WcGui::popTemplate(
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
                                        'DELETE_BT' => WcGui::popTemplate(
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
                        $_lurker[$usr] = WcGui::popTemplate(
                            'wcchat.users.guests.item', 
                            array(
                                'USER' => $usr, 
                                'IDLE' => WcTime::parseIdle($l)
                            )
                        );
                    }
                }
            }
        }

        // Sort lists alphabetically
        $on = $off = $on_new = $off_new = $lurker = '';
        ksort($_on); ksort($_off); ksort($_lurker);
        ksort($_on_new); ksort($_off_new);
        foreach($_on as $k => $v) { $on .= $v; }
        foreach($_off as $k => $v) { $off .= $v; }
        foreach($_on_new as $k => $v) { $on_new .= $v; }
        foreach($_off_new as $k => $v) { $off_new .= $v; }
        foreach($_lurker as $k => $v) { $lurker .= $v; }

        // Store autocomplete list in SESSION
        if($autocomplete) {
            WcPgc::wcSetSession('autocomplete', ',' . $autocomplete);
        }

        $output = 
            WcGui::popTemplate(
                'wcchat.users.inner',
                array(
                    'JOINED' => (
                        ($on || $on_new) ? 
                        WcGui::popTemplate('wcchat.users.joined', array('USERS' => $on_new . $on)) : 
                        WcGui::popTemplate('wcchat.users.joined.void')
                    ),
                    'OFFLINE' => WcGui::popTemplate(
                        'wcchat.users.offline', 
                        array('USERS' => $off_new . $off), 
                        ($off || $off_new)
                    ),
                    'GUESTS' => WcGui::popTemplate('wcchat.users.guests', 
                        array('USERS' => $lurker), 
                        $lurker
                    ),
                )
            ); 

         return $output;
    }

    /**
     * Parses/replaces parts of the user data string
     * 
     * @since 1.4
     * @param array|null $array replacements
     * @param array|null $udata 3rd party user data    
     * @return string
     */
    public function parseDataString($array = NULL, $udata = NULL) {
        // Return empty data string if no replacement requests exist
        if($array == NULL && $udata == NULL) {
            return base64_encode('|||0|0|');
        } else {
            if($udata == NULL) {
                $udata = $this->data;
            }
            
            // Current user values
            $current = array(
                'avatar' => base64_encode($udata['avatar']), 
                'email' => base64_encode($udata['email']), 
                'web' => base64_encode($udata['web']), 
                'timeZone' =>  $udata['timeZone'], 
                'hourMode' => $udata['hourMode'], 
                'pass' => $udata['pass']
            );

            // Replace values according to suplied array
            if($array !== NULL) {
                foreach($array as $key => $value) {
                    if(
                        (
                            $key == 'avatar' || 
                            $key == 'email' || 
                            $key == 'web'
                        ) && 
                        WcUtils::hasData($value)
                    ) {
                        $value = base64_encode($value);
                    }
                    $current[$key] = $value;
                }
            }
            
            return base64_encode(implode('|', $current));
        }    
    }

    /**
     * Updates/Refreshes Screen User List (Ajax Component)
     * 
     * @param string|null $visit New user visit tag provided by ajax caller     
     * @since 1.4
     * @return string|void Html Template
     */
    public function getListChanges($visit = NULL) {

        // Store user ping
        $this->setPing();

        if($this->isBanned !== FALSE) { return 'You are banned!'; }
        if(!$this->hasPermission('USER_LIST', 'skip_msg')) {
            return 'Can\'t display users.';
        }

        $contents = $this->rawList;
        $user_row = $this->match($this->name, NULL, 'RETURN_MATCH');
        $original_row = $user_row;
        $changes = FALSE;

        // Reset user status on a new visit or update guest user listing if enabled
        if($visit != NULL) {
            if($this->hasProfileAccess) {
                $user_row = $this->update($user_row, 'update_status', 0);
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
                        $this->data['firstJoin'] != '0'
                    )
                )
            ) {
                if($this->match($this->name) === FALSE) {
                    if($this->name != 'Guest') {
                        $contents .= "\n" . 
                            base64_encode($this->name) . '|' . 
                            $this->parseDataString() . 
                            '|0|' . 
                            time() . 
                            '|0'
                        ;
                    }
                } else {
                    $user_row = $this->update($user_row, 'user_visit');
                }
                $changes = TRUE;
            }
        }

        // Creates New User/Update user last activity on join, also update status 
        if(WcPgc::myGet('join') == '1') {
            if($this->match($this->name) === FALSE) {
                if($this->name != 'Guest') {
                    $contents .= "\n" . 
                        base64_encode($this->name) . '|' . 
                        $this->parseDataString() . '|' . 
                        time(). '|' . 
                        time(). '|1'
                    ;
                }
            } else {
                $user_row = $this->update($user_row, 'user_join');
            }
            $user_row = $this->update($user_row, 'update_status', 1);
            $changes = TRUE;
        }

        // Update user last activity on new message
        if(WcPgc::myGet('new') == '1') {
            $user_row = $this->update($user_row, 'new_message');
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
            WcFile::writeFile(USERL, trim($contents), 'w');
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

        $output = $this->parseRawList(
            $contents, $mod_perm, $edit_perm, $del_perm, $visit
        );

        // Sets a cookie to enlarge refresh delay if user is idling
        if(
            !WcPgc::myCookie('idle_refresh') && 
            $visit === NULL && 
            (time() - $this->data['lastAct']) > IDLE_START && 
            REFRESH_DELAY_IDLE != 0
        ) {
            WcPgc::wcSetCookie('idle_refresh', REFRESH_DELAY_IDLE);
        }

        // Return user list if changes exist
        $sfv = strtoupper(dechex(crc32(trim($output))));
        if(
            ($sfv != WcPgc::mySession('user_list_sfv')) || 
            !WcPgc::mySession('user_list_sfv') || 
            $visit !== NULL || 
            WcPgc::myGet('ilmod')
        ) {
            WcPgc::wcSetSession('user_list_sfv', $sfv);
            return $output;
        }
    }

}

?>