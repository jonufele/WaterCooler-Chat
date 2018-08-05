<?php

// Compares Two Passwords (Supplied and Stored)

    if(!isset($this)) { die(); }

    $pass = $this->myGet('pass');
    
    // Halt if password don't match, return a 0 to ajax caller
    if(md5(md5($pass)) != $this->uPass) {
    
        echo 0;
    
    } else {
    
        // Password ok, update server/client variables
        $this->wcSetCookie('chatpass', md5(md5($pass)));

        echo 1;
        
        // Get rid of the pending account recovery file if exists
        if(file_exists($this->dataDir . 'tmp/rec_' . base64_encode($this->name))) {
            unlink($this->dataDir . 'tmp/rec_' . $u);
        }
    }

?>