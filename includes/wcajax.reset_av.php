<?php

// Resets Avatar

    if(!isset($this)) { die(); }

    $efile = '';
    // If user avatar exists, strip its control timestamp
    if($this->uData['avatar']) {
        list($efile, $_time) = explode('?', $this->uData['avatar']); 
    }
    
    // If avatar exists, reset user value and delete image file
    if(file_exists($this->includeDirServer . 'files/avatars/' . $efile)) {
        $nstring = $this->parseUDataString(
            array('avatar' => '')
        );
        
        $towrite = preg_replace(
            '/(' . base64_encode($this->name) . ')\|(.*?)\|/', 
            '\\1|' . $nstring . '|', 
            $this->userList
        );

        $this->writeFile(USERL, $towrite, 'w');
        echo 'Avatar successfully reset!';
        unlink($this->includeDirServer . 'files/avatars/' . $efile);
    } else {
        echo 'No Avatar to reset!';
    }

?>