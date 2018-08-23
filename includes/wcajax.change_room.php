<?php

// Changes to Another Room / Starts a private conversation

    if(!isset($this)) { die(); }

    $room_name = html_entity_decode(WcPgc::myGet('n'));

    if(
        strpos($room_name, 'pm_') !== FALSE && 
        !file_exists(self::$roomDir . base64_encode($room_name) . '.txt')
    ) {
        if(
            $this->user->hasPermission('PM_ROOM', 'skip_msg') && 
            !$this->user->isMuted && 
            !$this->user->isBanned
        ) {
            
            // Check if user status is set to not disturb
            $target = $this->room->getConvTarget($room_name);
            $user_data = $this->user->getData($target);
            if(
                (time() - $this->user->getPing($target)) <= self::$catchWindow && 
                $user_data['status'] == 2
            ) {
                echo 'ERROR: User does not want to be disturbed at the moment!';
                die();
            } else {
                // Check if current user is joined
                if($this->user->isLoggedIn && $this->user->data['status'] != 0) {
                    file_put_contents(
                        self::$roomDir . base64_encode($room_name) . '.txt',
                        time() . '|*' . base64_encode('Private Conversation Room') . '| has been created.' . "\n"
                    );
                    file_put_contents(
                        self::$roomDir . 'def_' . base64_encode($room_name) . '.txt',
                        $this->room->parseDefString()
                    );
                    file_put_contents(
                        self::$roomDir . 'topic_' . base64_encode($room_name) . '.txt',
                        ''
                    );
                    file_put_contents(
                        self::$roomDir . 'updated_' . base64_encode($room_name) . '.txt',
                        ''
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

    WcTime::handleLastRead('store');
    WcPgc::wcSetSession('current_room', $room_name);
    WcPgc::wcSetCookie('current_room', $room_name);
    echo $this->room->parseList();

?>