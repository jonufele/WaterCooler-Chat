<?php

// Shows/Hides Edit Buttons/Links

    if(!isset($this)) { die(); }

    if($this->myCookie('hide_edit')) {
        $this->wcUnsetCookie('hide_edit');
    } else {
        $this->wcSetCookie('hide_edit', '1');
    }

?>