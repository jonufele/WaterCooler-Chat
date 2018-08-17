<?php

// Updates user settings

    if(!isset($this)) { die(); }

    // Halt if no profile access or no edit permission
    if(!$this->user->hasProfileAccess) { echo 'NO_ACCESS'; die(); }
    if(!$this->user->hasPermission('PROFILE_E', 'skip_msg')) { echo 'NO_ACCESS'; die(); }

    $user_data = $this->user->getData();
    $email = WcPgc::myPost('email');
    $web = WcPgc::myPost('web');
    $timezone = WcPgc::myPost('timezone');
    $hformat = WcPgc::myPost('hformat');
    
    $error = '';
    $pass = '';

    // Halt if Email or Web Url are not valid
    if(
        (!filter_var($email, FILTER_VALIDATE_EMAIL) && trim($email)) || 
        (!filter_var($web, FILTER_VALIDATE_URL) && trim($web))
    ) {
        $error = 'ERROR: Invalid Web/Email';
    }

    if(WcPgc::myPost('resetp') == '1' && !$error) {
        
        // If user is a moderator can't reset password (or any user would have access to
        // moderator status by acessing the unprotected profile and setting a password)
        if($this->user->isMod) {
            $error = 'ERROR: A moderator password cannot be reset! If you wish, you can supply a new password directly.';
        } else {
            $pass = '';
            WcPgc::wcUnsetCookie('chatpass');
        }
    } elseif(!$error) {
        $passe = md5(md5(WcPgc::myPost('pass')));
        $pass = (WcPgc::myPost('pass') ? $passe : $this->user->data['pass']);
        if(WcPgc::myPost('pass')) {
            WcPgc::wcUnsetCookie('chatpass');
        }
    }

    if(!$error) {
        $nstring = $this->user->parseDataString(
            array(
                'email' => $email,
                'web' => $web,
                'timeZone' => $timezone,
                'hourMode' => $hformat,
                'pass' => $pass
            )
        );

        $towrite = preg_replace(
            '/^(' . base64_encode($this->user->name) . ')\|(.*?)\|/m', 
            '\\1|' . $nstring . '|', 
            $this->user->rawList
        );

        WcFile::writeFile(USERL, $towrite, 'w');
    }

    // Generate tags for javascript form manipulation
    if(!$error) {
        if(
            ($timezone != $this->user->data['timeZone'] || 
            $hformat != $this->user->data['hourMode'])
        ) {
            echo 'RELOAD_MSG';
        }
        if($pass != '') { echo ' RESETP_CHECKBOX'; }
        if(
            WcPgc::myPost('pass') && 
            WcPgc::myPost('resetp') != '1'
        ) {
            echo ' RELOAD_PASS_FORM';
        }
    } else {
        echo $error;
    }

?>