<?php

// Updates Topic Description

    if(!isset($this)) { die(); }

    if(
        !$this->user->hasPermission('TOPIC_E', 'skip_msg') && 
        !$this->room->hasConvPermission(WcPgc::mySession('current_room'))
    ) { die(); }
    
    $t = WcPgc::myGet('t');

    if($t != $this->room->topic) {
        WcFile::writeFile(TOPICL, $t, 'w', 'allow_empty');
        echo $this->room->parseTopicContainer();
        
        // Clear topic lock
        $target = self::$dataDir . 'tmp/tlock_' . 
        base64_encode(WcPgc::mySession('current_room'));
    
        if(file_exists($target)) {
            unlink($target);
        }
    }

?>