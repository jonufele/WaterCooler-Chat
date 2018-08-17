<?php

// Uploads an avatar

    if(!isset($this)) { die(); }

    // Process if form field has been supplied
    if(isset($_FILES['avatar']['tmp_name'])) {

        $type = WcFile::getFileExt($_FILES['avatar']['name']);
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
            $dest = self::$includeDirServer . 
                'files/avatars/' . base64_encode($this->user->name) . '.' . 
                str_replace('image/', '', $_FILES['avatar']['type'])
            ;
            $dest_write = base64_encode($this->user->name) . '.' . 
                str_replace('image/', '', $_FILES['avatar']['type'])
            ;
            
            // Try to create a cropped thumbnail, halt otherwise
            if(WcImg::thumbnailCreateCr($_FILES['avatar']['tmp_name'], $dest, $tn_size)) {
            
                unlink($_FILES['avatar']['tmp_name']);
                $nstring =  $this->user->parseDataString(
                    array(
                        'avatar' => $dest_write . '?' . time()  
                    )
                );

                // Set user's avatar value
                $towrite = preg_replace(
                    '/^(' . base64_encode($this->user->name) . ')\|(.*?)\|/m', 
                    '\\1|' . $nstring . '|', 
                    $this->user->rawList
                );

                WcFile::writeFile(USERL, $towrite, 'w');
                echo 'Avatar successfully set!';
            }
        }
    }

?>