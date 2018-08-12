<?php

// Updates user settings

    if(!isset($this)) { die(); }

    // Halt if no profile access or no edit permission
    if(!$this->hasProfileAccess) { echo 'NO_ACCESS'; die(); }
    if(!$this->hasPermission('PROFILE_E', 'skip_msg')) { echo 'NO_ACCESS'; die(); }

    $user_data = $this->userData();
    $email = $this->myPost('email');
    $web = $this->myPost('web');
    $timezone = $this->myPost('timezone');
    $hformat = $this->myPost('hformat');
    
    $error = '';
    $pass = '';

    // Halt if Email or Web Url are not valid
    if(
        (!filter_var($email, FILTER_VALIDATE_EMAIL) && trim($email)) || 
        (!filter_var($web, FILTER_VALIDATE_URL) && trim($web))
    ) {
        $error = 'ERROR: Invalid Web/Email';
    }

    if($this->myPost('resetp') == '1' && !$error) {
        
        // If user is a moderator can't reset password (or any user would have access to
        // moderator status by acessing the unprotected profile and setting a password)
        if($this->isMod) {
            $error = 'ERROR: A moderator password cannot be reset! If you wish, you can supply a new password directly.';
        } else {
            $pass = '';
            $this->wcUnsetCookie('chatpass');
        }
    } elseif(!$error) {
        $passe = md5(md5($this->myPost('pass')));
        $pass = ($this->myPost('pass') ? $passe : $this->uData['pass']);
        if($this->myPost('pass')) {
            $this->wcUnsetCookie('chatpass');
        }
    }

    if(!$error) {
        $nstring = $this->parseUDataString(
            array(
                'email' => $email,
                'web' => $web,
                'timeZone' => $timezone,
                'hourMode' => $hformat,
                'pass' => $pass
            )
        );

        $towrite = preg_replace(
            '/^(' . base64_encode($this->name) . ')\|(.*?)\|/m', 
            '\\1|' . $nstring . '|', 
            $this->userList
        );

        $this->writeFile(USERL, $towrite, 'w');
    }

    // Generate tags for javascript form manipulation
    if(!$error) {
        if(
            ($timezone != $this->uData['timeZone'] || 
            $hformat != $this->uData['hourMode'])
        ) {
            echo 'RELOAD_MSG';
        }
        if($pass != '') { echo ' RESETP_CHECKBOX'; }
        if(
            $this->myPost('pass') && 
            $this->myPost('resetp') != '1'
        ) {
            echo ' RELOAD_PASS_FORM';
        }
    } else {
        echo $error;
    }

?>