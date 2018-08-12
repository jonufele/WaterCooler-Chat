<?php

    if(!isset($this)) { die(); }

    $output = '';
    $oname = base64_decode($this->myGET('id'));
    
    // Halt if target is a Moderator and user is not current nor Master Moderator
    if(
        !$this->isMasterMod && 
        $this->getMod($oname) !== FALSE && 
        $this->name != $oname && 
        trim($oname, ' ')
    ) {
        echo 'You cannot delete other moderator!';
        die();
    }

    if($this->name == $oname) {
        echo 'You cannot delete your own account';
        die();
    }
    
    // Halt if target user is invalid (happens if the user was renamed)
    if($this->userMatch($oname) === FALSE) {
        echo 'Invalid target user (If you just renamed the user, close the form and retry after the name update)!';
        die();
    }
    
    // Check for delete permission
    if($this->hasPermission('USER_D', 'skip_msg')) {
        $users = $users2 = explode("\n", trim($this->userList));
        foreach($users as $k => $v) {
            if(
                strpos(
                    "\n" . $v, 
                    "\n" . base64_encode($oname) . '|'
                ) !== FALSE
            ) {
                $this->writeFile(
                    USERL, 
                    trim(str_replace(
                        "\n\n",
                        "\n",
                        str_replace(
                            $v,
                            '',
                            $this->userList
                        )
                    )), 
                    'w'
                );
                echo 'User '.$oname.' has been successfully removed!';
                
                // Delete pm room files
                foreach($users2 as $k2 => $v2) {
                    list($user_name, $tmp) = explode('|', trim($v2), 2);
                    if($user_name != base64_encode($oname)) {
                        $pm_room_name = $this->parsePmRoomName(
                            $oname, 
                            base64_decode($user_name)
                        );
                        
                        if(file_exists(
                                $this->roomDir . 
                                base64_encode($pm_room_name) . '.txt'
                        )) {
                            unlink(
                                $this->roomDir . 
                                base64_encode($pm_room_name) . '.txt'
                            );
                        }
                        
                        foreach(
                            glob(
                                $this->roomDir . 
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
        $pm_room_name_self = $this->parsePmRoomName(
            $this->name, 
            $oname
        );
                        
        if($this->mySession('current_room') == $pm_room_name_self) {
            $this->wcSetSession('current_room', DEFAULT_ROOM);
            $this->wcSetCookie('current_room', DEFAULT_ROOM);    
        }
        touch(ROOMS_LASTMOD);
        
    } else {
        echo 'You do not have permission to delete users!';
        die();
    }

?>