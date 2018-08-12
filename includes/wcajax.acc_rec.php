<?php

// Recovers the account

    if(!isset($this)) { die(); }

    // Email exists? No pending recovery? Has permission? 
    if(
        $this->uData['email'] && 
        !file_exists($this->dataDir . 'tmp/rec_'.base64_encode($this->name)) && 
        $this->hasPermission('ACC_REC', 'skip_msg')
    ) {
        if(mail(
            $this->uData['email'],
            'Account Recovery',
            "Someone (probably you) has requested an account recovery.\n
                Click the following link in order to reset your account password:\n\n" . 
                $this->myServer('HTTP_REFERER') . '?recover=' . $this->uData['pass'] . "&u=" . urlencode(base64_encode($this->name)) . 
                "\n\nIf you did not request this, please ignore it.",
            $this->mailHeaders(
                (trim(ACC_REC_EMAIL) ? trim(ACC_REC_EMAIL) : 'no-reply@' . $this->myServer('SERVER_NAME')),
                TITLE,
                $this->uData['email'],
                $this->name
            )
        )) {
            echo 'A message was sent to the account email with recovery instructions.';
            touch($this->dataDir . 'tmp/rec_' . base64_encode($this->name));
        } else {
            echo 'Failed to send E-mail!';
        };
        
    // Does not meet requiments, check pending recovery
    } elseif(file_exists($this->dataDir . 'tmp/rec_' . base64_encode($this->name))) {
        echo 'A pending recovery already exists for this account.';
    }

?>