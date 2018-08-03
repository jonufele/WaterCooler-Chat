<?php

// Deletes A Room

    // Halt if no permission to delete rooms
    if(!$this->hasPermission('ROOM_D')) { die(); }
    $changes = 0;
    $room_move = '';
    $oname = $this->myGet('oname');
    $enc = base64_encode($oname);
    
    // Room Name exists?
    if(trim($oname) && file_exists($this->roomDir . $enc . '.txt')) {
    
        // Halt if default room
        if(trim($oname) == DEFAULT_ROOM) {
            echo 'The Default Room cannot be deleted!';
            die();
        }
        // Delete room files
        unlink($this->roomDir . $enc . '.txt');
        unlink($this->roomDir . 'def_' . $enc.'.txt');
        unlink($this->roomDir . 'topic_' . $enc.'.txt');
        if(file_exists($this->roomDir . 'hidden_' . $enc . '.txt')) {
            unlink($this->roomDir . 'hidden_' . $enc . '.txt');
        }
        // If deleted room is current room, move user to the default room
        if($this->mySession('current_room') == $oname) {
            $this->wcSetSession('current_room', DEFAULT_ROOM);
            $this->wcSetCookie('current_room', DEFAULT_ROOM);
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