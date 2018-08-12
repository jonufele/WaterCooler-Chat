<?php

// Processes User Edition

    if(!isset($this)) { die(); }

    $output = '';
    $oname = $this->myPost('oname');

    // Halt if target is a Moderator and user is not current nor Master Moderator
    if(!$this->isMasterMod && $this->getMod($oname) !== FALSE && $this->name != $oname && trim($oname, ' ')) {
        echo 'You cannot edit other moderator!';
        die();
    }
    
    // Halt if target user is invalid (happens if the user was renamed)
    if($this->userMatch($oname) === FALSE) {
        echo 'Invalid Target User (If you just renamed the user, close the form and retry after the name update)!';
        die();
    } 
    $udata = $this->userData($oname);
    
    // Process moderator request if target user is not a moderator already
    if($this->myPost('moderator') && $this->getMod($oname) === FALSE) {
        if($this->hasPermission('MOD', 'skip_msg')) {
        
            // User must have a password in order to be assigned as moderator
            if($udata['pass']) {
                $this->writeFile(MODL, "\n" . base64_encode($oname), 'a');
                $output .= '- Successfully set ' . $oname . ' as moderator.' . "\n";
            } else {
                $output .= '- Cannot set ' . $oname . ' as moderator: Not using a password.MOD_OFF' . "\n";
            }
        }
    }
    
    // Process un-moderator request if target user is a moderator
    if(!$this->myPost('moderator') && $this->getMod($oname) !== FALSE) {
        if($this->hasPermission('UNMOD', 'skip_msg')) {
            if($oname && $this->getMod($oname) !== FALSE) {
                $this->writeFile(
                    MODL, 
                    str_replace("\n" . base64_encode($oname), '', $this->modList), 
                    'w', 
                    'allow_empty'
                );
                $output .= '- Successfully removed ' . $oname . ' moderator status.' . "\n";
            } else {
                $output .= '- ' . $oname . ': invalid moderator!' . "\n";
            }
        }
    }
    
    // Process mute request if target user is not muted already
    if($this->myPost('muted') && $this->getMuted($oname) === FALSE) {
        if($this->hasPermission('MUTE', 'skip_msg')) {
            $xpar = '';
            
            // Parse requested mute time if any
            if($this->myPost('muted_time')) {
            
                if(ctype_digit($this->myPost('muted_time'))) {
                    $xpar = ' ' . (time() + (60 * abs(intval($this->myPost('muted_time')))));
                }
            } else { 
                $xpar = ' 0';
            }
            
            $this->writeFile(
                MUTEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode($oname) . ' ([0-9]+)/', 
                    '', 
                    $this->mutedList
                ) . "\n" . base64_encode($oname) . $xpar,
                'w'
            );
            $output .= '- Successfully muted ' . $oname . 
                (
                    $this->myPost('muted_time') ? 
                    ' for ' . abs(intval($this->myPost('muted_time'))) . ' minute(s)' :
                     ''
                ) . '.' . "\n"
            ;
        }
    }
    
    // Process unmute request if target user is muted
    if(!$this->myPost('muted') && $this->getMuted($oname) !== FALSE) {
        if($this->hasPermission('UNMUTE', 'skip_msg')) {
            $this->writeFile(
                MUTEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode(trim($oname)) . ' ([0-9]+)/', 
                    '', 
                    $this->mutedList
                ),
                'w', 
                'allow_empty'
            );

            $output .= '- Successfully unmuted ' . $oname . "\n";
        }
    }
  
    // Process ban request if target user is not banned already
    if($this->myPost('banned') && $this->getBanned($oname) === FALSE) {
        if($this->hasPermission('BAN', 'skip_msg')) {
            
            // Parse requested ban time if any
            $xpar = '';
            if($this->myPost('banned_time')) {                       
                if(ctype_digit($this->myPost('banned_time'))) {
                    $xpar = ' '.(time() + (60 * abs(intval($this->myPost('banned_time')))));
                }
            } else { $xpar = ' 0'; }
            
            $this->writeFile(
                BANNEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode($oname) . ' ([0-9]+)/', 
                    '', 
                    $this->bannedList
                ) . "\n" . base64_encode($oname) . $xpar, 
                'w'
            );
            $output .= '- Successfully banned ' . $oname . 
                (
                    $this->myPost('banned_time') ? 
                    ' for ' . abs(intval($this->myPost('banned_time'))) . ' minute(s)' : 
                    ''
                ) . '.' . "\n"
            ;
        }
    }
    
    // Process unban request if target user is banned
    if(!$this->myPost('banned') && $this->getBanned($oname) !== FALSE) {
        if($this->hasPermission('UNBAN', 'skip_msg')) {
            $this->writeFile(
                BANNEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode($oname) . ' ([0-9]+)/', 
                    '', 
                    $this->bannedList
                ), 
                'w', 
                'allow_empty'
            );
            $output .= '- Successfully unbanned '.$oname."\n";
        }
    }

    $changes = 0; $name_err = FALSE;
    if($this->hasPermission('USER_E', 'skip_msg')) {
        if($this->myPost('name') != $oname) {
        
            // Check if new name already exists
            if($this->userMatch($this->myPost('name')) !== FALSE) {
                $output .= '- Cannot rename: '.$this->myPost('name').' already exists!';
                $name_err = TRUE;
            // Check if name is valid
            } elseif(
                strlen(trim($this->myPost('name'), ' ')) == 0 || 
                strlen(trim($this->myPost('name'))) > 30 || 
                preg_match("/[\?<>\$\{\}\"\: ]/i", $this->myPost('name'))
            ) {
                $output .= '- Invalid Nickname, too long (max = 30) OR 
                    containing invalid characters: ? < > $ { } " : space' . "\n";
                $name_err = TRUE;
                
            } else {
                // all ok, name will be renamed when the file is written
                $changes = 1;
                $output .= '- Name Renamed Successfully!'."\n";
            }
        }
        
        // Process web address request
        if($this->myPost('web') != $udata['web']) {
            if(filter_var($this->myPost('web'), FILTER_VALIDATE_URL)) {
                $udata['web'] = $this->myPost('web');
                $changes = 1;
                $output .= '- Web Address Successfully set!' . "\n";
            } else {
                $output .= '- Invalid Web Address!';
            }                        
        }

        // Process email address request
        if($this->myPost('email') != $udata['email']) {
            if(filter_var($this->myPost('email'), FILTER_VALIDATE_EMAIL)) {
                $udata['email'] = $this->myPost('email');
                $changes = 1;
                $output .= '- Email Address Successfully set!' . "\n";
            } else {
                $output .= '- Invalid Web Address!';
            }
        }

        // Process reset avatar request
        if($this->myPost('reset_avatar') && $udata['avatar']) {
            $udata['avatar'] = '';
            $changes = 1;
            $output .= '- Avatar Successfully Reset!' . "\n";
        }

        // Process reset password request
        if($this->myPost('reset_pass') && $udata['pass']) {
            $udata['pass'] = '';
            $changes = 1;
            $output .= '- Password Successfully Reset!' . "\n";
        }

        // Process password regenerate request
        if($this->myPost('regen_pass')) {
            $npass = $this->randNumb(8);
            $udata['pass'] = md5(md5($npass));
            $changes = 1;
            $output .= '- Password Successfully Re-generated: ' . $npass . "\n";
        }

        if($changes) {
            $nstring = $this->parseUDataString(
                NULL,
                $udata
            );
            
            // If name is the same or name error exists, write with the name unchanged
            if($this->myPost('name') == $oname || $name_err) {
                $towrite = preg_replace(
                    '/^(' . base64_encode($oname) . ')\|(.*?)\|/m', 
                    '\\1|' . $nstring . '|', 
                    $this->userList
                );
                
            // If not, write with the new name
            } elseif(trim($this->myPost('name'), ' ')) {
                $towrite = preg_replace(
                    '/^(' . base64_encode($oname) . ')\|(.*?)\|/m', 
                    base64_encode($this->myPost('name')) . '|' . base64_encode($nstring) . '|', 
                    $this->userList
                );
                
                // Update the moderator, mute and ban lists
                if($this->getMod($oname) !== FALSE) {
                    $this->writeFile(
                        MODL, 
                        preg_replace(
                            '/^(' . base64_encode($oname) . ')$/m', 
                            base64_encode($this->myPost('name')), 
                            $this->modList
                        ), 
                        'w'
                    );
                }
                
                if($this->getMuted($oname) !== FALSE) {
                    $this->writeFile(
                        MUTEDL, 
                        preg_replace(
                            '/^' . base64_encode($oname) . ' ([0-9]+)$/m', 
                            base64_encode($this->myPost('name')) . ' \\1', 
                            $this->mutedList
                        ), 
                        'w'
                    );
                }
                
                if($this->getBanned($oname) !== FALSE) {
                    $this->writeFile(
                        BANNEDL, 
                        preg_replace(
                            '/^' . base64_encode($oname) . ' ([0-9]+)$/m', 
                            base64_encode($this->myPost('name')) . ' \\1',
                            $this->bannedList
                        ), 
                        'w'
                    );
                }
                
                // Rename pm room files
                $users = explode("\n", trim($this->readFile(USERL)));
                foreach($users as $k => $v) {
                    list($user_name, $tmp) = explode('|', trim($v), 2);
                    if($user_name != base64_encode($this->myPost('name'))) {
                    
                        $old_pm_room_name = $this->parsePmRoomName(
                            $oname, 
                            base64_decode($user_name)
                        );
                        $pm_room_name = $this->parsePmRoomName(
                            $this->myPost('name'), 
                            base64_decode($user_name)
                        );    
                        
                        if(file_exists(
                                $this->roomDir . 
                                base64_encode($old_pm_room_name) . '.txt'
                        )) {
                            rename(
                                $this->roomDir . 
                                base64_encode($old_pm_room_name) . '.txt',
                                $this->roomDir . 
                                base64_encode($pm_room_name) . '.txt'
                            );
                        }
                        
                        foreach(
                            glob(
                                $this->roomDir . 
                                '*_' . base64_encode($old_pm_room_name) . '.txt'
                            ) as $file
                        ) {
                            rename(
                                $file,
                                str_replace(
                                    base64_encode($old_pm_room_name),
                                    base64_encode($pm_room_name),
                                    $file
                                )
                            );
                        }
                    }
                }
                
                // Check if current room needs to be updated
                
                $pm_room_name_self = $this->parsePmRoomName(
                    $this->name, 
                    $oname
                );
                
                if($this->mySession('current_room') == $pm_room_name_self) {
                    $this->wcSetSession('current_room', $pm_room_name_self);
                    $this->wcSetCookie('current_room', $pm_room_name_self);    
                }
                touch(ROOMS_LASTMOD);
            }

            $this->writeFile(USERL, $towrite, 'w');
        }
    }
    echo trim($output);

?>