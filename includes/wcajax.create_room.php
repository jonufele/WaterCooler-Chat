<?php

// Creates a Room

    if(!isset($this)) { die(); }

    // Halt if no permission to create rooms
    if(!$this->hasPermission('ROOM_C')) { die(); }

    $room_name = urldecode($this->myGet('n'));
    
    // Halt if name contains "pm_" (reserved for user pm rooms)
    if(strpos($room_name, 'pm_') !== FALSE) {
        echo 'ERROR: Room name cannot contain the string "pm_"';
        die();
    }
    
    // Target room exists? Is room name valid?
    if(
        !file_exists($this->roomDir . base64_encode($room_name) . '.txt') && 
        trim($room_name, ' ') && 
        !preg_match("/[\?<>\$\{\}\"\:\|,;]/i", $room_name)
    ) {
        file_put_contents(
            $this->roomDir . base64_encode($room_name) . '.txt', 
            time() . '|*' . base64_encode($this->name) . '|created the room.' . "\n"
        );
        file_put_contents(
            $this->roomDir . 'def_' . base64_encode($room_name) . '.txt',
            '0|0|0|0|'
        );
        file_put_contents(
            $this->roomDir . 'topic_' . base64_encode($room_name) . '.txt', 
            ''
        );
        touch(ROOMS_LASTMOD);
        echo $this->parseRooms();

    } else {
        // Room name is invalid
        echo 'ERROR: Room ' . $room_name . ' already exists OR invalid room name' . "\n" . 
            '(illegal char.: ? < > $ { } " : | , ;)!';
    }

?>