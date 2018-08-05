<?php

// Changes to Another Room

    if(!isset($this)) { die(); }

    $room_name = html_entity_decode($this->myGet('n'));
    $this->handleLastRead('store');
    $this->wcSetSession('current_room', $room_name);
    $this->wcSetCookie('current_room', $room_name);
    echo $this->parseRooms();

?>