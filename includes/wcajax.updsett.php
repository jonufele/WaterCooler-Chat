<?php

// Updates user settings

    // Halt if no profile access or no edit permission
    if(!$this->hasProfileAccess) { echo 'NO_ACCESS'; die(); }
    if(!$this->hasPermission('PROFILE_E', 'skip_msg')) { echo 'NO_ACCESS'; die(); }

    $user_data = $this->userData();
    $email = $this->myPost('email');
    $web = $this->myPost('web');
    $timezone = $this->myPost('timezone');
    $hformat = $this->myPost('hformat');

    // Halt if Email or Web Url are not valid
    if(
        (!filter_var($email, FILTER_VALIDATE_EMAIL) && trim($email)) || 
        (!filter_var($web, FILTER_VALIDATE_URL) && trim($web))
    ) {
        echo 'INVALID';
        die();
    }

    if($this->myPost('resetp') == '1') {
        $pass = '';
        $this->wcUnsetCookie('chatpass');
    } else {
        $passe = md5(md5($this->myPost('pass')));
        $pass = ($this->myPost('pass') ? $passe : $this->uPass);
        if($this->myPost('pass')) {
            $this->wcUnsetCookie('chatpass');
        }
    }

    $nstring = base64_encode($this->uAvatar) . '|' . 
        base64_encode($email) . '|' . 
        base64_encode($web) . '|' . 
        $timezone . '|' . 
        $hformat . '|' . 
        $pass
    ;

    $towrite = preg_replace(
        '/^(' . base64_encode($this->name) . ')\|(.*?)\|/m', 
        '\\1|' . base64_encode($nstring) . '|', 
        $this->userList
    );

    $this->writeFile(USERL, $towrite, 'w');

    // Generate tags for javascript form manipulation
    if(
        $timezone != $this->uTimezone || 
        $hformat != $this->uHourFormat
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

?>