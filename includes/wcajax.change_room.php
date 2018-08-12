<?php

// Changes to Another Room / Starts a private conversation

    if(!isset($this)) { die(); }

    $room_name = html_entity_decode($this->myGet('n'));

    if(
        strpos($room_name, 'pm_') !== FALSE && 
        !file_exists($this->roomDir . base64_encode($room_name) . '.txt')
    ) {
        if(
            $this->hasPermission('PM_ROOM', 'skip_msg') && 
            !$this->isMuted && 
            !$this->isBanned
        ) {
            
            // Check if user status is set to not disturb
            $target = $this->getPmRoomTarget($room_name);
            $user_data = $this->userData($target);
            if(
                (time() - $this->getPing($target)) <= $this->catchWindow && 
                $user_data['status'] == 2
            ) {
                echo 'ERROR: User does not want to be disturbed at the moment!';
                die();
            } else {
                // Check if current user is joined
                if($this->isLoggedIn && $this->uData['status'] != 0) {
                    file_put_contents(
                        $this->roomDir . base64_encode($room_name) . '.txt',
                        time() . '|*' . base64_encode('Private Conversation Room') . '| has been created.' . "\n"
                    );    
                } else {
                    echo 'ERROR: You need to join the chat in order to start a private conversation!';
                    die();
                }
            }
        } else {
            echo 'ERROR: You do not have permission to initiate a private conversation!';
            die();
        }
    }

    $this->handleLastRead('store');
    $this->wcSetSession('current_room', $room_name);
    $this->wcSetCookie('current_room', $room_name);
    echo $this->parseRooms();

?>