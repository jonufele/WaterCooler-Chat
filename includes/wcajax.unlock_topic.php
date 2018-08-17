<?php

    // Checks if a topic is locked, if not, locks

    if(!isset($this)) { die(); }
    
    $target = self::$dataDir . 'tmp/tlock_' . 
        base64_encode(WcPgc::mySession('current_room'));
    
    if(file_exists($target)) {
        unlink($target);
    }

?>