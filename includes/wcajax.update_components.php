<?php

// Updates Ajax Components (Chat blocks that need to be refreshed periodically)

    $output = $this->parseUsers() . '[$]' . 
        $this->checkTopicChanges() . '[$]' . 
        $this->refreshRooms() . '[$]' . 
        (
            (filemtime($this->roomDir . 'hidden_' . base64_encode($this->mySession('current_room')) . '.txt') <= $this->catchWindow) ? 
            $this->checkHiddenMsg() : 
            ''
        ) . '[$]' . 
        $this->updateMsgOnceE() . '[$]' . 
        $this->mySession('alert_msg') . '[$]' .
        str_replace('[$]', base64_encode('[$]'), $this->updateMsg())
    ;
    if(trim($output, '[$]')) { echo $output; }

    if($this->mySession('alert_msg')) { $this->wcUnsetSession('alert_msg'); }

?>