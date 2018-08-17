<?php

// Processes User Edition

    if(!isset($this)) { die(); }

    $output = '';
    $oname = WcPgc::myPost('oname');

    // Halt if target is a Moderator and user is not current nor Master Moderator
    if(!$this->user->isMasterMod && 
        $this->user->getMod($oname) !== FALSE && 
        $this->user->name != $oname && 
        trim($oname, ' ')
    ) {
        echo 'You cannot edit other moderator!';
        die();
    }
    
    // Halt if target user is invalid (happens if the user was renamed)
    if($this->user->match($oname) === FALSE) {
        echo 'Invalid Target User (If you just renamed the user, close the form and retry after the name update)!';
        die();
    } 
    $udata = $this->user->getData($oname);
    
    // Process moderator request if target user is not a moderator already
    if(WcPgc::myPost('moderator') && $this->user->getMod($oname) === FALSE) {
        if($this->user->hasPermission('MOD', 'skip_msg')) {
        
            // User must have a password in order to be assigned as moderator
            if($udata['pass']) {
                WcFile::writeFile(MODL, "\n" . base64_encode($oname), 'a');
                $output .= '- Successfully set ' . $oname . ' as moderator.' . "\n";
            } else {
                $output .= '- Cannot set ' . $oname . ' as moderator: Not using a password.MOD_OFF' . "\n";
            }
        }
    }
    
    // Process un-moderator request if target user is a moderator
    if(!WcPgc::myPost('moderator') && $this->user->getMod($oname) !== FALSE) {
        if($this->user->hasPermission('UNMOD', 'skip_msg')) {
            if($oname && $this->user->getMod($oname) !== FALSE) {
                WcFile::writeFile(
                    MODL, 
                    str_replace("\n" . base64_encode($oname), '', $this->user->modList), 
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
    if(WcPgc::myPost('muted') && $this->user->getMuted($oname) === FALSE) {
        if($this->user->hasPermission('MUTE', 'skip_msg')) {
            $xpar = '';
            
            // Parse requested mute time if any
            if(WcPgc::myPost('muted_time')) {
            
                if(ctype_digit(WcPgc::myPost('muted_time'))) {
                    $xpar = ' ' . (time() + (60 * abs(intval(WcPgc::myPost('muted_time')))));
                }
            } else { 
                $xpar = ' 0';
            }
            
            WcFile::writeFile(
                MUTEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode($oname) . ' ([0-9]+)/', 
                    '', 
                    $this->user->mutedList
                ) . "\n" . base64_encode($oname) . $xpar,
                'w'
            );
            $output .= '- Successfully muted ' . $oname . 
                (
                    WcPgc::myPost('muted_time') ? 
                    ' for ' . abs(intval(WcPgc::myPost('muted_time'))) . ' minute(s)' :
                     ''
                ) . '.' . "\n"
            ;
        }
    }
    
    // Process unmute request if target user is muted
    if(!WcPgc::myPost('muted') && $this->user->getMuted($oname) !== FALSE) {
        if($this->user->hasPermission('UNMUTE', 'skip_msg')) {
            WcFile::writeFile(
                MUTEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode(trim($oname)) . ' ([0-9]+)/', 
                    '', 
                    $this->user->mutedList
                ),
                'w', 
                'allow_empty'
            );

            $output .= '- Successfully unmuted ' . $oname . "\n";
        }
    }
  
    // Process ban request if target user is not banned already
    if(WcPgc::myPost('banned') && $this->user->getBanned($oname) === FALSE) {
        if($this->user->hasPermission('BAN', 'skip_msg')) {
            
            // Parse requested ban time if any
            $xpar = '';
            if(WcPgc::myPost('banned_time')) {                       
                if(ctype_digit(WcPgc::myPost('banned_time'))) {
                    $xpar = ' '.(time() + (60 * abs(intval(WcPgc::myPost('banned_time')))));
                }
            } else { $xpar = ' 0'; }
            
            WcFile::writeFile(
                BANNEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode($oname) . ' ([0-9]+)/', 
                    '', 
                    $this->user->bannedList
                ) . "\n" . base64_encode($oname) . $xpar, 
                'w'
            );
            $output .= '- Successfully banned ' . $oname . 
                (
                    WcPgc::myPost('banned_time') ? 
                    ' for ' . abs(intval(WcPgc::myPost('banned_time'))) . ' minute(s)' : 
                    ''
                ) . '.' . "\n"
            ;
        }
    }
    
    // Process unban request if target user is banned
    if(!WcPgc::myPost('banned') && $this->user->getBanned($oname) !== FALSE) {
        if($this->user->hasPermission('UNBAN', 'skip_msg')) {
            WcFile::writeFile(
                BANNEDL, 
                preg_replace(
                    '/' . "\n" . base64_encode($oname) . ' ([0-9]+)/', 
                    '', 
                    $this->user->bannedList
                ), 
                'w', 
                'allow_empty'
            );
            $output .= '- Successfully unbanned '.$oname."\n";
        }
    }

    $changes = 0; $name_err = FALSE;
    if($this->user->hasPermission('USER_E', 'skip_msg')) {
        if(WcPgc::myPost('name') != $oname) {
        
            // Check if new name already exists
            if($this->user->match(WcPgc::myPost('name')) !== FALSE) {
                $output .= '- Cannot rename: '.WcPgc::myPost('name').' already exists!';
                $name_err = TRUE;
            // Check if name is valid
            } elseif(
                strlen(trim(WcPgc::myPost('name'), ' ')) == 0 || 
                strlen(trim(WcPgc::myPost('name'))) > 30 || 
                preg_match("/[\?<>\$\{\}\"\: ]/i", WcPgc::myPost('name'))
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
        if(WcPgc::myPost('web') != $udata['web']) {
            if(filter_var(WcPgc::myPost('web'), FILTER_VALIDATE_URL)) {
                $udata['web'] = WcPgc::myPost('web');
                $changes = 1;
                $output .= '- Web Address Successfully set!' . "\n";
            } else {
                $output .= '- Invalid Web Address!';
            }                        
        }

        // Process email address request
        if(WcPgc::myPost('email') != $udata['email']) {
            if(filter_var(WcPgc::myPost('email'), FILTER_VALIDATE_EMAIL)) {
                $udata['email'] = WcPgc::myPost('email');
                $changes = 1;
                $output .= '- Email Address Successfully set!' . "\n";
            } else {
                $output .= '- Invalid Web Address!';
            }
        }

        // Process reset avatar request
        if(WcPgc::myPost('reset_avatar') && $udata['avatar']) {
            $udata['avatar'] = '';
            $changes = 1;
            $output .= '- Avatar Successfully Reset!' . "\n";
        }

        // Process reset password request
        if(WcPgc::myPost('reset_pass') && $udata['pass']) {
            $udata['pass'] = '';
            $changes = 1;
            $output .= '- Password Successfully Reset!' . "\n";
        }

        // Process password regenerate request
        if(WcPgc::myPost('regen_pass')) {
            $npass = WcUtils::randNumb(8);
            $udata['pass'] = md5(md5($npass));
            $changes = 1;
            $output .= '- Password Successfully Re-generated: ' . $npass . "\n";
        }

        if($changes) {
            $nstring = $this->user->parseDataString(
                NULL,
                $udata
            );
            
            // If name is the same or name error exists, write with the name unchanged
            if(WcPgc::myPost('name') == $oname || $name_err) {
                $towrite = preg_replace(
                    '/^(' . base64_encode($oname) . ')\|(.*?)\|/m', 
                    '\\1|' . $nstring . '|', 
                    $this->user->rawList
                );
                
            // If not, write with the new name
            } elseif(trim(WcPgc::myPost('name'), ' ')) {
                $towrite = preg_replace(
                    '/^(' . base64_encode($oname) . ')\|(.*?)\|/m', 
                    base64_encode(WcPgc::myPost('name')) . '|' . base64_encode($nstring) . '|', 
                    $this->user->rawList
                );
                
                // Update the moderator, mute and ban lists
                if($this->user->getMod($oname) !== FALSE) {
                    WcFile::writeFile(
                        MODL, 
                        preg_replace(
                            '/^(' . base64_encode($oname) . ')$/m', 
                            base64_encode(WcPgc::myPost('name')), 
                            $this->user->modList
                        ), 
                        'w'
                    );
                }
                
                if($this->user->getMuted($oname) !== FALSE) {
                    WcFile::writeFile(
                        MUTEDL, 
                        preg_replace(
                            '/^' . base64_encode($oname) . ' ([0-9]+)$/m', 
                            base64_encode(WcPgc::myPost('name')) . ' \\1', 
                            $this->user->mutedList
                        ), 
                        'w'
                    );
                }
                
                if($this->user->getBanned($oname) !== FALSE) {
                    WcFile::writeFile(
                        BANNEDL, 
                        preg_replace(
                            '/^' . base64_encode($oname) . ' ([0-9]+)$/m', 
                            base64_encode(WcPgc::myPost('name')) . ' \\1',
                            $this->user->bannedList
                        ), 
                        'w'
                    );
                }
                
                // Rename pm room files
                $users = explode("\n", trim(WcFile::readFile(USERL)));
                foreach($users as $k => $v) {
                    list($user_name, $tmp) = explode('|', trim($v), 2);
                    if($user_name != base64_encode(WcPgc::myPost('name'))) {
                    
                        $old_pm_room_name = $this->room->parseConvName(
                            $oname, 
                            base64_decode($user_name)
                        );
                        $pm_room_name = $this->room->parseConvName(
                            WcPgc::myPost('name'), 
                            base64_decode($user_name)
                        );    
                        
                        if(file_exists(
                                self::$roomDir . 
                                base64_encode($old_pm_room_name) . '.txt'
                        )) {
                            rename(
                                self::$roomDir . 
                                base64_encode($old_pm_room_name) . '.txt',
                                self::$roomDir . 
                                base64_encode($pm_room_name) . '.txt'
                            );
                        }
                        
                        foreach(
                            glob(
                                self::$roomDir . 
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
                
                $pm_room_name_self = $this->room->parseConvName(
                    $this->user->name, 
                    $oname
                );
                
                if(WcPgc::mySession('current_room') == $pm_room_name_self) {
                    WcPgc::wcSetSession('current_room', $pm_room_name_self);
                    WcPgc::wcSetCookie('current_room', $pm_room_name_self);    
                }
                touch(ROOMS_LASTMOD);
            }

            WcFile::writeFile(USERL, $towrite, 'w');
        }
    }
    echo trim($output);

?>