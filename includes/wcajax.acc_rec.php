<?php

// Recovers the account

    if(!isset($this)) { die(); }

    // Email exists? No pending recovery? Has permission? 
    if(
        $this->user->data['email'] && 
        !file_exists(self::$dataDir . 'tmp/rec_'.base64_encode($this->user->name)) && 
        $this->user->hasPermission('ACC_REC', 'skip_msg')
    ) {
        if(mail(
            $this->user->data['email'],
            'Account Recovery',
            "Someone (probably you) has requested an account recovery.\n
                Click the following link in order to reset your account password:\n\n" . 
                WcPgc::myServer('HTTP_REFERER') . '?recover=' . 
                $this->user->data['pass'] . "&u=" . 
                urlencode(base64_encode($this->user->name)) . 
                "\n\nIf you did not request this, please ignore it.",
            WcUtils::mailHeaders(
                (trim(ACC_REC_EMAIL) ? trim(ACC_REC_EMAIL) : 'no-reply@' . WcPgc::myServer('SERVER_NAME')),
                TITLE,
                $this->user->data['email'],
                $this->user->name
            )
        )) {
            echo 'A message was sent to the account email with recovery instructions.';
            touch(self::$dataDir . 'tmp/rec_' . base64_encode($this->user->name));
        } else {
            echo 'Failed to send E-mail!';
        };
        
    // Does not meet requiments, check pending recovery
    } elseif(file_exists(self::$dataDir . 'tmp/rec_' . base64_encode($this->user->name))) {
        echo 'A pending recovery already exists for this account.';
    }

?>