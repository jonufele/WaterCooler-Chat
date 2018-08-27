<?php

/**
 * WaterCooler Chat (Room class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcRoom {

    /**
     * Room Definitions Array
     *
     * @since 1.4
     * @var array
     */
    public $def;

    /**
     * Message List (Raw)
     *
     * @since 1.1
     * @var string
     */
    public $rawMsgList;

    /**
     * Updated Message List (Raw)
     *
     * @since 1.2
     * @var string
     */
    public $rawUpdatedMsgList;

    /**
     * Event List (Raw)
     *
     * @since 1.1
     * @var string
     */
    public $rawEventList;

    /**
     * Room Topic
     *
     * @since 1.1
     * @var string
     */
    public $topic;

    /**
     * Conversation Room Tag
     *
     * @since 1.4
     * @var bool
     */
    public $isConv = FALSE;
    
    /**
     * User object
     *
     * @since 1.4
     * @var obj
     */
    private $user;
    
    function __construct(WcUser $user) {
        $this->user = $user;
    }

    /**
     * Initializes current room variables
     * 
     * @since 1.4
     * @return void
     */
    public function initCurr() {
 
        // Initialize Current Room Session / Cookie
        if(!WcPgc::mySession('current_room')) {
            WcPgc::wcSetSession('current_room', (
                (
                    WcPgc::myCookie('current_room') && 
                    file_exists(
                        WcChat::$roomDir . 
                        base64_encode(WcPgc::myCookie('current_room')) . '.txt'
                    )
                ) ? 
                WcPgc::myCookie('current_room') : 
                DEFAULT_ROOM
            ));
        } elseif(
            !file_exists(
                WcChat::$roomDir . 
                base64_encode(WcPgc::mySession('current_room')) . '.txt'
            )
        ) {
            WcPgc::wcSetSession('current_room', DEFAULT_ROOM);
            WcPgc::wcSetSession('reset_msg', '1');
            WcPgc::wcSetCookie('current_room', DEFAULT_ROOM);
        }
    }
    
    /**
     * Initiates all definitions for the current room
     * 
     * @since 1.4
     * @return void
     */    
    public function initCurrDef() {   
        $this->def = $this->getDef(WcPgc::mySession('current_room'));
    }

    /**
     * Checks if user has permission to read/write from/to a room 
     * 
     * @since 1.2
     * @param string $room_name
     * @param string/null $mode Write(W)/Read(R)
     * @return bool
     */
    public function hasPermission($room_name, $mode = NULL) {

        // If room is a private conversation, check only the two participants
        if(strpos($room_name, 'pm_') !== FALSE) {
            return $this->hasConvPermission($room_name);
        }
    
        list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $last_mod) = 
            explode(
                '|', 
                WcFile::readFile(
                    WcChat::$roomDir . 
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
                if($this->user->isLoggedIn && $this->user->isMasterMod === TRUE) { $permission = TRUE; }
            break;
            case '2':
                if($this->user->isLoggedIn && $this->user->isMod === TRUE) { $permission = TRUE; }
            break;
            case '3':
                if($this->user->isLoggedIn && $this->user->isCertified === TRUE) { $permission = TRUE; }
            break;
            case '4':
                if($this->user->isLoggedIn && !$this->user->data['pass']) {
                    $permission = TRUE;
                }
            break;
            default: $permission = TRUE;
        }
        return $permission;
    }

    /**
     * Checks if user has permission to read/write from/to a private message room 
     * 
     * @since 1.4
     * @param string $room_name
     * @return bool
     */
    public function hasConvPermission($room_name) {

        // If room is a private conversation, check only the two participants
        if(strpos($room_name, 'pm_') !== FALSE) {
            list($par1, $par2) = 
                explode(
                    '_', 
                    str_replace('pm_', '', $room_name)
                );
            if(
                (
                    $par1 == base64_encode($this->user->name) || 
                    $par2 == base64_encode($this->user->name)
                ) && 
                $this->user->hasProfileAccess
            ) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * Updates Current Room Last Modified Bit
     * 
     * @since 1.4
     * @return void
     */    
    public function updateCurrLastMod() {
        
        WcFile::writeFile(
            ROOM_DEF_LOC,
            $this->parseDefString(
                array('lastMod' => WcTime::parseMicroTime())
            ),
            'w'
        );
    }

    /**
     * Gets Room's Last Modified Time (Either stored or file's)
     * 
     * @since 1.4
     * @return int
     */
    public function parseLastMod($target = NULL) {
        if($target === NULL) {
            if($this->def['lastMod']) {
                return $this->def['lastMod'];
            } else {
               return WcTime::parseFileMTime(MESSAGES_LOC);
            }
        } else {       
            $room_def = explode(
                '|', 
                WcFile::readFile(
                    WcChat::$roomDir . 'def_' . base64_encode($target) . '.txt'
                )
            );
            
            if($room_def[4]) {
                return $room_def[4];
            } else {
                return WcTime::parseFileMTime(
                    WcChat::$roomDir . base64_encode($target) . '.txt'
                );
            }
        }
    }

    /**
     * Generates the unique pm room name from two user names
     * 
     * @since 1.4
     * @return int
     */
    public function parseConvName($name1, $name2) {
        $participants = array($name1, $name2);
        sort($participants);
        return 'pm_' . 
            base64_encode($participants[0]) . '_' . 
            base64_encode($participants[1])
        ;
    }
    
    /**
     * Gets the target user from the pm room name
     * 
     * @since 1.4
     * @return int
     */
    public function getConvTarget($room_name) {
        $participants = 
            explode(
                '_', 
                str_replace('pm_', '', $room_name)
            );
            
        if($participants[0] != base64_encode($this->user->name)) {
            return base64_decode($participants[0]);
        }
        
        if($participants[1] != base64_encode($this->user->name)) {
            return base64_decode($participants[1]);
        }
        
    }
    
    /**
     * Gets a room definitions
     * 
     * @since 1.4
     * @return array
     */    
    public function getDef($room) {
    
        $room_loc = WcChat::$roomDir . 'def_' . base64_encode($room) . '.txt';
        if(file_exists($room_loc)) {
            list($wperm, $rperm, $larch_vol, $larch_vol_msg_n, $last_mod) = 
                explode(
                    '|', 
                    WcFile::readFile(
                        WcChat::$roomDir . 'def_' . base64_encode($room) . '.txt'
                    )
                );
            
            return array(
                'wPerm'         => intval($wperm),
                'rPerm'         => intval($rperm),
                'lArchVol'      => intval($larch_vol),
                'lArchVolMsgN'  => intval($larch_vol_msg_n),
                'lastMod'       => $last_mod
            );
        } else {
            return FALSE;
        }
    }

    /**
     * Generates the room list, rooms are topic containers, not user containers
     * 
     * @since 1.2
     * @return string Html Template
     */
    public function parseList() {

        $rooms = $create_room = '';

        // Scan rooms folder
        foreach(glob(WcChat::$roomDir . '*.txt') as $file) {
            $room_name = 
                base64_decode(str_replace(
                    array(WcChat::$roomDir, '.txt'), 
                    '', 
                    $file
                ));

            // Skip any special room related files (definitions, topic, updated msg)
            // hidden_* files were renamed to updated_*, still, keep it here to avoid errors 
            if(
                strpos(basename($file), 'def_') === FALSE && 
                strpos(basename($file), 'topic_') === FALSE && 
                strpos(basename($file), 'updated_') === FALSE && 
                strpos(basename($file), 'hidden_') === FALSE && 
                strpos($room_name, 'pm_') === FALSE
            ) {

                // Parse the edit form if user has permission
                $edit_form = $edit_icon = '';
                if(
                    $this->user->hasPermission('ROOM_E', 'skip_msg') && 
                    strpos($room_name, 'pm_') === FALSE
                ) {
                    list($perm, $t1, $t2, $t3, $t4) = explode(
                        '|', 
                        WcFile::readFile(
                            WcChat::$roomDir . 'def_' . 
                            base64_encode($room_name) . '.txt'
                        )
                    );
                    $enc = base64_encode($file);
                    $edit_form = 
                        WcGui::popTemplate(
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
                                'DELETE_BT' => WcGui::popTemplate(
                                    'wcchat.rooms.edit_form.delete_bt', 
                                    array('ID' => $enc), 
                                    $room_name != DEFAULT_ROOM
                                )                        
                            )
                        );
                    $edit_icon = WcGui::popTemplate(
                        'wcchat.rooms.edit_form.edit_icon', 
                        array(
                            'ID' => base64_encode($file),
                            'OFF' => (WcPgc::myCookie('hide_edit') == 1 ? '_off' : '')
                        )
                    );
                }

                // Parse room if user has read permission for this specific room
                if($this->hasPermission($room_name, 'R')) {
                    if($room_name == WcPgc::mySession('current_room')) {
                        $rooms .=
                            WcGui::popTemplate(
                                'wcchat.rooms.current_room',
                                array(
                                    'TITLE' => $room_name,
                                    'EDIT_BT' => $edit_icon,
                                    'FORM' => $edit_form,
                                    'NEW_MSG' => WcGui::popTemplate('wcchat.rooms.new_msg.off')
                                )
                            );
                    } else {
                        $lastread = WcTime::handleLastRead('read', $room_name);
                        $lastmod = $this->parseLastMod($room_name);

                        $rooms .= 
                            WcGui::popTemplate(
                                'wcchat.rooms.room',
                                array(
                                    'TITLE' => $room_name,
                                    'EDIT_BT' => $edit_icon,
                                    'FORM' => $edit_form,
                                    'NEW_MSG' => WcGui::popTemplate(
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
        if($this->user->hasPermission('ROOM_C', 'skip_msg')) {
            $create_room =
                WcGui::popTemplate(
                    'wcchat.rooms.create', 
                    array(
                        'OFF' => (
                            (WcPgc::myCookie('hide_edit') == 1) ? '_off' : ''
                        )
                    )
                );
        }

        // Replace list with message if no permission to read the list
        if(!$this->user->hasPermission('ROOM_LIST', 'skip_msg')) {
            $rooms = 'Can\'t display rooms.';
        }

        return WcGui::popTemplate(
            'wcchat.rooms.inner', 
            array('ROOMS' => $rooms, 'CREATE' => $create_room)
        );
    }

    /**
     * Parses The Topic Container Template/Contents
     * 
     * @since 1.2
     * @return string Html Template
     */
    public function parseTopicContainer() {
    
        $down_perm = ($this->user->hasPermission('ATTACH_DOWN', 'skip_msg') ? TRUE : FALSE);
    
        $this->topic = WcFile::readFile(TOPICL);

        $topic_con = nl2br(WcGui::parseBbcode(
            $this->topic,
            $down_perm,
            $this->user->name
        ));
        
        if(strpos($this->topic, '[**]') !== FALSE) {
            list($v1, $v2) = explode('[**]', $this->topic);
            $topic_con = WcGui::popTemplate(
                'wcchat.topic.box.partial',
                array(
                    'TOPIC_PARTIAL' => nl2br(WcGui::parseBbcode(
                        $v1,
                        $down_perm,
                        $this->user->name
                    )),
                    'TOPIC_FULL' => nl2br(
                        WcGui::parseBbcode(
                            str_replace(
                                '[**]', 
                                '', 
                                $this->topic
                            )),
                            $down_perm,
                            $this->user->name
                    )
                )
            );
        }
         
        if($this->isConv) {
            list($user1, $user2) = 
            explode(
                '_', 
                str_replace('pm_', '', WcPgc::mySession('current_room'))
            );
            $udata1 = $this->user->getData(base64_decode($user1));
            $udata2 = $this->user->getData(base64_decode($user2));
            $room_name = WcGui::popTemplate(
                'wcchat.topic.pm_room',
                array(
                    'AVATAR' => ($udata1['avatar'] ? 
                        WcChat::$includeDir . 'files/avatars/' . $udata1['avatar'] : 
                        WcChat::$includeDirTheme . DEFAULT_AVATAR
                    ),
                    'NAME' =>base64_decode($user1),
                    'AVATAR2' => ($udata2['avatar'] ? 
                        WcChat::$includeDir . 'files/avatars/' . $udata2['avatar'] : 
                        WcChat::$includeDirTheme . DEFAULT_AVATAR
                    ),
                    'NAME2' => base64_decode($user2)
                )
            );

        } else {
            $room_name = WcPgc::mySession('current_room');
        }

        return WcGui::popTemplate(
            'wcchat.topic.inner',
            array(
                'CURRENT_ROOM' => $room_name,
                'TOPIC' =>
                    WcGui::popTemplate(
                        'wcchat.topic.box',
                        array(
                            'TOPIC_CON' => ($this->topic ? $topic_con : 'No topic set.'),
                            'TOPIC_TXT' => ($this->topic ? stripslashes($this->topic) : ''),
                            'TOPIC_EDIT_BT' => WcGui::popTemplate(
                                'wcchat.topic.edit_bt',
                                array('OFF' => (WcPgc::myCookie('hide_edit') == 1 ? '_off' : '')),
                                $this->user->hasPermission('TOPIC_E', 'skip_msg') || 
                                $this->hasConvPermission(WcPgc::mySession('current_room'))
                            ),
                            'BBCODE' => WcGui::popTemplate(
                                'wcchat.toolbar.bbcode',
                                array(
                                    
                                    'SMILIES' => WcGui::iSmiley('wc_topic_txt', 'wc_topic_txt'),
                                    'FIELD' => 'wc_topic_txt',
                                    'CONT' => 'wc_topic_txt',
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
     * @param int|null $older_index index of the first old (hidden) message
     * @param int|null $single_msg_reload Used to reload a single message (only inner contents are needed)    
     * @return array
     */
    public function parseMsg(
        $lines, $lastread, $action, 
        $older_index = NULL, $single_msg_reload = NULL
    ) {

        // Initialize the displayed messages index
        $index = 0;
        $output = $new = $first_elem = $first_elem_name = '';
        $previous = $prev_unique_id = $skip_new = $first_elem_time = 0;
        $puser = '';
        $old_start = FALSE;        
        $today_date = gmdate('d-M', time() + ($this->user->data['timeZone'] * 3600));
        if($older_index != NULL) { $older_index = str_replace('js_', '', $older_index); }
        
        // Build an array with avatars to be displayed in posts
        $avatar_array = array();
        if($action != 'SEND') {
            if(trim($this->user->rawList)) {
                $av_lines = explode("\n", trim($this->user->rawList));
                foreach($av_lines as $k => $v) {
                    list($tmp1, $tmp2, $tmp3, $tmp4) = explode('|', trim($v));
                    list($tmp5, $tmp6) = explode('|', base64_decode($tmp2), 2);
                    $avatar_array[base64_decode($tmp1)] = base64_decode($tmp5);
                }
            }
        } else {
            // If user is simply sending a message, only the user avatar is needed
            $avatar_array[$this->user->name] = $this->user->data['avatar'];
        }

        // Halt if no permission to read
        if(!$this->hasPermission(WcPgc::mySession('current_room'), 'R')) {
            echo 'You do not have permission to access this room!';
            die();
        }
        
        // Get the first lastread at room visit if any (will be useful while loading older messages)
        $lastread_new = (
            (
                WcPgc::mySession('lastread_arch_' . WcPgc::mySession('current_room')) && 
                $older_index !== NULL
            ) ? 
            WcPgc::mySession('lastread_arch_' . WcPgc::mySession('current_room')) : 
            $lastread
        );
 
        // Scan the available post lines and populates the respective templates

        // Invert order in order to scan the newest first    
        krsort($lines);
        foreach($lines as $k => $v) {
            list($time_or, $user_or, $msg) = explode('|', trim($v), 3);
            $pm_target = FALSE;
            
            // Check if user contains a target parameter (Private Message)
            if(strpos($user_or, '-') !== FALSE) {
                list($pm_target, $nuser) = explode('-', $user_or);
                $user = $nuser;
            } else {
                $user = $user_or;
            }
            
            $par = $edited_par = array('', '');
            // Check if time contains an extra parameter (Edited Message)
            if(strpos($time_or, '-') !== FALSE) {
                $par = explode('-', $time_or);
                $time = $par[1];
                $edited_par = explode('-', base64_decode($par[0]), 2);
            } else {
                $time = $time_or;
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
            $time_date = gmdate('d-M', $time + ($this->user->data['timeZone'] * 3600));

            // Halt scan if no batch retrieval and current message is no longer new
            if(
                WcPgc::myGet('all') != 'ALL' && 
                $time <= $lastread && 
                $older_index === NULL
            ) {
                break;
            }
            
            // Generate an unique id for the post message (html object id)
            $unique_id = (
                !$self ? 
                ($time . '|' . $user_or) : 
                ($time . '|*' . $user)
            );
            
            // The id to use for manipulating the raw source
            $id_source = (
                 !$self ? 
                (str_replace('*', '', $time_or) . '|' . $user_or) : 
                (str_replace('*', '', $time_or) . '|*' . $user)
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
                        WcPgc::myGet('all') == 'ALL'
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
                if(
                    (WcPgc::myGet('all') == 'ALL' || $older_index !== NULL) && 
                    !$skip_new && 
                    !WcPgc::myCookie('ign_' . base64_encode($puser))
                ) {
                    if($previous) {
                        if(
                            $time <= $lastread_new && 
                            $previous > $lastread_new && 
                            $puser != base64_encode($this->user->name)
                        ) {
                            $new = WcGui::popTemplate('wcchat.posts.new_msg_separator');
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
                        $time < WcPgc::myCookie(
                            'start_point_' . 
                            WcPgc::mySession('current_room')
                        ) && 
                        $older_index === NULL &&
                        LOAD_EX_MSG !== FALSE
                    ) || 
                    (
                        ($time < WcPgc::mySession('global_start_point')) && 
                        LOAD_EX_MSG === FALSE && 
                        WcPgc::mySession('global_start_point')
                    )
                ) {
                    $start_point = (
                        LOAD_EX_MSG !== FALSE ? 
                        WcPgc::myCookie(
                            'start_point_' . 
                            WcPgc::mySession('current_room')
                        ) : 
                        WcPgc::mySession('global_start_point')
                    );
                    
                    // "Clear Screen" Tag
                    $time_date2 = gmdate('d-M', $start_point + ($this->user->data['timeZone'] * 3600));
                    $output = 
                        WcGui::popTemplate(
                            'wcchat.post_container',
                            array(
                                'CONTENT' =>  
                                    WcGui::popTemplate(
                                        'wcchat.posts.self',
                                        array(
                                            'STYLE' => 'dislay:inline',
                                            'TIMESTAMP' => 
                                                gmdate(
                                                    (($this->user->data['hourMode'] == '1') ? 'H:i': 'g:i a'), 
                                                    $start_point + ($this->user->data['timeZone'] * 3600)
                                                ).
                                                ($time_date2 != $today_date ? ' ' . $time_date2 : ''),
                                            'USER' => $this->user->name,
                                            'MSG' => (
                                                LOAD_EX_MSG !== FALSE ? 
                                                WcGui::popTemplate('wcchat.posts.undo_clear_screen') : 
                                                WcGui::popTemplate('wcchat.posts.global_clear_screen')
                                            ),
                                            'ID' => $unique_id,
                                            'HIDE_ICON' => '',
                                            'UPDATED_NOTE' => '',
                                            'EDIT_TAG' => '',
                                            'EDIT_CONTAINER' => ''
                                        )
                                    ),
                                'SKIP_ON_OLDER_LOAD' => '_skip',
                                'ID' => $unique_id
                            )
                        ) . $new . $output;
                    break;
                }

                // Skip current line if the post belongs to the current user and the user is not sending a message nor performing a batch retrieval (Older posts, new room visit)
                if(
                    WcPgc::myGet('all') != 'ALL' && 
                    $older_index === NULL && 
                    base64_decode($user) == $this->user->name && 
                    $action != 'SEND' && 
                    $single_msg_reload === NULL
                ) {
                    $index++;
                    continue;
                }
                
                $down_perm = ($this->user->hasPermission('ATTACH_DOWN', 'skip_msg') ? TRUE : FALSE);
                $post_edit_perm = ($this->user->hasPermission('POST_E', 'skip_msg') && 
                (
                    (
                        (time() < ($time + POST_EDIT_TIMEOUT) || POST_EDIT_TIMEOUT == 0) && 
                        $this->user->name == base64_decode($user)
                    ) || $this->user->isMod
                )) ? TRUE : FALSE;

                // Process the post if not part of an ignore
                if(!WcPgc::myCookie('ign_' . $user)) {
                    if(!$self) {
                        if(
                            $pm_target === FALSE || 
                            (
                                (
                                    base64_decode($pm_target) == $this->user->name || 
                                    base64_decode($user) == $this->user->name
                                ) && 
                                $this->user->hasProfileAccess && 
                                $pm_target !== FALSE
                            )
                        ) {
                            $tmp = WcGui::popTemplate(
                                'wcchat.posts.normal',
                                array(
                                    'ALL' => ($action != 'SEND' ? WcPgc::myGet('ALL') : 'ALL'),
                                    'PM_SUFIX' => ($pm_target !== FALSE ? '_pm' : ''),
                                    'PM_TAG' => WcGui::popTemplate(
                                        'wcchat.posts.normal.pm_tag', 
                                        '', 
                                        $pm_target !== FALSE
                                    ),
                                    'EDIT_TAG' => WcGui::popTemplate(
                                        'wcchat.posts.normal.edit_tag',
                                        array(
                                            'BT' => WcGui::popTemplate(
                                                'wcchat.posts.normal.edit_tag.bt',
                                                array(
                                                    'ID' => $unique_id,
                                                    'TAG' => $par[0],
                                                    'CLASS' => ( 
                                                        (
                                                            (
                                                                base64_decode($user) != $this->user->name || 
                                                                (
                                                                    base64_decode($user) == $this->user->name && 
                                                                    time() > ($time + POST_EDIT_TIMEOUT) && 
                                                                    POST_EDIT_TIMEOUT != 0
                                                                )
                                                            ) && 
                                                            $this->user->isMod
                                                        ) ? (
                                                            (WcPgc::myCookie('hide_edit') == 1) ? 
                                                            'edit_bt_off' : 
                                                            'edit_bt'
                                                        ) : 
                                                        ''
                                                    )
                                                ),
                                                $post_edit_perm && !WcPgc::mySession('archive'),
                                                (WcPgc::mySession('archive') ? WcGui::popTemplate('wcchat.posts.archived') : '')
                                            ),
                                            'EDITED' => WcGui::popTemplate(
                                                'wcchat.posts.normal.edit_tag.edited',
                                                 array(
                                                    'TARGET' => WcGui::popTemplate(
                                                        'wcchat.posts.normal.edit_tag.edited.target',
                                                        array('NAME' => $edited_par[1]),
                                                        WcUtils::hasData($edited_par[1]) && 
                                                        base64_decode($user) != $edited_par[1]
                                                    )
                                                ), 
                                                WcUtils::hasData($edited_par[0])
                                            ),
                                            'ID' => $unique_id
                                        )
                                    ),
                                    'EDIT_CONTAINER' => WcGui::popTemplate(
                                        'wcchat.posts.edit_container',
                                        array(
                                            'ID' => $unique_id
                                        ),
                                        $post_edit_perm
                                    ),
                                    'STYLE' => (
                                        WcPgc::myCookie('hide_time') ? 
                                        'display:none' : 
                                        'display:inline'
                                    ),
                                    'TIMESTAMP' => 
                                        gmdate(
                                            (($this->user->data['hourMode'] == '1') ? 'H:i' : 'g:i a'), 
                                            $time + ($this->user->data['timeZone'] * 3600)
                                        ) . 
                                        ($time_date != $today_date ? ' ' . $time_date : '')
                                    ,
                                    'POPULATE_START' => WcGui::popTemplate(
                                        'wcchat.posts.normal.populate_start', 
                                        array('USER' => addslashes(base64_decode($user))), 
                                        base64_decode($user) != $this->user->name
                                    ),
                                    'USER' => base64_decode($user),
                                    'POPULATE_END' => WcGui::popTemplate(
                                        'wcchat.posts.normal.populate_end', 
                                        '', 
                                        base64_decode($user) != $this->user->name
                                    ),
                                    'PM_TARGET' => (
                                            (base64_decode($pm_target) != $this->user->name) ?
                                            WcGui::popTemplate(
                                                'wcchat.posts.normal.pm_target', 
                                                array('TITLE' => base64_decode($pm_target)), 
                                                $pm_target !== FALSE
                                            ) :
                                            WcGui::popTemplate(
                                                'wcchat.posts.normal.pm_target.self', 
                                                array('TITLE' => base64_decode($pm_target)), 
                                                $pm_target !== FALSE
                                            )
                                        ),
                                    'MSG' => WcGui::popTemplate(
                                        'wcchat.posts.hidden' . ($hidden ? '_mod' : ''), 
                                        '', 
                                        $hidden || WcPgc::myCookie($hidden_cookie),
                                        WcGui::parseBbcode(
                                            WcGui::parseLongMsg($msg, $unique_id),
                                            $down_perm,
                                            $this->user->name,
                                            $unique_id
                                        )
                                    ),
                                    'ID' => $unique_id,
                                    'HIDE_ICON' => WcGui::popTemplate(
                                        'wcchat.posts.hide_icon', 
                                        array(
                                            'REVERSE' => (
                                                ($hidden || WcPgc::myCookie($hidden_cookie)) ? 
                                                '_r' : 
                                                ''
                                            ), 
                                            'ID' => $unique_id,
                                            'ID_SOURCE' => $id_source, 
                                            'OFF' => '',
                                            'PRIVATE' => ($pm_target ? 1 : 0)
                                        ),
                                        !WcPgc::mySession('archive')
                                    ),
                                    'AVATAR' => (
                                        isset($avatar_array[base64_decode($user)]) ? 
                                        (
                                            WcUtils::hasData($avatar_array[base64_decode($user)]) ? 
                                            WcChat::$includeDir . 'files/avatars/' . $avatar_array[base64_decode($user)] : 
                                            WcChat::$includeDirTheme . DEFAULT_AVATAR
                                        ) : 
                                        WcChat::$includeDirTheme . DEFAULT_AVATAR
                                    ),
                                    'WIDTH' => WcGui::popTemplate(
                                        'wcchat.posts.normal.width', 
                                        array('WIDTH' => 
                                            (AVATAR_SIZE ? AVATAR_SIZE : $this->user->defAvatarSize)
                                        )
                                    ),
                                    'UPDATED_NOTE' => WcGui::popTemplate(
                                        'wcchat.posts.updated_note',
                                        array('ID' => $unique_id),
                                        $this->user->isLoggedIn
                                    )
                                )
                            );
                               
                            if($single_msg_reload !== NULL) {
                                return $tmp;
                            } else { 
                                $output = WcGui::popTemplate(
                                    'wcchat.post_container',
                                    array(
                                        'CONTENT' => $tmp,
                                        'SKIP_ON_OLDER_LOAD' => '',
                                        'ID' => $unique_id
                                    )
                                ). $new . $output;
                            }
                            
                            $index++;
                            
                            if(WcPgc::myGet('all') == 'ALL' || $older_index !== NULL) {
                                $first_elem = ($hidden ? 
                                    str_replace('|', '*|', $unique_id) : 
                                    $unique_id
                                );
                                $first_elem_time = $time;
                                $first_elem_user = $user;
                            }
                        }
                    } else {
                        $tmp = WcGui::popTemplate(
                            'wcchat.posts.self',
                            array(
                                'SKIP_ON_OLDER_LOAD' => '',
                                'EDIT_TAG' => WcGui::popTemplate(
                                    'wcchat.posts.normal.edit_tag',
                                    array(
                                        'BT' => WcGui::popTemplate(
                                            'wcchat.posts.normal.edit_tag.bt',
                                            array(
                                                'ID' => $unique_id,
                                                'TAG' => $par[0],
                                                'CLASS' => ( 
                                                    (
                                                        (
                                                            base64_decode($user) != $this->user->name || 
                                                            (
                                                                base64_decode($user) == $this->user->name && 
                                                                time() > ($time + POST_EDIT_TIMEOUT) && 
                                                                POST_EDIT_TIMEOUT != 0
                                                            )
                                                        ) && 
                                                        $this->user->isMod
                                                    ) ? (
                                                        (WcPgc::myCookie('hide_edit') == 1) ? 
                                                        'edit_bt_off' : 
                                                        'edit_bt'
                                                    ) : 
                                                    ''
                                                )
                                            ),
                                            $post_edit_perm && !WcPgc::mySession('archive')
                                        ),
                                        'EDITED' => WcGui::popTemplate(
                                            'wcchat.posts.normal.edit_tag.edited',
                                             array(
                                                'TARGET' => WcGui::popTemplate(
                                                    'wcchat.posts.normal.edit_tag.edited.target',
                                                    array('NAME' => $edited_par[1]),
                                                    WcUtils::hasData($edited_par[1]) && 
                                                    base64_decode($user) != $edited_par[1]
                                                )
                                            ), 
                                            WcUtils::hasData($edited_par[0])
                                        ),
                                        'ID' => $unique_id
                                    )
                                ),
                                'EDIT_CONTAINER' => WcGui::popTemplate(
                                    'wcchat.posts.edit_container',
                                    array(
                                        'ID' => $unique_id
                                    ),
                                    $post_edit_perm
                                ),
                                'STYLE' => (WcPgc::myCookie('hide_time') ? 'display: none' : 'dislay:inline'),
                                'TIMESTAMP' => 
                                    gmdate(
                                        (($this->user->data['hourMode'] == '1') ? 'H:i': 'g:i a'), 
                                        $time + ($this->user->data['timeZone'] * 3600)
                                    ).
                                    ($time_date != $today_date ? ' '.$time_date : ''),
                                'USER' => base64_decode($user),
                                'MSG' => WcGui::popTemplate(
                                    'wcchat.posts.hidden' . ($hidden ? '_mod' : ''), 
                                    '', 
                                    $hidden || WcPgc::myCookie($hidden_cookie), 
                                    WcGui::parseBbcode(
                                        WcGui::parseLongMsg($msg, $unique_id),
                                        $down_perm,
                                        $this->user->name,
                                        $unique_id
                                    )
                                ),
                                'ID' => $unique_id,
                                'HIDE_ICON' => WcGui::popTemplate(
                                    'wcchat.posts.hide_icon', 
                                    array(
                                        'REVERSE' => (
                                            ($hidden || WcPgc::myCookie($hidden_cookie)) ? 
                                            '_r' : 
                                            ''
                                        ), 
                                        'ID' => $unique_id,
                                        'ID_SOURCE' => $id_source, 
                                        'OFF' => '',
                                        'PRIVATE' => ($pm_target ? 1 : 0)
                                    ),
                                    !WcPgc::mySession('archive')
                                ),
                                'UPDATED_NOTE' => WcGui::popTemplate(
                                    'wcchat.posts.updated_note',
                                    array('ID' => $unique_id),
                                    $this->user->isLoggedIn
                                )
                            )
                        );
                        
                        if($single_msg_reload !== NULL) {
                                return $tmp;
                        } else { 
                            $output = WcGui::popTemplate(
                                'wcchat.post_container',
                                array(
                                    'CONTENT' => $tmp,
                                    'SKIP_ON_OLDER_LOAD' => '',
                                    'ID' => $unique_id
                                )
                            ). $new . $output;
                        }
                        
                        $index++;
                        if(WcPgc::myGet('all') == 'ALL' || $older_index !== NULL) {
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
            $first_elem_name != base64_encode($this->user->name) && 
            !$skip_new && 
            $output
        ) {
            $output = WcGui::popTemplate('wcchat.posts.new_msg_separator') . $output;
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
    public function parseMsgE($lines, $lastread, $action) {
    
        $output = '';
        
        // Store today's date to strip of today's events
        $today_date = gmdate('d-M', time() + ($this->user->data['timeZone'] * 3600));

        krsort($lines);
        foreach($lines as $k => $v) {
        
            // Count the number of parameters
            // (3 = normal event, 4 = event with a target user)
            $tar_user = '';
            $target_is_usergroup = FALSE;
            if(substr_count($v, '|') == 3) {
                list($time, $user, $tar_user, $msg) = explode('|', trim($v), 4);
                if(strpos($tar_user, '*') !== FALSE) {
                    $tar_user = base64_decode(
                        str_replace('*', '', $tar_user)
                    );
                    $target_is_usergroup = TRUE;
                } else {
                    $tar_user = base64_decode($tar_user);
                }
            } else {
                      list($time, $user, $msg) = explode('|', trim($v), 3);
            }
            
            $read = FALSE;
            if($tar_user) {
                if(!$target_is_usergroup && $tar_user == WcPgc::mySession('cname')) {
                    $read = TRUE;    
                }
                if($target_is_usergroup && $this->hasPermission($tar_user, 'R')) {
                    $read = TRUE;
                }
            } else {
                $read = TRUE;
            }
            
            // Process event if target is current user or no target
            if($read) {
            
                // Event's date to compare with today's
                $time_date = gmdate('d-M', $time + ($this->user->data['timeZone'] * 3600));
                
                // Halt if: event is not new or 
                // user doesn't have a read point and not sending or 
                // user already has the event listed
                if(
                    ($time <= $lastread) || 
                    (!$lastread && $action != 'SEND') || 
                    (time() - $time) > WcChat::$catchWindow
                ) {
                    break;
                }

                // Skip current line if event's user is the current user and 
                // is not sending
                if(
                    base64_decode($user) == $this->user->name && 
                    $action != 'SEND'
                ) { continue; }

                // Parse event if not outdated
                if($time > $lastread) {
                    $output = WcGui::popTemplate(
                        'wcchat.posts.event',
                        array(
                            'STYLE' => (WcPgc::myCookie('hide_time') ? 'display: none' : 'dislay:inline'),
                            'TIMESTAMP' => 
                                gmdate(
                                    (($this->user->data['hourMode'] == '1') ? 'H:i': 'g:i a'), 
                                    $time + ($this->user->data['timeZone'] * 3600)
                                ).
                                ($time_date != $today_date ? ' '.$time_date : ''),
                            'MSG' => WcGui::parseBbcode(
                                $msg,
                                ($this->user->hasPermission('ATTACH_DOWN', 'skip_msg') ? TRUE : FALSE),
                                $this->user->name
                            )
                        )
                    ) . $output;
                }
            }
        }
        return $output;
    }

       /**
     * Parses/replaces parts of the room data string
     * 
     * @since 1.4
     * @param array|null $array replacements
     * @param array|null $udata 3rd party user data    
     * @return string
     */
    public function parseDefString($array = NULL, $rdata = NULL) {
        // Return empty data string if no replacement requests exist
        if($array == NULL && $rdata == NULL) {
            return '0|0|0|0|'.WcTime::parseMicroTime();
        } else {
            if($rdata == NULL) {
                $rdata = $this->def;
            }
            
            // Current user values
            $current = array(
                'wPerm' => intval($rdata['wPerm']), 
                'rPerm' => intval($rdata['rPerm']), 
                'lArchVol' => intval($rdata['lArchVol']), 
                'lArchVolMsgN' =>  intval($rdata['lArchVolMsgN']), 
                'lastMod' => $rdata['lastMod']
            );

            // Replace values according to suplied array
            if($array !== NULL) {
                foreach($array as $key => $value) {
                    $current[$key] = $value;
                }
            }
            
            return implode('|', $current);
        }    
    }

   /**
     * Checks if Topic has Changed, if yes, returns the populated html template (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */
    public function getTopicChanges() {
    
        $lastmod_t = WcTime::parseFileMTime(TOPICL);
        $lastread_t = WcTime::handleLastRead(
            'read', 
            'topic_' . WcPgc::mySession('current_room')
        );

        // If changed or reload request exists, return the parsed html template
        if($lastmod_t > $lastread_t || WcPgc::myGet('reload') == '1') {
            WcTime::handleLastRead('store', 'topic_' . WcPgc::mySession('current_room'));
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
    public function getListChanges($visit = NULL) {
    
        $lastread = WcTime::handleLastRead('read', 'rooms_lastread');
        $lastmod = WcTime::parseFileMTime(ROOMS_LASTMOD);

        // If changed, return the parsed html template
        if($lastread < $lastmod || $visit !== NULL) {
            WcTime::handleLastRead('store', 'rooms_lastread');
            return $this->parseList();
        }
    }

    /**
     * Retrieves the list of Updated Messages (Ajax Component)
     * 
     * @since 1.3
     * @return string|void
     */
    public function getUpdatedMsg() {
    
        // Truncate file if older than catch window
        if(
            (time() - WcTime::parseFileMTime(MESSAGES_UPDATED)) > 
            WcChat::$catchWindow && 
            WcUtils::hasData($this->rawUpdatedMsgList)
        ) {
            WcFile::writeFile(
                MESSAGES_UPDATED,
                '',
                'w',
                'allow_empty'
            );
        }
    
        // Send contents to ajax caller if is new
        $lastmod = WcTime::parseFileMTime(MESSAGES_UPDATED);
        if(
            $lastmod > 
            WcTime::handleLastRead(
                'read', 
                'updated_' . WcPgc::mySession('current_room')
            )
        ) {
            WcTime::handleLastRead(
                'store', 
                'updated_' . WcPgc::mySession('current_room')
            );
            
            return trim($this->rawUpdatedMsgList, ' ');
        }
    }

    /**
     * Parses Event Messages (Ajax Component)
     * 
     * @since 1.2
     * @return string|void Html Template
     */
    public function getNewMsgE() {
    
        if(!$this->user->hasPermission('READ_MSG', 'skip_msg')) {
            return 'Can\'t display messages.';
        }
        $output_e = '';
        $lastmod = WcTime::parseFileMTime(EVENTL);
        
        // Read last read point
        $lastread = WcTime::handleLastRead('read', 'events_');

        // Parse event messages if modified and is no new room visit
        if($lastmod > $lastread && WcPgc::myGet('all') != 'ALL') {
       
            // Store a new read point
            WcTime::handleLastRead('store', 'events_');

            // Parse/retrieve other user's messages if they exist
            if(WcUtils::hasData($this->rawEventList)) {
                $lines = explode("\n", trim($this->rawEventList));
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
    public function getNewMsg() {

        if($this->user->isBanned !== FALSE) {
            return 'You are banned!';
        }
        if(!$this->user->hasPermission('READ_MSG', 'skip_msg')) {
            return 'Can\'t display messages.';
        }
        
        $output = '';
        $lastmod = $this->parseLastMod();
        $older_index = WcPgc::myGet('n');
        if(!WcUtils::hasData($older_index)) { $older_index = NULL; }

        $lastread = WcTime::handleLastRead('read');

        // Reload messages if reset_msg tag exists
        if(WcPgc::mySession('reset_msg')) { $_GET['all'] = 'ALL'; }

        // Halt if is no new room visit and lastread is not set (user might not be accepting cookies)
        if(WcPgc::myGet('all') != 'ALL' && !$lastread) { die(); }

        // Store the first lastread to be used on older message loading
        if(WcPgc::myGet('all') == 'ALL' && $lastread) {
            WcPgc::wcSetSession(
                'lastread_arch_' . WcPgc::mySession('current_room'), 
                $lastread
            );
        }

        $lines = array();
        $index = 0;
        
        // Reset archive cached volume if any
        if(
            WcPgc::mySession('archive') && 
            $older_index === NULL && 
            !WcPgc::myGet('loop')
        ) {
            WcPgc::wcUnsetSession('archive');
        }

        // Parse post messages if new messages exist or new room visit or older index exists
        if(
            $lastmod > $lastread || 
            WcPgc::myGet('all') == 'ALL' || 
            $older_index !== NULL
        ) {
            
            // If "load existing messages" is disabled, automatically set a global start point 
            if(!WcPgc::mySession('global_start_point') && LOAD_EX_MSG === FALSE) {
                WcPgc::wcSetSession('global_start_point', time());
            }
            
            WcTime::handleLastRead('store');
            // Set a start point for events the first time messages are read (new chat visit),
            // skip current event buffer content
            if(WcPgc::myGet('new_visit') == '1') {
                WcTime::handleLastRead('store', 'events_');
            }

            // Retrieve post messages
            if(strlen($this->rawMsgList) > 0 || WcPgc::mySession('archive')) {
                if(!WcPgc::mySession('archive')) {
                    $lines = explode("\n", trim($this->rawMsgList));
                } else {
                    // Scan Current Archive volume
                    $archive_vol = intval(WcPgc::mySession('archive'));
                    $archive_target = 
                        WcChat::$roomDir . 'archived/' .
                        base64_encode(WcPgc::mySession('current_room')) . '.' . 
                        ($this->def['lArchVol'] + 1 - $archive_vol)
                    ;
                    $lines = explode("\n", trim(WcFile::readFile($archive_target)));
                }
                
                // Scan next archive volume if oldest chat post is the archive's last
                if(
                    $older_index !== NULL && 
                    strpos(
                        str_replace('*|', '|', $lines[0]), 
                        str_replace('js_', '', $older_index)
                    ) !== FALSE
                ) {
                    
                    $next_archive_vol =  (intval(WcPgc::mySession('archive')) + 1);
                    $next_archive_target = 
                        WcChat::$roomDir . 'archived/' .
                        base64_encode(WcPgc::mySession('current_room')) . '.' . 
                        ($this->def['lArchVol'] + 1 - $next_archive_vol)
                    ;

                    if(file_exists($next_archive_target)) {
                        $lines = explode(
                            "\n", 
                            trim(WcFile::readFile($next_archive_target))
                        );
                        $older_index = 'beginning';
                        WcPgc::wcSetSession('archive', $next_archive_vol);
                    }  
                }
                list($output, $index, $first_elem) =
                    $this->parseMsg($lines, $lastread, 'RETRIEVE', $older_index);
            }
        }

        // If content exists before the oldest displayed message or archives exist, initiate the controls to load older posts
        $older_controls = '';
        if(count($lines) && WcPgc::myGet('all') == 'ALL' && LOAD_EX_MSG === TRUE) {
            if($first_elem) {
                list($tmp1, $tmp2) = explode($first_elem, $this->rawMsgList, 2);
                if(strpos($tmp1, "\n") !== FALSE || $this->def['lArchVol'] > 0) {
                    $older_controls = WcGui::popTemplate('wcchat.posts.older');
                }
            } else {
                // No first element returned but lines exist? Add controls in case of screen cleanup.
                $older_controls = WcGui::popTemplate('wcchat.posts.older');
            }
        }

        // Return output, also remove auto-scroll Javascript tags from retrieved older messages
        if(trim($output)) {
            $output = $older_controls.
            (
                ($older_index !== NULL) ? 
                str_replace(
                    array('wc_scroll(\'\')', 'wc_scroll(\'ALL\')'), 
                    '', 
                    $output . WcGui::popTemplate('wcchat.posts.older.block_separator')
                ) : 
                $output
            );
        }

        // Process RESET tag
        if(WcPgc::mySession('reset_msg')) {
            $output = 'RESET' . $output;
            WcPgc::wcUnsetSession('reset_msg');
        }

        // Delay Older Message/New Room Visit Loading (In order to display the loader image)
        if(($older_index !== NULL) || WcPgc::myGet('all') == 'ALL') { sleep(1); }
        
        return $output;
    }

}

?>