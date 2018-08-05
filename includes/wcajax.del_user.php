<?php

    if(!isset($this)) { die(); }

    $output = '';
    $oname = base64_decode($this->myGET('id'));
    
    // Halt if target is a Moderator and user is not current nor Master Moderator
    if(
        !$this->isMasterMod && 
        $this->getMod($oname) !== FALSE && 
        $this->name != $oname && 
        trim($oname, ' ')
    ) {
        echo 'You cannot edit other moderator!';
        die();
    }

    if($this->name == $oname) {
        echo 'You cannot delete your own account';
        die();
    }
    
    // Halt if target user is invalid (happens if the user was renamed)
    if($this->userMatch($oname) === FALSE) {
        echo 'Invalid Target User (If you just renamed the user, close the form and retry after the name update)!';
        die();
    }
    
    // Check for edit permission
    if($this->hasPermission('USER_E', 'skip_msg')) {
        $users = explode("\n", $this->userList);
        foreach($users as $k => $v) {
            if(strpos("\n" . $v, "\n" . base64_encode($oname) . '|') !== FALSE) {
                $this->writeFile(
                    USERL, 
                    str_replace(
                        "\n\n",
                        "\n",
                        str_replace(
                            $v,
                            '',
                            $this->userList
                        )
                    ), 
                    'w'
                );
                echo 'User '.$oname.' has been successfully removed!';    
            }
        }
    } else {
        echo 'You do not have permission to edit users!';
        die();
    }

?>