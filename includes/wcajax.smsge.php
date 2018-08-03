<?php

// Writes an event message

    if($this->myGet('t')) {
        $towrite = $this->writeEvent(
            $this->myGet('t'), 
            '', 
            'RETURN_RAW_LINE'
        );
        $output = $this->parseMsgE(
            array(trim($towrite)), 
            0, 
            'SEND'
        );
        if($output) { echo $output; }
    }

?>