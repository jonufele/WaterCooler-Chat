<?php

// Creates a Room

    // Halt if no permission to create rooms
    if(!$this->hasPermission('ROOM_C')) { die(); }
    $room_name = $this->myGet('n');
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
        echo 'Room ' . $room_name . ' already exists OR invalid room name' . "\n" . 
            '(illegal char.: ? < > $ { } " : | , ;)!';
    }

?>