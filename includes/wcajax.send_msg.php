<?php

// Processes/Writes a post message, also handles ignore action

    if(!isset($this)) { die(); }

    // Halt if no permission to post or no profile access
    if(!$this->user->hasPermission('POST')) { die(); }
    if(!$this->user->hasProfileAccess) {
        echo 'Account Access Denied!';
        die();
    }

    if(strlen(WcPgc::myGet('t')) > 0) {

        // Is ignore request?
        if(preg_match('#^(/ignore|/unignore)#i', WcPgc::myGet('t'))) {

            // Yes, is ignore request, does user has permission to ignore?
            if(!$this->user->hasPermission('IGNORE')) { die(); }

            // Yes, has permission, process request
            $par = explode(' ', trim(WcPgc::myGet('t')), 2);
            switch($par[0]) {
                case '/ignore':
                    if($this->user->match($par[1]) !== FALSE) {
                        WcFile::writeEvent(
					$this->user->name,
					'is ignoring you.', 
					$par[1]
				);
                        WcPgc::wcSetCookie(
                            'ign_' . base64_encode($par[1]), 
                            '1'
                        );
                        echo 'Successfully ignored ' . $par[1];
                    } else {
                        echo 'User ' . $par1 . ' does not exist.';
                    }
                break;
                
                case '/unignore':
                    if(
                        $this->user->match($par[1]) !== FALSE && 
                        WcPgc::myCookie('ign_' . base64_encode($par[1]))
                    ) {
                        WcFile::writeEvent(
					$this->user->name,
					'is no longer ignoring you.',
					$par[1]
				);
                        WcPgc::wcUnsetCookie(
                            'ign_' . base64_encode($par[1])
                        );
                        echo 'Successfully unignored ' . $par[1];
                    } else {
                        echo 'User ' . $par[1] . ' does not exist / Not being ignored.';
                    }
                break;
            }
            
        } else {

            // No ignore, normal post

            // Halt if banned or muted
            $muted_msg = $banned_msg = '';
            if($this->user->isMuted !== FALSE && $this->user->name) {
                $muted_msg = 'You have been set as mute'.
                    (
                        $this->user->isMuted ? 
                        ' (' . WcTime::parseIdle($this->user->isMuted, 1) . ' remaining)' : 
                        ''
                    ) . ', you cannot talk for the time being!'
                ;
            }
            
            if($this->user->isBanned !== FALSE) {
                $banned_msg = 'You are banned' . 
                (
                    intval($this->user->isBanned) ? 
                    ' (' . intval(($this->user->isBanned - time())) . ' seconds remaining)' : 
                    ''
                ) . '!';
            }
            
            if(!$muted_msg && !$banned_msg) {

                // Not banned or muted, has permission?
                if(!$this->room->hasPermission(WcPgc::mySession('current_room'), 'W')) {
                    echo 'You don\'t have permission to post messages here!'; die();
                }

                // Check if last post is within anti-spam limit
                if((time() - intval(WcPgc::mySession('lastpost'))) < ANTI_SPAM) {
                    echo 'Anti Spam: Please allow ' . ANTI_SPAM . 's between posted messages!';
                    die();
                }
                $name_prefix = '';
                $text = WcPgc::myGet('t');
                
                if($this->room->isConv) {
                    $target = $this->room->getConvTarget(WcPgc::mySession('current_room'));
                    $target_data = $this->user->getData($target);
                    if($target_data['status'] == 2) {
                        echo 'User does not want to be disturbed at the moment!';
                        die();
                    }
                }

                // Tag private message
                if(preg_match('#^(/me )#i', $text)) {
                    $name_prefix = '*';
                    $text = str_replace('/me ', '', $text);
                }

                // Pre-process private message
                if(preg_match('#^(/pm )#i', $text)) {
                
                    if($this->room->isConv) {
                        echo 'You are already on a private message room, no need for the /pm command!';
                        die();
                    }
                
                    if(!$this->user->hasPermission('PM_SEND')) { die(); }
                    
                    $target = $ntext = '';
                    if(
                        strpos(
                            str_replace(
                                '/pm ', 
                                '', 
                                $text
                            ), ' '
                        ) !== FALSE
                    ) {
                        list($target, $ntext) = explode(
                            ' ', 
                            str_replace('/pm ', '', $text), 
                            2 
                        );
                    }
                    
                    if(strlen(trim($ntext)) > 0 && strlen(trim($target)) > 0) {
                        $target = trim($target, ' ');
                        
                        if($this->user->match($target) === FALSE) {
                            echo 'User ' . $target . ' does not exist!';
                            die();
                        } else {
                            $user_status = $this->user->getData($target);
                            if(
                                (time() - $this->user->getPing($target)) <= self::$catchWindow && 
                                $user_status['status'] == 2
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

                // Check if posts contains images / videos, generate thumbnail bbcode tags
                $text = WcGui::parseBbcodeThumb($text);

                // Check if the new post will overflow the store buffer, if yes, delete the oldest line (1st line)
                $source = $this->room->rawMsgList;
                if(substr_count($source, "\n") >= CHAT_STORE_BUFFER) {
                    list($v1, $v2) = explode("\n", $source, 2);
                    $source = $v2;
                    
                    // Arquive discarded message if enabled
                    if(ARCHIVE_MSG === TRUE) {
                        if($this->room->def['lArchVol'] == 0) { $this->room->def['lArchVol'] = 1; }
                        $archive = str_replace(
                            '.txt', 
                            '.' . $this->room->def['lArchVol'], 
                            str_replace(basename(
                                MESSAGES_LOC), 
                                'archived/' . basename(MESSAGES_LOC),
                                MESSAGES_LOC
                            )
                        );
                        
                        // Check if current arquive is full, if yes, start a new arquive
                        if(($this->room->def['lArchVolMsgN'] + 1) > CHAT_STORE_BUFFER) {
                            $this->room->def['lArchVol']++;
                            $this->room->def['lArchVolMsgN'] = 1;
                            $archive = str_replace(
                                '.txt', 
                                '.' . $this->room->def['lArchVol'], 
                                str_replace(basename(
                                    MESSAGES_LOC), 
                                    'archived/' . basename(MESSAGES_LOC),
                                    MESSAGES_LOC
                                )
                            );
                            WcFile::writeFile(
                                ROOM_DEF_LOC, 
                                $this->room->parseDefString(NULL, $this->room->def),
                                'w'
                            );    
                        } else {
                            $this->room->def['lArchVolMsgN']++;
                            WcFile::writeFile(
                                ROOM_DEF_LOC,
                                $this->room->parseDefString(NULL, $this->room->def),
                                'w'
                            );
                        }
                        WcFile::writeFile($archive, $v1."\n", 'a');
                    }
                }

                // Write and return the post parsed as html code
                $towrite = 
                    WcTime::parseMicroTime() . '|' . 
                    $name_prefix . base64_encode($this->user->name) . '|' . 
                    str_replace("\n", '<br>', strip_tags($text)) . 
                    "\n"
                ;
                WcFile::writeFile(MESSAGES_LOC, $source . $towrite, 'w');
                $this->room->updateCurrLastMod();
                touch(ROOMS_LASTMOD);
                list($output, $index, $first_elem) = $this->room->parseMsg(
                    array(trim($towrite)), 
                    0, 
                    'SEND'
                );
                WcPgc::wcSetSession('lastpost', time());
                echo $output;
            } else {
                echo ($banned_msg ? $banned_msg : $muted_msg);
            }
        }
    }

?>