<?php

// Hides/Unhides a Posted Message

    if(!isset($this)) { die(); }

    $id = WcPgc::myGet('id');
    $id_hidden = str_replace('|', '*|', $id);
    $id_source = WcPgc::myGet('id_source');
    $id_source_hidden = str_replace('|', '*|', $id_source);
    
    $action = $output = '';
    $hard_mode = $soft_mode = FALSE;
    $cookie_name = 'hide_' . $id;
    
    $down_perm = ($this->user->hasPermission('ATTACH_DOWN', 'skip_msg') ? TRUE : FALSE);

    // If message has not been hidden by a moderator, hide it (or allow soft hide)
    if(strpos($this->room->rawMsgList, $id . '|') !== FALSE) {
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
                    '/^' . preg_quote($id_source) . '\|(.*)$/im', 
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
                echo WcGui::popTemplate('wcchat.posts.hidden', array('ID' => $id));
            }
            $soft_mode = TRUE;
        } else {
            // If cookie exists, ignore hard mode, undo soft mode first
            if(WcPgc::myCookie($cookie_name, 'BOOL')) {
                WcPgc::wcUnsetCookie($cookie_name);
                preg_match_all(
                    '/^' . preg_quote($id_source) . '\|(.*)$/im', 
                    $this->room->rawMsgList,
                    $matches
                );
                $output = WcGui::parseBbcode(
                    $matches[1][0],
                    $down_perm,
                    $this->user->name
                );
            } else {
                // If message is private, it's not necessary to hide to all users
                if(WcPgc::myGet('private') == '1') {
                    echo 'ERROR: This message can only be seen by 1 user, not necessary to hide to all users.'; die();
                }
                WcFile::writeFile(
                        MESSAGES_LOC, 
                        str_replace($id_source, $id_source_hidden, $this->room->rawMsgList), 
                        'w'
                );
                $this->room->updateCurrLastMod();
                $hard_mode = TRUE;
                $output = WcGui::popTemplate('wcchat.posts.hidden_mod', array('ID' => $id));
            }
        }

    }

    // If message has been hidden by a moderator, unhide it if current user is a moderator under edit mode
    if(strpos($this->room->rawMsgList, $id_hidden . '|') !== FALSE) {
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
            '/^' . preg_quote($id_source_hidden) . '\|(.*)$/im',
            $this->room->rawMsgList,
            $matches
        );
        WcFile::writeFile(
            MESSAGES_LOC, 
            str_replace($id_source_hidden, $id_source, $this->room->rawMsgList), 
            'w'
        );
        $this->room->updateCurrLastMod();
        $output = WcGui::parseBbcode(
            $matches[1][0],
            $down_perm,
            $this->user->name
        );
    }

    if($hard_mode === TRUE) {
        // Write message id, replace old value if exists
        if(strpos($this->room->rawUpdatedMsgList, $id) === FALSE && $action == 'hide') {
            WcFile::writeFile(
                MESSAGES_UPDATED, 
                ' ' . $id, 
                'a'
            );
        }
        if(strpos($this->room->rawUpdatedMsgList, $id) !== FALSE && $action == 'unhide') {
            WcFile::writeFile(
                MESSAGES_UPDATED, 
                str_replace(' ' . $id, '', $this->room->rawUpdatedMsgList), 
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