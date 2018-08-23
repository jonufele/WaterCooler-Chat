<?php

// Updates Room Name / Definitions

    if(!isset($this)) { die(); }

    // Halt if no room edit permission
    if(!$this->user->hasPermission('ROOM_E')) { die(); }
    $oname = WcPgc::myPost('oname');
    $nname = WcPgc::myPost('nname');
    $wperm = WcPgc::myPost('perm');
    $rperm = WcPgc::myPost('rperm');
    $enc = base64_encode($oname);
    
    // Get room definitions
    $room_def = $this->room->getDef($oname);
    $changes = 0;
    
    // Update permissions if changed
    if($room_def['wPerm'] != $wperm || $room_def['rPerm'] != $rperm) {
        WcFile::writeFile(
            self::$roomDir . 'def_' . $enc . '.txt',
            $this->room->parseDefString(
                array(
                    'wPerm' => $wperm,
                    'rPerm' => $rperm
                ),
                $room_def
            ),
            'w'
        );
        $changes++;
    }
    
    // Rename room if changed
    if($oname != $nname) {
        if(
            file_exists(self::$roomDir . base64_encode($nname) . '.txt') || 
            !trim($nname, ' ') || 
            preg_match("/[\?<>\$\{\}\"\:\|,;]/i", $nname)
        ) {
            echo 'ERROR: Room ' . $nname . ' already exists OR invalid room name'.
              "\n".
              '(illegal char.: ? < > $ { } " : | , ;)'
            ;
            die();
        }
        
            // Halt if name contains "pm_" (reserved for user pm rooms)
        if(strpos($nname, 'pm_') !== FALSE) {
            echo 'ERROR: Room name cannot contain the string "pm_"';
            die();
        }
        
        rename(
            self::$roomDir . $enc . '.txt', 
            self::$roomDir . base64_encode($nname) . '.txt'
        );
        rename(
            self::$roomDir . 'def_' . $enc . '.txt', 
            self::$roomDir . 'def_' . base64_encode($nname) . '.txt'
        );
        rename(
            self::$roomDir . 'topic_' . $enc . '.txt', 
            self::$roomDir . 'topic_' . base64_encode($nname) . '.txt'
        );
        
        if(file_exists(self::$roomDir . 'updated_' . $enc . '.txt')) {
            rename(
                self::$roomDir . 'updated_' . $enc . '.txt', 
                self::$roomDir . 'updated_' . base64_encode($nname) . '.txt'
            );
        }
        
        // If the renamed room is the default, rename in settings as well
        if(trim($oname) == trim(DEFAULT_ROOM)) {
            file_put_contents(
                self::$includeDirServer . 'settings.php',
                str_replace(
                    "'DEFAULT_ROOM', '" . str_replace("'", "\'", $oname) . "'",
                    "'DEFAULT_ROOM', '" . str_replace("'", "\'", $nname) . "'",
                    WcFile::readFile(self::$includeDirServer . 'settings.php')
                )
            );
        }
        
        // If the renamed room is the current room, reset current room client/server variables
        if(WcPgc::mySession('current_room') == $oname) {
            WcPgc::wcSetSession('current_room', $nname);
            WcPgc::wcSetCookie('current_room', $nname);
        }
        $changes++;
    }
    if($changes) {
        echo 'Room ' . $oname . ' Successfully updated!';
        touch(ROOMS_LASTMOD);
    }

?>