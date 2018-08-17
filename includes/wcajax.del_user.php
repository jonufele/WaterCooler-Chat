<?php

    if(!isset($this)) { die(); }

    $output = '';
    $oname = base64_decode(WcPgc::myGet('id'));
    
    // Halt if target is a Moderator and user is not current nor Master Moderator
    if(
        !$this->user->isMasterMod && 
        $this->user->getMod($oname) !== FALSE && 
        $this->user->name != $oname && 
        trim($oname, ' ')
    ) {
        echo 'You cannot delete other moderator!';
        die();
    }

    if($this->user->name == $oname) {
        echo 'You cannot delete your own account';
        die();
    }
    
    // Halt if target user is invalid (happens if the user was renamed)
    if($this->user->match($oname) === FALSE) {
        echo 'Invalid target user (If you just renamed the user, close the form and retry after the name update)!';
        die();
    }
    
    // Check for delete permission
    if($this->user->hasPermission('USER_D', 'skip_msg')) {
        $users = $users2 = explode("\n", trim($this->user->rawList));
        foreach($users as $k => $v) {
            if(
                strpos(
                    "\n" . $v, 
                    "\n" . base64_encode($oname) . '|'
                ) !== FALSE
            ) {
                WcFile::writeFile(
                    USERL, 
                    trim(str_replace(
                        "\n\n",
                        "\n",
                        str_replace(
                            $v,
                            '',
                            $this->user->rawList
                        )
                    )), 
                    'w'
                );
                echo 'RMVUser '.$oname.' has been successfully removed!';
                
                // Delete pm room files
                foreach($users2 as $k2 => $v2) {
                    list($user_name, $tmp) = explode('|', trim($v2), 2);
                    if($user_name != base64_encode($oname)) {
                        $pm_room_name = $this->room->parseConvName(
                            $oname, 
                            base64_decode($user_name)
                        );
                        
                        if(file_exists(
                                self::$roomDir . 
                                base64_encode($pm_room_name) . '.txt'
                        )) {
                            unlink(
                                self::$roomDir . 
                                base64_encode($pm_room_name) . '.txt'
                            );
                        }
                        
                        foreach(
                            glob(
                                self::$roomDir . 
                                '*_' . base64_encode($pm_room_name) . '.txt'
                            ) as $file
                        ) {
                            unlink($file);
                        }
                    }
                }
                break;    
            }
        }
        
        // Check if current room needs to be updated
        $pm_room_name_self = $this->room->parseConvName(
            $this->user->name, 
            $oname
        );
                        
        if(WcPgc::mySession('current_room') == $pm_room_name_self) {
            WcPgc::wcSetSession('current_room', DEFAULT_ROOM);
            WcPgc::wcSetCookie('current_room', DEFAULT_ROOM);    
        }
        touch(ROOMS_LASTMOD);
        
    } else {
        echo 'You do not have permission to delete users!';
        die();
    }

?>