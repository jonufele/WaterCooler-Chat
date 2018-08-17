<?php

// Shows/Hides TimeStamps

    if(!isset($this)) { die(); }

    if(WcPgc::myCookie('hide_time')) {
        WcPgc::wcUnsetCookie('hide_time');
    } else {
        WcPgc::wcSetCookie('hide_time', '1');
    }

?>