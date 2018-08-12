<?php

// Updates Room Name / Definitions

    if(!isset($this)) { die(); }

    // Halt if no room edit permission
    if(!$this->hasPermission('ROOM_E')) { die(); }
    $oname = $this->myPost('oname');
    $nname = $this->myPost('nname');
    $wperm = $this->myPost('perm');
    $rperm = $this->myPost('rperm');
    $enc = base64_encode($oname);
    
    // Get room definitions
    $room_def = $this->getRoomDef($oname);
    $changes = 0;
    
    // Update permissions if changed
    if($room_def['wPerm'] != $wperm || $room_def['rPerm'] != $rperm) {
        $this->writeFile(
            $this->roomDir . 'def_' . $enc . '.txt',
            $this->parseRDataString(
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
            file_exists($this->roomDir . base64_encode($nname) . '.txt') || 
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
            $this->roomDir . $enc . '.txt', 
            $this->roomDir . base64_encode($nname) . '.txt'
        );
        rename(
            $this->roomDir . 'def_' . $enc . '.txt', 
            $this->roomDir . 'def_' . base64_encode($nname) . '.txt'
        );
        rename(
            $this->roomDir . 'topic_' . $enc . '.txt', 
            $this->roomDir . 'topic_' . base64_encode($nname) . '.txt'
        );
        
        if(file_exists($this->roomDir . 'hidden_' . $enc . '.txt')) {
            rename(
                $this->roomDir . 'hidden_' . $enc . '.txt', 
                $this->roomDir . 'hidden_' . base64_encode($nname) . '.txt'
            );
        }
        
        // If the renamed room is the default, rename in settings as well
        if(trim($oname) == trim(DEFAULT_ROOM)) {
            file_put_contents(
                $this->includeDirServer . 'settings.php',
                str_replace(
                    "'DEFAULT_ROOM', '" . str_replace("'", "\'", $oname) . "'",
                    "'DEFAULT_ROOM', '" . str_replace("'", "\'", $nname) . "'",
                    $this->readFile($this->includeDirServer . 'settings.php')
                )
            );
        }
        
        // If the renamed room is the current room, reset current room client/server variables
        if($this->mySession('current_room') == $oname) {
            $this->wcSetSession('current_room', $nname);
            $this->wcSetCookie('current_room', $nname);
        }
        $changes++;
    }
    if($changes) {
        echo 'Room ' . $oname . ' Successfully updated!';
        touch(ROOMS_LASTMOD);
    }

?>