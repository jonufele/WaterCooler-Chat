<?php

// Changes to Another Room

    $room_name = $this->myGet('n');
    $this->handleLastRead('store');
    $this->wcSetSession('current_room', $room_name);
    $this->wcSetCookie('current_room', $room_name);
    echo $this->parseRooms();

?>