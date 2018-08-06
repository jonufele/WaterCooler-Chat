<?php

// Updates Ajax Components (Chat blocks that need to be refreshed periodically)

    if(!isset($this)) { die(); }

    $output = $this->refreshUsers() . '[$]' . 
        $this->checkTopicChanges() . '[$]' . 
        $this->refreshRooms() . '[$]' . 
        (
            (
                $this->parseFileMTime(
                    $this->roomDir . 'hidden_' . 
                    base64_encode($this->mySession('current_room')) . '.txt'
                ) <= 
                (time()+$this->catchWindow)
            ) ? 
            $this->checkHiddenMsg() : 
            ''
        ) . '[$]' . 
        $this->refreshMsgE() . '[$]' . 
        $this->mySession('alert_msg') . '[$]' .
        str_replace('[$]', base64_encode('[$]'), $this->refreshMsg())
    ;
    if(trim($output, '[$]')) { echo $output; }

    if($this->mySession('alert_msg')) { $this->wcUnsetSession('alert_msg'); }

?>