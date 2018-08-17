<?php

// Hides/Unhides a Posted Message

    if(!isset($this)) { die(); }

    $id = WcPgc::myGet('id');
    $id_hidden = str_replace('|', '*|', $id);
    $action = $output = '';
    $hard_mode = $soft_mode = FALSE;
    $cookie_name = 'hide_' . $id;
    
    $down_perm = ($this->user->hasPermission('ATTACH_DOWN', 'skip_msg') ? TRUE : FALSE);

    // If message has not been hidden by a moderator, hide it (or allow soft hide)
    if(strpos($this->room->rawMsgList, $id) !== FALSE) {
        $action = 'hide';
        // Check if action is being performed by: 
        // 1 - a normal user/moderator outside edit mode (soft hide, cookie only)
        // 2 - a moderator under edit mode (hard hide, all users)
        if(!$this->user->hasPermission('MSG_HIDE', 'skip_msg') || 
            WcPgc::myCookie('hide_edit') == 1
        ) {
            if(WcPgc::myCookie($cookie_name, 'BOOL')) {
                WcPgc::wcUnsetCookie($cookie_name);
                preg_match_all(
                    '/^' . preg_quote($id) . '\|(.*)$/im', 
                    $this->room->rawMsgList,
                    $matches
                );
                $output = WcGui::parseBbcode(
                    $matches[1][0],
                    $down_perm,
                    $this->user->name
                );
            } else { 
                WcPgc::wcSetCookie($cookie_name, 1);
                echo WcGui::popTemplate('wcchat.posts.hidden');
            }
            $soft_mode = TRUE;
        } else {
            // If cookie exists, ignore hard mode, undo soft mode first
            if(WcPgc::myCookie($cookie_name, 'BOOL')) {
                WcPgc::wcUnsetCookie($cookie_name);
                preg_match_all(
                    '/^' . preg_quote($id) . '\|(.*)$/im', 
                    $this->room->rawMsgList,
                    $matches
                );
                $output = WcGui::parseBbcode(
                    $matches[1][0],
                    $down_perm,
                    $this->user->name
                );
            } else {
                WcFile::writeFile(
                        MESSAGES_LOC, 
                        str_replace($id, $id_hidden, $this->room->rawMsgList), 
                        'w'
                );
                $this->room->updateCurrLastMod();
                $hard_mode = TRUE;
                $output = WcGui::popTemplate('wcchat.posts.hidden_mod');
            }
        }

    }

    // If message has been hidden by a moderator, unhide it if current user is a moderator under edit mode
    if(strpos($this->room->rawMsgList, $id_hidden) !== FALSE) {
        if(!$this->user->hasPermission('MSG_UNHIDE', 'skip_msg') || 
            WcPgc::myCookie('hide_edit') == 1
        ) {
            echo ($this->user->isMod ? 
                'ERROR: This message can only be unhidden under edit mode!' : 
                'ERROR: This message was hidden by a moderator, cannot be unhidden!'
            );
            die();
        }
        $action = 'unhide'; $hard_mode = TRUE;
        preg_match_all(
            '/^' . preg_quote($id_hidden) . '\|(.*)$/im',
            $this->room->rawMsgList,
            $matches
        );
        WcFile::writeFile(
            MESSAGES_LOC, 
            str_replace($id_hidden, $id, $this->room->rawMsgList), 
            'w'
        );
        $this->room->updateCurrLastMod();
        $output = WcGui::parseBbcode(
            $matches[1][0],
            $down_perm,
            $this->user->name
        );
    }

    if($hard_mode) {
        // Write message id, replace old value if exists
        if(strpos($this->room->rawHiddenMsgList, $id) === FALSE && $action == 'hide') {
            WcFile::writeFile(
                MESSAGES_HIDDEN, 
                ' ' . $id, 
                (
                    ((time() - WcTime::parseFileMTime(MESSAGES_HIDDEN)) > self::$catchWindow) ? 
                    'w' : 
                    'a'
                )
            );
        }
        if(strpos($this->room->rawHiddenMsgList, $id) !== FALSE && $action == 'unhide') {
            WcFile::writeFile(
                MESSAGES_HIDDEN, 
                str_replace(' ' . $id, '', $this->room->rawHiddenMsgList), 
                'w', 
                'allow_empty'
            );
        }
    }
    
    if($output) {
        echo str_replace(
            'wc_scroll()',
            '',
            $output
        );
    }

?>