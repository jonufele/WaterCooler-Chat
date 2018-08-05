<?php

// Uploads an avatar

    if(!isset($this)) { die(); }

    // Process if form field has been supplied
    if(isset($_FILES['avatar']['tmp_name'])) {

        $type = $this->getFileExt($_FILES['avatar']['name']);
        $allowed_types = array('jpeg', 'jpg', 'gif', 'png');

        // Halt if type is not among allowed types
        if(!in_array($type, $allowed_types)) {
            echo 'Invalid Image Type (allowed: ' . trim(
                implode(', ', $allowed_types), 
                ' '
            ) . ')';
        } else {
            // Set avatar size to 25px if not set
            if(!AVATAR_SIZE) { $tn_size = 25; } else { $tn_size = AVATAR_SIZE; }
            
            // Set destination paths
            $dest = $this->includeDirServer . 
                'files/avatars/' . base64_encode($this->name) . '.' . 
                str_replace('image/', '', $_FILES['avatar']['type'])
            ;
            $dest_write = base64_encode($this->name) . '.' . 
                str_replace('image/', '', $_FILES['avatar']['type'])
            ;
            
            // Try to create a cropped thumbnail, halt otherwise
            if($this->thumbnailCreateCr($_FILES['avatar']['tmp_name'], $dest, $tn_size)) {
            
                unlink($_FILES['avatar']['tmp_name']);
                $nstring = base64_encode($dest_write . '?' . time()) . '|' . 
                    base64_encode($this->uEmail) . '|' . 
                    base64_encode($this->uWeb) . '|' . 
                    $this->uTimezone . '|' . 
                    $this->uHourFormat . '|' . 
                    $this->uPass
                ;

                // Set user's avatar value
                $towrite = preg_replace(
                    '/^(' . base64_encode($this->name) . ')\|(.*?)\|/m', 
                    '\\1|' . base64_encode($nstring) . '|', 
                    $this->userList
                );

                $this->writeFile(USERL, $towrite, 'w');
                echo 'Avatar successfully set!';
            }
        }
    }

?>