<?php

// Deletes A Room

    if(!isset($this)) { die(); }

    // Halt if no permission to delete rooms
    if(!$this->user->hasPermission('ROOM_D')) { die(); }
    $changes = 0;
    $room_move = '';
    $oname = WcPgc::myGet('oname');
    $enc = base64_encode($oname);
    
    // Room Name exists?
    if(trim($oname) && file_exists(self::$roomDir . $enc . '.txt')) {
    
        // Halt if default room
        if(trim($oname) == DEFAULT_ROOM) {
            echo 'The Default Room cannot be deleted!';
            die();
        }
        // Delete room files
        unlink(self::$roomDir . $enc . '.txt');
        unlink(self::$roomDir . 'def_' . $enc.'.txt');
        unlink(self::$roomDir . 'topic_' . $enc.'.txt');
        if(file_exists(self::$roomDir . 'updated_' . $enc . '.txt')) {
            unlink(self::$roomDir . 'updated_' . $enc . '.txt');
        }
        // If deleted room is current room, move user to the default room
        if(WcPgc::mySession('current_room') == $oname) {
            WcPgc::wcSetSession('current_room', DEFAULT_ROOM);
            WcPgc::wcSetCookie('current_room', DEFAULT_ROOM);
            $room_move = 'RMV';
        }
        $changes++;
    }
    
    if($changes) {
        echo $room_move . 'Room ' . $oname . ' Successfully deleted!';
        // Rooms list has just changed, update modification time of the control file
        touch(ROOMS_LASTMOD);
    }

?>