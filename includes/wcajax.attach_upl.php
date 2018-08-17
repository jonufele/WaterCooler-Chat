<?php

// Uploads an attachment

    if(!isset($this)) { die(); }

    // Process request if form element exists and has permission to attach and attachmemts are enabled
    if(
        $_FILES['attach']['tmp_name'] && 
        $this->user->hasPermission('ATTACH_UPL', 'skip_msg') && 
        ATTACHMENT_UPLOADS
    ) {
        sleep(1);
        // Strip special characters from name
        $dest_name = preg_replace(
            '/[^A-Za-z0-9 _\.]/', 
            '', 
            $_FILES['attach']['name']
        );
        
        if(strlen($dest_name) > 13) {
            $dest_name = substr($dest_name, strlen($dest_name) - 13, 13);
        }
        
        // Destination path
        $dest = 
            self::$includeDirServer . 
            'files/attachments/' . 
            base64_encode($this->user->name) . '_' . 
            dechex(time()). '_' . 
            $dest_name
        ;
        
        // Get file type by extention
        $type = WcFile::getFileExt($dest_name);
        $file_ok = TRUE;
        $allowed_types = explode(' ', trim(ATTACHMENT_TYPES, ' '));
        $allowed_types_img = array('jpg', 'jpeg', 'gif', 'png');
        
        // Meets filesize limit? Is an allowed type? Destination does not exist?
        if(
            $_FILES['attach']['size'] <= (ATTACHMENT_MAX_FSIZE * 1024) && 
            in_array($type, $allowed_types) && 
            !file_exists($dest)
        ) {            
            copy($_FILES['attach']['tmp_name'], $dest);
            unlink($_FILES['attach']['tmp_name']);
            
            if(in_array($type, $allowed_types_img)) {
                echo WcImg::parseImg($dest, 'ATTACH');
            } else {
                echo '[attach_' . 
                    base64_encode($this->user->name) . '_' . 
                    dechex(time()) . '_' . 
                    intval($_FILES['attach']['size'] / 1014) . '_' . 
                    $dest_name .']'
                ;
            }
        // Halt, file does not meet requirements
        } else {
            if(!file_exists($dest)) {
                echo 'Error: Invalid file (Allowed: ' . 
                    trim(
                        implode(', ', $allowed_types), 
                        ' '
                    ) . 
                    ' up to 1024KB)'
                ;
            } else {
                echo 'Error: File already exists!';
            }
        }
    }

?>