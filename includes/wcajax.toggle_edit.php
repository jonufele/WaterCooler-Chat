<?php

// Shows/Hides Edit Buttons/Links

    if(!isset($this)) { die(); }

    if(WcPgc::myCookie('hide_edit')) {
        WcPgc::wcUnsetCookie('hide_edit');
    } else {
        WcPgc::wcSetCookie('hide_edit', '1');
    }

?>