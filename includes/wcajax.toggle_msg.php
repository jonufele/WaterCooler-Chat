<?php

// Hides/Unhides a Posted Message

    if(!isset($this)) { die(); }

    $id = $this->myGet('id');
    $id_hidden = str_replace('|', '*|', $id);
    $action = $output = '';
    $hard_mode = $soft_mode = FALSE;
    $cookie_name = 'hide_' . $id;

    // If message has not been hidden by a moderator, hide it (or allow soft hide)
    if(strpos($this->msgList, $id) !== FALSE) {
        $action = 'hide';
        // Check if action is being performed by: 
        // 1 - a normal user/moderator outside edit mode (soft hide, cookie only)
        // 2 - a moderator under edit mode (hard hide, all users)
        if(!$this->hasPermission('MSG_HIDE', 'skip_msg') || $this->mycookie('hide_edit') == 1) {
            if($this->myCookie($cookie_name, 'BOOL')) {
                $this->wcUnsetCookie($cookie_name);
                preg_match_all(
                    '/^' . preg_quote($id) . '\|(.*)$/im', 
                    $this->msgList,
                    $matches
                );
                $output = $this->parseBbcode($matches[1][0]);
            } else { 
                $this->wcSetCookie($cookie_name, 1);
                echo $this->popTemplate('wcchat.posts.hidden');
            }
            $soft_mode = TRUE;
        } else {
            // If cookie exists, ignore hard mode, undo soft mode first
            if($this->myCookie($cookie_name, 'BOOL')) {
                $this->wcUnsetCookie($cookie_name);
                preg_match_all(
                    '/^' . preg_quote($id) . '\|(.*)$/im', 
                    $this->msgList,
                    $matches
                );
                $output = $this->parseBbcode($matches[1][0]);
            } else {
                $this->writeFile(
                        MESSAGES_LOC, 
                        str_replace($id, $id_hidden, $this->msgList), 
                        'w'
                );
                $this->updateCurrRoomLastMod();
                $hard_mode = TRUE;
                $output = $this->popTemplate('wcchat.posts.hidden_mod');
            }
        }

    }

    // If message has been hidden by a moderator, unhide it if current user is a moderator under edit mode
    if(strpos($this->msgList, $id_hidden) !== FALSE) {
        if(!$this->hasPermission('MSG_UNHIDE', 'skip_msg') || $this->mycookie('hide_edit') == 1) {
            echo ($this->isMod ? 
                'ERROR: This message can only be unhidden under edit mode!' : 
                'ERROR: This message was hidden by a moderator, cannot be unhidden!'
            );
            die();
        }
        $action = 'unhide'; $hard_mode = TRUE;
        preg_match_all(
            '/^' . preg_quote($id_hidden) . '\|(.*)$/im',
            $this->msgList,
            $matches
        );
        $this->writeFile(
            MESSAGES_LOC, 
            str_replace($id_hidden, $id, $this->msgList), 
            'w'
        );
        $this->updateCurrRoomLastMod();
        $output = $this->parseBbcode($matches[1][0]);
    }

    if($hard_mode) {
        // Write message id, replace old value if exists
        if(strpos($this->hiddenMsgList, $id) === FALSE && $action == 'hide') {
            $this->writeFile(
                MESSAGES_HIDDEN, 
                ' ' . $id, 
                (
                    ((time() - $this->parseFileMTime(MESSAGES_HIDDEN)) > $this->catchWindow) ? 
                    'w' : 
                    'a'
                )
            );
        }
        if(strpos($this->hiddenMsgList, $id) !== FALSE && $action == 'unhide') {
            $this->writeFile(
                MESSAGES_HIDDEN, 
                str_replace(' ' . $id, '', $this->hiddenMsgList), 
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