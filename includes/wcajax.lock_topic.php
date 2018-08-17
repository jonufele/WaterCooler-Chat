<?php

    // Checks if a topic is locked, if not, locks

    if(!isset($this)) { die(); }
    
    $target = self::$dataDir . 'tmp/tlock_' . 
        base64_encode(WcPgc::mySession('current_room'));
    
    if(
        !file_exists($target)
    ) {
        file_put_contents($target, $this->user->name);
    
    } else {
    
        // If lock exists, overwrite if locker is offline or is current user
        $locker = WcFile::readFile($target);
        if(
            (time() - $this->user->getPing($locker)) > 
            self::$catchWindow || 
            $locker == $this->user->name
        ) {
            file_put_contents($target, $this->user->name);
        } else {
            echo 'Topic is currently being edited by ' . file_get_contents($target) . ', wait a few minutes.';
        }
    }

?>