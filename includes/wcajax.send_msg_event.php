<?php

// Writes an event message

    if(!isset($this)) { die(); }
    
    $msg = '';
    $target = NULL;

    switch(WcPgc::myGet('t')) {
        case 'join':
            $msg = ' has joined the chat.';
        break;
        case 'topic_update':
            // Target event to the other private conversation participant
            if($this->room->isConv) {
                $target = '|' . base64_encode($this->room->getConvTarget(
                    WcPgc::mySession('current_room')
                ));
            }

            // Target event to all users with access to the private room
            if(!$target && $this->room->def['rPerm']) {
                $target = '*' . base64_encode(WcPgc::mySession('current_room'));
            }

            $msg = 'updated the topic (' . 
                (
                    $this->room->isConv ? 
                    'Private Conversation' :
                    WcPgc::mySession('current_room')
                ) . 
            ')';
        break;    
    }

    if(WcPgc::myGet('t') && $msg) {
        $towrite = WcFile::writeEvent(
            $this->user->name,
            $msg,
            $target, 
            'RETURN_RAW_LINE'
        );
        $output = $this->room->parseMsgE(
            array(trim($towrite)), 
            0, 
            'SEND'
        );
        if($output) { echo $output; }
    }

?>