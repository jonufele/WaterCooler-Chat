<?php

// Resets Avatar

    if(!isset($this)) { die(); }

    $efile = '';
    // If user avatar exists, strip its control timestamp
    if($this->uAvatar) {
        list($efile, $_time) = explode('?', $this->uAvatar); 
    }
    
    // If avatar exists, reset user value and delete image file
    if(file_exists($this->includeDirServer . 'files/avatars/' . $efile)) {
        $nstring = '|' . base64_encode($this->uEmail) . '|' . 
            base64_encode($this->uWeb) . '|' . 
            $this->uTimezone . '|' . 
            $this->uHourFormat . '|' . 
            $this->uPass
        ;
        $towrite = preg_replace(
            '/(' . base64_encode($this->name) . ')\|(.*?)\|/', 
            '\\1|' . base64_encode($nstring) . '|', 
            $this->userList
        );

        $this->writeFile(USERL, $towrite, 'w');
        echo 'Avatar successfully reset!';
        unlink($this->includeDirServer . 'files/avatars/' . $efile);
    } else {
        echo 'No Avatar to reset!';
    }

?>