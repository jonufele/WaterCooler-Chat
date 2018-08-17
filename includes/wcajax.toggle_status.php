<?php

// Changes User Status (Available/Do Not Disturb)

    if(!isset($this)) { die(); }

    $current = $this->user->data['status'];

    if($current == 1) {
        WcFile::writeFile(
            USERL, 
            $this->user->update(NULL, 'update_status', 2), 
            'w'
        );
        echo 'Your status has been changed to: Do Not Disturb!';
    }
    
    if($current == 2) {
        WcFile::writeFile(
            USERL, 
            $this->user->update(NULL, 'update_status', 1), 
            'w'
        );
        echo 'You status has been changed to: Available!';
    }

?>