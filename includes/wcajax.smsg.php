<?php

// Processes/Writes a post message, also handles ignore action

    // Halt if no permission to post or no profile access
    if(!$this->hasPermission('POST')) { die(); }
    if(!$this->hasProfileAccess) {
        echo 'Account Access Denied!';
        die();
    }

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
                        $this->wcSetCookie(
                            'ign_' . $par[1], 
                            '1'
                        );
                        echo 'Successfully ignored ' . $par[1];
                    } else {
                        echo 'User ' . $par1 . ' does not exist.';
                    }
                break;
                
                case '/unignore':
                    if(
                        $this->userMatch($par[1]) !== FALSE && 
                        $this->myCookie('ign_' . base64_encode($par[1]))
                    ) {
                        $this->writeEvent('unignore', $par[1]);
                        $this->wcSetCookie(
                            'ign_' . $par[1], 
                            ''
                        );
                        echo 'Successfully unignored ' . $par[1];
                    } else {
                        echo 'User ' . $par1 . ' does not exist / Not being ignored.';
                    }
                break;
            }
            
        } else {

            // No ignore, normal post

            // Halt if banned or muted
            $muted_msg = $banned_msg = '';
            $muted = $this->getMuted($this->name);
            if($muted !== FALSE && $this->name) {
                $muted_msg = 'You have been set as mute'.
                    (
                        $muted ? 
                        ' (' . $this->parseIdle($muted, 1) . ' remaining)' : 
                        ''
                    ) . ', you cannot talk for the time being!'
                ;
            }
            
            if($this->isBanned !== FALSE) {
                $banned_msg = 'You are banned' . 
                (
                    intval($this->isBanned) ? 
                    ' (' . intval(($this->isBanned - time())) . ' seconds remaining)' : 
                    ''
                ) . '!';
            }
            
            if(!$muted_msg && !$banned_msg) {

                // Not banned or muted, has permission?
                if(!$this->hasRoomPermission($this->mySession('current_room'), 'W')) {
                    echo 'You don\'t have permission to post messages here!'; die();
                }

                // Check if last post is within anti-spam limit
                if((time() - $this->mySession('lastpost')) < ANTI_SPAM) {
                    echo 'Anti Spam: Please allow ' . ANTI_SPAM . 's between posted messages!';
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
                    list($target, $ntext) = explode(
                        ' ', 
                        str_replace('/pm ', '', $text), 
                        2
                    );
                    
                    if(strlen(trim($ntext)) > 0 && strlen(trim($target)) > 0) {
                        $target = trim($target, ' ');
                        
                        if($this->userMatch($target) === FALSE) {
                            echo 'User ' . $target . ' does not exist!';
                            die();
                        } else {
                            $user_status = $this->userData($target);
                            if(
                                (time() - $this->getPing($target)) <= $this->catchWindow && 
                                $user_status[8] == 2
                            ) {
                                echo 'User ' . $target . ' does not want to be disturbed at the moment!';
                                die();
                            }
                        }
                    } else {
                        echo 'Invalid Private Message Syntax ("/pm <user> <message>")';
                        die();
                    }
                    
                    $name_prefix = base64_encode($target) . '-';
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
                        if($this->roomLArchVol == 0) { $this->roomLArchVol = 1; }
                        $archive = str_replace(
                            '.txt', 
                            '.' . $this->roomLArchVol, 
                            MESSAGES_LOC
                        );
                        
                        // Check if current arquive is full, if yes, start a new arquive
                        if(($this->roomLArchVolMsgN + 1) > CHAT_STORE_BUFFER) {
                            $this->roomLArchVol++;
                            $this->roomLArchVolMsgN = 1;
                            $archive = str_replace(
                                '.txt', 
                                '.' . $this->roomLArchVol, 
                                MESSAGES_LOC
                            );
                            $this->writeFile(
                                ROOM_DEF_LOC, 
                                $this->roomWPerm . '|' . 
                                $this->roomRPerm . '|' . 
                                $this->roomLArchVol . '|' . 
                                $this->roomLArchVolMsgN . '|' . 
                                '',
                                'w'
                            );    
                        } else {
                            $this->roomLArchVolMsgN++;
                            $this->writeFile(
                                ROOM_DEF_LOC, 
                                $this->roomWPerm . '|' . 
                                $this->roomRPerm . '|' . 
                                $this->roomLArchVol . '|' . 
                                $this->roomLArchVolMsgN . '|' . 
                                '',
                                'w'
                            );
                        }
                        $this->writeFile($archive, $v1."\n", 'a');
                    }
                }

                // Write and return the post parsed as html code
                $towrite = 
                    $this->parseMicroTime() . '|' . 
                    $name_prefix . base64_encode($this->name) . '|' . 
                    strip_tags($text) . 
                    "\n"
                ;
                $this->writeFile(MESSAGES_LOC, $source . $towrite, 'w');
                $this->updateCurrRoomLastMod();
                touch(ROOMS_LASTMOD);
                list($output, $index, $first_elem) = $this->parseMsg(
                    array(trim($towrite)), 
                    0, 
                    'SEND'
                );
                $this->wcSetSession('lastpost', time());
                echo $output;
            } else {
                echo ($banned_msg ? $banned_msg : $muted_msg);
            }
        }
    }

?>