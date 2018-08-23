<?php

    if(!isset($this)) { die(); }
    
    $par = explode(WcPgc::myGet('id') . '|', "\n" . $this->room->rawMsgList);
    if(isset($par[1])) {
        $par2 = explode("\n", $par[1]);
        $par3 = explode("\n", $par[0]);
        echo $this->room->parseMsg(
            array($par3[count($par3)-1] . WcPgc::myGet('id') . '|' . $par2[0]),
            0,
            'RETRIEVE',
            NULL,
            'RELOAD_MSG'
        );
        
        sleep(1);
    }

?>