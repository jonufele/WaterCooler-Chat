<?php

// Changes User Status (Available/Do Not Disturb)

    if(!isset($this)) { die(); }

    $current = $this->uData[8];

    if($current == 1) {
        $this->writeFile(
            USERL, 
            $this->updateUser(NULL, 'update_status', 2), 
            'w'
        );
        echo 'Your status has been changed to: Do Not Disturb!';
    }
    
    if($current == 2) {
        $this->writeFile(
            USERL, 
            $this->updateUser(NULL, 'update_status', 1), 
            'w'
        );
        echo 'You status has been changed to: Available!';
    }

?>