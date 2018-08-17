<?php

// Resets Avatar

    if(!isset($this)) { die(); }

    $efile = '';
    // If user avatar exists, strip its control timestamp
    if($this->user->data['avatar']) {
        list($efile, $_time) = explode('?', $this->user->data['avatar']); 
    }
    
    // If avatar exists, reset user value and delete image file
    if(file_exists(self::$includeDirServer . 'files/avatars/' . $efile)) {
        $nstring = $this->user->parseDataString(
            array('avatar' => '')
        );
        
        $towrite = preg_replace(
            '/(' . base64_encode($this->user->name) . ')\|(.*?)\|/', 
            '\\1|' . $nstring . '|', 
            $this->user->rawList
        );

        WcFile::writeFile(USERL, $towrite, 'w');
        echo 'Avatar successfully reset!';
        unlink(self::$includeDirServer . 'files/avatars/' . $efile);
    } else {
        echo 'No Avatar to reset!';
    }

?>